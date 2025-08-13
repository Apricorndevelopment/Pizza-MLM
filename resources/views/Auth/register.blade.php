<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Geokranti Register Page</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <style>
        :root {
            --primary-green: #2e7d32;
            --dark-green: #1b5e20;
            --light-green: #81c784;
            --earth-brown: #5d4037;
            --sky-blue: #0288d1;
            --gradient: linear-gradient(135deg, var(--primary-green) 0%, var(--sky-blue) 100%);
        }

        body {
            background: url('/logoimg.png') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 20px;
            position: relative;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.3);
            z-index: -1;
        }

        .auth-container {
            max-width: 500px;
            width: 100%;
            margin: 2rem auto;
            padding: 1.5rem 2rem;
            border-radius: 20px;
            background: rgba(198, 198, 198, 0.578);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            position: relative;
            overflow: hidden;
        }

        .auth-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 6px;
            background: var(--gradient);
        }

        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 1.5rem;
        }

        .logo {
            width: 80px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
        }

        .logo img {
            width: 100px;
            height: auto;
        }

        .brand-name {
            font-family: 'Poppins', sans-serif;
            font-size: 2.2rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            letter-spacing: 1px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .auth-header {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .auth-header h1 {
            text-align: center;
            margin-bottom: 0.5rem;
            background: var(--gradient);
            border-radius: 30px;
            font-weight: 600;
            font-size: 1.8rem;
            padding: 10px 0;
            color: white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .text-muted {
            color: #6b7280;
            margin-bottom: 1rem;
        }

        .form-control {
            padding: 12px 15px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-green);
            box-shadow: 0 0 0 0.25rem rgba(46, 125, 50, 0.25);
        }

        .input-group-text {
            height: 50px;
            background-color: rgba(255, 255, 255, 0.611);
            border-right: none;
            color: var(--primary-green);
        }

        .input-group .form-control {
            background-color: rgba(255, 255, 255, 0.611);
            border-left: none;
        }

        .btn-auth {
            background: var(--gradient);
            color: white;
            padding: 12px;
            border-radius: 12px;
            font-weight: 600;
            width: 100%;
            margin-top: 1rem;
            border: none;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-auth:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(46, 125, 50, 0.3);
        }

        .btn-auth:active {
            transform: translateY(0);
        }

        .toggle-password {
            position: absolute;
            right: 15px;
            top: 15px;
            cursor: pointer;
            color: var(--primary-green);
            z-index: 5;
        }

        .form-check-input:checked {
            background-color: var(--primary-green);
            border-color: var(--primary-green);
        }

        a {
            color: var(--primary-green);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        a:hover {
            color: var(--dark-green);
            text-decoration: underline;
        }

        .alert {
            border-radius: 12px;
        }

        .privacy-policy {
            margin: 1.5rem 0;
            padding: 15px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            border-left: 3px solid var(--primary-green);
        }

        @media (max-width: 576px) {
            body {
                padding: 10px;
            }

            .auth-container {
                padding: 1rem;
                margin: 1rem auto;
            }

            .text-muted {
                font-size: 12px;
                line-height: 0.5;
            }

            .logo {
                width: 60px;
                height: 60px;
            }

            .brand-name {
                font-size: 1.8rem;
            }

            .auth-header h1 {
                font-size: 1.5rem;
            }

            .btn-auth {
                padding: 10px;
                margin-top: 0;
            }

            .privacy-policy {
                margin: 1rem 0;
                padding: 12px;
            }

        }
    </style>
</head>

<body>
    <div class="container">
        <div class="auth-container">
            <div class="auth-header">
                <div class="logo-container">
                    <div class="logo">
                        <img src="geokrantilogo-removebg.png" alt="Geokranti Logo">
                    </div>
                    <div class="brand-name">Geokranti</div>
                </div>
                <h1>Create Account</h1>
                <p class="text-muted">Join us today. Fill in your details to get started.</p>
            </div>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST" id="registrationForm">
                @csrf

                <div class="mb-3">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input type="text" name="full_name" value="{{ old('full_name') }}" class="form-control"
                            placeholder="Full Name" required>
                    </div>
                    @error('full_name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3 position-relative">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
                        <input type="text" name="sponsor_id" value="{{ old('sponsor_id', request('sponsor_id')) }}" id="sponsor_id"
                            class="form-control" placeholder="Sponsor ID" required>
                    </div>
                    @error('sponsor_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                    <small id="sponsor-message" class="text-muted"></small>
                </div>

                <div class="mb-3 position-relative">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
                        <input type="text" name="parent_id" value="{{ old('parent_id') }}" id="parent_id"
                            class="form-control" placeholder="Parent ID (optional)">
                    </div>
                    @error('parent_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                    <small id="parent-message" class="text-muted"></small>
                </div>

                <div class="mb-3">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input type="email" name="email" value="{{ old('email') }}" id="email"
                            class="form-control" placeholder="Email Address" required>
                    </div>
                    @error('email')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                    <small id="email-message" class="text-muted"></small>
                </div>

                <div class="mb-3">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="form-control"
                            placeholder="Phone Number" required>
                    </div>
                    @error('phone')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3 position-relative">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" name="password" id="password" class="form-control"
                            placeholder="Password" required>
                    </div>
                    <i class="fas fa-eye toggle-password" toggle="#password"></i>
                    @error('password')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                    <div class="password-requirements mt-2">
                        <small class="text-muted">Password must contain: at least 8 characters, 1 letter, 1 number, 1
                            special character (@$!%*#?&) </small>
                    </div>
                </div>

                <div class="mb-3 position-relative">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="form-control" placeholder="Confirm Password" required>
                    </div>
                    <i class="fas fa-eye toggle-password" toggle="#password_confirmation"></i>
                </div>

                <div class="privacy-policy mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="privacy_policy" name="privacy_policy"
                            required>
                        <label class="form-check-label" for="privacy_policy">
                            I agree to the Privacy Policy and Terms of Service
                        </label>
                    </div>
                </div>

                <button type="submit" onclick="return confirm('Are you sure you want to register')" class="btn btn-auth">
                    <i class="fas fa-user-plus me-2"></i> Register
                </button>

                <div class="text-center mt-4">
                    <p class="text-muted">Already have an account? <a href="{{ route('auth.login') }}">Login</a></p>
                </div>
                <p class="text-center text-muted">आओ साथ मिलकर प्राकृतिक खेती से जूडें ।</p>
            </form>
        </div>

    </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $('#sendOtpBtn').on('click', function() {
            let email = $('#email').val();

            if (!email) {
                alert('Please enter an email address first.');
                return;
            }

            $.ajax({
                url: "{{ route('send.email.otp') }}",
                type: "POST",
                data: {
                    email: email,
                    _token: "{{ csrf_token() }}"
                },
                success: function(res) {
                    if (res.status) {
                        $('#otpSection').show();
                        $('#otpStatus').text(res.message);
                    }
                },
                error: function(err) {
                    alert('Failed to send OTP. Please check email format or try again.');
                }
            });
        });
    </script>


    {{--
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
    {{-- for eye icon --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Check for sponsor_id in URL and prefill
            const urlParams = new URLSearchParams(window.location.search);
            const sponsorId = urlParams.get('sponsor_id');
            if (sponsorId) {
                document.getElementById('sponsor_id').value = sponsorId;
            }
        });


        document.querySelectorAll('.toggle-password').forEach(function(element) {
            element.addEventListener('click', function() {
                const input = document.querySelector(this.getAttribute('toggle'));
                if (input.type === 'password') {
                    input.type = 'text';
                    this.classList.remove('fa-eye');
                    this.classList.add('fa-eye-slash');
                } else {
                    input.type = 'password';
                    this.classList.remove('fa-eye-slash');
                    this.classList.add('fa-eye');
                }
            });
        });

        // Email availability check
        $('#email').on('blur', function() {
            const email = $(this).val();
            if (email) {
                $.ajax({
                    url: '/check-email',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        email: email
                    },
                    success: function(response) {
                        if (response.exists) {
                            $('#email-message').html(
                                '<span class="text-danger"><i class="fas fa-times-circle"></i> This email is already registered. Please enter another email</span>'
                            );
                        } else {
                            $('#email-message').html(
                                '<span class="text-success"><i class="fas fa-check-circle"></i> Email available</span>'
                            );
                        }
                    }
                });
            }
        });
    </script>


    <script>
        $('#sponsor_id').on('input', function() {
            let sponsorId = $(this).val();
            let statusIcon = $('#sponsor-status');
            let statusMessage = $('#sponsor-message');

            if (sponsorId.length === 0) {
                statusIcon.html('');
                statusMessage.text('');
                return;
            }

            $.ajax({
                url: '/check-sponsor/' + sponsorId,
                type: 'GET',
                success: function(response) {
                    if (response.exists) {
                        statusIcon.html('<i class="fas fa-check-circle text-success"></i>');
                        statusMessage.html(
                            `<span style="color: green;">Sponsor available: <strong>${response.name}</strong></span>`
                        );
                    } else {
                        statusIcon.html('<i class="fas fa-times-circle text-danger"></i>');
                        statusMessage.text('No sponsor found').css('color', 'red');
                    }
                },
                error: function() {
                    statusIcon.html('');
                    statusMessage.text('Error checking sponsor.').css('color', 'red');
                }
            });
        });

        $('#parent_id').on('input', function() {
            let parentId = $(this).val();
            let statusIcon = $('#parent-status');
            let statusMessage = $('#parent-message');

            if (parentId.length === 0) {
                statusIcon.html('');
                statusMessage.text('');
                return;
            }

            $.ajax({
                url: '/check-parent/' + parentId,
                type: 'GET',
                success: function(response) {
                    if (response.exists) {
                        statusIcon.html('<i class="fas fa-check-circle text-success"></i>');
                        statusMessage.html(
                            `<span style="color: green;">parent available: <strong>${response.name}</strong></span>`
                        );
                    } else {
                        statusIcon.html('<i class="fas fa-times-circle text-danger"></i>');
                        statusMessage.text('No parent found').css('color', 'red');
                    }
                },
                error: function() {
                    statusIcon.html('');
                    statusMessage.text('Error checking parent.').css('color', 'red');
                }
            });
        });
    </script>
    <script>
        // Validate email
        // ===========================================================================
        function validateEmail(email) {
            var regEx = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
            console.log(regEx.test(email));
            return regEx.test(email);
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js">
