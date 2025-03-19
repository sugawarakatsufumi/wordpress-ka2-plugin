<?php
global $post;
$separator = ""; // Simply change the separator to what ever you need e.g. / or >
$customPostType = array('restaurant','work','hospital'); //custom postの処理に指定
$str_html="";
$str_html .= '<nav class="pankz"><ul class="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">';
if (!is_front_page()) {
  $str_html .= '<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="';
  $str_html .=  get_option('home');
  $str_html .=  '">';
  $str_html .= '<span itemprop="name">'.get_bloginfo('name').'</span>';
  $str_html .= "</a><meta itemprop='position' content='2' /></li> ".$separator;
  if ( is_category() || is_singular('post') ) {//カテゴリー又は記事詳細
    $str_html .= '<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">';
    if( is_single() ){
      $categories = get_the_category();
      $cat_link = get_term_link($categories[0]->slug,'category');
      $cat_link = is_wp_error($cat_link)? 'No Post': $cat_link;
      $cat_name = $categories[0]->name? $categories[0]->name: 'Empty category';
    }elseif(is_category()){
      $categories = get_queried_object();
      $cat_link = get_term_link($categories->slug,'category');
      $cat_link = is_wp_error($cat_link)? 'No Post': $cat_link;
      $cat_name = $categories->name? $categories->name: 'Empty category';
    }
    $str_html .= '<a href="'.$cat_link.'" itemprop="item"><span itemprop="name">'.$cat_name.'</span></a>';
    $str_html .= '<meta itemprop="position" content="3" /></li>';
    if ( is_single() ) {
      $str_html .= $separator;
      $str_html .= "<li  class='breadcrumb-item' itemprop='itemListElement' itemscope itemtype='http://schema.org/ListItem'><a itemprop='item' href='".get_the_permalink()."'>".'<span itemprop="name">'.get_the_title()."</span></a><meta itemprop='position' content='4' /></li>";
    }
  }elseif( is_tax() || is_singular($customPostType) ) {//カテゴリー又は記事詳細
    $str_html .= '<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">';
    if( is_single() ){
      if ($post->post_type == 'restaurant') {
        $taxonomy = 'restaurant-category';
      } elseif ($post->post_type == 'hospital') {
        $taxonomy = 'depart';
      } elseif ($post->post_type == 'work') {
        $taxonomy = 'work-employ-status';
      } else {
        $taxonomy = 'category';
      }
      $terms = get_the_terms($post->ID, $taxonomy);
      //var_dump($taxonomy);
      $term_link = is_wp_error($terms)? 'No Post': get_term_link($terms[0]->term_id, $taxonomy);
      $term_name = $terms[0]->name? $terms[0]->name: 'Empty category';
    }elseif(is_tax()){
      $terms = get_queried_object();
      $term_link = get_term_link($terms->slug, $terms->taxonomy);
      $term_link = is_wp_error($term_link)? 'No Post': $term_link;
      $term_name = $terms->name? $terms->name: 'Empty category';
    }
    $str_html .= '<a href="'.$term_link.'" itemprop="item"><span itemprop="name">'.$term_name.'</span></a>';
    $str_html .= '<meta itemprop="position" content="3" /></li>';
    if ( is_single() ) {
      $str_html .= $separator;
      $str_html .= "<li  class='breadcrumb-item' itemprop='itemListElement' itemscope itemtype='http://schema.org/ListItem'><a itemprop='item' href='".get_the_permalink()."'>".'<span itemprop="name">'.get_the_title()."</span></a><meta itemprop='position' content='4' /></li>";
    }
  }elseif ( is_page() && $post->post_parent ) {
    $home = get_page(get_option('page_on_front'));
    for ($i = count($post->ancestors)-1; $i >= 0; $i--) {
      if (($home->ID) != ($post->ancestors[$i])) {
        $str_html .= '<li  class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="';
        $str_html .= __(get_permalink($post->ancestors[$i])); 
        $str_html .= '">';
        $str_html .= '<span itemprop="name">'.__(get_the_title($post->ancestors[$i])).'</span>';
        $str_html .= "</a><meta itemprop='position' content='3' /></li>".$separator;
      }
    }
    $str_html .= "<li  class='breadcrumb-item' itemprop='itemListElement' itemscope itemtype='http://schema.org/ListItem'><a itemprop='item' href='".get_the_permalink()."'>".'<span itemprop="name">'.get_the_title()."</span></a><meta itemprop='position' content='4' /></li>";
  } elseif (is_page()) {
    $str_html .= "<li  class='breadcrumb-item' itemprop='itemListElement' itemscope itemtype='http://schema.org/ListItem'><a itemprop='item' href='".get_the_permalink()."'>".'<span itemprop="name">'.get_the_title()."</span></a><meta itemprop='position' content='3' /></li>";
  } elseif (is_404()) {
    $str_html .= "<li  class='breadcrumb-item' itemprop='itemListElement' itemscope itemtype='http://schema.org/ListItem'>"."404"."</li>";
  }
} else {
  $str_html .= "<li  class='breadcrumb-item' itemprop='itemListElement' itemscope itemtype='http://schema.org/ListItem'><a itemprop='item' href='".home_url()."'>".'<span itemprop="name">'.get_bloginfo('name')."</span></a> <meta itemprop='position' content='2' /></li>";
}
$str_html .= '</ul></nav>';