<script type="application/ld+json">
{
  "@context" : "https://schema.org",
  "@type" : "Organization",
  "name" : "和光産業",
  "foundingDate":"2024/11/1", //設立日
  "description" : "知らなかった山形発見！地域密着のポータルサイトならボンヘイベルク",
  "url" : "dummy",
  "logo": "dummy",
  "image": "dummy",
  "telephone" : "0237-53-0100", //電話番号
  "address": {
    "addressLocality": "府中市",
    "addressRegion": "東京都",
    "postalCode": "0000-0000",
    "streetAddress": "2-1-1　本町ビル2F",
    "addressCountry": "JP"
  },
  "contactPoint" :[{
    "@type" : "ContactPoint",
    "telephone" : "0237-53-0100",
    "contactType" : "customer service"
    }
  }],
  "sameAs":[ //関連サイトやSNS
    "",
    "",
  ]
}
</script>
<?php if( is_singular(array('post','page')) ): ?>
<!--記事 && 固定ページ -->
<script type="application/ld+json">
{
  "@context": "http://schema.org",
  "@type": "Article",
  "mainEntityOfPage": {
    "@type": "WebPage",
    "@id": "<?php echo get_bloginfo('url'); ?>"
  },
  "headline": "<?php echo get_the_title(); ?>",
  <?php
    //説明文
    $content = html_entity_decode( wp_strip_all_tags( get_the_content(), true ) );
    if($content){
      $content = mb_substr(wp_strip_all_tags($content), 0, 160, 'UTF-8')."・・・";
    }else{
      $content = "特に内容はありません";
    }
  ?>
  "description": "<?php echo $content; ?>",
  <?php
    //画像の処理
    //goodlistの場合
    if(is_singular('goodslist')){
      $images = get_field('goods_gallery');
      $imgobj;
      if($images){
        foreach($images as $image){
          $imgobj[] = $image['url'];
        }
        echo '"image": ["'.implode('","',$imgobj).'"],';
      }else{
        echo '"image":["'.get_site_icon_url().'"],';
      }
    }else if( is_singular( array('post','page') ) ){
      $images = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', get_the_content(), $matches);
      $images = $matches[1];
      if($images){
        foreach($images as $image){
          $imgobj[] = $image;
        }
        echo '"image": ["'.implode('","',$imgobj).'"],';
      }else{
        echo '"image":["'.get_site_icon_url().'"],';
      }
    }else{
      echo '"image":["'.get_site_icon_url().'"],';
    }
  ?>
  "datePublished": "<?php echo get_the_time('Y/m/d');?>",
  "dateModified": "<?php echo get_the_modified_date('Y/m/d') ?>",
  "publisher": {
    "@type": "Organization",
    "name": "<?php echo  get_bloginfo('name'); ?>",
    "logo": {
      "@type": "ImageObject",
      "url": "<?php echo get_site_icon_url(); ?>"
    }
  },
  "author": {
    "@type": "Person",
    "name": "<?php the_author(); ?>"
  }
}
</script>
<?php endif; ?>