<?php
// Template Name: Buy

if (! is_user_logged_in()) {
    wp_redirect(site_url('/login'));
    exit;
}

get_header();

if (isset($_GET['data'])) {
    $decoded = base64_decode($_GET['data']);
    parse_str($decoded, $params);
    $post_id = intval($params['isds']);
    $title = get_the_title($post_id);
    $current_price = floatval(get_post_meta($post_id, 'current_price', true));
    $change_rate = floatval(get_post_meta($post_id, 'change_rate', true));
    $currancy = get_post_meta($post_id, 'currency', true);
    $currency_symbol = get_currency_symbol($currancy);
    $key_id = get_option('stripe_live_key_id');
    $key_secret = get_option('stripe_live_key_secret');
}
?>
<style>
    .delivery-modal {
        border-radius: 20px;
        padding: 20px 30px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
    }

    .delivery-modal .modal-title {
        font-size: 22px;
        font-weight: 700;
    }

    .delivery-modal p {
        font-size: 14px;
    }

    .delivery-option {
        width: 100%;
        padding: 14px 18px;
        border: 1px solid #ddd;
        border-radius: 8px;
        background: #fff;
        font-size: 16px;
        font-weight: 500;
        text-align: left;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: all 0.3s ease;
    }

    .delivery-option span {
        font-weight: bold;
        font-size: 20px;
        color: #333;
    }

    .delivery-option:hover {
        background: #136e1fff;
        color: #fff;
        border-color: #ccc;
    }

    .location-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1) !important;
        transform: translateY(-5px);
        transition: all 0.3s ease;
        border: 2px solid #aca900ff !important;
        background: #218838;
        color: #fff;

    }

    .location-card.selected {
        border: 2px solid #ffcc00 !important;
        /* Highlight border color */
        background-color: #218838 !important;
        /* Optional: change background */
        color: #fff !important;
        transform: translateY(-5px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        transition: all 0.3s ease;
    }

    .modal-header {
        background-color: #f7f7f7;
        border-bottom: 2px solid #e1e1e1;
    }

    .modal-body {
        font-family: 'Arial', sans-serif;
    }

    .address-list {
        max-height: 250px;
        overflow-y: auto;
    }

    .address-item {
        padding: 10px;
        background-color: #fff;
        border: 1px solid #e1e1e1;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .select-address {
        background-color: #28a745;
        color: white;
        border: none;
        padding: 4px 8px;
        border-radius: 50%;
    }

    .select-address:hover {
        background-color: #218838;
    }

    .btn-outline-success {
        border-color: #28a745;
        color: #28a745;
    }

    .btn-outline-success:hover {
        background-color: #28a745;
        color: white;
    }

    .btn-light {
        border: none;
        background-color: transparent;
        color: #6c757d;
    }

    .payment-option {
        padding: 10px;
        background-color: #fff;
        border: 1px solid #e1e1e1;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .payment-option span {
        font-size: 16px;
    }

    .payment-option .btn-outline-success {
        border-color: #28a745;
        color: #28a745;
    }

    .payment-option .btn-outline-success:hover {
        background-color: #28a745;
        color: #ffffff;
    }

    input[type=number]::-webkit-outer-spin-button,
    input[type=number]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type=number] {
        -moz-appearance: textfield;
    }
</style>

<section class="hero" style="padding-top: 80px">
    <div class="container text-center">
        <div class="row gx-1">
            <div class="col">
                <h1 class="aboutus-heading" data-aos="fade-down" data-aos-duration="1000">Currency</h1>
                <p class="lead mt-3 fw-bold" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean euismod bibendum laoreet.
                </p>
            </div>
        </div>
    </div>
</section>

<section>
    <div class="main-sections">
        <div class="buy-card text-center">
            <h2>Buy</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean euismod bibendum laoreet.</p>
            <div class="mb-4 d-flex justify-content-between align-content-center">
                <label class="form-label">Select Quantity</label>
                <div class="quantity-box">
                    <input type="number" id="quantity" value="1" min="1">
                    <div class="quantity-buttons">
                        <button type="button" id="btnPlus">+</button>
                        <button type="button" id="btnMinus">-</button>
                    </div>
                </div>
            </div>
            <div class="mb-4 d-flex justify-content-between align-content-center">
                <label class="form-label">Your Currency</label>
                <div class="custom-select">
                    <select id="currency" name="currency">
                        <option><?php echo strtoupper($currancy); ?></option>
                    </select>
                </div>
            </div>

            <div class="mb-4 d-flex justify-content-between align-items-center">
                <label class="form-label">Total Amount</label>
                <span class="total-amount fw-bold" id="totalAmount"
                    data-currency="<?php echo esc_attr($currency_symbol); ?>">0</span>
            </div>
            <div class="extra-options text-start">
                <div class="row g-2">
                    <div class="col-6">
                        <button type="button" class="btn btn-outline-success w-100" data-bs-toggle="modal"
                            data-bs-target="#deliveryModal">
                            Select Delivery Mode
                        </button>
                        <input type="text" id="deliveryModeInput" class="form-control mt-2 mb-3"
                            placeholder="No delivery mode selected" readonly>
                    </div>
                    <div class="col-6">
                        <button type="button" class="btn btn-outline-success w-100" data-bs-toggle="modal"
                            data-bs-target="#storeModal">
                            Nearby Store
                        </button>
                        <input type="text" id="storeInput" class="form-control mt-2 mb-3"
                            placeholder="No store selected" readonly>
                    </div>
                    <div class="col-6">
                        <button type="button" class="btn btn-outline-success w-100" data-bs-toggle="modal"
                            data-bs-target="#addressModal">
                            Select Address
                        </button>
                        <input type="text" id="addressInput" class="form-control mt-2 mb-3"
                            placeholder="No address selected" readonly>
                    </div>
                    <div class="col-6">
                        <button type="button" class="btn btn-outline-success w-100" data-bs-toggle="modal"
                            data-bs-target="#paymentModal">
                            Payment Mode
                        </button>
                        <input type="text" id="paymentInput" class="form-control mt-2"
                            placeholder="No payment mode selected" readonly>
                    </div>
                </div>
            </div>
            <button class="btn btn-buy w-50">Buy</button>
        </div>
    </div>
</section>

<div class="modal fade" id="deliveryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content delivery-modal">
            <div class="modal-header border-0">
                <h4 class="modal-title w-100 text-center fw-bold">Select Delivery Mode</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <p class="text-muted mb-4">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean euismod bibendum laoreet.
                </p>

                <div class="d-flex flex-column gap-3">
                    <button class="delivery-option" data-value="Pickup from Nearby Store">
                        Pickup from Nearby Store <span>&rsaquo;</span>
                    </button>
                    <button class="delivery-option" data-value="Home Delivery (In 3–4 Days)">
                        Home Delivery (In 3–4 Days) <span>&rsaquo;</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="storeModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h4 class="modal-title w-100 text-center fw-bold">Choose Nearby Store</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-center text-muted mb-4">
                    Select your preferred store from the list below.
                </p>
                <div class="mb-4 d-flex justify-content-center">
                    <input type="text" class="form-control w-75" id="location-search-input"
                        placeholder="Search for locations...">
                </div>
                <div class="row g-3">
                    <?php
                    $args = array(
                        'post_type' => 'locations',
                        'posts_per_page' => -1,
                        'post_status' => 'publish',
                    );
                    $query = new WP_Query($args);
                    if ($query->have_posts()):
                        $delay = 100;
                        while ($query->have_posts()):
                            $query->the_post();
                            $image_url = has_post_thumbnail()
                                ? get_the_post_thumbnail_url(get_the_ID(), 'medium')
                                : get_template_directory_uri() . '/assets/images/default-location.png';
                            $address = get_post_meta(get_the_ID(), 'location', true);
                    ?>
                            <div class="col-lg-2 col-md-3 col-sm-4 col-6 location-card-wrapper"
                                data-store-id="<?php the_ID(); ?>" data-store-name="<?php the_title(); ?>">
                                <div class="card location-card shadow-sm border-0 h-100 text-center" style="cursor:pointer;">
                                    <img src="<?php echo esc_url($image_url); ?>" class="card-img-top"
                                        alt="<?php the_title(); ?>" style="height:120px; object-fit:cover;">
                                    <div class="card-body p-2">
                                        <h6 class="card-title fw-bold mb-1"><?php the_title(); ?></h6>
                                        <?php if ($address): ?>
                                            <p class="mb-0 small"><?php echo esc_html($address); ?></p><?php endif; ?>
                                    </div>
                                </div>
                            </div>
                    <?php
                            $delay += 50;
                        endwhile;
                    else:
                        echo '<p class="text-center text-muted">No locations found.</p>';
                    endif;
                    wp_reset_postdata();
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addressModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content rounded-4 shadow-lg">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Select Address</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <p class="small text-muted">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean euismod bibendum laoreet.
                </p>
                <div id="addressList" class="d-flex flex-wrap justify-content-center gap-3"></div>
                <div id="newAddressForm" class="d-none text-start mt-4">
                    <div class="mb-3">
                        <label for="nameInput" class="form-label">Name</label>
                        <input type="text" class="form-control" id="nameInput" placeholder="Enter name">
                    </div>
                    <div class="mb-3">
                        <label for="emailInput" class="form-label">Email</label>
                        <input type="email" class="form-control" id="emailInput" placeholder="Enter email">
                    </div>
                    <div class="mb-3">
                        <label for="address_input" class="form-label">Address</label>
                        <input type="text" class="form-control" id="address_input" placeholder="Enter address">
                    </div>
                    <div class="mb-3">
                        <label for="phoneInput" class="form-label">Phone Number</label>
                        <input type="text" class="form-control" id="phoneInput" placeholder="Enter phone number">
                    </div>
                    <button class="btn btn-success w-100" id="saveNewAddress">Save Address</button>
                </div>
                <button class="btn btn-outline-success w-100 mt-3" id="addNewAddress">+ Add New Address</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="paymentModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Select Payment Mode</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="payment-option mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Credit Card</span>
                        <button class="btn btn-outline-success btn-sm select-payment-option">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
                <div class="payment-option mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Debit Card</span>
                        <button class="btn btn-outline-success btn-sm select-payment-option">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
                <div class="payment-option mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Cash On Pickup</span>
                        <button class="btn btn-outline-success btn-sm select-payment-option">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="currency_id" value="<?php echo esc_attr($post_id); ?>">
<input type="hidden" id="unitPrice" value="<?php echo esc_attr($current_price); ?>">
<input type="hidden" id="changeRate" value="<?php echo esc_attr($change_rate); ?>">
<script src="https://js.stripe.com/v3/"></script>
<script>
    jQuery(document).ready(function($) {
        let total = 0;

        function updateTotal() {
            let quantity = parseFloat($("#quantity").val()) || 0;
            let basePrice = parseFloat($("#unitPrice").val()) || 0;
            let changeRate = parseFloat($("#changeRate").val()) || 0;
            let unitPrice = basePrice * (1 + changeRate / 100);
            total = unitPrice * quantity;
            let withsymbol = unitPrice * quantity;
            let currencySymbol = $("#totalAmount").data("currency") || '';
            $("#totalAmount").text(currencySymbol + withsymbol.toFixed(2));
        }
        updateTotal();
        $("#btnPlus").on("click", function() {
            let q = parseInt($("#quantity").val()) || 1;
            $("#quantity").val(q + 1);
            updateTotal();
        });

        $("#btnMinus").on("click", function() {
            let q = parseInt($("#quantity").val()) || 1;
            if (q > 1) {
                $("#quantity").val(q - 1);
                updateTotal();
            }
        });

        $("#quantity").on("input", function() {
            let q = parseInt($(this).val());
            if (isNaN(q) || q < 1) {
                $(this).val(1);
            }
            updateTotal();
        });

        $(".delivery-option").on("click", function () {
            var value = $(this).data("value");
            $("#deliveryModeInput").val(value);
            $("#deliveryModal").modal("hide");
            if (value === "Home Delivery (In 3–4 Days)") {
                $("button[data-bs-target='#storeModal']")
                    .prop("disabled", true)
                    .addClass("disabled");
                $("#storeInput").val("").attr("placeholder", "Store selection disabled");
            } else {
                $("button[data-bs-target='#storeModal']")
                    .prop("disabled", false)
                    .removeClass("disabled");
                $("#storeInput").attr("placeholder", "No store selected");
            }
        });


        $(".location-card-wrapper").on("click", function() {
            var storeName = $(this).data("store-name");
            var storeId = $(this).data("store-id");
            $("#storeInput").val(storeName);
            if ($("#storeIdInput").length) {
                $("#storeIdInput").val(storeId);
            } else {
                $("<input>").attr({
                    type: "hidden",
                    id: "storeIdInput",
                    name: "store_id",
                    value: storeId
                }).appendTo("form");
            }
            $("#storeModal").modal("hide");
        });

        function loadAddresses() {
            $.ajax({
                url: '<?php echo admin_url("admin-ajax.php"); ?>',
                method: "POST",
                data: {
                    action: "get_user_addresses"
                },
                beforeSend: function() {
                    $("#loaderOverlay").fadeIn(300).css("display", "flex");
                },
                success: function(response) {
                    if (response.success) {
                        $("#addressList").empty();
                        response.data.forEach((addr, index) => {
                            let card = `
                        <div class="address-card ${index === 0 ? 'selected' : ''}" 
                             data-address='${JSON.stringify(addr)}'>
                            <div>
                                <strong>${addr.name}</strong>
                                <div class="address-info">${addr.address}, ${addr.phone}</div>
                            </div>
                            <button class="btn btn-light btn-sm delete-address" data-index="${index}">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>`;
                            $("#addressList").append(card);
                        });
                    }
                },
                complete: function() {
                    $("#loaderOverlay").fadeOut(300);
                }
            });
        }

        $(document).on("click", ".address-card", function() {
            let addressObj = $(this).data("address");
            let fullAddress = '';
            if (typeof addressObj === "object") {
                fullAddress = [
                    addressObj.name,
                    addressObj.address,
                    addressObj.phone
                ].filter(Boolean).join(", ");
            } else {
                fullAddress = addressObj;
            }

            $("#addressInput").val(fullAddress);
            $(".address-card").removeClass("selected");
            $(this).addClass("selected");
            $("#addressModal").modal("hide");
        });

        $("#addNewAddress").on("click", function() {
            $("#newAddressForm").removeClass("d-none");
            $("#addressList").addClass("d-none");
            $(this).addClass("d-none");
        });

        $("#nameInput").on("keypress", function (e) {
            let char = String.fromCharCode(e.which);
            if (!/[a-zA-Z\s]/.test(char)) {
                e.preventDefault();
            }
        });

        $("#saveNewAddress").on("click", function() {
            let name = $("#nameInput").val().trim();
            let email = $("#emailInput").val().trim();
            let address = $("#address_input").val().trim();
            let phone = $("#phoneInput").val().trim();

            if (!name) {
                alert("Name is required!");
                return;
            } else if (!/^[A-Z][a-zA-Z\s]*$/.test(name)) {
                alert("Name must start with a capital letter.");
                return;
            }

            let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!email) {
                alert("Email is required!");
                return;
            } else if (!emailPattern.test(email)) {
                alert("Enter a valid email address.");
                return;
            }

            if (!address) {
                alert("Address is required!");
                return;
            }

            let phonePattern = /^[0-9]{10}$/;
            if (!phone) {
                alert("Phone number is required!");
                return;
            } else if (!phonePattern.test(phone)) {
                alert("Enter a valid 10-digit phone number.");
                return;
            }

            $.ajax({
                url: '<?php echo admin_url("admin-ajax.php"); ?>',
                method: "POST",
                data: {
                    action: "save_user_address",
                    name,
                    email,
                    address,
                    phone
                },
                beforeSend: function() {
                    $("#loaderOverlay").fadeIn(300).css("display", "flex");
                },
                success: function(response) {
                    if (response.success) {
                        loadAddresses();
                        $("#newAddressForm").addClass("d-none");
                        $("#addressList").removeClass("d-none");
                        $("#addNewAddress").removeClass("d-none");
                        $("#nameInput, #emailInput, #address_input, #phoneInput").val('');
                    } else {
                        alert("Failed to save address.");
                    }
                },
                error: function() {
                    alert("There was an error saving the address.");
                },
                complete: function() {
                    $("#loaderOverlay").fadeOut(300);
                }
            });
        });


        $('#addressModal').on('show.bs.modal', function() {
            $("#newAddressForm").addClass("d-none");
            $("#addressList").removeClass("d-none");
            $("#addNewAddress").removeClass("d-none");
            $("#nameInput, #emailInput, #address_input, #phoneInput").val('');
            loadAddresses();
        });

        $(".payment-option").on("click", function(e) {
            if (!$(e.target).hasClass("select-payment-option") && !$(e.target).is("i")) {
                $(this).find(".select-payment-option").trigger("click");
            }
        });

        $(".select-payment-option").on("click", function() {
            var selectedPaymentMode = $(this).siblings("span").text();
            $("#paymentInput").val(selectedPaymentMode);
            $("#paymentModal").modal("hide");
        });

        $(document).on("click", ".delete-address", function(e) {
            e.stopPropagation();

            let index = $(this).data("index");

            if (!confirm("Are you sure you want to delete this address?")) return;

            $.ajax({
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                method: "POST",
                data: {
                    action: "delete_user_address",
                    index: index
                },
                beforeSend: function() {
                    $("#loaderOverlay").fadeIn(300).css("display", "flex");
                },
                success: function(response) {
                    if (response.success) {
                        loadAddresses();
                    } else {
                        alert(response.data.message || "Failed to delete address.");
                    }
                },
                error: function() {
                    alert("There was an error deleting the address.");
                },
                complete: function() {
                    $("#loaderOverlay").fadeOut(300);
                }
            });
        });
        $(".btn-buy").on("click", function() {
            if (!custom_ajax.is_logged_in) {
                window.location.href = custom_ajax.login_url;
                return;
            }
            let price = total.toFixed(2);
            let deliveryMode = $("#deliveryModeInput").val();
            let storeId = $("#storeIdInput").val();
            let addressInput = $("#addressInput").val();
            let paymentInput = $("#paymentInput").val();
            let currency_id = $("#currency_id").val();
            if (!price || !deliveryMode || !addressInput || !paymentInput) {
                alert("Please fill all required options!");
                return;
            }
            if (deliveryMode === "Pickup from Nearby Store" && (!storeId || storeId === "")) {
                alert("Please select a store for pickup!");
                return;
            }
            $.ajax({
                url: '<?php echo admin_url("admin-ajax.php"); ?>',
                method: "POST",
                data: {
                    action: "save_orders_details",
                    security: custom_ajax.nonce,
                    price: price,
                    delivery_mode: deliveryMode,
                    store_id: storeId,
                    address: addressInput,
                    payment_mode: paymentInput,
                    currency_id: currency_id
                },
                beforeSend: function() {
                    $("#loaderOverlay").fadeIn(300).css("display", "flex");
                },
                success: function(response) {
                    if (response.success) {
                        var order_id = response.data.order_id;
                        $.post(custom_ajax.ajax_url, {
                            action: 'create_stripe_session',
                            order_id: order_id,
                            security: custom_ajax.nonce
                        }, function(session) {
                            if (session.data.id) {
                                var stripe = Stripe('<?php echo $key_id; ?>');
                                stripe.redirectToCheckout({
                                    sessionId: session.data.id
                                });
                            } else {
                                alert('Failed to initiate payment.');
                            }
                        });
                    } else {
                        alert("Failed to save order.");
                    }
                },
                error: function() {
                    alert("There was an error saving the order.");
                },
                complete: function() {
                    $("#loaderOverlay").fadeOut(300);
                }
            });
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const locationCards = document.querySelectorAll('.location-card');
        locationCards.forEach(card => {
            card.addEventListener('click', function() {
                locationCards.forEach(c => c.classList.remove('selected'));
                this.classList.add('selected');
                const storeId = this.closest('.location-card-wrapper').dataset.storeId;
                const storeName = this.closest('.location-card-wrapper').dataset.storeName;
                console.log('Selected Store:', storeId, storeName);
            });
        });
    });
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('location-search-input');
        const storeCards = document.querySelectorAll('.location-card-wrapper');

        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase();

            storeCards.forEach(card => {
                const storeName = card.dataset.storeName.toLowerCase();
                const storeAddress = card.querySelector('.card-body p') ?
                    card.querySelector('.card-body p').textContent.toLowerCase() :
                    '';

                if (storeName.includes(query) || storeAddress.includes(query)) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
</script>

<?php get_footer(); ?>