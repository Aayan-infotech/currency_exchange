<?php
// Template Name: KYC Verification
get_header();
?>
<section class="main-sections">
    <div class="auth-container">
        <div class="auth-card" data-aos="fade-up" data-aos-duration="800">
            <div class="auth-header">
                <h2 class="auth-title">KYC Details</h2>
            </div>
            <form id="loginForm">
                <div class="form-group">
                    <label for="social-security" class="form-label">Social Security Number</label>
                    <input type="text" class="form-control" id="social-security" placeholder="Enter SS Number" required />
                </div>
                <div class="form-group">
                    <label for="id-type" class="form-label">Select ID Type</label>
                    <select class="form-control" id="id-type" required>
                        <option value="" disabled selected>Select ID Type</option>
                        <option value="passport">Passport</option>
                        <option value="driver-license">Driver's License</option>
                        <option value="national-id">National ID</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="id-number" class="form-label">ID Number</label>
                    <input type="text" class="form-control" id="id-number" placeholder="Enter your ID Number" required />
                </div>
            </form>
            <div class="auth-footer text-center">
                <button type="submit" class="btn btn-success auth-btn mb-1">Upload</button>
            </div>
            <div class="auth-footer text-center">
                <button type="submit" class="btn btn-light auth-btn">Skip</button>
            </div>
        </div>
    </div>
</section>
<?php get_footer(); ?>