<?php
/*
Plugin Name: wako2025 機能拡張プラグイン
Description: ※ACFに依存します 機能:AuthorThumbnail表示 SeoDesc管理 OGP管理 canonical表示 json表示 SeoTitle表示 パンクズを[pnkz]で表示 管理画面一覧におすすめ記事の表示 共通コンテンツ
Author: カッツプロダクション 菅原勝文
Version: 1.0
*/
class wako2025Plugin {
  public function __construct(){
    add_filter( 'get_avatar', array($this,'acf_profile_avatar'), 10, 5 );
    add_filter( 'wp_head', array($this,'set_ogp') );
    add_filter( 'wp_head', array($this,'set_desc') );
    remove_action( 'wp_head', 'wp_generator');
    remove_action( 'wp_head', 'rel_canonical');
    add_action( 'wp_head', array($this, 'add_canonical') );
    add_filter( 'wp_head', array($this,'set_json_ld') );
    add_filter( 'after_setup_theme', array($this,'set_title') );
    add_shortcode( 'pnkz', array($this, 'simple_breadcrumb') );
    add_shortcode( 'linkcard', array($this, 'linkcard_render') );
    //add_filter( 'manage_posts_columns', array($this, 'add_posts_columns') );
    //add_action( 'manage_posts_custom_column', array($this, 'custom_posts_column'), 10, 2 );
    add_action('admin_menu', array($this, 'orign_extend_options_menu') );
    add_filter( 'wp_head', array($this,'set_gtm_head') );
    add_filter( 'wp_head', array($this,'set_noindex') );
    add_filter( 'wp_unique_post_slug', array($this,'custom_auto_post_slug'), 10, 4 );
    //add_action( 'admin_print_footer_scripts', array($this,'limit_category_select') );
  }
  public function set_ogp(){
    require_once dirname( __FILE__ ) . '/includes/header-ogp.php';
  }
  public function set_desc(){
    require_once dirname( __FILE__ ) . '/includes/header-desc.php';
  }
  public function add_canonical() {
    require_once dirname( __FILE__ ) . '/includes/header-canonical.php';
  }
  public function set_json_ld(){
    require_once dirname( __FILE__ ) . '/includes/header-json-ld.php';
  }
  public function set_title() {
    add_theme_support( 'title-tag' );
    add_theme_support('post-thumbnails');
  }
  public function set_gtm_head() {
    if( !is_user_logged_in() ){
       if( $set_gtm = get_option('gtm_setting') ){ ?>
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','<?php echo $set_gtm; ?>');</script>
        <!-- End Google Tag Manager -->
      <?php }
    }
  }
  //ユーザーのサムネール表示
  public function acf_profile_avatar( $avatar, $id_or_email, $size, $default, $alt ) {
    require_once dirname( __FILE__ ) . '/includes/acf-profile-avatar.php';
    return acfProfileAvatar::render($avatar, $id_or_email, $size, $default, $alt);
  }
  
  //パンくずショートコード
  public function simple_breadcrumb() {
    require_once dirname( __FILE__ ) . '/includes/pnkz.php';
    return $str_html;
  }
  function add_posts_columns( $columns ) {
    $columns['recommend'] = 'おすすめ記事';
    return $columns;
  }
  function custom_posts_column( $column_name, $post_id ) {
    if ( $column_name == 'recommend' ) {
      $cf_example = get_post_meta( $post_id, 'recommend_flag', true );
      echo ( $cf_example ) ? '✅' : '－';
    }
  }
  
  //サイト独自設定
  private $orign_options_array = array("side_bn_html","ft_bn1_html","ft_bn2_html","gtm_setting","og_img_url");//ここに項目を追加
  public function orign_extend_options_menu() {
    add_options_page('サイト独自設定', 'サイト独自設定', 7, 'orign_extend_options_menu', array($this, 'orign_extend_options_menu_page') );
    add_action( 'admin_init', array($this, 'register_orign_extend_options_menu') );
  }
  public function register_orign_extend_options_menu() {
    foreach($this->orign_options_array as $val){
      register_setting( 'orign_extend_settings_group', $val );
    }
  }
  public function orign_extend_options_menu_page() {
    require_once dirname( __FILE__ ) . '/includes/orign-extend-options-menu-page.php';
  }
  public function set_noindex() {
    if( is_attachment() ){
      echo '<meta name="robots" content="noindex">';
    }
  }
  //カテゴリを一個に制限classic editor限定
  public function limit_category_select() {
    require_once dirname( __FILE__ ) . '/includes/limit-term-select.php';
  }
  // リンクカードをレンダリングするメソッド
  public function linkcard_render($atts) {
    require_once dirname( __FILE__ ) . '/includes/linkcard-render.php';
    return LinkcardRenderer::render($atts);
  }
  // %postname%の時のみ有効 slug自動生成の形式をpost-[id] に変更 日本語URL防止対策
  function custom_auto_post_slug( $slug, $post_ID, $post_status, $post_type ) {
    if ( $post_type == 'post' ) {
      $slug = 'post-' . $post_ID;
    }
    return $slug;
  }
}

$base_information = new wako2025Plugin();

//keniの共通コンテンツを移植 関数被りを防ぐために変数名をkeni_cc_customと変更
require_once dirname( __FILE__ ) . '/includes/common-content.php';