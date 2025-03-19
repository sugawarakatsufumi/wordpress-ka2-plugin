<?php
//権威の共通コンテンツを移植
function keni_cc_custom_init() {
	register_post_type( 'keni_cc_custom', array(
		'labels'            => array(
			'name'                => __( '共通コンテンツ', 'theme-name' ),
			'singular_name'       => __( '共通コンテンツ', 'theme-name' ),
			'all_items'           => __( '共通コンテンツ一覧', 'theme-name' ),
			'new_item'            => __( '新規投稿を追加', 'theme-name' ),
			'add_new'             => __( '新規追加', 'theme-name' ),
			'add_new_item'        => __( '新しい共通コンテンツを追加', 'theme-name' ),
			'edit_item'           => __( '共通コンテンツを編集', 'theme-name' ),
			'view_item'           => __( '共通コンテンツを表示', 'theme-name' ),
			'search_items'        => __( '共通コンテンツを検索', 'theme-name' ),
			'not_found'           => __( '共通コンテンツが見つかりませんでした。', 'theme-name' ),
			'not_found_in_trash'  => __( 'ゴミ箱内に共通コンテンツが見つかりませんでした。', 'theme-name' ),
			'parent_item_colon'   => __( 'Parent store', 'theme-name' ),
			'menu_name'           => __( '共通コンテンツ', 'theme-name' ),
		),
		'public'            => true,
		'hierarchical'      => false,
		'show_ui'           => true,
		'show_in_nav_menus' => true,
		'supports'          => array( 'title', 'editor' ),
		'has_archive'       => false,
		'rewrite'           => true,
		'query_var'         => true,
		'menu_icon'         => 'dashicons-media-code',
		'show_in_rest'      => true,
		'rest_base'         => 'keni_cc_custom',
		'menu_position'     => 5,
		'exclude_from_search' => true,
		'rest_controller_class' => 'WP_REST_Posts_Controller',
	) );

}
add_action( 'init', 'keni_cc_custom_init' );

function keni_cc_custom_updated_messages( $messages ) {
	global $post;

	$permalink = get_permalink( $post );

	$messages['keni_cc_custom'] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => sprintf( __('Keni cc updated. <a target="_blank" href="%s">View keni cc</a>', 'keni'), esc_url( $permalink ) ),
		2 => __('Custom field updated.', 'keni'),
		3 => __('Custom field deleted.', 'keni'),
		4 => __('Keni cc updated.', 'keni'),
		/* translators: %s: date and time of the revision */
		5 => isset($_GET['revision']) ? sprintf( __('Keni cc restored to revision from %s', 'keni'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => sprintf( __('Keni cc published. <a href="%s">View keni cc</a>', 'keni'), esc_url( $permalink ) ),
		7 => __('Keni cc saved.', 'keni'),
		8 => sprintf( __('Keni cc submitted. <a target="_blank" href="%s">Preview keni cc</a>', 'keni'), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
		9 => sprintf( __('Keni cc scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview keni cc</a>', 'keni'),
		// translators: Publish box date format, see https://secure.php.net/manual/en/function.date.php
		date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( $permalink ) ),
		10 => sprintf( __('Keni cc draft updated. <a target="_blank" href="%s">Preview keni cc</a>', 'keni'), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
	);

	return $messages;
}
add_filter( 'post_updated_messages', 'keni_cc_custom_updated_messages' );


function get_keni_common_contents( $atts, $comm_post_id ) {
	$id = null;
	extract( shortcode_atts( array(
		'id' => $comm_post_id,
	), $atts ) );
	$content = get_post( $id, "ARRAY_A" );

	if ( isset( $content['post_content'] ) && $content['post_status'] == "publish" ) {
		return do_shortcode( $content['post_content'] );
	} else {
		return "";
	}
}
add_shortcode( 'cc', 'get_keni_common_contents' );

//-----------------------------------------------------
// admin 共通コンテンツ パーマリンク非表示
//-----------------------------------------------------
add_filter( 'get_sample_permalink_html' , 'keni_admin_permalink' );
function keni_admin_permalink( $permalink_html ){
	global $post;

	if( $post->post_type == 'keni_cc_custom' || preg_match( '/post_type=keni_cc_custom/', $permalink_html ) ){
		$permalink_html = false;
	}
	return $permalink_html;
}

//-----------------------------------------------------
// admin 共通コンテンツ一覧
//-----------------------------------------------------

function keni_add_posts_columns_common_contents( $columns ) {
	$columns['shortcode'] = __('Short Code', 'keni');
	return $columns;
}
function keni_add_posts_columns_common_contents_list( $column_name, $post_id ) {
	$screen = get_current_screen();
	if ( $screen ->post_type == 'keni_cc_custom' ) {
		if ( $column_name == 'shortcode' ) {
			echo ( ! empty( $post_id ) ) ? '<input type="text" value="[cc id=' . $post_id . ']" readonly="">' : '';
		}
	}
}
add_filter( 'manage_edit-keni_cc_custom_columns', 'keni_add_posts_columns_common_contents' );
add_action( 'manage_keni_cc_custom_posts_custom_column', 'keni_add_posts_columns_common_contents_list', 10, 2 );

function keni_posts_columns_common_contents( $ch ){
	$ch = array(
		'title' => __('Title', 'keni'),
		'shortcode' => __('Short Code', 'keni'),
		'date' => __('Date', 'keni'),
	);
	return $ch;
}
add_filter( 'manage_keni_cc_custom_posts_columns', 'keni_posts_columns_common_contents' );
