<?php

function show_admin_bar_for_admins_only($show)
{
    if (current_user_can('administrator')) {
        return true;
    }
    return false;
}
add_filter('show_admin_bar', 'show_admin_bar_for_admins_only');

function mytheme_setup()
{
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    register_nav_menus(array(
        'primary_menu' => __('Primary Menu', 'currencyexchange'),
        'company_team' => __('Company And Team', 'currencyexchange'),
        'wise_product' => __('Wise Products', 'currencyexchange'),
        'resources'    => __('Resources', 'currencyexchange'),
        'copyright'    => __('Copyright', 'currencyexchange'),
    ));
    add_theme_support('custom-logo');
}
add_action('after_setup_theme', 'mytheme_setup');

require_once get_template_directory() . '/assets/dependencies/inc/wp-bootstrap-navwalker.php';

function mytheme_enqueue_assets()
{
    wp_enqueue_style('mytheme-style', get_stylesheet_uri());
     wp_enqueue_style(
        'my-style',
        get_stylesheet_directory_uri() . '/assets/css/main.css',
        [],
        filemtime(get_stylesheet_directory() . '/assets/css/main.css')
    );

    wp_enqueue_style(
        'fontawesome-css',
        get_template_directory_uri() . '/assets/dependencies/fontawesome/css/all.min.css'
    );
    wp_enqueue_style(
        'bootstrap-min-css',
        get_template_directory_uri() . '/assets/dependencies/bootstrap/css/bootstrap.min.css'
    );
    wp_enqueue_style(
        'sweetalert2-css',
        'https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css',
        array(),
        null
    );
    wp_enqueue_script(
        'bootstrap-js',
        get_template_directory_uri() . '/assets/dependencies/bootstrap/js/bootstrap.bundle.min.js',
        array('jquery'),
        null,
        true
    );
    wp_enqueue_script(
        'sweetalert2-js',
        'https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js',
        array('jquery'),
        null,
        true
    );
    // wp_enqueue_script(
    //     'mytheme-function',
    //     get_template_directory_uri() . '/assets/js/main.js',
    //     array('jquery', 'sweetalert2-js'),
    //     null,
    //     true
    // );

    wp_enqueue_script(
        'mytheme-function',
        get_template_directory_uri() . '/assets/js/main.js',
        ['jquery','sweetalert2-js'],
        filemtime(get_template_directory() . '/assets/js/main.js'),
        true
    );
    wp_localize_script('mytheme-function', 'custom_ajax', [
        'ajax_url'     => admin_url('admin-ajax.php'),
        'nonce'        => wp_create_nonce('mytheme_global_nonce'),
        'redirect_url' => home_url(),
        'login_url'    => site_url('/login'),
        'is_logged_in' => is_user_logged_in(),
    ]);
}
add_action('wp_enqueue_scripts', 'mytheme_enqueue_assets');



function mytheme_customize_register($wp_customize)
{
    $wp_customize->add_section('contact_info_section', array(
        'title' => __('Contact Info', 'mytheme'),
        'priority' => 30,
        'description' => __('Add your mobile number, email, and location here.', 'mytheme'),
    ));
    $wp_customize->add_setting('contact_mobile', array(
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('contact_mobile', array(
        'label' => __('Mobile Number', 'mytheme'),
        'section' => 'contact_info_section',
        'type' => 'text',
    ));
    $wp_customize->add_setting('contact_email', array(
        'default' => '',
        'sanitize_callback' => 'sanitize_email',
    ));
    $wp_customize->add_control('contact_email', array(
        'label' => __('Email Address', 'mytheme'),
        'section' => 'contact_info_section',
        'type' => 'email',
    ));
    $wp_customize->add_setting('contact_location', array(
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('contact_location', array(
        'label' => __('Location', 'mytheme'),
        'section' => 'contact_info_section',
        'type' => 'text',
    ));
    $wp_customize->add_section('offer_text_section', array(
        'title' => __('Offer Settings', 'mytheme'),
        'priority' => 30,
    ));
    $wp_customize->add_setting('offer_text', array(
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('offer_text', array(
        'label' => __('Offer Text', 'mytheme'),
        'section' => 'offer_text_section',
        'type' => 'text',
    ));
    $wp_customize->add_section('footer_about_section', array(
        'title' => __('Footer About & Social', 'mytheme'),
        'priority' => 31,
        'description' => __('Manage footer logo, description and social media links.', 'mytheme'),
    ));
    $wp_customize->add_setting('footer_logo', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'footer_logo', array(
        'label' => __('Footer Logo', 'mytheme'),
        'section' => 'footer_about_section',
        'settings' => 'footer_logo',
    )));
    $wp_customize->add_setting('footer_about_text', array(
        'default' => '',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    $wp_customize->add_control('footer_about_text', array(
        'label' => __('Footer About Text', 'mytheme'),
        'section' => 'footer_about_section',
        'type' => 'textarea',
    ));
    $socials = array('facebook', 'twitter', 'instagram', 'youtube');
    foreach ($socials as $social) {
        $wp_customize->add_setting("footer_social_{$social}", array(
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
        ));
        $wp_customize->add_control("footer_social_{$social}", array(
            'label' => ucfirst($social) . ' URL',
            'section' => 'footer_about_section',
            'type' => 'url',
        ));
    }
}
add_action('customize_register', 'mytheme_customize_register');

// Handle AJAX signup************************

function custom_user_registration_handler()
{
    if (! isset($_POST['security']) || ! wp_verify_nonce($_POST['security'], 'mytheme_global_nonce')) {
        wp_send_json(['success' => false, 'message' => 'Security check failed.']);
    }
    $name     = sanitize_text_field($_POST['name']);
    $email    = sanitize_email($_POST['email']);
    $number   = sanitize_text_field($_POST['number']);
    $password = sanitize_text_field($_POST['password']);
    $confirm  = sanitize_text_field($_POST['confirm_password']);
    if (empty($name) || empty($email) || empty($number) || empty($password) || empty($confirm)) {
        wp_send_json(['success' => false, 'message' => 'All fields are required.']);
    }
    if ($password !== $confirm) {
        wp_send_json(['success' => false, 'message' => 'Passwords do not match.']);
    }
    if (email_exists($email) || username_exists($email)) {
        wp_send_json(['success' => false, 'message' => 'Email already registered.']);
    }
    if (empty($_POST['captcha'])) {
        wp_send_json(['success' => false, 'message' => 'Captcha is required.']);
    }
    $response = sanitize_text_field($_POST['captcha']);
    $remoteip = $_SERVER['REMOTE_ADDR'];
    $secret_key = RECAPTCHA_SECRET_KEY;

    $verify   = wp_remote_get(
        "https://www.google.com/recaptcha/api/siteverify?secret={$secret_key}&response={$response}&remoteip={$remoteip}"
    );

    $verified = json_decode(wp_remote_retrieve_body($verify));

    if (empty($verified->success)) {
        wp_send_json(['success' => false, 'message' => 'Captcha verification failed.']);
    }

    $username = sanitize_user(current(explode('@', $email)), true);
    if (username_exists($username)) {
        $username .= rand(1000, 9999);
    }
    $user_id = wp_create_user($username, $password, $email);
    if (is_wp_error($user_id)) {
        wp_send_json(['success' => false, 'message' => $user_id->get_error_message()]);
    }
    $user = new WP_User($user_id);
    $user->set_role('subscriber');
    update_user_meta($user_id, 'full_name', $name);
    update_user_meta($user_id, 'mobile_number', $number);
    wp_send_json(['success' => true, 'message' => 'Registration successful!']);
}
add_action('wp_ajax_nopriv_custom_user_registration', 'custom_user_registration_handler');
add_action('wp_ajax_custom_user_registration', 'custom_user_registration_handler');


// Handle AJAX login************************

function custom_user_login()
{
    if (
        ! isset($_POST['security'])
        || ! wp_verify_nonce($_POST['security'], 'mytheme_global_nonce')
    ) {
        wp_send_json(['success' => false, 'message' => 'Security check failed.']);
    }
    if (empty($_POST['captcha'])) {
        wp_send_json(['success' => false, 'message' => 'Captcha is required.']);
    }
    $response = sanitize_text_field($_POST['captcha']);
    $remoteip = $_SERVER['REMOTE_ADDR'];
    $secret_key = RECAPTCHA_SECRET_KEY;

    $verify   = wp_remote_get(
        "https://www.google.com/recaptcha/api/siteverify?secret={$secret_key}&response={$response}&remoteip={$remoteip}"
    );

    $verified = json_decode(wp_remote_retrieve_body($verify));

    if (empty($verified->success)) {
        wp_send_json(['success' => false, 'message' => 'Captcha verification failed.']);
    }
    $email    = sanitize_email($_POST['email'] ?? '');
    $password = sanitize_text_field($_POST['password'] ?? '');
    $remember = isset($_POST['remember']) && $_POST['remember'] === 'true';

    if (empty($email) || empty($password)) {
        wp_send_json(['success' => false, 'message' => 'All fields are required.']);
    }

    $user = get_user_by('email', $email);
    if (! $user) {
        wp_send_json(['success' => false, 'message' => 'Invalid email or password.']);
    }

    $creds = [
        'user_login'    => $user->user_login,
        'user_password' => $password,
        'remember'      => $remember,
    ];

    $user_signon = wp_signon($creds, false);

    if (is_wp_error($user_signon)) {
        wp_send_json(['success' => false, 'message' => 'Invalid email or password.']);
    }

    wp_set_current_user($user_signon->ID);
    wp_set_auth_cookie($user_signon->ID, $remember);

    wp_send_json([
        'success'  => true,
        'message'  => 'Login successful!',
        'redirect' => site_url('/account')
    ]);
}
add_action('wp_ajax_nopriv_custom_user_login', 'custom_user_login');
add_action('wp_ajax_custom_user_login', 'custom_user_login');


// Handle AJAX Otp************************

function custom_send_otp()
{
    if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'mytheme_global_nonce')) {
        wp_send_json(['success' => false, 'message' => 'Security check failed.']);
    }
    $email = sanitize_email($_POST['email'] ?? '');
    $user = get_user_by('email', $email);
    if (!$user) {
        wp_send_json(['success' => false, 'message' => 'No user found with this email.']);
    }
    if (empty($_POST['captcha'])) {
        wp_send_json(['success' => false, 'message' => 'Captcha is required.']);
    }
    $response = sanitize_text_field($_POST['captcha']);
    $remoteip = $_SERVER['REMOTE_ADDR'];
    $secret_key = RECAPTCHA_SECRET_KEY;

    $verify   = wp_remote_get(
        "https://www.google.com/recaptcha/api/siteverify?secret={$secret_key}&response={$response}&remoteip={$remoteip}"
    );

    $verified = json_decode(wp_remote_retrieve_body($verify));

    if (empty($verified->success)) {
        wp_send_json(['success' => false, 'message' => 'Captcha verification failed.']);
    }
    $otp = rand(100000, 999999);
    update_user_meta($user->ID, '_reset_password_otp', $otp);
    update_user_meta($user->ID, '_reset_password_otp_expire', time() + 600);
    wp_mail($email, 'Your OTP for Password Reset', "Your OTP is: $otp. It expires in 10 minutes.");
    wp_send_json(['success' => true, 'message' => 'OTP sent sucessfully.', 'otp' => $otp]);
}
add_action('wp_ajax_nopriv_custom_send_otp', 'custom_send_otp');
add_action('wp_ajax_custom_send_otp', 'custom_send_otp');

// Handle AJAX reset Password************************

function custom_reset_password()
{
    if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'mytheme_global_nonce')) {
        wp_send_json(['success' => false, 'message' => 'Security check failed.']);
    }
    $email = sanitize_email($_POST['email'] ?? '');
    $otp = sanitize_text_field($_POST['otp'] ?? '');
    $password = sanitize_text_field($_POST['password'] ?? '');
    if (empty($_POST['captcha'])) {
        wp_send_json(['success' => false, 'message' => 'Captcha is required.']);
    }
    $user = get_user_by('email', $email);
    if (!$user) {
        wp_send_json(['success' => false, 'message' => 'Invalid email.']);
    }
    $saved_otp = get_user_meta($user->ID, '_reset_password_otp', true);
    $otp_expire = get_user_meta($user->ID, '_reset_password_otp_expire', true);
    if ($otp != $saved_otp || time() > $otp_expire) {
        wp_send_json(['success' => false, 'message' => 'OTP is invalid or expired.']);
    }
    wp_set_password($password, $user->ID);
    delete_user_meta($user->ID, '_reset_password_otp');
    delete_user_meta($user->ID, '_reset_password_otp_expire');
    wp_send_json(['success' => true, 'message' => 'Password reset successfully!']);
}
add_action('wp_ajax_nopriv_custom_reset_password', 'custom_reset_password');
add_action('wp_ajax_custom_reset_password', 'custom_reset_password');



function custom_location_search()
{
    global $wpdb;
    if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'mytheme_global_nonce')) {
        wp_send_json(['success' => false, 'message' => 'Security check failed.']);
    }
    $search       = sanitize_text_field($_POST['search'] ?? '');
    $sort         = sanitize_text_field($_POST['sort'] ?? 'latest');
    $user_id      = get_current_user_id();
    $user_country = get_user_meta($user_id, 'country', true);
    $order_by = "p.post_date DESC";
    switch ($sort) {
        case 'asc':
            $order_by = "p.post_title ASC";
            break;
        case 'desc':
            $order_by = "p.post_title DESC";
            break;
        case 'oldest':
            $order_by = "p.post_date ASC";
            break;
        case 'latest':
            $order_by = "p.post_date DESC";
            break;
    }
    $sql = "
        SELECT DISTINCT p.ID, p.post_title, p.post_date
        FROM {$wpdb->posts} p
        LEFT JOIN {$wpdb->postmeta} pm ON (p.ID = pm.post_id)
        WHERE p.post_type = 'locations'
          AND p.post_status = 'publish'
    ";
    if (is_user_logged_in() && $user_country && strtolower($user_country) !== 'all') {
        $country_like = '%' . $wpdb->esc_like($user_country) . '%';
        $sql .= $wpdb->prepare(" 
            AND EXISTS (
                SELECT 1 FROM {$wpdb->postmeta} pmc
                WHERE pmc.post_id = p.ID 
                  AND pmc.meta_key = 'country'
                  AND pmc.meta_value LIKE %s
            )
        ", $country_like);
    }
    if (!empty($search)) {
        $like = '%' . $wpdb->esc_like($search) . '%';
        $sql .= $wpdb->prepare("
            AND (
                p.post_title LIKE %s
                OR (pm.meta_key IN ('number','email','location') AND pm.meta_value LIKE %s)
            )
        ", $like, $like);
    }
    $sql .= " ORDER BY $order_by";
    $results = $wpdb->get_results($sql);
    ob_start();
    if ($results) {
        $delay = 200;
        foreach ($results as $row) {
            $image_url = has_post_thumbnail($row->ID)
                ? get_the_post_thumbnail_url($row->ID, 'large')
                : get_template_directory_uri() . '/assets/images/default-location.png';
            $phone   = get_post_meta($row->ID, 'number', true);
            $email   = get_post_meta($row->ID, 'email', true);
            $address = get_post_meta($row->ID, 'location', true);
            $timing  = get_post_meta($row->ID, 'timing', true);
            ?>
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-duration="800" data-aos-delay="<?php echo esc_attr($delay); ?>">
                <div class="card location-card text-white">
                    <img src="<?php echo esc_url($image_url); ?>" class="card-img" alt="<?php echo esc_attr($row->post_title); ?>" />
                    <div class="card-img-overlay">
                        <h4 class="fw-bold"><?php echo esc_html($row->post_title); ?></h4>
                        <?php if ($phone) : ?><p><i class="fas fa-phone me-2"></i><?php echo esc_html($phone); ?></p><?php endif; ?>
                        <?php if ($email) : ?><p><i class="fas fa-envelope me-2"></i><?php echo esc_html($email); ?></p><?php endif; ?>
                        <?php if ($address) : ?><p><i class="fas fa-map-marker-alt me-2"></i><?php echo esc_html($address); ?></p><?php endif; ?>
                        <?php if ($timing) : ?><p><i class="fas fa-clock me-2"></i><?php echo esc_html($timing); ?></p><?php endif; ?>
                    </div>
                </div>
            </div>
            <?php
            $delay += 100;
        }
    } else {
        echo '<p>No locations found.</p>';
    }
    $html = ob_get_clean();
    wp_send_json([
        'success' => true,
        'html'    => $html,
    ]);
}

add_action('wp_ajax_custom_location_search', 'custom_location_search');
add_action('wp_ajax_nopriv_custom_location_search', 'custom_location_search');



function custom_currency_search()
{
    global $wpdb;
    if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'mytheme_global_nonce')) {
        wp_send_json(['success' => false, 'message' => 'Security check failed.']);
    }
    $search  = sanitize_text_field($_POST['search'] ?? '');
    $sort    = sanitize_text_field($_POST['sort'] ?? 'latest');
    $user_id = get_current_user_id();
    $user_country = get_user_meta($user_id, 'country', true);
    $order_by = "p.post_date DESC";
    switch ($sort) {
        case 'asc':
            $order_by = "p.post_title ASC";
            break;
        case 'desc':
            $order_by = "p.post_title DESC";
            break;
        case 'oldest':
            $order_by = "p.post_date ASC";
            break;
        case 'latest':
        default:
            $order_by = "p.post_date DESC";
            break;
    }
    $sql = "
        SELECT DISTINCT p.ID, p.post_title, p.post_date
        FROM {$wpdb->posts} p
        LEFT JOIN {$wpdb->postmeta} pm ON (p.ID = pm.post_id)
        WHERE p.post_type = 'currency'
          AND p.post_status = 'publish'
    ";
    if (is_user_logged_in() && $user_country && strtolower($user_country) !== 'all') {
        $sql .= $wpdb->prepare(" 
            AND EXISTS (
                SELECT 1 FROM {$wpdb->postmeta} pmc
                WHERE pmc.post_id = p.ID 
                  AND pmc.meta_key = 'country'
                  AND pmc.meta_value = %s
            )
        ", $user_country);
    }
    if (!empty($search)) {
        $like = '%' . $wpdb->esc_like($search) . '%';
        $sql .= $wpdb->prepare("
            AND (
                p.post_title LIKE %s
                OR (pm.meta_key IN ('country','current_price','change_rate') AND pm.meta_value LIKE %s)
            )
        ", $like, $like);
    }

    $sql .= " ORDER BY $order_by";

    $results = $wpdb->get_results($sql);

    ob_start();

    if ($results) :
        foreach ($results as $row) :
            $country       = get_post_meta($row->ID, 'country', true);
            $current_price = get_post_meta($row->ID, 'current_price', true);
            $change_rate   = get_post_meta($row->ID, 'change_rate', true);

            $change_class = '';
            if ($change_rate > 0) {
                $change_class = 'positive-change';
                $change_rate  = '+' . $change_rate . '%';
            } elseif ($change_rate < 0) {
                $change_class = 'negative-change';
                $change_rate  = $change_rate . '%';
            } else {
                $change_rate = '0%';
            }
        ?>
            <tr>
                <td><strong><?php echo esc_html($row->post_title); ?></strong></td>
                <td class="d-none d-md-table-cell"><strong><?php echo esc_html($country); ?></strong></td>
                <td><?php echo esc_html($current_price); ?></td>
                <td class="<?php echo esc_attr($change_class); ?>"><?php echo esc_html($change_rate); ?></td>
                <td class="positive-change">
                    <?php
                    $params = 'isds=' . $row->ID;
                    $encoded_params = base64_encode($params);
                    ?>
                    <button
                        class="btn btn-buy mt-0"
                        onclick="window.location.href='<?php echo esc_url(site_url('/buy?data=' . $encoded_params)); ?>'">
                        Buy
                    </button>
                </td>
            </tr>
    <?php
        endforeach;
    else :
        echo '<tr><td colspan="5">No currencies found.</td></tr>';
    endif;

    $html = ob_get_clean();

    wp_send_json([
        'success' => true,
        'html'    => $html,
    ]);
}
add_action('wp_ajax_nopriv_custom_currency_search', 'custom_currency_search');
add_action('wp_ajax_custom_currency_search', 'custom_currency_search');










add_action('wp_ajax_update_user_country', 'update_user_country_callback');
add_action('wp_ajax_nopriv_update_user_country', 'update_user_country_callback');
function update_user_country_callback()
{
    if (!is_user_logged_in()) {
        wp_send_json_error('User not logged in!');
    }
    $user_id  = get_current_user_id();
    $country  = isset($_POST['country']) ? sanitize_text_field($_POST['country']) : '';
    update_user_meta($user_id, 'country', $country);
    wp_send_json_success('Country updated successfully!');
}

// Add custom columns to Currency post type list

function currency_custom_columns($columns)
{
    $new = array();
    foreach ($columns as $key => $title) {
        $new[$key] = $title;
        if ($key === 'title') {
            $new['country']       = __('Country', 'textdomain');
            $new['current_price'] = __('Current Price', 'textdomain');
            $new['change_rate']   = __('Change Rate (%)', 'textdomain');
        }
    }
    return $new;
}
add_filter('manage_currency_posts_columns', 'currency_custom_columns');

function currency_custom_columns_data($column, $post_id)
{
    switch ($column) {
        case 'country':
            $country = get_post_meta($post_id, 'country', true);
            echo esc_html($country ?: '—');
            break;

        case 'current_price':
            $current_price = get_post_meta($post_id, 'current_price', true);
            echo esc_html($current_price ?: '—');
            break;

        case 'change_rate':
            $change_rate = get_post_meta($post_id, 'change_rate', true);
            echo esc_html($change_rate ?: '—');
            break;
    }
}
add_action('manage_currency_posts_custom_column', 'currency_custom_columns_data', 10, 2);

// Register custom columns
function orders_custom_columns($columns)
{
    $new_columns = [];
    $new_columns['cb']    = $columns['cb'];
    $new_columns['title'] = __('Title', 'textdomain');
    $new_columns['currency']      = __('Currency', 'textdomain');
    $new_columns['price']         = __('Price', 'textdomain');
    $new_columns['payment_mode']  = __('Payment Mode', 'textdomain');
    $new_columns['address']       = __('Address', 'textdomain');
    $new_columns['delivery_mode'] = __('Delivery Mode', 'textdomain');
    $new_columns['store']         = __('Store Location', 'textdomain');
    $new_columns['status']         = __('Payment Status', 'textdomain');
    if (isset($columns['date'])) {
        $new_columns['date'] = $columns['date'];
    }
    return $new_columns;
}
add_filter('manage_orders_posts_columns', 'orders_custom_columns');

function orders_custom_columns_data($column, $post_id)
{
    switch ($column) {
        case 'price':
            $price = get_post_meta($post_id, 'price', true);
            echo esc_html($price ?: '—');
            break;

        case 'payment_mode':
            $payment_mode = get_post_meta($post_id, 'payment_mode', true);
            echo esc_html($payment_mode ?: '—');
            break;

        case 'address':
            $address = get_post_meta($post_id, 'address', true);
            echo esc_html($address ?: '—');
            break;

        case 'delivery_mode':
            $delivery_mode = get_post_meta($post_id, 'delivery_mode', true);
            echo esc_html($delivery_mode ?: '—');
            break;
        case 'store':
            $store_id = get_post_meta($post_id, 'store_id', true);
            if ($store_id) {
                $store_title = get_the_title($store_id);
                $store_link  = get_edit_post_link($store_id);
                echo '<a href="' . esc_url($store_link) . '" target="_blank">' . esc_html($store_title) . '</a>';
            } else {
                echo '—';
            }
            break;
        case 'currency':
            $currency_id = get_post_meta($post_id, 'currency_id', true);
            if ($currency_id) {
                $currency_title = get_the_title($currency_id);
                $currency_link  = get_edit_post_link($currency_id);
                echo '<a href="' . esc_url($currency_link) . '" target="_blank">' . esc_html($currency_title) . '</a>';
            } else {
                echo '—';
            }
            break;
        case 'status':
            $status = get_post_meta($post_id, 'status', true) ?: 'Processing';
            $colors = [
                'Processing' => '#f0ad4e',
                'Completed'  => '#5cb85c',
                'Failed'     => '#d9534f',
            ];
            $color = isset($colors[$status]) ? $colors[$status] : '#6c757d';
            echo '<button type="button" style="background-color:' . esc_attr($color) . '; color:#fff; border:none; padding:4px 10px; border-radius:4px;">' . esc_html(ucfirst($status)) . '</button>';
            break;
    }
}
add_action('manage_orders_posts_custom_column', 'orders_custom_columns_data', 10, 2);

add_action('wp_ajax_save_user_address', 'save_user_address');
add_action('wp_ajax_nopriv_save_user_address', 'save_user_address');

function save_user_address()
{
    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => 'User is not logged in.'));
    }
    $user_id = get_current_user_id();
    $name = sanitize_text_field($_POST['name']);
    $email = sanitize_email($_POST['email']);
    $address = sanitize_text_field($_POST['address']);
    $phone = sanitize_text_field($_POST['phone']);

    $user_address = array(
        'name'    => $name,
        'email'   => $email,
        'address' => $address,
        'phone'   => $phone
    );

    $existing_addresses = get_user_meta($user_id, 'user_addresses', true);

    if (!$existing_addresses) {
        $existing_addresses = array();
    }

    $existing_addresses[] = $user_address;

    update_user_meta($user_id, 'user_addresses', $existing_addresses);

    wp_send_json_success(array('message' => 'Address saved successfully.'));
}

add_action('wp_ajax_get_user_addresses', 'get_user_addresses');
add_action('wp_ajax_nopriv_get_user_addresses', 'get_user_addresses');

function get_user_addresses()
{
    if (!is_user_logged_in()) {
        wp_send_json_error(['message' => 'User not logged in.']);
    }
    $user_id = get_current_user_id();
    $addresses = get_user_meta($user_id, 'user_addresses', true);
    if (!$addresses) {
        $addresses = [];
    }
    wp_send_json_success($addresses);
}


add_action('wp_ajax_delete_user_address', 'delete_user_address');
add_action('wp_ajax_nopriv_delete_user_address', 'delete_user_address');

function delete_user_address()
{
    if (!is_user_logged_in()) {
        wp_send_json_error(['message' => 'User not logged in.']);
    }
    $user_id = get_current_user_id();
    $index   = intval($_POST['index']);
    $addresses = get_user_meta($user_id, 'user_addresses', true);
    if (!$addresses || !is_array($addresses)) {
        wp_send_json_error(['message' => 'No addresses found.']);
    }
    if (!isset($addresses[$index])) {
        wp_send_json_error(['message' => 'Invalid address index.']);
    }
    unset($addresses[$index]);
    $addresses = array_values($addresses);
    update_user_meta($user_id, 'user_addresses', $addresses);
    wp_send_json_success(['message' => 'Address deleted successfully.']);
}

add_action('wp_ajax_save_orders_details', 'save_orders_details');
add_action('wp_ajax_nopriv_save_orders_details', 'save_orders_details');

function save_orders_details()
{
    if (! isset($_POST['security']) || ! wp_verify_nonce($_POST['security'], 'mytheme_global_nonce')) {
        wp_send_json(['success' => false, 'message' => 'Security check failed.']);
    }
    $store_id      = isset($_POST['store_id']) ? intval($_POST['store_id']) : 0;
    $currency_id   = isset($_POST['currency_id']) ? intval($_POST['currency_id']) : 0;
    $address       = sanitize_text_field($_POST['address'] ?? '');
    $delivery_mode = sanitize_text_field($_POST['delivery_mode'] ?? '');
    $payment_mode  = sanitize_text_field($_POST['payment_mode'] ?? '');
    $total_price   = floatval($_POST['price'] ?? 0);
    $order_id = wp_insert_post([
        'post_type'   => 'orders',
        'post_title'  => 'temp-order',
        'post_status' => 'publish',
    ]);
    if (is_wp_error($order_id)) {
        wp_send_json_error(['message' => 'Order could not be created']);
    }
    wp_update_post([
        'ID'         => $order_id,
        'post_title' => $order_id
    ]);
    update_post_meta($order_id, 'store_id', $store_id);
    update_post_meta($order_id, 'currency_id', $currency_id);
    update_post_meta($order_id, 'address', $address);
    update_post_meta($order_id, 'delivery_mode', $delivery_mode);
    update_post_meta($order_id, 'payment_mode', $payment_mode);
    update_post_meta($order_id, 'price', $total_price);
    $user_id = get_current_user_id();
    update_post_meta($order_id, 'customer_id', $user_id);
    wp_send_json_success([
        'message'   => 'Order saved successfully!',
        'order_id'  => $order_id,
        'order_num' => $order_id
    ]);
}

use \Stripe\Stripe;
use \Stripe\Checkout\Session;

add_action('wp_ajax_create_stripe_session', 'create_stripe_session');
add_action('wp_ajax_nopriv_create_stripe_session', 'create_stripe_session');

function create_stripe_session()
{
    if (! isset($_POST['security']) || ! wp_verify_nonce($_POST['security'], 'mytheme_global_nonce')) {
        wp_send_json_error(['message' => 'Security check failed']);
    }
    $order_id = intval($_POST['order_id']);
    if (!$order_id) {
        wp_send_json_error(['message' => 'Invalid order ID']);
    }
    $key_secret = get_option('stripe_live_key_secret');
    $price       = get_post_meta($order_id, 'price', true);
    $currency_id = get_post_meta($order_id, 'currency_id', true) ?: 'usd';
    $currency    = get_post_meta($currency_id, 'currency', true) ?: 'usd';
    if (!file_exists(get_template_directory() . '/stripe/vendor/autoload.php')) {
        wp_send_json_error(['message' => 'Stripe library not found']);
    }
    require_once get_template_directory() . '/stripe/vendor/autoload.php';
    \Stripe\Stripe::setApiKey($key_secret);
    $zero_decimal_currencies = [
        'bif',
        'clp',
        'djf',
        'gnf',
        'jpy',
        'kmf',
        'krw',
        'mga',
        'pyg',
        'rwf',
        'ugx',
        'vnd',
        'vuv',
        'xaf',
        'xof',
        'xpf'
    ];
    if (in_array(strtolower($currency), $zero_decimal_currencies, true)) {
        $amount = intval($price);
    } else {
        $amount = intval($price * 100);
    }
    try {
        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => strtolower($currency),
                    'product_data' => [
                        'name' => 'Order #' . $order_id,
                    ],
                    'unit_amount' => $amount,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => home_url('/order-status/?order_id=' . $order_id . '&session_id={CHECKOUT_SESSION_ID}'),
            'cancel_url'  => home_url('/order-status/?order_id=' . $order_id),
        ]);
        wp_send_json_success(['id' => $session->id]);
    } catch (\Exception $e) {
        wp_send_json_error(['message' => 'Stripe error: ' . $e->getMessage()]);
    }
}


function get_currency_symbols()
{
    return [
        'AED' => 'د.إ',
        'AFN' => '؋',
        'ALL' => 'L',
        'AMD' => '֏',
        'ANG' => 'ƒ',
        'AOA' => 'Kz',
        'ARS' => '$',
        'AUD' => 'A$',
        'AWG' => 'ƒ',
        'AZN' => '₼',
        'BAM' => 'KM',
        'BBD' => 'B$',
        'BDT' => '৳',
        'BGN' => 'лв',
        'BHD' => '.د.ب',
        'BIF' => 'FBu',
        'BMD' => '$',
        'BND' => 'B$',
        'BOB' => 'Bs.',
        'BRL' => 'R$',
        'BSD' => '$',
        'BTN' => 'Nu.',
        'BWP' => 'P',
        'BYN' => 'Br',
        'BZD' => 'BZ$',
        'CAD' => 'C$',
        'CDF' => 'FC',
        'CHF' => 'CHF',
        'CLP' => '$',
        'CNY' => '¥',
        'COP' => '$',
        'CRC' => '₡',
        'CUP' => '₱',
        'CVE' => '$',
        'CZK' => 'Kč',
        'DJF' => 'Fdj',
        'DKK' => 'kr',
        'DOP' => 'RD$',
        'DZD' => 'دج',
        'EGP' => '£',
        'ETB' => 'Br',
        'EUR' => '€',
        'FJD' => 'FJ$',
        'FKP' => '£',
        'GBP' => '£',
        'GEL' => '₾',
        'GHS' => 'GH₵',
        'GIP' => '£',
        'GMD' => 'D',
        'GNF' => 'FG',
        'GTQ' => 'Q',
        'GYD' => 'G$',
        'HKD' => 'HK$',
        'HNL' => 'L',
        'HRK' => 'kn',
        'HTG' => 'G',
        'HUF' => 'Ft',
        'IDR' => 'Rp',
        'ILS' => '₪',
        'INR' => '₹',
        'IQD' => 'ع.د',
        'ISK' => 'kr',
        'JMD' => 'J$',
        'JOD' => 'د.ا',
        'JPY' => '¥',
        'KES' => 'KSh',
        'KGS' => 'лв',
        'KHR' => '៛',
        'KMF' => 'CF',
        'KRW' => '₩',
        'KWD' => 'د.ك',
        'KYD' => '$',
        'KZT' => '₸',
        'LAK' => '₭',
        'LBP' => 'ل.ل',
        'LKR' => 'Rs',
        'LRD' => '$',
        'LSL' => 'L',
        'LYD' => 'ل.د',
        'MAD' => 'د.م.',
        'MDL' => 'L',
        'MGA' => 'Ar',
        'MKD' => 'ден',
        'MMK' => 'K',
        'MNT' => '₮',
        'MOP' => 'MOP$',
        'MRU' => 'UM',
        'MUR' => '₨',
        'MVR' => 'Rf',
        'MWK' => 'MK',
        'MXN' => '$',
        'MYR' => 'RM',
        'MZN' => 'MT',
        'NAD' => 'N$',
        'NGN' => '₦',
        'NIO' => 'C$',
        'NOK' => 'kr',
        'NPR' => '₨',
        'NZD' => 'NZ$',
        'OMR' => 'ر.ع',
        'PAB' => 'B/.',
        'PEN' => 'S/.',
        'PGK' => 'K',
        'PHP' => '₱',
        'PKR' => '₨',
        'PLN' => 'zł',
        'PYG' => '₲',
        'QAR' => 'ر.ق',
        'RON' => 'lei',
        'RSD' => 'дин',
        'RUB' => '₽',
        'RWF' => 'FRw',
        'SAR' => 'ر.س',
        'SBD' => 'SI$',
        'SCR' => '₨',
        'SEK' => 'kr',
        'SGD' => 'S$',
        'SHP' => '£',
        'SLL' => 'Le',
        'SOS' => 'Sh.so',
        'SRD' => '$',
        'STN' => 'Db',
        'SZL' => 'E',
        'THB' => '฿',
        'TJS' => 'ЅМ',
        'TMT' => 'm',
        'TND' => 'د.ت',
        'TOP' => 'T$',
        'TRY' => '₺',
        'TTD' => 'TT$',
        'TWD' => 'NT$',
        'TZS' => 'TSh',
        'UAH' => '₴',
        'UGX' => 'USh',
        'USD' => '$',
        'UYU' => '$U',
        'UZS' => "so'm",
        'VND' => '₫',
        'VUV' => 'VT',
        'WST' => 'WS$',
        'XAF' => 'FCFA',
        'XCD' => 'EC$',
        'XOF' => 'CFA',
        'XPF' => 'CFPF',
        'YER' => '﷼',
        'ZAR' => 'R',
        'ZMW' => 'ZK',
        'USDC' => 'USDC'
    ];
}

function get_currency_symbol($currency_code)
{
    $symbols = get_currency_symbols();
    return isset($symbols[strtoupper($currency_code)]) ? $symbols[strtoupper($currency_code)] : '';
}


add_action('admin_menu', 'stripe_keys_admin_menu');
function stripe_keys_admin_menu()
{
    add_menu_page(
        'Stripe Settings',
        'Stripe Settings',
        'manage_options',
        'stripe-keys',
        'render_stripe_keys_page',
        'dashicons-admin-generic',
        80
    );
}

function render_stripe_keys_page()
{
    ?>
    <div class="wrap">
        <h1>stripe API Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('stripe_keys_group');
            do_settings_sections('stripe-keys');
            submit_button();
            ?>
        </form>
    </div>
<?php
}

// stripe credential settins*****************************

add_action('admin_init', 'stripe_keys_register_settings');
function stripe_keys_register_settings()
{
    register_setting('stripe_keys_group', 'stripe_live_key_id');
    register_setting('stripe_keys_group', 'stripe_live_key_secret');
    add_settings_section('stripe_keys_section', 'Live API Keys', null, 'stripe-keys');
    add_settings_field(
        'stripe_live_key_id',
        'Published Key ID',
        'stripe_live_key_id_field',
        'stripe-keys',
        'stripe_keys_section'
    );
    add_settings_field(
        'stripe_live_key_secret',
        'Secret Key',
        'stripe_live_key_secret_field',
        'stripe-keys',
        'stripe_keys_section'
    );
}

function stripe_live_key_id_field()
{
    $value = esc_attr(get_option('stripe_live_key_id'));
    echo "<input type='text' name='stripe_live_key_id' value='$value' class='regular-text'>";
}

function stripe_live_key_secret_field()
{
    $value = esc_attr(get_option('stripe_live_key_secret'));
    echo "<input type='text' name='stripe_live_key_secret' value='$value' class='regular-text'>";
}


function enqueue_recaptcha_script()
{
    wp_enqueue_script('google-recaptcha', 'https://www.google.com/recaptcha/api.js', [], null, true);
}
add_action('wp_enqueue_scripts', 'enqueue_recaptcha_script');


add_action('wp_ajax_nopriv_update_profile', 'update_profile_callback');
add_action('wp_ajax_update_profile', 'update_profile_callback');
function update_profile_callback() {
    if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'update_profile_nonce')) {
        wp_send_json_error(['message'=>'Security check failed']);
    }
    $user_id = intval($_POST['user_id']);
    if (!$user_id) wp_send_json_error(['message'=>'Invalid user']);
    $fullName = sanitize_text_field($_POST['fullName']);
    $mobile   = sanitize_text_field($_POST['mobileNumber']);
    $idType   = sanitize_text_field($_POST['idType']);
    $ssn      = sanitize_text_field($_POST['ssn']);
    $bankName = sanitize_text_field($_POST['bankName']);
    wp_update_user([
        'ID'           => $user_id,
        'display_name' => $fullName,
    ]);
    update_user_meta($user_id, 'full_name', $fullName);
    update_user_meta($user_id, 'mobile_number', $mobile);
    update_user_meta($user_id, 'id_type', $idType);
    update_user_meta($user_id, 'ssn', $ssn);
    update_user_meta($user_id, 'bank_name', $bankName);

    wp_send_json_success(['message'=>'Profile updated successfully']);
}


add_action('wp_ajax_nopriv_change_password', 'change_password_callback');
add_action('wp_ajax_change_password', 'change_password_callback');
function change_password_callback() {
    if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'change_password_nonce')) {
        wp_send_json_error(['message'=>'Security check failed']);
    }
    $user_id = intval($_POST['user_id']);
    $password = sanitize_text_field($_POST['password']);
    $confirmPassword = sanitize_text_field($_POST['confirmPassword']);
    if ($password !== $confirmPassword) {
        wp_send_json_error(['message'=>'Passwords do not match']);
    }
    wp_set_password($password, $user_id);
    wp_send_json_success(['message'=>'Password changed successfully']);
}


