<?php

// ==============================
// Create SMS User Role
// ==============================
function create_sms_user_role() {
    add_role(
        'sms_user',
        'SMS User',
        array(
            'read' => true,
            'edit_posts' => false,
            'delete_posts' => false,
            'edit_sms_sms'         => true,
            'read_sms_sms'         => true,
            'delete_sms_sms'       => false,
            'edit_sms_smss'        => true,
            'edit_others_sms_smss' => false,
            'publish_sms_smss'     => true,
            'read_private_sms_smss'=> false,
            'upload_files' => true,
            'edit_users'   => false,
        )
    );
}
add_action('init', 'create_sms_user_role');


// ==============================
// Redirect SMS User to dashboard
// ==============================
function sms_user_login_redirect($redirect_to, $request, $user) {
    if (isset($user->roles) && in_array('sms_user', $user->roles, true)) {
        return site_url('/sms-dashboard/');
    }
    return $redirect_to;
}
add_filter('login_redirect', 'sms_user_login_redirect', 10, 3);


// ==============================
// Profile Form Shortcode
// ==============================
function sms_dashboard_profile_form() {
    if (!is_user_logged_in()) {
        return '<p>You must be logged in.</p>';
    }

    $user_id = get_current_user_id();
    $user = get_userdata($user_id);
    $output = '';

    if (isset($_POST['sms_update_profile'])) {
        check_admin_referer('sms_update_profile_action', 'sms_update_profile_nonce');

        wp_update_user([
            'ID'         => $user_id,
            'first_name' => sanitize_text_field($_POST['first_name']),
            'last_name'  => sanitize_text_field($_POST['last_name']),
            'description'=> sanitize_textarea_field($_POST['bio']),
        ]);

        if (!empty($_FILES['profile_picture']['name'])) {
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            $attachment_id = media_handle_upload('profile_picture', 0);
            if (!is_wp_error($attachment_id)) {
                update_user_meta($user_id, 'profile_picture', $attachment_id);
            }
        }

        $output .= '<p class="success">Profile updated!</p>';
    }

    $output .= '<form method="post" enctype="multipart/form-data">';
    $output .= wp_nonce_field('sms_update_profile_action','sms_update_profile_nonce', true, false);
    $output .= '<label>First Name</label><input type="text" name="first_name" value="' . esc_attr($user->first_name) . '">';
    $output .= '<label>Last Name</label><input type="text" name="last_name" value="' . esc_attr($user->last_name) . '">';
    $output .= '<label>Bio</label><textarea name="bio">' . esc_textarea($user->description) . '</textarea>';
    $output .= '<label>Profile Picture</label><input type="file" name="profile_picture">';
    $output .= '<button type="submit" name="sms_update_profile">Update Profile</button>';
    $output .= '</form>';

    return $output;
}
add_shortcode('sms_profile_form', 'sms_dashboard_profile_form');


// ==============================
// List SMS Shortcode
// ==============================
function sms_dashboard_list() {
    if (!is_user_logged_in()) return '';

    $user_id = get_current_user_id();
    $query = new WP_Query([
        'post_type'      => 'sms_sms',
        'author'         => $user_id,
        'posts_per_page' => -1,
    ]);

    if (!$query->have_posts()) {
        return '<p>You have no SMS posts yet.</p>';
    }

    $output = '<h3>Your SMS</h3><ul>';
    while ($query->have_posts()) {
        $query->the_post();
        $output .= '<li>' . esc_html(get_the_title()) .
                   ' <a href="' . esc_url(get_delete_post_link(get_the_ID())) . '">Delete</a></li>';
    }
    $output .= '</ul>';

    wp_reset_postdata();
    return $output;
}
add_shortcode('sms_list', 'sms_dashboard_list');


// ==============================
// Add SMS Shortcode
// ==============================
function sms_dashboard_add_form() {
    if (!is_user_logged_in()) return '';

    $output = '';
    if (isset($_POST['sms_add_post'])) {
        check_admin_referer('sms_add_post_action', 'sms_add_post_nonce');

        wp_insert_post([
            'post_type'   => 'sms_sms',
            'post_title'  => sanitize_text_field($_POST['sms_title']),
            'post_status' => 'publish',
            'post_author' => get_current_user_id(),
        ]);

        $output .= '<p class="success">SMS Added!</p>';
    }

    $output .= '<form method="post">';
    $output .= wp_nonce_field('sms_add_post_action','sms_add_post_nonce', true, false);
    $output .= '<label>SMS Title</label><input type="text" name="sms_title" required>';
    $output .= '<button type="submit" name="sms_add_post">Add SMS</button>';
    $output .= '</form>';

    return $output;
}
add_shortcode('sms_add_form', 'sms_dashboard_add_form');

