jQuery(document).ready(function ($) {
    AOS.init({
        duration: 800,
        easing: 'ease-in-out',
        once: true,
        mirror: false
    });
    $(window).on('scroll', function () {
        const $navbar = $('.navbar-custom');
        if ($(this).scrollTop() > 50) {
            $navbar.css({
                'padding': '10px 0',
                'box-shadow': '0 2px 10px rgba(0, 0, 0, 0.1)'
            });
        } else {
            $navbar.css({
                'padding': '15px 0',
                'box-shadow': 'none'
            });
        }
    });
    $('.btn').on('mouseenter', function () {
        $(this).css('transform', 'translateY(-2px)');
    }).on('mouseleave', function () {
        $(this).css('transform', 'translateY(0)');
    });
    $("#passwordToggle").on("click", function () {
        let input = $("#confirm_password");
        let icon = $(this).find("i");

        if (input.attr("type") === "password") {
            input.attr("type", "text");
            icon.removeClass("fa-eye").addClass("fa-eye-slash");
        } else {
            input.attr("type", "password");
            icon.removeClass("fa-eye-slash").addClass("fa-eye");
        }
    });

    $("#loginpasswordToggle").on("click", function () {
        let input = $("#password");
        let icon = $(this).find("i");
        if (input.attr("type") === "password") {
            input.attr("type", "text");
            icon.removeClass("fa-eye").addClass("fa-eye-slash");
        } else {
            input.attr("type", "password");
            icon.removeClass("fa-eye-slash").addClass("fa-eye");
        }
    });

    function showError(input, message) {
        removeError(input);
        input.after('<small class="error-msg text-danger">' + message + '</small>');
    }
    function removeError(input) {
        input.next(".error-msg").remove();
    }
    $("#SignUpForm input").on("input", function () {
        let input = $(this);
        removeError(input);
        if (input.attr("id") === "email") {
            let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(input.val().trim())) {
                showError(input, "Enter a valid email address.");
            }
        }
        if (input.attr("id") === "number") {
            let numberPattern = /^[0-9]{10}$/;
            if (!numberPattern.test(input.val().trim())) {
                showError(input, "Enter a valid 10-digit mobile number.");
            }
        }
        if (input.attr("id") === "password") {
            if (input.val().length < 6) {
                showError(input, "Password must be at least 6 characters.");
            }
        }
        if (input.attr("id") === "confirm_password") {
            if (input.val() !== $("#password").val()) {
                showError(input, "Passwords do not match.");
            }
        }
    });
    let name = $("#name");
    name.on("keypress", function (e) {
        let char = String.fromCharCode(e.which);
        if (!/[a-zA-Z\s]/.test(char)) {
            e.preventDefault();
        }
    });
    $("#SignUpForm").on("submit", function (e) {
        e.preventDefault();
        let valid = true;
        let email = $("#email");
        let number = $("#number");
        let password = $("#password");
        let confirm_password = $("#confirm_password");
        if (!name.val().trim()) {
            showError(name, "Name is required.");
            valid = false;
        } else if (!/^[A-Z][a-zA-Z\s]*$/.test(name.val().trim())) {
            showError(name, "First letter must be capital.");
            valid = false;
        }
        let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!email.val().trim()) {
            showError(email, "Email is required.");
            valid = false;
        } else if (!emailPattern.test(email.val().trim())) {
            showError(email, "Enter a valid email address.");
            valid = false;
        }

        let numberPattern = /^[0-9]{10}$/;
        if (!number.val().trim()) {
            showError(number, "Mobile number is required.");
            valid = false;
        } else if (!numberPattern.test(number.val().trim())) {
            showError(number, "Enter a valid 10-digit mobile number.");
            valid = false;
        }

        if (!password.val().trim()) {
            showError(password, "Password is required.");
            valid = false;
        } else if (password.val().length < 6) {
            showError(password, "Password must be at least 6 characters.");
            valid = false;
        }

        if (!confirm_password.val().trim()) {
            showError(confirm_password, "Confirm your password.");
            valid = false;
        } else if (password.val() !== confirm_password.val()) {
            showError(confirm_password, "Passwords do not match.");
            valid = false;
        }

        if (!valid) return;

        let captchaResponse = grecaptcha.getResponse();
        if (!captchaResponse) {
            Swal.fire("Error", "Please complete the captcha.", "error");
            return;
        }

        let formData = {
            action: "custom_user_registration",
            security: custom_ajax.nonce,
            name: name.val(),
            email: email.val(),
            number: number.val(),
            password: password.val(),
            confirm_password: confirm_password.val(),
            captcha: captchaResponse
        };

        $.ajax({
            url: custom_ajax.ajax_url,
            type: "POST",
            data: formData,
            dataType: "json",
            beforeSend: function () {
                $("#loaderOverlay").fadeIn(300).css("display", "flex");
            },
            success: function (response) {
                if (response.success) {
                    window.location.href = custom_ajax.login_url;
                } else {
                    Swal.fire("Error", response.message, "error");
                    grecaptcha.reset();
                }
            },
            error: function () {
                Swal.fire("Error", "Something went wrong. Please try again.", "error");
                grecaptcha.reset();
            },
            complete: function () {
                $("#loaderOverlay").fadeOut(300);
            }
        });
    });

    $("#loginForm").on("submit", function (e) {
        e.preventDefault();
        let email = $("#email");
        let password = $("#password");
        let remember = $("#rememberMe").is(":checked");
        let redirect_url = $(this).data('redirect_url');
        let valid = true;
        $(".error-message").text("");
        if (!email.val().trim()) {
            showError(email, "Email is required.");
            valid = false;
        } else {
            let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email.val().trim())) {
                showError(email, "Please enter a valid email address.");
                valid = false;
            }
        }
        if (!password.val().trim()) {
            showError(password, "Password is required.");
            valid = false;
        } else if (password.val().length < 6) {
            showError(password, "Password must be at least 6 characters.");
            valid = false;
        }
        if (valid) {
            let captchaResponse = grecaptcha.getResponse();
            if (!captchaResponse) {
                Swal.fire("Error", "Please complete the captcha.", "error");
                valid = false;
            }
        }
        if (!valid) return;
        let formData = {
            action: "custom_user_login",
            security: custom_ajax.nonce,
            email: email.val().trim(),
            password: password.val(),
            remember: remember,
            captcha: grecaptcha.getResponse()
        };
        $.ajax({
            url: custom_ajax.ajax_url,
            type: "POST",
            data: formData,
            dataType: "json",
            beforeSend: function () {
                $("#loaderOverlay").fadeIn(300).css("display", "flex");
            },
            success: function (response) {
                if (response.success) {
                    Swal.fire("Success", response.message, "success").then(() => {
                        window.location.href = redirect_url || "/";
                    });
                } else {
                    Swal.fire("Error", response.message, "error");
                    grecaptcha.reset();
                }
            },
            error: function () {
                Swal.fire("Error", "Something went wrong. Please try again.", "error");
                grecaptcha.reset();
            },
            complete: function () {
                $("#loaderOverlay").fadeOut(300);
            }
        });
    });

    function isValidEmail(email) {
        let pattern = /^[a-zA-Z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/i;
        return pattern.test(email);
    }
    $("#forgotForm input").on("input", function () {
        removeError($(this));
        if ($(this).attr("id") === "email") {
            if (!isValidEmail($(this).val().trim())) {
                showError($(this), "Enter a valid email address.");
            }
        }
        if ($(this).attr("id") === "new-password") {
            if ($(this).val().length < 6) {
                showError($(this), "Password must be at least 6 characters.");
            }
        }
    });
    $('#send-otp-btn').on('click', function (e) {
        e.preventDefault();
        let email = $('#email');
        let emailVal = email.val().trim();
        if (!emailVal) {
            showError(email, 'Email is required.');
            return;
        }
        if (!isValidEmail(emailVal)) {
            showError(email, 'Enter a valid email address.');
            return;
        }
        let captchaResponse = grecaptcha.getResponse();

        if (!captchaResponse) {
            Swal.fire("Error", "Please complete the captcha.", "error");
            valid = false;
        }
        $.ajax({
            url: custom_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'custom_send_otp',
                email: emailVal,
                security: custom_ajax.nonce,
                captcha: captchaResponse
            },
            dataType: 'json',
            beforeSend: function () {
                $("#loaderOverlay").fadeIn(300).css("display", "flex");
            },
            success: function (response) {
                if (response.success) {
                    Swal.fire('Success', response.message, 'success');
                    $('#otp-section, #password-section, #reset-password-btn').show();
                    $('#send-otp-btn').hide();
                } else {
                    Swal.fire('Error', response.message, 'error');
                    grecaptcha.reset();
                }
            },
            error: function () {
                Swal.fire('Error', 'Something went wrong.', 'error');
                grecaptcha.reset();
            },
            complete: function () {
                $("#loaderOverlay").fadeOut(300);
            },
        });
    });

    $('#reset-password-btn').on('click', function () {
        let emailVal = $('#email').val().trim();
        let otpVal = $('#otp').val().trim();
        let passwordVal = $('#new-password').val().trim();
        if (!otpVal || !passwordVal) {
            Swal.fire('Error', 'OTP and new password are required.', 'error');
            return;
        }
        if (passwordVal.length < 6) {
            Swal.fire('Error', 'Password must be at least 6 characters.', 'error');
            return;
        }
        let captchaResponse = grecaptcha.getResponse();
        if (!captchaResponse) {
            Swal.fire("Error", "Please complete the captcha.", "error");
            valid = false;
        }
        $.ajax({
            url: custom_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'custom_reset_password',
                email: emailVal,
                otp: otpVal,
                password: passwordVal,
                security: custom_ajax.nonce,
            },
            dataType: 'json',
            beforeSend: function () {
                $("#loaderOverlay").fadeIn(300).css("display", "flex");
            },
            success: function (response) {
                if (response.success) {
                    Swal.fire('Success', response.message, 'success').then(() => {
                        window.location.href = custom_ajax.redirect_url ?? "/";
                    });
                } else {
                    Swal.fire('Error', response.message, 'error');
                    grecaptcha.reset();
                }
            },
            error: function () {
                Swal.fire('Error', 'Something went wrong.', 'error');
                grecaptcha.reset();
            },
            complete: function () {
                $("#loaderOverlay").fadeOut(300);
            },
        });
    });

    function fetchLocations() {
        var search = $("#location-search-input").val();
        var sort = $("#sort-options").val();
        $.ajax({
            url: custom_ajax.ajax_url,
            type: "POST",
            data: {
                action: "custom_location_search",
                search: search,
                sort: sort,
                security: custom_ajax.nonce
            },
            dataType: "json",
            beforeSend: function () {
                $("#loaderOverlay").fadeIn(300).css("display", "flex");
            },
            success: function (response) {
                if (response.success) {
                    $(".location .row.g-4").html(response.html);
                } else {
                    $(".location .row.g-4").html('<p>' + response.message + '</p>');
                }
            },
            error: function () {
                $(".location .row.g-4").html("<p>Something went wrong.</p>");
            },
            complete: function () {
                $("#loaderOverlay").fadeOut(300);
            },
        });
    }
    $("#location-search-input").on("input", function () {
        fetchLocations();
    });
    $("#sort-options").on("change", function () {
        fetchLocations();
    });
    $("#reset-button").on("click", function () {
        location.reload();
    });

    function fetchCurrency() {
        var search = $("#search_currency").val();
        var sort = $("#currency-sort-options").val();

        $.ajax({
            url: custom_ajax.ajax_url,
            type: "POST",
            data: {
                action: "custom_currency_search",
                search: search,
                sort: sort,
                security: custom_ajax.nonce
            },
            dataType: "json",
            beforeSend: function () {
                $("#loaderOverlay").fadeIn(300).css("display", "flex");
            },
            success: function (response) {
                if (response.success) {
                    $(".currency-table-custom tbody").html(response.html);
                } else {
                    $(".currency-table-custom tbody").html('<tr><td colspan="5">' + response.message + '</td></tr>');
                }
            },
            error: function () {
                $(".currency-table-custom tbody").html('<tr><td colspan="5">Something went wrong.</td></tr>');
            },
            complete: function () {
                $("#loaderOverlay").fadeOut(300);
            },
        });
    }

    $("#search_currency").on("input", function () {
        fetchCurrency();
    });

    $("#currency-sort-options").on("change", function () {
        fetchCurrency();
    });

    $("#reset-currency-button").on("click", function () {
        location.reload();
    });
    const parent = $("#userDropdown").closest(".dropdown");
    parent.hover(
        function () {
            $(this).find(".dropdown-menu").addClass("show");
        },
        function () {
            $(this).find(".dropdown-menu").removeClass("show");
        }
    );

    $("#userCountry").on("change", function () {
        var country = $(this).val();
        $.ajax({
            url: custom_ajax.ajax_url,
            type: "POST",
            data: {
                action: "update_user_country",
                country: country,
            },
            beforeSend: function () {
                $("#loaderOverlay").fadeIn(300).css("display", "flex");
            },
            success: function (response) {
                console.log(response);
                location.reload();
            },
            complete: function () {
                $("#loaderOverlay").fadeOut(300);
            },
        });
    });


    function validateProfileForm() {
        let valid = true;
        $(".text-danger").text("");
        let fullName = $("#fullName").val().trim();
        let mobile = $("#mobileNumber").val().trim();
        let idType = $("#idType").val();
        let ssn = $("#ssn").val().trim();
        let bankName = $("#bankName").val();
        if (fullName.length < 3) {
            $("#fullNameError").text("Full Name must be at least 3 characters");
            valid = false;
        }
        let mobilePattern = /^[0-9]{10,15}$/;
        if (!mobilePattern.test(mobile)) {
            $("#mobileNumberError").text("Enter a valid mobile number");
            valid = false;
        }
        if (!idType) {
            $("#idTypeError").text("Please select ID Type");
            valid = false;
        }
        let ssnPattern = /^[0-9]{4,}$/;
        if (!ssnPattern.test(ssn)) {
            $("#ssnError").text("Enter a valid SSN");
            valid = false;
        }
        if (!bankName) {
            $("#bankNameError").text("Please select Bank Name");
            valid = false;
        }
        return valid;
    }

    $("#profileForm").on("submit", function (e) {
        e.preventDefault();
        if (!validateProfileForm()) {
            return;
        }
        let formData = $(this).serialize();
        $.ajax({
            type: "POST",
            url: custom_ajax.ajax_url,
            data: formData + "&action=update_profile",
            beforeSend: function () {
                $(".btn-update").prop("disabled", true).text("Updating...");
            },
            success: function (response) {
                if (response.success) {
                    Swal.fire("Success", response.data.message, "success");
                } else {
                    Swal.fire("Error", response.data.message, "error");
                }
                $(".btn-update").prop("disabled", false).text("Update");
            },
            error: function () {
                Swal.fire("Error", "Something went wrong, please try again.", "error");
                $(".btn-update").prop("disabled", false).text("Update");
            }
        });
    });
    $(document).on("input change", ".form-control, select", function () {
        $(this).next(".text-danger").text("");
    });


    $("#passwordForm").on("submit", function (e) {
        e.preventDefault();
        $("#passwordError").text("");
        $("#confirmPasswordError").text("");
        let password = $("#password").val().trim();
        let confirmPassword = $("#confirmPassword").val().trim();
        let valid = true;
        if (!password) {
            $("#passwordError").text("Password is required");
            valid = false;
        } else if (password.length < 6) {
            $("#passwordError").text("Password must be at least 6 characters long");
            valid = false;
        }

        if (!confirmPassword) {
            $("#confirmPasswordError").text("Please confirm your password");
            valid = false;
        } else if (password !== confirmPassword) {
            $("#confirmPasswordError").text("Passwords do not match");
            valid = false;
        }

        if (!valid) return;
        let formData = $(this).serialize();
        $.ajax({
            type: "POST",
            url: custom_ajax.ajax_url,
            data: formData + "&action=change_password",
            beforeSend: function () {
                $("#passwordForm .btn-update").text("Changing...").prop("disabled", true);
            },
            success: function (response) {
                if (response.success) {
                    $("#passwordForm")[0].reset();
                    Swal.fire("Success", response.data.message, "success");
                    window.location.href = custom_ajax.login_url;
                } else {
                    Swal.fire("Error", response.data.message, "error");
                }
                $("#passwordForm .btn-update").text("Change Password").prop("disabled", false);
            },
            error: function () {
                Swal.fire("Error", "Something went wrong. Please try again.", "error");
                $("#passwordForm .btn-update").text("Change Password").prop("disabled", false);
            }
        });
    });
});