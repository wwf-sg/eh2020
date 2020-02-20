<?php
/* ==========================================================================
    Core
    - Wordpress backend and admin helpers
  ========================================================================== */
// require_once('functions/core/add-editor-formats.php');
// require_once('functions/core/add-image-sizes.php');
// require_once('functions/core/add-svg-upload-support.php');
// require_once('functions/core/add-vcard-support.php');
// require_once('functions/core/remove-emoji-scripts.php');
// require_once('functions/core/remove-wp-embed.php');
// require_once('functions/core/remove-json-api-scripts.php');
// require_once('functions/core/remove-wp-xml-scripts.php');
// require_once('functions/core/remove-default-editor.php');
// require_once('functions/core/remove-gutenberg-styles.php');

/* ==========================================================================
    Template
    - Frontend Specific helpers
  ========================================================================== */
// require_once('functions/template/get-template-partial.php');
// require_once('functions/template/limit-chars.php');
// require_once('functions/template/limit-words.php');
// require_once('functions/template/slugify.php');
// require_once('functions/template/check-if-ajax.php');
// require_once('functions/template/get-file-type.php');
// require_once('functions/template/pretty-print.php');

/* ==========================================================================
    Plugin
    - Plugin only helpers
  ========================================================================== */
// require_once('functions/plugin/yoast-seo.php');

/* ==========================================================================
    Custom
    - Site specific custom actions or util functions
  ========================================================================== */

/* ==========================================================================
    Shortcodes
    - Custom shortcodes from the /shortcodes directory
  ========================================================================== */

/* ==========================================================================
    Required
    - DO NOT REMOVE - required to setup the script enqueuing
  ========================================================================== */



/**
 * Downloads an image from the specified URL and attaches it to a post as a post thumbnail.
 *
 * @param string $file    The URL of the image to download.
 * @param int    $post_id The post ID the post thumbnail is to be associated with.
 * @param string $desc    Optional. Description of the image.
 * @return string|WP_Error Attachment ID, WP_Error object otherwise.
 */
function Generate_Featured_Image($file, $post_id, $desc)
{

  require_once(ABSPATH . "wp-admin" . '/includes/image.php');
  require_once(ABSPATH . "wp-admin" . '/includes/file.php');
  require_once(ABSPATH . "wp-admin" . '/includes/media.php');

  // Set variables for storage, fix file filename for query strings.
  preg_match('/[^\?]+\.(jpe?g|jpg|gif|png)\b/i', $file, $matches);
  if (!$matches) {
    return new WP_Error('image_sideload_failed', __('Invalid image URL'));
  }

  $file_array = array();
  $file_array['name'] = basename($matches[0]);

  // Download file to temp location.
  $file_array['tmp_name'] = download_url($file);

  // If error storing temporarily, return the error.
  if (is_wp_error($file_array['tmp_name'])) {
    return $file_array['tmp_name'];
  }
  // Do the validation and storage stuff.
  $id = media_handle_sideload($file_array, $post_id, $desc);

  // If error storing permanently, unlink.
  if (is_wp_error($id)) {
    @unlink($file_array['tmp_name']);
    return $id;
  }

  set_post_thumbnail($post_id, $id);

  return $id;
}

if (isset($_GET['test'])) {

  $url = "http://157.230.44.162/imgs/44899a735279ea0e75ff5c72af2a4c2d.png";

  Generate_Featured_Image($file = $url, $post_id = 3, $desc = '');
}


// require_once 'functions/generate-image/index.php';
require_once 'functions/required.php';
require_once 'functions/signatures.php';
