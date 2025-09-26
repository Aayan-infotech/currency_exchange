<?php
// Template Name: Reset Password
get_header();
$site_key   = RECAPTCHA_SITE_KEY;
?>
<section class="main-sections">
    <div class="auth-container">
        <div class="auth-card" data-aos="fade-up" data-aos-duration="800">
            <div class="auth-header">
                <h2 class="auth-title">Reset Password</h2>
                <p class="auth-subtitle">Create a new password for your account</p>
            </div>
            <form id="resetPasswordForm">
                <div class="form-group">
                    <label for="newPassword" class="form-label">New Password</label>
                    <div class="password-input-group">
                        <input type="password" class="form-control" id="newPassword" placeholder="Enter New Password" required />
                        <span class="password-toggle" id="newPasswordToggle">
                            <i class="far fa-eye"></i>
                        </span>
                    </div>
                    <div class="password-strength">
                        <div class="password-strength-bar" id="passwordStrengthBar"></div>
                    </div>
                    <small class="text-muted">Use at least 8 characters with a mix of letters, numbers and symbols</small>
                </div>
                <div class="form-group">
                    <label for="confirmPassword" class="form-label">Confirm New Password</label>
                    <div class="password-input-group">
                        <input type="password" class="form-control" id="confirmPassword" placeholder="Confirm New Password" required />
                        <span class="password-toggle" id="confirmPasswordToggle">
                            <i class="far fa-eye"></i>
                        </span>
                    </div>
                    <div class="password-match text-danger" id="passwordMatchError">Passwords do not match</div>
                </div>
            </form>
            <div class="auth-footer text-center mt-2">
                <button type="submit" class="btn btn-success auth-btn mb-1" id="resetPasswordBtn">Reset Password</button>
            </div>
        </div>
    </div>
</section>
<?php get_footer(); ?>