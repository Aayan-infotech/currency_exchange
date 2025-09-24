<?php get_header(); ?>
<style>
    .hero-home {
        position: relative;
        background: url('<?php echo get_template_directory_uri(); ?>/assets/images/homesection.gif') no-repeat center center/cover;
        height: 100vh;
        color: white;
        display: flex;
        align-items: center;
    }
</style>
<section class="hero-home" style="padding-top: 80px;">
    <div class="container">
        <div class="row gx-1">
            <div class="col-12 col-md-8">
                <h1 class="currency-heading" data-aos="fade-right" data-aos-duration="1000">
                    Your Ultimate Currency Converter Tool
                </h1>
                <p class="lead mt-3" data-aos="fade-right" data-aos-duration="1000" data-aos-delay="200">
                    100 countries, 50 currencies, Get the account's but its save your money record for world.
                </p>
                <div
                    class="hero-buttons d-flex justify-content-center align-items-center mt-5"
                    data-aos="fade-up"
                    data-aos-duration="1000"
                    data-aos-delay="400">
                    <a href="<?php echo site_url(); ?>/currency" class="btn btn-light btn-lg">Buy Now</a>
                    <a href="<?php echo site_url(); ?>/about-us" class="btn btn-outline-light btn-lg">Find More</a>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="currency-circle">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/Frame 3531.png" height="200" width="500" class="img-fluid" alt="Currency illustration" />
                </div>
            </div>
        </div>
    </div>
</section>
<section class="services">
    <div class="container">
        <div class="text-center mb-5">
            <img
                src="<?php echo get_template_directory_uri(); ?>/assets/images/ourservices.png"
                class="our-services img-fluid"
                alt="Our Services"
                data-aos="fade-up"
                data-aos-duration="800" />
        </div>
        <div class="row g-4">
            <?php
            $args = array(
                'post_type'      => 'services',
                'posts_per_page' => 9,
                'post_status'    => 'publish',
            );
            $query = new WP_Query($args);
            if ($query->have_posts()) :
                $delay = 200;
                while ($query->have_posts()) : $query->the_post();
                    $icon_id = get_post_meta(get_the_ID(), 'icon', true);
                    $icon_url = $icon_id ? wp_get_attachment_url($icon_id) : get_template_directory_uri() . '/assets/images/setting_Icon.png';
                    $excerpt = wp_trim_words(get_the_excerpt(), 50, '...');
            ?>
                    <div class="col-md-4" data-aos="fade-up" data-aos-duration="800" data-aos-delay="<?php echo $delay; ?>">
                        <div class="service-card">
                            <div class="mb-4">
                                <img src="<?php echo esc_url($icon_url); ?>" height="50" width="50" alt="<?php the_title_attribute(); ?>" />
                            </div>
                            <h3><?php the_title(); ?></h3>
                            <p><?php echo esc_html($excerpt); ?></p>
                            <button class="btn btn-services">
                                <a href="<?php the_permalink(); ?>" style="color: white;" class="text-decoration-none">Learn More</a>
                            </button>
                        </div>
                    </div>
            <?php
                    $delay += 100;
                endwhile;
                wp_reset_postdata();
            else :
                echo '<p>No services found.</p>';
            endif;
            ?>
        </div>
    </div>
</section>
<section class="currency-table">
    <div class="container">
        <div class="text-center mb-5">
            <img
                src="<?php echo get_template_directory_uri(); ?>/assets/images/currencyexchange.png"
                class="currency-exchange img-fluid"
                alt="Currency Exchange"
                data-aos="fade-up"
                data-aos-duration="800" />
        </div>
        <div class="row justify-content-center">
            <div class="col">
                <div class="table-responsive" data-aos="fade-up" data-aos-duration="800" data-aos-delay="500">
                    <table class="table">
                        <thead class="table-dark" style="font-style: italic">
                            <tr class="italic">
                                <th>Currency</th>
                                <th class="d-none d-md-table-cell">Country</th>
                                <th>Current Price</th>
                                <th>Change Rate</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (is_user_logged_in()) {
                                $user_id = get_current_user_id();
                                $country = get_user_meta($user_id, 'country', true);
                                $args = array(
                                    'post_type'      => 'currency',
                                    'posts_per_page' => -1,
                                    'post_status'    => 'publish',
                                    'orderby'        => 'title',
                                    'order'          => 'ASC',
                                    'meta_query'     => array(
                                        array(
                                            'key'     => 'country',
                                            'value'   => $country ? $country : 'united states',
                                            'compare' => '='
                                        )
                                    )
                                );
                            } else {
                                $args = array(
                                    'post_type'      => 'currency',
                                    'posts_per_page' => -1,
                                    'post_status'    => 'publish',
                                    'orderby'        => 'title',
                                    'order'          => 'ASC',
                                );
                            }
                            $query = new WP_Query($args);
                            if ($query->have_posts()) :
                                while ($query->have_posts()) : $query->the_post();
                                    $country       = get_post_meta(get_the_ID(), 'country', true);
                                    $current_price = get_post_meta(get_the_ID(), 'current_price', true);
                                    $change_rate   = get_post_meta(get_the_ID(), 'change_rate', true);
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
                                        <td><strong><?php the_title(); ?></strong></td>
                                        <td class="d-none d-md-table-cell"><strong><?php echo esc_html($country); ?></strong></td>
                                        <td><?php echo esc_html($current_price); ?></td>
                                        <td class="<?php echo esc_attr($change_class); ?>"><?php echo esc_html($change_rate); ?></td>
                                        <td>
                                            <?php
                                            $params = 'isds=' . get_the_ID();
                                            $encoded_params = base64_encode($params);
                                            ?>
                                            <button
                                                class="btn btn-buy"
                                                onclick="window.location.href='<?php echo esc_url(site_url('/buy?data=' . $encoded_params)); ?>'">
                                                Buy
                                            </button>
                                        </td>
                                    </tr>
                            <?php
                                endwhile;
                                wp_reset_postdata();
                            else :
                                echo '<tr><td colspan="5">No currencies found.</td></tr>';
                            endif;
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="about-us mb-5">
    <div class="container">
        <div class="text-center mb-5">
            <img
                src="<?php echo get_template_directory_uri(); ?>/assets/images/aboutus.png"
                class="currency-exchange img-fluid"
                alt="About Us"
                data-aos="fade-up"
                data-aos-duration="800" />
        </div>
        <div class="">
            <div class="row gx-5">
                <div class="col-md-6" data-aos="fade-right" data-aos-duration="800">
                    <div>
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/Rectangle 5551.png" class="img-fluid" alt="About company" />
                    </div>
                </div>
                <div class="col-md-6 mt-4" data-aos="fade-left" data-aos-duration="800" data-aos-delay="200">
                    <div class="">
                        <h3 class="fw-bold">About the company</h3>
                        <p>
                            s parturient montes, nascetur ridiculus mus. Nam fermentum, nulla luctus pharetra vulputate, felis
                            tellus mollis orci, sed rhoncus pronin sapien nunc accuan eget.Lorem ipsum dolor sit amet, consectetur
                            adipiscing elit. Aenean euismod bibendum laoreet.
                        </p>
                        <a href="<?php echo site_url(); ?>/about-us" class="btn btn-view-more rounded-pill">View More</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php get_footer(); ?>