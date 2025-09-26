<!doctype html>
<html lang="en">

<head>
    <?php wp_head(); ?>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Foreign Currency Exchange</title>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />
</head>
<style>
    .admin-navbar {
        top: 32px !important;
    }

    @media (max-width: 782px) {
        .admin-navbar {
            top: 42px !important;
        }
    }
</style>
<?php
$user_id  = get_current_user_id();
$saved_country = $user_id ? get_user_meta($user_id, 'country', true) : '';
?>

<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-custom fixed-top <?php if (current_user_can('administrator')) echo 'admin-navbar'; ?>">
            <div class="container">
                <a class="navbar-brand" href="<?php echo site_url(); ?>">Foreign Currency Exchange</a>
                <?php if (is_user_logged_in() && (is_page('currency') || is_page('location'))) : ?>
                    <?php
                    $args = array(
                        'post_type'      => 'currency',
                        'posts_per_page' => -1,
                        'post_status'    => 'publish',
                        'fields'         => 'ids',
                    );
                    $currency_posts = get_posts($args);
                    $countries = array();
                    foreach ($currency_posts as $post_id) {
                        $country = get_post_meta($post_id, 'country', true);
                        if (!empty($country)) {
                            $countries[] = $country;
                        }
                    }
                    $countries = array_unique($countries);
                    sort($countries);
                    ?>
                    <form class="d-flex me-3">
                        <select class="form-select form-select-sm" id="userCountry">
                            <option value="all">Select All</option>
                            <?php foreach ($countries as $country) : ?>
                                <option value="<?php echo esc_attr(strtolower($country)); ?>"
                                    <?php selected($saved_country, strtolower($country)); ?>>
                                    <?php echo esc_html($country); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </form>
                <?php endif; ?>
                <button
                    class="navbar-toggler"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#navbarNav"
                    aria-controls="navbarNav"
                    aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"><i class="fas fa-bars" style="color: white"></i></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'primary_menu',
                        'container'      => false,
                        'menu_class'     => 'navbar-nav mx-auto',
                        'fallback_cb'    => '__return_false',
                        'depth'          => 2,
                        'walker'         => new Bootstrap_Navwalker(),
                    ));
                    ?>
                    <div class="d-flex justify-content-end">
                        <?php if (is_user_logged_in()) : ?>
                            <div class="dropdown">
                                <a class="btn btn-outline-light rounded-circle" href="#" role="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-user"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                    <li class="dropdown-item-text">
                                        <strong><?php echo wp_get_current_user()->display_name; ?></strong>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="<?php echo wp_logout_url(site_url()); ?>">
                                            <i class="fas fa-sign-out-alt"></i> Logout
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        <?php else : ?>
                            <a href="<?php echo site_url(); ?>/login" class="btn btn-outline-light rounded-pill me-2">Log In</a>
                            <a href="<?php echo site_url(); ?>/signup" class="btn btn-success rounded-pill">Create Account</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </nav>
    </header>