<?php
    $canonical = null;
    if( is_home() || is_front_page() ) { //トップページ
      $canonical = home_url('/');
    } elseif ( is_post_type_archive() ) { // カスタム投稿タイプ
      $canonical = get_post_type_archive_link( get_query_var('post_type') );
    } elseif ( is_tax() ) { //タクソノミー
      $canonical = get_term_link(get_queried_object()->term_id);
    } elseif ( is_category() ) { //カテゴリー
      $canonical = get_category_link( get_query_var('cat') );
    } else if(is_tag()){ //タグ
      $canonical = get_tag_link(get_queried_object()->term_id);
    } elseif ( is_search() ) { //検索ページ
      $canonical = get_search_link();
    } elseif ( is_page() || is_single() ) { //固定ページ・シングルページ
      $canonical = get_permalink();
    } else{ //その他のページ
      $canonical = home_url('/');
    }
    echo '<link rel="canonical" href="'.$canonical.'">'."\n";