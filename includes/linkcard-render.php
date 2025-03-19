<?php
class LinkcardRenderer{
  public static function render($atts) {
    // 引数を受け取り、デフォルト値を設定
    $atts = shortcode_atts(
      array(
        'imgsrc' => plugin_dir_url(__FILE__).'assets/img/OGP.png',
        'url' => site_url(),     // リンク先URL
        'title' => get_bloginfo('name'), // タイトル
        'desc' => get_bloginfo('description'),   // 説明
        'target'=>'_blank',
        'class'=>''
      ),
      $atts,
      'linkcard'
    );
    // HTMLコードを生成
    $noopener = '';
    if($atts['target'] == '_blank'){
      $noopener = 'rel="noopener noreferrer"';
    }
    $output = '<a href="' . esc_url($atts['url']) . '" target="'.esc_html($atts['target']).'" '.$noopener.' class="'.esc_url($atts['class']).'  bhb-linkcard d-block border border-1 rounded-4 shadow-sm mb-3 p-3">';
    $output .= '<div class="row gx-3">';
    $output .= '<div class="col-12 col-md-4">';
    $output .= '<img src="' . esc_url($atts['imgsrc']) . '" class="object-fit-cover w-100 h-100 bhb-linkcard__img" alt="Icon" />';
    $output .= '</div>';
    $output .= '<div class="col-12 col-md-8 align-content-center">';
    $output .= '<p class="bhb-linkcard__title fs-5 mb-0">' . esc_html($atts['title']) . '&nbsp;<i class="bi bi-box-arrow-up-right"></i></p>';
    $output .= '<p class="bhb-linkcard__desc fs-6 mb-0">' . esc_html($atts['desc']) . '</p>';
    $output .= '</div>';
    $output .= '</div><!--/.row-->';
    $output .= '</a>';

    // 生成したHTMLを返す
    return $output;
  }
}
?>