<div class="wrap">
  <h1>サイト独自設定</h1>
  <p>独自設定はこちらで行います</p>
  <form method="post" action="options.php">
    <?php 
      settings_fields( 'orign_extend_settings_group' );
      do_settings_sections( 'orign_extend_settings_group' );
    ?>
    <table class="form-table">
      <tbody>
        <tr>
          <th scope="row">
          <label for="gtm_setting">GTMコード</label>
          </th>
          <td><input type="text" id="gtm_setting" class="regular-text" name="gtm_setting" value="<?php echo get_option('gtm_setting'); ?>" ></td>
        </tr>
        <tr>
          <th scope="row">
          <label for="og_img_url">デフォルトOG画像(Now Printing兼用)</label>
          </th>
          <td><input type="text" id="og_img_url" class="regular-text" name="og_img_url" value="<?php echo get_option('og_img_url'); ?>" ></td>
        </tr>
      </tbody>
    </table>
    <?php submit_button(); ?>
  </form>
</div>