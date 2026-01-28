<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Metinca - Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('assets/css/login.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.26.3/dist/sweetalert2.min.css">
    <style>
        .register-container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="login-card" style="width: 100%; max-width: 500px;">
            <!-- Logo Section -->
            <div class="logo-section">
                <div class="logo-icon">
                    <i class="bi bi-person-plus-fill"></i>
                </div>
                <h1 class="brand-name">Metinca Starter App</h1>
                <p class="brand-tagline">Daftar akun baru untuk mengakses aplikasi</p>
            </div>

            <!-- Alert Example (hidden by default) -->
            <div class="alert alert-danger d-none" id="errorAlert" role="alert">
                <i class="bi bi-exclamation-circle-fill me-2"></i>
                <span id="errorMessage">Terjadi kesalahan saat registrasi!</span>
            </div>

            <!-- Register Form -->
            <form id="registerForm">
                <!-- Full Name -->
                @csrf
                <div class="mb-3">
                    <label for="fullname" class="form-label">
                        <i class="bi bi-person-fill me-1"></i>Nama Lengkap
                    </label>
                    <div class="input-group">
                        <input type="text" name="name" class="form-control with-icon" id="fullname" placeholder="Masukkan nama lengkap" required>
                        <span class="input-icon">
                            <i class="bi bi-person"></i>
                        </span>
                    </div>
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label for="register-email" class="form-label">
                        <i class="bi bi-envelope-fill me-1"></i>Email
                    </label>
                    <div class="input-group">
                        <input type="email" name="email" class="form-control with-icon" id="register-email" placeholder="Masukkan email" required>
                        <span class="input-icon">
                            <i class="bi bi-envelope"></i>
                        </span>
                    </div>
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label for="register-password" class="form-label">
                        <i class="bi bi-lock-fill me-1"></i>Password
                    </label>
                    <div class="input-group">
                        <input type="password" name="password" class="form-control with-icon" id="register-password" placeholder="Masukkan password (min 8 karakter)" required minlength="8">
                        <span class="input-icon password-toggle" onclick="togglePasswordRegister()">
                            <i class="bi bi-eye" id="toggleIconRegister"></i>
                        </span>
                    </div>
                </div>

                <!-- Confirm Password -->
                <div class="mb-3">
                    <label for="register-confirm-password" class="form-label">
                        <i class="bi bi-lock-fill me-1"></i>Konfirmasi Password
                    </label>
                    <div class="input-group">
                        <input type="password" name="password_confirmation" class="form-control with-icon" id="register-confirm-password" placeholder="Konfirmasi password" required minlength="8">
                        <span class="input-icon password-toggle" onclick="togglePasswordConfirm()">
                            <i class="bi bi-eye" id="toggleIconConfirm"></i>
                        </span>
                    </div>
                </div>

                <!-- Terms & Conditions -->
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="terms" required>
                    <label class="form-check-label" for="terms">
                        Saya setuju dengan <a href="#" class="text-decoration-none">Syarat & Ketentuan</a>
                    </label>
                </div>

                <!-- Register Button -->
                <button type="submit" class="btn-login" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);" id="submitBtn">
                    <i class="bi bi-person-plus-fill me-2"></i>Daftar Akun
                </button>
            </form>

            <!-- Login Link -->
            <div class="signup-link">
                Sudah punya akun? <a href="{{ route('login') }}">Login di sini</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.26.3/dist/sweetalert2.all.min.js"></script>
    <script>
        // Toggle Password Visibility - Register
        function togglePasswordRegister() {
            const passwordInput = document.getElementById('register-password');
            const toggleIcon = document.getElementById('toggleIconRegister');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('bi-eye');
                toggleIcon.classList.add('bi-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('bi-eye-slash');
                toggleIcon.classList.add('bi-eye');
            }
        }

        // Toggle Password Visibility - Confirm Password
        function togglePasswordConfirm() {
            const passwordInput = document.getElementById('register-confirm-password');
            const toggleIcon = document.getElementById('toggleIconConfirm');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('bi-eye');
                toggleIcon.classList.add('bi-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('bi-eye-slash');
                toggleIcon.classList.add('bi-eye');
            }
        }

        // Handle Register Form Submit
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const fullname = document.getElementById('fullname').value.trim();
            const email = document.getElementById('register-email').value.trim();
            const password = document.getElementById('register-password').value;
            const passwordConfirm = document.getElementById('register-confirm-password').value;
            const terms = document.getElementById('terms').checked;
            const submitBtn = document.getElementById('submitBtn');

            // Validate password match
            if (password !== passwordConfirm) {
                showError('Password tidak cocok!');
                return;
            }

            // Validate password length
            if (password.length < 8) {
                showError('Password minimal 8 karakter!');
                return;
            }

            // Validate terms
            if (!terms) {
                showError('Anda harus setuju dengan Syarat & Ketentuan!');
                return;
            }

            // Disable submit button
            submitBtn.disabled = true;
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Memproses...';

            // Prepare form data
            const formData = new FormData(this);

            // Send register request
            fetch('{{ route('register.store') }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            })
            .then(response => {
                console.log('Response Status:', response.status);
                return response.json().then(data => {
                    return { status: response.status, data: data };
                });
            })
            .then(({ status, data }) => {
                console.log('Response Data:', data);
                
                if (status === 201 && data.success) {
                    // Registrasi BERHASIL
                    Swal.fire({
                        title: 'Registrasi Berhasil!',
                        text: 'Akun Anda telah terdaftar. Anda akan diarahkan ke halaman login.',
                        icon: 'success',
                        confirmButtonText: 'OK',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didClose: () => {
                            // Redirect ke login
                            window.location.href = '{{ route('login') }}';
                        }
                    });
                } else {
                    // Registrasi GAGAL
                    showError(data.message || 'Registrasi gagal!');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            })
            .catch(error => {
                console.error('Fetch Error:', error);
                
                let errorMessage = 'Terjadi kesalahan saat registrasi';
                
                if (error instanceof SyntaxError) {
                    errorMessage = 'Server error - response tidak valid (JSON parse error)';
                } else if (error.message) {
                    errorMessage = error.message;
                }
                
                showError(errorMessage);
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        });

        // Show Error Message
        function showError(message) {
            const errorAlert = document.getElementById('errorAlert');
            const errorMessage = document.getElementById('errorMessage');
            
            errorMessage.textContent = message;
            errorAlert.classList.remove('d-none');
            
            // Scroll ke error message
            errorAlert.scrollIntoView({ behavior: 'smooth', block: 'center' });
            
            // Auto hide after 5 seconds
            setTimeout(() => {
                errorAlert.classList.add('d-none');
            }, 5000);
        }

        // Hide error alert when user starts typing
        document.getElementById('fullname').addEventListener('input', function() {
            document.getElementById('errorAlert').classList.add('d-none');
        });

        document.getElementById('register-email').addEventListener('input', function() {
            document.getElementById('errorAlert').classList.add('d-none');
        });

        document.getElementById('register-password').addEventListener('input', function() {
            document.getElementById('errorAlert').classList.add('d-none');
        });

        document.getElementById('register-confirm-password').addEventListener('input', function() {
            document.getElementById('errorAlert').classList.add('d-none');
        });
    </script>
</body>
</html>
