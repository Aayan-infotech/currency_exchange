<?php
// Template Name: Profile
get_header(); ?>
<section class="hero" style="padding-top: 80px">
    <div class="container text-center">
        <div class="row gx-1">
            <div class="col">
            <h1 class="profile-heading" data-aos="fade-down" data-aos-duration="1000">My Profile</h1>
            <p class="lead mt-3 fw-bold" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200">
                View and manage your account information
            </p>
            </div>
        </div>
    </div>
</section>

<div class="profile-section">
    <div class="container">
        <div class="profile-card" data-aos="fade-up" data-aos-duration="800">
            <div class="profile-header">
                <?php 
                $current_user = wp_get_current_user();
                $user_id = $current_user->ID;
                $name = get_user_meta($user_id,'full_name',true);
                ?>
                <div class="profile-avatar">
                    <?php echo get_avatar( $current_user->ID, 80 ); ?>
                </div>
                <p class="profile-greeting">Hello!</p>
                <h2 class="profile-name"><?php echo esc_html($name); ?></h2>
            </div>
            <div class="profile-details">
                <div class="detail-row">
                    <span class="detail-label">Name</span>
                    <span class="detail-value"><?php echo esc_html($name); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Email ID</span>
                    <span class="detail-value"><?php echo esc_html($current_user->user_email); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Mobile Number</span>
                    <span class="detail-value">
                        <?php echo esc_html(get_user_meta($current_user->ID, 'mobile_number', true) ?: '-'); ?>
                    </span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Social Security Number</span>
                    <span class="detail-value">
                        <?php echo esc_html(get_user_meta($current_user->ID, 'ssn', true) ?: '-'); ?>
                    </span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">ID Type</span>
                    <span class="detail-value">
                        <?php echo esc_html(get_user_meta($current_user->ID, 'id_type', true) ?: '-'); ?>
                    </span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Bank Name</span>
                    <span class="detail-value">
                        <?php echo esc_html(get_user_meta($current_user->ID, 'bank_name', true) ?: '-'); ?>
                    </span>
                </div>
            </div>
            <div class="profile-actions">
                <button class="btn btn-edit" onclick="window.location.href='<?php echo site_url('/edit-profile'); ?>'">
                    <i class="fas fa-edit me-2"></i>Edit Profile
                </button>
                <button class="btn btn-change-password" onclick="window.location.href='<?php echo site_url('/change-password'); ?>'">
                    <i class="fas fa-key me-2"></i>Change Password
                </button>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>