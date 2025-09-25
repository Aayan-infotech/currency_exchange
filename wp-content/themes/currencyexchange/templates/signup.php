<?php
// Template Name: SignUp
get_header(); ?>
<section class="main-sections">
    <div class="auth-container">
        <div class="auth-card" data-aos="fade-up" data-aos-duration="800">
            <div class="auth-header">
                <h2 class="auth-title">Sign Up</h2>
            </div>
            <form id="SignUpForm">
                <div class="form-group">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter name" />
                    <small class="error-message text-danger"></small>
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email Address" />
                    <small class="error-message text-danger"></small>
                </div>

                <div class="form-group">
                    <label for="number" class="form-label">Mobile Number</label>
                    <input type="number" class="form-control" id="number" name="number" placeholder="Enter Mobile Number" />
                    <small class="error-message text-danger"></small>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">New Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter New Password" />
                    <small class="error-message text-danger"></small>
                </div>

                <div class="form-group">
                    <label for="confirm_password" class="form-label">Confirm New Password</label>
                    <div class="password-input-group">
                        <input type="password" class="form-control" id="confirm_password" placeholder="Confirm New Password" />
                        <span class="password-toggle" id="passwordToggle">
                            <i class="far fa-eye"></i>
                        </span>
                    </div>
                    <small class="error-message text-danger"></small>
                </div>
                <div class="g-recaptcha" data-sitekey="6LeJ-NMrAAAAABlzKCaiLWKLvK6oAnSyDHMgdhLc"></div>
                <div class="auth-footer text-center mt-2">
                    <button type="submit" class="btn btn-success auth-btn mb-1">Sign Up</button>
                    <p>Already have an account? <a href="<?php echo site_url('/login'); ?>" class="auth-link text-black">Login</a></p>
                </div>
            </form>
        </div>
    </div>
</section>
<?php get_footer(); ?>