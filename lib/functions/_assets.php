<?php
// import sms live js file
function enqueue_sms_live_script() {
    wp_enqueue_script(
        'sms-live-js',
        get_template_directory_uri() . '/app/js/smsLive.js',
        ['jquery'],
        null,
        true
    );
    wp_localize_script('sms-live-js', 'smsAjax', [
        'ajax_url' => admin_url('admin-ajax.php'),
    ]);
}
add_action('wp_enqueue_scripts', 'enqueue_sms_live_script');

// import main stylesheet css file
function sms_custom_css() {
    wp_enqueue_style(
        'sms-custom-css',
        get_template_directory_uri() . '/dist/css/main.css',
        array(),
        filemtime(get_template_directory() . '/dist/css/main.css'),
        'all'
    );
}
add_action('wp_enqueue_scripts', 'sms_custom_css');

// import main js file
function sms_custom_js() {
    wp_enqueue_script(
        'sms-bundle-js',
        get_stylesheet_directory_uri() . '/dist/js/bundle.js',
        ['jquery'],
        filemtime(get_stylesheet_directory() . '/dist/js/bundle.js'), 
        true 
    );
    
}
add_action('wp_enqueue_scripts', 'sms_custom_js');

