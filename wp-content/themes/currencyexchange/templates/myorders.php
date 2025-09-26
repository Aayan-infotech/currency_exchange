<?php
// Template Name: Profile
get_header(); ?>
 <style>
      .myorders-heading {
        font-weight: 900;
        font-style: Black;
        font-size: 80px;
        line-height: 80.1px;
        letter-spacing: 0%;
        vertical-align: middle;
      }

      /* Orders Section */
      .orders-section {
        background: url('./assets/images/Grid\ 1.png') no-repeat center center/cover;
        padding: 60px 0;
        min-height: 60vh;
      }

      .order-status {
        background-color: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
      }
      .btn-select {
        background-color: #275e03;
        color: white;
        padding: 10px 30px;
        border-radius: 8px;
        border: none;
        transition: 0.3s;
        width: 250px;
      }
      /* Hover effect */
      .btn-select:hover {
        background-color: #1f4802;
      }
      /* Active/selected button */
      .btn-select.active {
        background-color: white; /* or any color you want */
        color: #275e03;
        border: 2px solid #275e03;
      }

      .view-btn-select {
        background-color: #275e03;
        color: white;
        padding: 10px 30px;
        border-radius: 8px;
        border: none;
      }

      .order-item {
        background-color: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
        transition: transform 0.3s ease;
      }

      .order-item:hover {
        transform: translateY(-5px);
      }

      .order-amount {
        font-size: 16px;
        font-weight: 700;
        color: var(--dark-color);
      }

      .view-btn {
        background-color: transparent;
        border: 1px solid var(--primary-color);
        color: var(--primary-color);
        padding: 8px 20px;
        border-radius: 20px;
        transition: all 0.3s ease;
      }

      .view-btn:hover {
        background-color: var(--primary-color);
        color: white;
      }

      /* Footer */
      footer {
        color: black;
        padding: 60px 0 30px;
      }

      .footer-heading {
        font-weight: 600;
        margin-bottom: 20px;
        font-size: 1.2rem;
      }

      .footer-links {
        list-style: none;
        padding: 0;
      }

      .footer-links li {
        margin-bottom: 10px;
        transition: transform 0.3s ease;
      }

      .footer-links li:hover {
        transform: translateX(5px);
      }

      .footer-links a {
        color: black;
        text-decoration: none;
        transition: color 0.3s ease;
      }

      .footer-links a:hover {
        color: var(--primary-color);
      }

      .copyright {
        margin-top: 40px;
        padding-top: 20px;
        border-top: 1px solid #444;
        text-align: center;
        color: #ccc;
      }

      /* Animations */
      .fade-in {
        opacity: 0;
        transform: translateY(20px);
        transition:
          opacity 0.6s ease,
          transform 0.6s ease;
      }

      .fade-in.visible {
        opacity: 1;
        transform: translateY(0);
      }

      /* Responsive Adjustments */
      @media (max-width: 1200px) {
        .myorders-heading {
          font-size: 65px;
          line-height: 65px;
        }
      }

      @media (max-width: 992px) {
        .myorders-heading {
          font-size: 50px;
          line-height: 50px;
        }

        /* Adjust footer columns */
        footer .row {
          text-align: center;
        }

        .order-item {
          text-align: center;
        }

        .order-item .d-flex {
          flex-direction: column;
          gap: 15px;
        }
      }

      @media (max-width: 768px) {
        .myorders-heading {
          font-size: 40px;
          line-height: 40px;
        }

        .hero {
          height: 40vh;
        }

        /* Adjust footer copyright */
        .copyright {
          flex-direction: column;
          text-align: center;
        }

        .copyright .d-flex {
          margin-top: 15px;
          justify-content: center;
          flex-wrap: wrap;
        }

        .copyright .me-3 {
          margin: 5px;
        }

        .order-amount {
          font-size: 20px;
        }
      }

      @media (max-width: 576px) {
        .navbar-brand {
          font-size: 1rem;
        }

        .myorders-heading {
          font-size: 32px;
          line-height: 32px;
        }

        .hero {
          height: 35vh;
        }

        .footer-heading {
          font-size: 1rem;
        }

        .order-amount {
          font-size: 18px;
        }
      }
    </style>
<!-- Hero Section -->
    <section class="hero" style="padding-top: 80px">
      <div class="container text-center">
        <div class="row gx-1">
          <div class="col">
            <h1 class="myorders-heading" data-aos="fade-down" data-aos-duration="1000">My Orders</h1>
            <p class="lead mt-3 fw-bold" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200">
              Track and manage your currency exchange orders.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- Orders Section -->
    <div class="orders-section">
      <div class="container">
        <!-- Order Status Section -->
        <div class="order-status" data-aos="fade-up" data-aos-duration="800">
          <div class="d-flex justify-content-evenly align-items-center">
            <button type="button" class="btn-select">Delivered</button>
            <button type="button" class="btn-select active">On The Way</button>
          </div>
        </div>

        <!-- Order Items -->
        <div class="order-item" data-aos="fade-up" data-aos-duration="800" data-aos-delay="100">
          <div class="d-flex justify-content-between align-items-center">
            <div class="order-amount">100$</div>
            <div class="order-amount">Florida</div>
            <div class="order-amount">86</div>
            <div class="order-amount">+0.20%</div>
            <button type="button" class="view-btn-select">View</button>
          </div>
        </div>

        <div class="order-item" data-aos="fade-up" data-aos-duration="800" data-aos-delay="200">
          <div class="d-flex justify-content-between align-items-center">
            <div class="order-amount">100$</div>
            <div class="order-amount">Florida</div>
            <div class="order-amount">86</div>
            <div class="order-amount">+0.20%</div>
            <button type="button" class="view-btn-select">View</button>
          </div>
        </div>

        <div class="order-item" data-aos="fade-up" data-aos-duration="800" data-aos-delay="300">
          <div class="d-flex justify-content-between align-items-center">
            <div class="order-amount">100$</div>
            <div class="order-amount">Florida</div>
            <div class="order-amount">86</div>
            <div class="order-amount">+0.20%</div>
            <button type="button" class="view-btn-select">View</button>
          </div>
        </div>
      </div>
    </div>

<?php get_footer(); ?>