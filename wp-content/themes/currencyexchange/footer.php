<div>
    <?php wp_footer(); ?>
</div>
<footer class="pt-5" style="background: #f3f3f3;">
    <div class="container">
        <div class="row">
            <div class="row">
                <div class="col-md-3 col-6 mb-4" data-aos="fade-up" data-aos-duration="800">
                    <h5 class="footer-heading">Company and team</h5>
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'company_team',
                        'container' => false,
                        'menu_class' => 'footer-links',
                        'fallback_cb' => false,
                        'depth' => 1,
                    ));
                    ?>
                </div>

                <div class="col-md-3 col-6 mb-4" data-aos="fade-up" data-aos-duration="800" data-aos-delay="100">
                    <h5 class="footer-heading">Wise Products</h5>
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'wise_product',
                        'container' => false,
                        'menu_class' => 'footer-links',
                        'fallback_cb' => false,
                        'depth' => 1,
                    ));
                    ?>
                </div>

                <div class="col-md-3 col-6 mb-4" data-aos="fade-up" data-aos-duration="800" data-aos-delay="200">
                    <h5 class="footer-heading">Resources</h5>
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'resources',
                        'container' => false,
                        'menu_class' => 'footer-links',
                        'fallback_cb' => false,
                        'depth' => 1,
                    ));
                    ?>
                </div>
                <div class="col-md-3 col-6 mb-4" data-aos="fade-up" data-aos-duration="800" data-aos-delay="300">
                    <h5 class="footer-heading">Follow us</h5>
                    <ul class="footer-links">
                        <li><i class="fas fa-map-marker-alt me-2"></i><span>USA</span></li>
                        <li><i class="fas fa-phone me-2"></i>+44 3423 343232</li>
                        <li><i class="fas fa-envelope me-2"></i>lourdesharris@msn.com</li>
                    </ul>
                </div>
            </div>

            <!-- <h3 class="fw-bold pb-3" style="font-style: italic" data-aos="fade-up" data-aos-duration="800">
                <a href="<?php echo site_url(); ?>" class="text-black text-decoration-none">Foreign Currency Exchange</a>
            </h3> -->
            <div class="copyright d-flex justify-content-between align-items-center py-3 px-2"
                data-aos="fade-up" data-aos-duration="800" style="border:none;">
                <div>
                    <h3 class="fw-bold pb-3" style="font-style: italic" data-aos="fade-up" data-aos-duration="800">
                        <a href="<?php echo site_url(); ?>" class="text-black text-decoration-none">Foreign Currency
                            Exchange</a>
                    </h3>
                </div>
                <div class="d-flex">
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'copyright',
                        'container' => false,
                        'menu_class' => 'footer-links d-flex gap-3',
                        'fallback_cb' => false,
                        'depth' => 1,
                    ));
                    ?>
                </div>
            </div>
            <div class="copyright d-flex justify-content-between align-items-center py-3 px-2 border-top"
                data-aos="fade-up" data-aos-duration="800">

                <!-- Left side -->
                <div>
                    <p class="mb-0 text-black">
                        © <?php echo date('Y'); ?>
                        <a href="<?php echo site_url(); ?>" class="text-black text-decoration-none">
                            Foreign Currency Exchange.
                        </a>
                        All rights reserved.
                    </p>
                </div>

                <!-- Center -->
                <div class="d-flex align-items-center">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/Make_In_India.png"
                        alt="Made in India" width="100" >
                </div>

                <!-- Right side -->
                <div>
                    <p class="mb-0 text-black">
                        Designed &amp; Developed by
                        <a href="https://aayaninfotech.com" target="_blank"
                            class="text-black text-decoration-none fw-bold">
                            Aayan Infotech
                        </a>
                    </p>
                </div>
            </div>

        </div>
    </div>
</footer>
<div id="loaderOverlay">
    <div class="loader-wrapper">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/loader.gif" alt="Loading...">
    </div>
</div>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
</body>

</html>