<?php
// Template Name: Edit Profile
get_header(); ?>
<style>
    .profile-info h3 {
            margin: 0;
            font-weight: 700;
            color: var(--dark-color);
        }

        .profile-info p {
            margin: 5px 0 0;
            color: #666;
            font-size: 14px;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--dark-color);
            display: block;
        }

        .form-control {
            padding: 12px 15px;
            border-radius: 8px;
            border: 1px solid #ddd;
            transition: all 0.3s ease;
            width: 100%;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(0, 166, 81, 0.25);
        }

        .select-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            padding-right: 2.5rem;
        }

        .password-input-group {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
        }

        .password-requirements {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .password-requirements h6 {
            margin-bottom: 10px;
            color: var(--dark-color);
        }

        .requirement {
            display: flex;
            align-items: center;
            margin-bottom: 5px;
        }

        .requirement i {
            margin-right: 8px;
            font-size: 12px;
        }

        .requirement.valid {
            color: var(--primary-color);
        }

        .requirement.invalid {
            color: #6c757d;
        }

        /* Button Styles */
        .btn-update {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            width: 100%;
            margin-top: 10px;
        }

        .btn-update:hover {
            background-color: #008740;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 166, 81, 0.3);
        }

        .btn-reset {
            background-color: #6c757d;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            margin-right: 10px;
        }

        .btn-reset:hover {
            background-color: #5a6268;
            transform: translateY(-2px);
        }

        .forgot-link {
            color: var(--primary-color);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .forgot-link:hover {
            text-decoration: underline;
        }

        /* Section Titles */
        .section-title {
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--primary-color);
            display: inline-block;
        }
</style>
<!-- Hero Section -->
<section class="hero" style="padding-top: 80px">
    <div class="container text-center">
        <div class="row gx-1">
            <div class="col">
                <h1 class="profile-heading" data-aos="fade-down" data-aos-duration="1000">My Profile</h1>
                <p class="lead mt-3 fw-bold" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200">
                    Manage your account information and security settings.
                </p>
            </div>
        </div>
    </div>
</section>
<div class="profile-section">
    <div class="container">
        <?php 
        $current_user = wp_get_current_user();
        $user_id = $current_user->ID;
        $name      = get_user_meta($user_id, 'full_name', true);
        $mobile    = get_user_meta($user_id, 'mobile_number', true);
        $id_type   = get_user_meta($user_id, 'id_type', true);
        $ssn       = get_user_meta($user_id, 'ssn', true);
        $bank_name = get_user_meta($user_id, 'bank_name', true);
        if (empty($name)) {
            $name = $current_user->display_name;
        }
        ?>
        <div class="profile-card" data-aos="fade-up" data-aos-duration="800">
            <div class="profile-header">
                <div class="profile-avatar">
                    <?php echo get_avatar($current_user->ID, 80); ?>
                </div>
                <p class="profile-greeting">Hello!</p>
                <h2 class="profile-name"><?php echo esc_html($name); ?></h2>
            </div>

            <h4 class="section-title">Personal Information</h4>
            <form id="profileForm">
    <?php wp_nonce_field('update_profile_nonce','security'); ?>
    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">

    <div class="row">
        <div class="col-md-6">
            <label for="fullName" class="form-label">Full Name</label>
            <input type="text" class="form-control" id="fullName" name="fullName" value="<?php echo esc_attr($name); ?>">
            <span class="text-danger" id="fullNameError"></span>
        </div>
        <div class="col-md-6">
            <label for="mobileNumber" class="form-label">Mobile Number</label>
            <input type="tel" class="form-control" id="mobileNumber" name="mobileNumber" value="<?php echo esc_attr($mobile); ?>">
            <span class="text-danger" id="mobileNumberError"></span>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <label for="idType" class="form-label">Select ID Type</label>
            <select class="form-control" id="idType" name="idType">
                <option value="">Select ID Type</option>
                <option value="passport" <?php selected($id_type,'passport'); ?>>Passport</option>
                <option value="driver-license" <?php selected($id_type,'driver-license'); ?>>Driver's License</option>
                <option value="national-id" <?php selected($id_type,'national-id'); ?>>National ID</option>
            </select>
            <span class="text-danger" id="idTypeError"></span>
        </div>
        <div class="col-md-6">
            <label for="email" class="form-label">Email ID</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo esc_attr($current_user->user_email); ?>" disabled>
            <span class="text-danger" id="emailError"></span>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <label for="ssn" class="form-label">Social Security Number</label>
            <input type="text" class="form-control" id="ssn" name="ssn" value="<?php echo esc_attr($ssn); ?>">
            <span class="text-danger" id="ssnError"></span>
        </div>
        <div class="col-md-6">
            <label for="bankName" class="form-label">Select Bank Name</label>
            <select class="form-control" id="bankName" name="bankName">
                <option value="">Select Bank Name</option>
                <option value="bank1" <?php selected($bank_name,'bank1'); ?>>Bank of America</option>
                <option value="bank2" <?php selected($bank_name,'bank2'); ?>>Chase Bank</option>
                <option value="bank3" <?php selected($bank_name,'bank3'); ?>>Wells Fargo</option>
                <option value="bank4" <?php selected($bank_name,'bank4'); ?>>Citibank</option>
            </select>
            <span class="text-danger" id="bankNameError"></span>
        </div>
    </div>

    <button type="submit" class="btn btn-update">Update</button>
</form>


            <h4 class="section-title mt-5">Change Password</h4>
            <form id="passwordForm">
    <?php wp_nonce_field('change_password_nonce','security'); ?>
    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">

    <div class="row">
        <div class="col-md-6">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password">
            <span class="text-danger" id="passwordError"></span>
        </div>
        <div class="col-md-6">
            <label for="confirmPassword" class="form-label">Confirm Password</label>
            <input type="password" class="form-control" id="confirmPassword" name="confirmPassword">
            <span class="text-danger" id="confirmPasswordError"></span>
        </div>
    </div>

    <button type="submit" class="btn btn-update">Change Password</button>
</form>

        </div>
    </div>
</div>


<?php get_footer(); ?>