<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Geokranti Login Page</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
            max-width: 480px;
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
            height: 5px;
            background: var(--gradient);
        }

        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            margin-bottom: 1.5rem;
        }

        .logo {
            width: 80px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            /* box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1); */
            /* background: linear-gradient(135deg, var(--primary-green), var(--dark-green)); */
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
            border-radius: 30px;
            font-weight: 900;
            font-size: 1.8rem;
            padding: 10px 0;
        }

        .text-muted {
            color: #6b7280;
            margin-bottom: 1.2rem;
        }

        .form-control {
            padding: 12px 15px;
            border-radius: 12px;
            margin-bottom: 0.5rem;
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
            margin-top: 0.5rem;
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

            .text-muted {
                font-size: 12px;
                line-height: 1.3;
            }

            .btn-auth {
                padding: 10px;
                margin-top: 0;
            }

            .auth-header h1 {
                font-size: 1.5rem;
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
                <h1>Welcome Back</h1>
                <p class="text-muted">Nice to see you again. Sign in to continue to your account</p>
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

            <form action="{{ route('auth.login') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input type="text" name="email" class="form-control" placeholder="Email Address"
                            value="{{ old('email') }}" required>
                    </div>
                    @error('email')
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
                </div>

                <div class="d-flex justify-content-between mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="rememberMe" name="remember">
                        <label class="form-check-label" for="rememberMe">Remember me</label>
                    </div>
                    <a href="{{ route('password.request') }}">Forgot password?</a>
                </div>

                <button type="submit" onclick="return confirm('Are you sure you want to Login')" class="btn btn-auth">
                    <i class="fas fa-sign-in-alt me-2"></i> Login
                </button>

                <div class="text-center mt-4">
                    <p class="text-muted">Don't have an account? <a href="{{ route('auth.register') }}">Sign up</a></p>
                </div>
                <p class="text-center text-muted">You are going to choose a noble field</p>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
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
    </script>
</body>

</html>
