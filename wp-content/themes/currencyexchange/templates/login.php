<?php
// Template Name: User Login
get_header();
$site_key = RECAPTCHA_SITE_KEY;
$secret_key = RECAPTCHA_SECRET_KEY;
print_r($site_key);
die;
?>
<section class="main-sections">
    <div class="auth-container">
        <div class="auth-card" data-aos="fade-up" data-aos-duration="800">
            <div class="auth-header">
                <h2 class="auth-title">Login</h2>
                <p class="auth-subtitle">Welcome back! Please enter your details</p>
            </div>
            <form id="loginForm">
                <?php wp_nonce_field('ajax-login-nonce', 'security'); ?>
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email Address" />
                    <small class="error-message text-danger"></small>
                </div>
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="password-input-group">
                        <input type="password" class="form-control" id="password" placeholder="Password" />
                        <span class="password-toggle" id="loginpasswordToggle">
                            <i class="far fa-eye"></i>
                        </span>
                    </div>
                    <small class="error-message text-danger"></small>
                </div>
                <div class="auth-options">
                    <a href="<?php echo site_url(); ?>/forgot-password" class="forgot-link">Forgot Password?</a>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="rememberMe" name="remember" />
                        <label class="form-check-label" for="rememberMe">Remember Me</label>
                    </div>
                </div>
                <div class="g-recaptcha" data-sitekey="<?php echo $site_key; ?>"></div>
                <div class="auth-footer text-center mt-2">
                    <button type="submit" class="btn btn-success auth-btn mb-1">Login</button>
                    <p>Doesn't have an account? <a href="<?php echo site_url(); ?>/signup" class="auth-link text-black">Sign up</a></p>
                </div>
            </form>
            <div id="login-message" class="text-center mt-2"></div>
        </div>
    </div>
</section>

<?php get_footer(); ?>