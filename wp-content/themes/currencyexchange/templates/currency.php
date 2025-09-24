<?php
// Template Name: Currency
get_header();
?>
<section class="hero" style="padding-top: 80px">
    <div class="container text-center">
        <div class="row gx-1">
            <div class="col">
                <h1 class="aboutus-heading" data-aos="fade-down" data-aos-duration="1000">Currency</h1>
                <p class="lead mt-4 fw-bold" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200">
                    Real-time currency exchange rates and trading options.
                </p>
            </div>
        </div>
    </div>
</section>

<div class="main-sections">
    <section class="currency-table-custom">
        <div class="container">
            <div class="search-section" data-aos="fade-up" data-aos-duration="800" data-aos-delay="300">
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <button
                        type="button"
                        id="reset-currency-button"
                        class="btn btn-success d-flex align-items-center justify-content-center"
                        data-aos="fade-right"
                        data-aos-duration="800">
                        <i class="fas fa-sync-alt me-1"></i> Reset
                    </button>
                    <input
                        type="text"
                        class="form-control flex-grow-1"
                        id="search_currency"
                        placeholder="Search currencies..."
                        data-aos="fade-right"
                        data-aos-duration="800"
                        data-aos-delay="400" />
                    <select class="form-select w-auto" id="currency-sort-options" data-aos="fade-left" data-aos-duration="800">
                        <option value="">Filter By</option>
                        <option value="asc">A to Z</option>
                        <option value="desc">Z to A</option>
                        <option value="oldest">Oldest</option>
                        <option value="latest">Latest</option>
                    </select>
                </div>
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
</div>

<?php get_footer(); ?>