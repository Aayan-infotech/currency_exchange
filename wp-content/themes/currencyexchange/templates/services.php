<?php
// Template Name: Services
get_header();
?>

<section class="hero" style="padding-top: 80px">
    <div class="container text-center">
        <div class="row gx-1">
            <div class="col">
                <h1 class="aboutus-heading" data-aos="fade-down" data-aos-duration="1000">Our Services</h1>
                <p class="lead mt-3" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean euismod bibendum laoreet.
                </p>
            </div>
        </div>
    </div>
</section>

<section class="services">
    <div class="container">
        <div class="row g-4">
            <?php
            $args = array(
                'post_type'      => 'services',
                'posts_per_page' => -1,
                'post_status'    => 'publish',
                'order'       => 'ASC'
            );
            $query = new WP_Query($args);
            if ($query->have_posts()) :
                $delay = 200;
                while ($query->have_posts()) : $query->the_post();
                    $icon_id = get_post_meta(get_the_ID(), 'icon', true);
                    $title = wp_trim_words(get_the_title(), 10, '...');
                    $icon_url = $icon_id ? wp_get_attachment_url($icon_id) : get_template_directory_uri() . '/assets/images/setting_Icon.png';
                    $excerpt = wp_trim_words(get_the_excerpt(), 30, '...');
            ?>
                    <div class="col-md-4" data-aos="fade-up" data-aos-duration="800" data-aos-delay="<?php echo $delay; ?>">
                        <div class="service-card">
                            <div class="mb-4">
                                <img src="<?php echo esc_url($icon_url); ?>" height="50" width="50" alt="<?php the_title_attribute(); ?>" />
                            </div>
                            <h3><?php echo esc_html($title); ?></h3>
                            <p><?php echo esc_html($excerpt); ?></p>
                            <a href="<?php the_permalink(); ?>" style="color: white;" class="text-decoration-none btn btn-services">Learn More</a>
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

<?php get_footer(); ?>