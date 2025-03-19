<!-- OGP -->
<?php
  global $post;
  //singular以外のOG画像設定
  if( get_option('og_img_url') ){
    $ogp_home_image = get_option('og_img_url');
  }else{
    $ogp_home_image = str_replace("/includes/", "/", plugin_dir_url(__FILE__))."assets/img/OGP.png";
  }

  //サイト概要の取得
  if ($post !== null) {
    // 安全にプロパティを読み取る
    $defalt_excerpt = $post->post_excerpt;
    $defalt_post = $post->post_content;
  } else {
    // $post が null の場合のエラーハンドリング
    $defalt_excerpt = '';
    $defalt_post = '';
  }
  $defalt_excerpt = trim(strip_tags( $defalt_excerpt ));
  $seo_desc = get_field('seo_description');
  $og_desc = get_field('og_description');
  if(!$og_desc){//OGDESCが空なら
    if ( !$seo_desc ) {//投稿で抜粋が設定されていない場合は記事の文字を抽出
      $ogp_description = mb_substr(str_replace(array("\r\n", "\r", "\n"), '', strip_tags($defalt_post) ), 0, 120);
    }else{
      $ogp_description = mb_substr(str_replace(array("\r\n", "\r", "\n"), '', strip_tags($seo_desc) ), 0, 120);
    }
  }else{//OGDESECが入ってるなら
    $ogp_description = strip_tags($og_desc);
  }
  $ogp_description = htmlspecialchars($ogp_description);
?>

<meta property="og:type" content="<?php echo (is_singular() ? 'article' : 'website'); ?>">
<meta name="twitter:card" content="summary">
<?php
if (is_singular()){//単一記事ページの場合
  if(have_posts()): while(have_posts()): the_post();
    echo '<meta property="og:description" content="'.$ogp_description.'">';echo "\n";//抜粋を表示
  endwhile; endif;
  if( get_field('og_title') ){ //OG画像がある場合は優先して表示する(ACFに依存)
    $title = get_field('og_title');
  }else{
    $title = get_the_title();
  }
  if ( is_front_page() ) {
    $title = get_bloginfo('name');
  }
  echo '<meta property="og:title" content="'; echo $title; echo '">';echo "\n";//単一記事タイトルを表示
  echo '<meta property="og:url" content="'; the_permalink(); echo '">';echo "\n";//単一記事URLを表示
} else {//単一記事ページページ以外の場合（アーカイブページやホームなど）
  $description = get_bloginfo('description');
  $title = get_bloginfo('name');
  $url = home_url();

  if ( is_category() ) {//カテゴリ用設定
    $description = strip_tags( category_description() );
    $title = wp_title('｜'.get_bloginfo('name'), false , 'right');
    $category = get_the_category();
    $url = esc_url( get_category_link( $category[0]->term_id) );
  }
  echo '<meta property="og:description" content="'; echo $description; echo '">';echo "\n";//カテゴリの説明
  echo '<meta property="og:title" content="'; echo $title; echo '">';echo "\n";//「一般設定」管理画面で指定したブログのタイトルを表示
  echo '<meta property="og:url" content="'; echo $url; echo '">';echo "\n";//「一般設定」管理画面で指定したブログのURLを表示取る
}
$content = '';
if ( isset( $post->post_content ) ){
  $content = $post->post_content;
}
$searchPattern = '/<img.*?src=(["\'])(.+?)\1.*?>/i';//投稿にイメージがあるか調べる
if (is_singular()){//単一記事ページの場合
  if(get_field('og_img')){//singularでOG画像がある場合は優先して表示する(ACFに依存)
    $image = wp_get_attachment_image_src( get_field('og_img'), 'large');
    echo '<meta property="og:image" content="'.$image[0].'">';echo "\n";
  }else{//投稿にサムネイルがある場合の処理
    if (has_post_thumbnail()){
      $image_id = get_post_thumbnail_id();
      $image = wp_get_attachment_image_src( $image_id, 'full');
      echo '<meta property="og:image" content="'.$image[0].'">';echo "\n";
    } else if ( preg_match( $searchPattern, $content, $imgurl ) && !is_archive()) {//投稿にサムネイルは無いが画像がある場合の処理
      echo '<meta property="og:image" content="'.$imgurl[2].'">';echo "\n";
    } else if ( $ogp_home_image ){//ホームイメージが設定されている場合
      echo '<meta property="og:image" content="'.$ogp_home_image.'">';echo "\n";
    } else {//投稿にサムネイルも画像も無い場合の処理
      $ogp_image =  $ogp_home_image;
      echo '<meta property="og:image" content="'.$ogp_image.'">';echo "\n";
    }
  }
} else {//単一記事ページページ以外の場合（アーカイブページやホームなど）
  $ogp_image = $ogp_home_image;
}

if( !empty($ogp_image) ) {//使えそうな$ogp_imageがある場合
  echo '<meta property="og:image" content="'.$ogp_image.'">';echo "\n";
}

?>
<meta property="og:site_name" content="<?php bloginfo('name'); ?>">
<meta property="og:locale" content="ja_JP">
<!-- /OGP -->
