<!-- DESCRIPTION -->
<?php
  global $post;
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
  if(!$seo_desc){//SEODESCが空なら
    if(!$defalt_excerpt){//抜粋空なら記事から自動生成
      $desc = mb_substr(str_replace(array("\r\n", "\r", "\n"), '', strip_tags($defalt_post) ), 0, 120);
    }else{
      $desc = $defalt_excerpt;
    }
  }else{
    $desc = mb_substr(str_replace(array("\r\n", "\r", "\n"), '', strip_tags($seo_desc) ), 0, 120);
  }
  $desc = htmlspecialchars($desc);
  $seo_description = $desc;
?>
<?php
if (is_singular()){//単一記事ページの場合
  if(have_posts()): while(have_posts()): the_post();
    echo '<meta name="description" content="'.$seo_description.'">';echo "\n";
  endwhile; endif;
} else {//単一記事ページページ以外の場合（アーカイブページやホームなど）
  $description = get_bloginfo('description');
  if ( is_category() ) {//カテゴリ用設定
    $description = strip_tags( category_description() );
    if(!$description){
      $description = get_bloginfo('description');
    }
  }
  echo '<meta name="description" content="'; echo $description; echo '">';echo "\n";//カテゴリの説明
}
?>
<!-- /DESCRIPTION -->
