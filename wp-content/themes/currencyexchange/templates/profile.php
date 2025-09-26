<?php
// Template Name: Profile
get_header(); ?>
<section class="hero" style="padding-top: 80px">
    <div class="container text-center">
        <div class="row gx-1">
            <div class="col">
            <h1 class="profile-heading" data-aos="fade-down" data-aos-duration="1000">My Profile</h1>
            <p class="lead mt-3" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200">
                View and manage your account information
            </p>
            </div>
        </div>
    </div>
</section>

<div class="profile-section">
      <div class="container">
        <div class="profile-card" data-aos="fade-up" data-aos-duration="800">
          <!-- Profile Header -->
          <div class="profile-header">
            <div class="profile-avatar">RF</div>
            <p class="profile-greeting">Hello!</p>
            <h2 class="profile-name">Robert Fox</h2>
          </div>

          <!-- Profile Details -->
          <div class="profile-details">
            <div class="detail-row">
              <span class="detail-label">Name</span>
              <span class="detail-value">Robert Fox</span>
            </div>
            <div class="detail-row">
              <span class="detail-label">Email ID</span>
              <span class="detail-value">robert@gmail.com</span>
            </div>
            <div class="detail-row">
              <span class="detail-label">Mobile Number</span>
              <span class="detail-value">+01123456789</span>
            </div>
            <div class="detail-row">
              <span class="detail-label">Social Security Number</span>
              <span class="detail-value">123456</span>
            </div>
            <div class="detail-row">
              <span class="detail-label">ID Type</span>
              <span class="detail-value">-</span>
            </div>
            <div class="detail-row">
              <span class="detail-label">Bank Name</span>
              <span class="detail-value">-</span>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="profile-actions">
            <button class="btn btn-edit" onclick="window.location.href='edit-profile.html'">
              <i class="fas fa-edit me-2"></i>Edit Profile
            </button>
            <button class="btn btn-change-password" onclick="window.location.href='change-password.html'">
              <i class="fas fa-key me-2"></i>Change Password
            </button>
          </div>
        </div>
      </div>
    </div>
<?php get_footer(); ?>