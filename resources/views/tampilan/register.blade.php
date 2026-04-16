<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Daftar - Perpustakaan Digital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #f7cac9 0%, #92a8d1 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
        }

        .register-wrapper {
            width: 100%;
        }

        .register-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
            animation: slideIn 0.5s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .register-header {
            background: linear-gradient(135deg,#92a8d1 100%);
            padding: 40px 30px;
            text-align: center;
            color: white;
        }

        .register-header h1 {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }

        .register-header p {
            font-size: 14px;
            opacity: 0.95;
        }

        .register-body {
            padding: 40px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-control {
            padding: 12px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
            background-color: #f8f9fa;
        }

        .form-control:focus {
            background-color: white;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            outline: none;
        }

        .form-control::placeholder {
            color: #999;
        }

        .form-control.is-invalid {
            border-color: #dc3545;
            background-color: #fff5f5;
        }

        .form-control.is-invalid:focus {
            border-color: #dc3545;
            box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1);
        }

        .invalid-feedback {
            display: block;
            margin-top: 8px;
            font-size: 13px;
            color: #dc3545;
            font-weight: 500;
        }

        .alert {
            margin-bottom: 20px;
            padding: 14px 16px;
            border-radius: 8px;
            border: none;
            font-size: 14px;
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-danger {
            background-color: #fee;
            color: #c33;
            border-left: 4px solid #dc3545;
        }

        .alert-success {
            background-color: #efe;
            color: #060;
            border-left: 4px solid #28a745;
        }

        .alert strong {
            font-weight: 700;
        }

        .btn-register {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #2ab95a 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 16px;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
            color: white;
            text-decoration: none;
        }

        .btn-register:active {
            transform: translateY(0);
        }

        .register-footer {
            text-align: center;
            margin-top: 24px;
            padding-top: 24px;
            border-top: 1px solid #e0e0e0;
        }

        .register-footer p {
            color: #666;
            font-size: 14px;
            margin: 0 0 12px 0;
        }

        .btn-login {
            display: inline-block;
            padding: 10px 24px;
            background-color: #f0f0f0;
            color: #667eea;
            border: 2px solid #667eea;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn-login:hover {
            background-color: #667eea;
            color: white;
            text-decoration: none;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .form-row-full {
            grid-column: 1 / -1;
        }

        @media (max-width: 480px) {
            .register-container {
                max-width: 100%;
            }

            .register-body {
                padding: 30px 20px;
            }

            .register-header {
                padding: 30px 20px;
            }

            .register-header h1 {
                font-size: 28px;
            }

            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="register-wrapper">
        <div class="register-container">
            <!-- Header -->
            <div class="register-header">
                <h1>
                    <i class="fas fa-user-plus"></i>
                    {{$judul}}
                </h1>
                <p>Perpustakaan Digital</p>
            </div>

            <!-- Body -->
            <div class="register-body">
                <!-- Error Message -->
                @if (session()->has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle"></i> <strong>{{ session('error')}}</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <!-- Success Message -->
                @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle"></i> <strong>{{ session('message')}}</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Register Form -->
                <form action="{{ route('tampilan.register.process') }}" method="POST">
                    @csrf

                    <!-- Nama Field -->
                    <div class="form-group">
                        <label for="nama">
                            <i class="fas fa-user"></i> Nama Lengkap
                        </label>
                        <input type="text" 
                               name="nama" 
                               id="nama"
                               value="{{old('nama')}}"
                               class="form-control @error('nama') is-invalid @enderror" 
                               placeholder="Masukkan Nama Lengkap"
                               required>
                        @error('nama')
                        <span class="invalid-feedback" role="alert">
                            <i class="fas fa-times-circle"></i> {{$message}}
                        </span>
                        @enderror
                    </div>

                    <!-- Email Field -->
                    <div class="form-group">
                        <label for="email">
                            <i class="fas fa-envelope"></i> Email
                        </label>
                        <input type="email" 
                               name="email" 
                               id="email"
                               value="{{old('email')}}"
                               class="form-control @error('email') is-invalid @enderror" 
                               placeholder="Masukkan Alamat Email"
                               required>
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <i class="fas fa-times-circle"></i> {{$message}}
                        </span>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div class="form-group">
                        <label for="password">
                            <i class="fas fa-lock"></i> Password
                        </label>
                        <input type="password" 
                               name="password" 
                               id="password"
                               class="form-control @error('password') is-invalid @enderror" 
                               placeholder="Masukkan Password"
                               required>
                        @error('password')
                        <span class="invalid-feedback" role="alert">
                            <i class="fas fa-times-circle"></i> {{$message}}
                        </span>
                        @enderror
                    </div>

                    <!-- Phone Field -->
                    <div class="form-group">
                        <label for="hp">
                            <i class="fas fa-phone"></i> No Handphone
                        </label>
                        <input type="text" 
                               name="hp" 
                               id="hp"
                               value="{{old('hp')}}"
                               class="form-control @error('hp') is-invalid @enderror" 
                               placeholder="Masukkan Nomor Handphone"
                               required>
                        @error('hp')
                        <span class="invalid-feedback" role="alert">
                            <i class="fas fa-times-circle"></i> {{$message}}
                        </span>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn-register">
                        <i class="fas fa-user-check"></i> Daftar
                    </button>
                </form>

                <!-- Footer -->
                <div class="register-footer">
                    <p>Sudah memiliki akun?</p>
                    <a href="{{route('tampilan.login')}}" class="btn-login">
                        <i class="fas fa-sign-in-alt"></i> Masuk Di Sini
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>