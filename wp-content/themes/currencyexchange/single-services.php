<?php
get_header();
if (have_posts()) :
    while (have_posts()) : the_post();
        $title       = get_the_title();
        $content     = get_the_content();
        $featured_img = get_the_post_thumbnail_url(get_the_ID(), 'full');
?>
        <section class="hero" style="padding-top: 80px">
            <div class="container text-center">
                <div class="row gx-1">
                    <div class="col">
                        <h1 class="aboutus-heading" data-aos="fade-down" data-aos-duration="1000"><?php echo esc_html($title); ?></h1>
                    </div>
                </div>
            </div>
        </section>
        <section class="about-us mb-5">
            <div class="container">
                <div class="row gx-5 py-5 pb-5">
                    <?php if ($featured_img) : ?>
                        <div class="col-12 text-center" data-aos="fade-right" data-aos-duration="800">
                            <div class="service-image-wrapper">
                                <img src="<?php echo esc_url($featured_img); ?>"
                                    class="img-fluid service-image"
                                    alt="<?php echo esc_attr($title); ?>" />
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="mb-5" data-aos="fade-up" data-aos-duration="800">
                    <p class="about-paragraph">
                        <?php echo wp_kses_post($content); ?>
                    </p>
                </div>
            </div>
        </section>
<?php
    endwhile;
endif;

get_footer();
