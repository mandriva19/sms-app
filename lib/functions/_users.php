<?php

function register_user_location_meta() {
    register_meta('user', 'user_location', array(
        'type'         => 'string',
        'description'  => 'User location field',
        'single'       => true,
        'show_in_rest' => true, // Makes it available in REST API
    ));
}
add_action('init', 'register_user_location_meta');

add_user_meta(1, 'user_location', 'თბილისი', true);

add_user_meta(7, 'user_location', 'ქუთაისი', true);