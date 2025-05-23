<?php
class acfProfileAvatar{
  public static function render( $avatar, $id_or_email, $size, $default, $alt ) {
    // Get user by id or email
    if ( is_numeric( $id_or_email ) ) {
      $id   = (int) $id_or_email;
      $user = get_user_by( 'id' , $id );
    } elseif ( is_object( $id_or_email ) ) {
      if ( ! empty( $id_or_email->user_id ) ) {
        $id   = (int) $id_or_email->user_id;
        $user = get_user_by( 'id' , $id );
      }
    } else {
      $user = get_user_by( 'email', $id_or_email );
    }
    if ( ! $user ) {
      return $avatar;
    }
    // Get the user id
    $user_id = $user->ID;
    // Get the file id
    $image_id = get_user_meta($user_id, 'author_thumbnail', true); // CHANGE TO YOUR FIELD NAME
    // Bail if we don't have a local avatar
    if ( ! $image_id ) {
      return $avatar;
    }
    // Get the file size
    $image_url  = wp_get_attachment_image_src( $image_id, 'thumbnail' ); // Set image size by name
    // Get the file url
    $avatar_url = $image_url[0];
    if(is_admin()){
      $avatar = '<img alt="' . $alt . '" src="' . $avatar_url . '" class="rounded-pill avatar avatar-' . $size .' height="'.$size.'" width="'.$size.'" >';
    }else{
    // Get the img markup
      $avatar = '<img alt="' . $alt . '" src="' . $avatar_url . '" class="w-100 bhb-ratio-1x1 rounded-pill avatar avatar-' . $size . '">';
    }
    // Return our new avatar
    return $avatar;
  }
}