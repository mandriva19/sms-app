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

// add_user_meta(1, 'user_location', 'თბილისი', true);
// delete_user_meta(1, 'user_location', 'თბილისი', true);

// add_user_meta(7, 'user_location', 'ქუთაისი', true);

// Show field in user profile (both self-edit and admin-edit)
function show_user_location_field($user) {
    $location = get_user_meta($user->ID, 'user_location', true);
    ?>
    <h3>Additional Information</h3>
    <table class="form-table">
        <tr>
            <th><label for="user_location">Location</label></th>
            <td>
                <input type="text" name="user_location" id="user_location" value="<?php echo esc_attr($location); ?>" class="regular-text" />
                <br><span class="description">Enter your location (city, state, etc.)</span>
            </td>
        </tr>
    </table>
    <?php
}
add_action('show_user_profile', 'show_user_location_field');        // User editing their own profile
add_action('edit_user_profile', 'show_user_location_field');        // Admin editing user profile

// Save the field when profile is updated
function save_user_location_field($user_id) {
    // Check if user can edit this profile
    if (!current_user_can('edit_user', $user_id)) {
        return false;
    }
    
    // Sanitize and save the field
    if (isset($_POST['user_location'])) {
        update_user_meta($user_id, 'user_location', sanitize_text_field($_POST['user_location']));
    }
}
add_action('personal_options_update', 'save_user_location_field');   // User updating their own profile
add_action('edit_user_profile_update', 'save_user_location_field');  // Admin updating user profile