<?php

function custom_register_login_shortcode()
{
    wp_register_style(
        'custom-register-login-style', // Handle for the style
        plugins_url('assets/css/style.css', __FILE__) // URL to the CSS file
    );

    // Start output buffering
    ob_start();
?>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');



        .container {
            position: relative;
            color: white;
            width: 100%;
            height: 450px;
            border: 2px solid #ff2770;
            box-shadow: 0 0 25px #ff2770;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            /* min-height: 100vh; */
            background: #25252b;
        }

        .container .form-box {
            position: absolute;
            top: 0;
            width: 65%;
            height: 100%;
            display: flex;
            justify-content: center;
            flex-direction: column;
        }

        .form-box.Login {
            left: 0;
            padding: 0 40px;
        }

        .form-box.Login .animation {
            transform: translateX(0%);
            transition: .7s;
            opacity: 1;
            transition-delay: calc(.1s * var(--S));
        }

        .container.active .form-box.Login .animation {
            transform: translateX(-120%);
            opacity: 0;
            transition-delay: calc(.1s * var(--D));
        }

        .form-box.Register {
            /* display: none; */
            right: 0;
            padding: 0 60px;
        }

        .form-box.Register .animation {
            transform: translateX(120%);
            transition: .7s ease;
            opacity: 0;
            filter: blur(10px);
            transition-delay: calc(.1s * var(--S));
        }

        .container.active .form-box.Register .animation {
            transform: translateX(0%);
            opacity: 1;
            filter: blur(0px);
            transition-delay: calc(.1s * var(--li));
        }

        .form-box h2 {
            font-size: 32px;
            text-align: center;
        }

        .form-box .input-box {
            position: relative;
            width: 100%;
            height: 50px;
            margin-top: 25px;
        }


        .input-box input {
            width: 100%;
            height: 100%;
            background: transparent;
            border: none;
            outline: none;
            font-size: 16px;
            color: #fff;
            font-weight: 600;
            border-bottom: 2px solid #fff;
            padding-right: 23px;
            transition: .5s;
        }

        .input-box input:focus,
        .input-box input:valid {
            border-bottom: 2px solid #ff2770;
        }

        .input-box label {
            position: absolute;
            top: 50%;
            left: 0;
            transform: translateY(-50%);
            font-size: 16px;
            color: #fff;
            transition: .5s;
        }

        .input-box input:focus~label,
        .input-box input:valid~label {
            top: -5px;
            color: #ff2770;
        }

        .input-box box-icon {
            position: absolute;
            top: 50%;
            right: 0;
            font-size: 18px;
            transform: translateY(-50%);
            color: #fff;
        }

        .input-box input:focus~box-icon,
        .input-box input:valid~box-icon {
            color: #ff2770;
        }

        .btn {
            color: white;
            position: relative;
            width: 100%;
            height: 45px;
            background: transparent;
            border-radius: 40px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            border: 2px solid #ff2770;
            overflow: hidden;
            z-index: 1;
        }

        .btn::before {
            content: "";
            position: absolute;
            height: 300%;
            width: 100%;
            background: linear-gradient(#25252b, #ff2770, #25252b, #ff2770);
            top: -100%;
            left: 0;
            z-index: -1;
            transition: .5s;
        }

        .btn:hover:before {
            top: 0;
        }

        .regi-link {
            font-size: 14px;
            text-align: center;
            margin: 20px 0 10px;
        }

        .regi-link a {
            text-decoration: none;
            color: #ff2770;
            font-weight: 600;
        }

        .regi-link a:hover {
            text-decoration: underline;
        }

        .info-content {
            position: absolute;
            top: 0;
            height: 100%;
            width: 35%;
            display: flex;
            justify-content: center;
            flex-direction: column;
        }

        .info-content.Login {
            right: 0;
            text-align: right;
            padding: 0 20px 0 0;
        }

        .info-content.Login .animation {
            transform: translateX(0);
            transition: .7s ease;
            transition-delay: calc(.1s * var(--S));
            opacity: 1;
            filter: blur(0px);
        }

        .container.active .info-content.Login .animation {
            transform: translateX(120%);
            opacity: 0;
            filter: blur(10px);
            transition-delay: calc(.1s * var(--D));
        }

        .info-content.Register {
            /* display: none; */
            left: 0;
            text-align: left;
            padding: 0 0px 0px 20px;
            pointer-events: none;
        }

        .info-content.Register .animation {
            transform: translateX(-120%);
            transition: .7s ease;
            opacity: 0;
            filter: blur(10PX);
            transition-delay: calc(.1s * var(--S));
        }

        .container.active .info-content.Register .animation {
            transform: translateX(0%);
            opacity: 1;
            filter: blur(0);
            transition-delay: calc(.1s * var(--li));
        }

        .info-content h2 {
            text-transform: uppercase;
            font-size: 36px;
            line-height: 1.3;
        }

        .info-content p {
            font-size: 16px;
        }

        .container .curved-shape {
            position: absolute;
            right: 0;
            top: -5px;
            height: 600px;
            width: 100%;
            background: linear-gradient(45deg, #25252b, #ff2770);
            /*transform: rotate(10deg) skewY(40deg);*/
            transform: rotate(10deg) skewY(40deg);
            transform-origin: bottom right;
            transition: 1.5s ease;
            transition-delay: 1.6s;
        }

        .container.active .curved-shape {
            transform: rotate(0deg) skewY(0deg);
            transition-delay: .5s;
        }

        .container .curved-shape2 {
            position: absolute;
            left: 250px;
            top: 100%;
            height: 700px;
            width: 100%;
            background: #25252b;
            border-top: 3px solid #ff2770;
            transform: rotate(0deg) skewY(0deg);
            transform-origin: bottom left;
            transition: 1.5s ease;
            transition-delay: .5s;
        }

        .container.active .curved-shape2 {
            transform: rotate(-11deg) skewY(-41deg);
            transition-delay: 1.2s;
        }

        @media only screen and (max-width:767px) {

            .info-content {
                display: none;
            }

            .form-box {
                position: relative !important;
                width: 100% !important;
            }

            .container {
                display: flex;
                flex-wrap: wrap;
            }

            .container.active .form-box.Login {
                display: none;
            }

            .container .curved-shape,
            .container .curved-shape2 {
                transform: rotate(0deg) skewY(60deg);
            }
        }

        .input-box {
            position: relative;
            margin-bottom: 20px;
        }

        .dropdown {
            width: 100%;
            height: 100%;
            background: transparent;
            border: none;
            outline: none;
            font-size: 16px;
            color: #fff;
            font-weight: 600;
            border-bottom: 2px solid #fff;
            padding-right: 23px;
            transition: .5s;
        }

        .dropdown-selected {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .dropdown-options {
            position: absolute;
            width: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            border: 1px solid white;
            border-radius: 5px;
            display: none;
            z-index: 100;
            max-height: 150px;
            overflow-y: auto;
        }

        .dropdown-option {
            padding: 10px;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .dropdown-option:hover {
            background-color: rgba(255, 105, 180, 0.7);
            /* Matching theme color */
        }

        .dropdown-active .dropdown-options {
            display: block;
        }

        .label {
            position: absolute;
            top: -10px;
            left: 10px;
            font-size: 12px;
            color: white;
        }

        div#dropdown-role {
            width: 100%;
            height: 100%;
            background: transparent;
            border: none;
            outline: none;
            font-size: 16px;
            color: #fff;
            font-weight: 600;
            border-bottom: 2px solid #fff;
            padding-right: 23px;
            transition: .5s;
        }

        .dropdown-options {
            left: -1px;
            top: 48px;
            width: calc(100% + 2px);
            border: 2px solid white;
            border-top: 0;
            display: none;
        }

        .container.active .form-box.Register .animation {
            transition-delay: 0.3s !important;
        }

        .container.active .input-box.animation:has(.dropdown.dropdown-active) {
            margin-bottom: 100px;
            transition-delay: 0.3s !important;
        }

        .dropdown.dropdown-active {
            border-bottom: none;
            border-radius: 5px 5px 0 0;
        }

        input:-internal-autofill-selected {
            background-color: transparent !important;
        }
    </style>

    <div class="container">
        <div class="curved-shape"></div>
        <div class="curved-shape2"></div>
        <div class="form-box Login">
            <h2 class="animation" style="--D:0; --S:21">Login</h2>
            <form action="#">
                <div class="input-box animation" style="--D:1; --S:22">
                    <input type="text" required>
                    <label for="">Username</label>
                    <box-icon style="fill: white;" type='solid' name='user'></box-icon>
                </div>

                <div class="input-box animation" style="--D:2; --S:23">
                    <input type="password" required>
                    <label for="">Password</label>
                    <box-icon style="fill: white;" name='lock-alt' type='solid'></box-icon>
                </div>

                <div class="input-box animation" style="--D:3; --S:24">
                    <button class="btn" type="submit">Login</button>
                </div>

                <div class="regi-link animation" style="--D:4; --S:25">
                    <p>Don't have an account? <br> <a href="#" class="SignUpLink">Sign Up</a></p>
                </div>
            </form>
        </div>

        <div class="info-content Login">
            <h2 class="animation" style="--D:0; --S:20">WELCOME BACK!</h2>
            <p class="animation" style="--D:1; --S:21">We are happy to have you with us again. If you need anything, we
                are here to help.</p>
        </div>

        <div class="form-box Register">
            <h2 class="animation" style="--li:17; --S:0">Register</h2>
            <form action="http://localhost/neo/wp-admin/admin.php?page=wpjobportal_company&task=savecompany"
                method="post" enctype="multipart/form-data">
                <!-- Hidden fields -->
                <input type="hidden" name="uid" id="uid" value="">
                <input type="hidden" name="form_request" id="form_request" value="wpjobportal">
                <input type="hidden" name="package" id="package" value="companies">
                <input type="hidden" name="isadmin" id="isadmin" value="1">
                <input type="hidden" name="_wpnonce" id="_wpnonce" value="c5a3f27c5d">

                <!-- Username -->
                <div class="input-box animation" style="--li:18; --S:1">
                    <input type="text" name="uname" id="uname" required>
                    <label for="uname">Username</label>
                    <box-icon style="fill: white;" type='solid' name='user'></box-icon>
                </div>

                <!-- Role Dropdown -->
                <div class="input-box animation" style="--li:19; --S:2">
                    <div class="dropdown" id="dropdown-role">
                        <div class="dropdown-selected" id="dropdown-selected">Select Role</div>
                        <div class="dropdown-options" id="dropdown-options">
                            <div class="dropdown-option" data-value="company">Company</div>
                            <div class="dropdown-option" data-value="jobseeker">Jobseeker</div>
                        </div>
                    </div>
                    <label for="hidden-role"></label>
                    <box-icon style="fill: white;" name='briefcase'></box-icon>
                </div>
                <input type="hidden" name="role" id="hidden-role" required> <!-- Hidden input to store selected value -->

                <!-- Email -->
                <div class="input-box animation" style="--li:19; --S:3">
                    <input type="email" name="contactemail" id="contactemail" required>
                    <label for="contactemail">Contact Email</label>
                    <box-icon style="fill: white;" name='envelope' type='solid'></box-icon>
                </div>

                <!-- Submit Button -->
                <div class="input-box animation" style="--li:21; --S:4">
                    <button class="btn" type="submit">Register Company</button>
                </div>
                <div class="regi-link animation" style="--li:22; --S:5.5">
                    <p>Don't have an account? <br> <a href="#" class="SignInLink">Sign In</a></p>
                </div>
            </form>


        </div>

        <div class="info-content Register">
            <h2 class="animation" style="--li:17; --S:0">WELCOME!</h2>
            <p class="animation" style="--li:18; --S:1">We’re delighted to have you here. If you need any assistance,
                feel free to reach out.</p>
        </div>

    </div>

    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
    <script>
        // form changing
        const container = document.querySelector('.container');
        const LoginLink = document.querySelector('.SignInLink');
        const RegisterLink = document.querySelector('.SignUpLink');

        RegisterLink.addEventListener('click', () => {
            container.classList.add('active');
        })

        LoginLink.addEventListener('click', () => {
            container.classList.remove('active');
        })

        // dropdown
        document.addEventListener('DOMContentLoaded', function() {
            const dropdown = document.getElementById('dropdown-role');
            const selected = document.getElementById('dropdown-selected');
            const optionsContainer = document.getElementById('dropdown-options');
            const hiddenInput = document.getElementById('hidden-role');
            const options = document.querySelectorAll('.dropdown-option');

            // Toggle dropdown on click
            dropdown.addEventListener('click', (e) => {
                e.stopPropagation(); // Prevent immediate closing from outside click listener
                dropdown.classList.toggle('dropdown-active');
            });

            // Handle option selection
            options.forEach(option => {
                option.addEventListener('click', () => {
                    selected.innerText = option.innerText; // Update the visible selected text
                    hiddenInput.value = option.getAttribute('data-value'); // Update the hidden input value
                    dropdown.classList.remove('dropdown-active'); // Close the dropdown
                });
            });

            // Close dropdown if clicked outside
            document.addEventListener('click', function(event) {
                if (!dropdown.contains(event.target)) {
                    dropdown.classList.remove('dropdown-active'); // Close only if outside click
                }
            });
        });
    </script>
<?php
    // Return the buffered output
    return ob_get_clean();
}

// Register the shortcode with WordPress
add_shortcode('custom_register_login', 'custom_register_login_shortcode');
?>