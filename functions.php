<?php
// safe helper function for ACF "get_field" to protect theme dependency on ACF.
function safe_get_field($field_name, $default = '') {
    return function_exists('get_field') ? get_field($field_name) : $default;
}

// ACF JSON save point for saving acf field-and-post groups
add_filter('acf/settings/save_json', function() {
    return get_stylesheet_directory() . '/acf-json';
});

// ACF JSON load point  
add_filter('acf/settings/load_json', function($paths) {
    $paths[] = get_stylesheet_directory() . '/acf-json';
    return $paths;
});
// functions partials
require_once get_stylesheet_directory() . '/lib/_assets.php';
require_once get_stylesheet_directory() . '/lib/_authorData.php';
require_once get_stylesheet_directory() . '/lib/_smsUserGroup.php';
require_once get_stylesheet_directory() . '/lib/_users.php';
require_once get_stylesheet_directory() . '/lib/_ajaxHandler.php';

// fixes for admin sms listings labeled as Auto Draft
add_action('acf/save_post', function($post_id) {

    // Only run for 'sms' post type
    if (get_post_type($post_id) !== 'sms_sms') return;

    // Only if the post title is empty
    $post = get_post($post_id);
    if (!empty($post->post_title) && $post->post_title !== 'Auto Draft') return;

    // Get the sms_text ACF field
    $sms_text = function_exists('get_field') ? get_field('sms_text', $post_id) : get_the_content($post_id);
    if (!$sms_text) return;

    // Create a title from the first 5 words (adjust as needed)
    $words = wp_trim_words($sms_text, 5, '...');
    $title = $words ?: 'SMS #' . $post_id;

    // Update the post title without triggering infinite loop
    remove_action('acf/save_post', __FUNCTION__); 
    wp_update_post([
        'ID'         => $post_id,
        'post_title' => $title,
    ]);
    add_action('acf/save_post', __FUNCTION__);
}, 20);




