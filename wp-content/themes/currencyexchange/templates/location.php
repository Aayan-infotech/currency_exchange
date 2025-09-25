<?php
//Template Name: Location
?>
<?php get_header(); ?>
<!-- Hero Section -->
<section class="hero" style="padding-top: 80px">
    <div class="container text-center">
        <div class="row gx-1">
            <div class="col">
                <h1 class="aboutus-heading" data-aos="fade-down" data-aos-duration="1000">Location</h1>
                <p class="lead mt-3" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200">
                    Find our branches worldwide for all your currency exchange needs.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Location Section -->
<section class="location">
    <div class="container">
        <div class="search-section" data-aos="fade-up" data-aos-duration="800">
            <div class="row align-items-center g-2 search-section mt-2 mb-4" data-aos="fade-up" data-aos-duration="800"
                data-aos-delay="300">

                <!-- Reset Button -->
                <!-- <div class="col-auto" data-aos="fade-right" data-aos-duration="800">
                    <button type="button" id="reset-button"
                        class="btn btn-success d-flex align-items-center justify-content-center">
                        <i class="fas fa-sync-alt me-1"></i> Reset
                    </button>
                </div> -->

                <!-- Search Input (50%) -->
                <div class="col-6" data-aos="fade-right" data-aos-duration="800" data-aos-delay="400">
                    <input type="text" class="form-control" id="location-search-input"
                        placeholder="Search for locations..." />
                </div>

                <!-- Spacer to push filter to end -->
                <div class="col"></div>

                <!-- Sort Select at End -->
                <div class="col-auto" data-aos="fade-left" data-aos-duration="800">
                    <select class="form-select w-auto" id="sort-options">
                        <option value="">Filter By</option>
                        <option value="asc">A to Z</option>
                        <option value="desc">Z to A</option>
                        <option value="oldest">Oldest</option>
                        <option value="latest">Latest</option>
                    </select>
                </div>

            </div>

        </div>
        <div class="row g-4">
            <?php
            $paged = max(1, get_query_var('paged'));
            $args = array(
                'post_type' => 'locations',
                'posts_per_page' => 9,
                'paged' => $paged,
                'post_status' => 'publish',
            );
            $query = new WP_Query($args);
            if ($query->have_posts()):
                $delay = 200;
                while ($query->have_posts()):
                    $query->the_post();
                    $image_url = has_post_thumbnail()
                        ? get_the_post_thumbnail_url(get_the_ID(), 'large')
                        : get_template_directory_uri() . '/assets/images/default-location.png';
                    $phone = get_post_meta(get_the_ID(), 'number', true);
                    $email = get_post_meta(get_the_ID(), 'email', true);
                    $address = get_post_meta(get_the_ID(), 'location', true);
                    $timing = get_post_meta(get_the_ID(), 'timing', true);
            ?>
                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-duration="800"
                        data-aos-delay="<?php echo esc_attr($delay); ?>">
                        <div class="card location-card text-white">
                            <img src="<?php echo esc_url($image_url); ?>" class="card-img" alt="<?php the_title(); ?>" />
                            <div class="card-img-overlay">
                                <h4 class="fw-bold"><?php the_title(); ?></h4>
                                <?php if ($phone): ?>
                                    <p><i class="fas fa-phone me-2"></i><?php echo esc_html($phone); ?></p><?php endif; ?>
                                <?php if ($email): ?>
                                    <p><i class="fas fa-envelope me-2"></i><?php echo esc_html($email); ?></p><?php endif; ?>
                                <?php if ($address): ?>
                                    <p><i class="fas fa-map-marker-alt me-2"></i><?php echo esc_html($address); ?></p>
                                <?php endif; ?>
                                <?php if ($timing): ?>
                                    <p><i class="fas fa-clock me-2"></i><?php echo esc_html($timing); ?></p><?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php
                    $delay += 100;
                endwhile;
                $total_pages = $query->max_num_pages;
                $current_page = max(1, $paged);
                if ($total_pages > 1): ?>
                    <div class="text-center mt-4">
                        <nav aria-label="Locations pagination">
                            <ul class="pagination custom-pagination justify-content-center align-items-center mb-0">
                                <li class="page-item <?php echo ($current_page <= 1) ? 'disabled' : ''; ?>">
                                    <a class="page-link"
                                        href="<?php echo ($current_page > 1) ? esc_url(get_pagenum_link($current_page - 1)) : '#'; ?>">
                                        &laquo; Previous
                                    </a>
                                </li>
                                <li class="page-item disabled mx-2">
                                    <span class="page-link">Page <?php echo $current_page; ?> of
                                        <?php echo $total_pages; ?></span>
                                </li>
                                <li class="page-item <?php echo ($current_page >= $total_pages) ? 'disabled' : ''; ?>">
                                    <a class="page-link"
                                        href="<?php echo ($current_page < $total_pages) ? esc_url(get_pagenum_link($current_page + 1)) : '#'; ?>">
                                        Next &raquo;
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
            <?php endif;
            else:
                echo '<p>No locations found.</p>';
            endif;
            wp_reset_postdata();
            ?>
        </div>
    </div>
</section>
<?php get_footer(); ?>