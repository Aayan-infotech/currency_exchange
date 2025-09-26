<?php
// Template Name: Forgot Password
get_header();
$site_key   = RECAPTCHA_SITE_KEY;
?>
<!-- Hero Section -->
<section class="hero" style="padding-top: 80px">
    <div class="container text-center">
        <div class="row gx-1">
            <div class="col">
                <h1 class="forgot-heading" data-aos="fade-down" data-aos-duration="1000">Forgot Password</h1>
                <p class="lead mt-3" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200">
                    Recover access to your account with our secure password reset process.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Forgot Password Form -->
<section class="main-sections">
    <div class="auth-container">
        <div class="auth-card" data-aos="fade-up" data-aos-duration="800">
            <div class="auth-header">
                <h2 class="auth-title">Forgot Password</h2>
                <p class="auth-subtitle">Enter your email to receive a password reset OTP</p>
            </div>
            <form id="forgotForm">
                <?php wp_nonce_field('mytheme_global_nonce', 'security'); ?>
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control" id="email" placeholder="Enter your registered email address" required />
                    <small class="error-message text-danger"></small>
                </div>

                <!-- OTP Section (hidden initially) -->
                <div class="form-group" id="otp-section" style="display:none;">
                    <label for="otp" class="form-label">OTP</label>
                    <input type="text" class="form-control" id="otp" placeholder="Enter the OTP sent to your email" />
                    <small class="error-message text-danger"></small>
                </div>

                <!-- New Password Section (hidden initially) -->
                <div class="form-group" id="password-section" style="display:none;">
                    <label for="new-password" class="form-label">New Password</label>
                    <input type="password" class="form-control" id="new-password" placeholder="Enter new password" />
                    <small class="error-message text-danger"></small>
                </div>
                <div class="auth-footer text-center mt-2">
                    <button type="submit" class="btn btn-success auth-btn mb-4" id="send-otp-btn">Send OTP</button>
                    <button type="button" class="btn btn-success auth-btn" id="reset-password-btn" style="display:none;">Reset Password</button>
                </div>
            </form>
        </div>
    </div>
</section>

<?php get_footer(); ?>