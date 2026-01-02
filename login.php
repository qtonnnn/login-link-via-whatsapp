<?php
include("koneksi.php");

?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login System - WhatsApp OTP</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #1a237e 0%, #4a148c 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        
        .login-container {
            width: 100%;
            max-width: 420px;
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 16px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            animation: fadeIn 0.5s ease-out;
        }
        
        .login-header {
            background: linear-gradient(to right, #1a237e, #4a148c);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        
        .login-header h1 {
            font-size: 28px;
            margin-bottom: 8px;
            letter-spacing: 1px;
        }
        
        .login-header p {
            opacity: 0.9;
            font-size: 15px;
        }
        
        .login-form {
            padding: 30px;
        }
        
        .input-group {
            position: relative;
            margin-bottom: 25px;
        }
        
        .input-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
            font-size: 14px;
        }
        
        .input-group input {
            width: 100%;
            padding: 14px 14px 14px 45px;
            border: 2px solid #ddd;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s;
        }
        
        .input-group input:focus {
            border-color: #4a148c;
            outline: none;
            box-shadow: 0 0 0 3px rgba(74, 20, 140, 0.1);
        }
        
        .input-group i {
            position: absolute;
            left: 15px;
            top: 40px;
            color: #666;
            font-size: 18px;
        }
        
        .input-group .show-password {
            left: auto;
            right: 15px;
            cursor: pointer;
            color: #4a148c;
        }
        
        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            font-size: 14px;
        }
        
        .remember {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .remember input {
            width: 16px;
            height: 16px;
            accent-color: #4a148c;
        }
        
        .forgot-password {
            color: #4a148c;
            text-decoration: none;
            font-weight: 600;
        }
        
        .forgot-password:hover {
            text-decoration: underline;
        }
        
        .login-button {
            width: 100%;
            padding: 15px;
            background: linear-gradient(to right, #1a237e, #4a148c);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            letter-spacing: 0.5px;
        }
        
        .login-button:hover {
            background: linear-gradient(to right, #283593, #6a1b9a);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        
        .login-button:active {
            transform: translateY(0);
        }
        
        .social-login {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .social-btn {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 20px;
            color: white;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .social-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
        }
        
        .google {
            background-color: #dd4b39;
        }
        
        .facebook {
            background-color: #3b5998;
        }
        
        .twitter {
            background-color: #1da1f2;
        }
        
        .register-link {
            text-align: center;
            font-size: 15px;
            color: #555;
        }
        
        .register-link a {
            color: #4a148c;
            text-decoration: none;
            font-weight: 600;
        }
        
        .register-link a:hover {
            text-decoration: underline;
        }
        
        .error-message {
            color: #e53935;
            font-size: 14px;
            margin-top: 5px;
            display: none;
        }
        
        .success-message {
            color: #43a047;
            font-size: 14px;
            margin-top: 5px;
            display: none;
        }
        
        .footer {
            text-align: center;
            padding: 20px;
            font-size: 12px;
            color: #888;
            border-top: 1px solid #eee;
            background-color: #f9f9f9;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }
        
        @media (max-width: 480px) {
            .login-container {
                max-width: 100%;
            }
            
            .login-form {
                padding: 20px;
            }
            
            .remember-forgot {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1><i class="fab fa-whatsapp"></i> Login System</h1>
            <p>Masuk ke akun Anda menggunakan WhatsApp</p>
        </div>
        
        <form class="login-form" id="loginForm">
            <div class="input-group">
                <label for="whatsapp"><i class="fab fa-whatsapp"></i> Nomor WhatsApp</label>
                <input type="tel" id="whatsapp" placeholder="Contoh: 081234567890" required>
                <i class="fab fa-whatsapp"></i>
                <div class="error-message" id="whatsappError">Nomor WhatsApp tidak valid</div>
            </div>
            
            <div class="input-group" id="otpGroup" style="display:none;">
                <label for="otp"><i class="fas fa-shield-alt"></i> Kode OTP</label>
                <input type="text" id="otp" placeholder="Masukkan kode OTP" maxlength="6">
                <i class="fas fa-shield-alt"></i>
                <div class="error-message" id="otpError">Kode OTP tidak valid</div>
            </div>
            
            <button type="button" class="login-button" id="sendLinkBtn" style="margin-bottom: 25px;">
                <i class="fab fa-whatsapp"></i> Kirim Link Login ke WhatsApp
            </button>
            
            <button type="submit" class="login-button" id="loginButton" style="display:none;">
                <span id="buttonText"><i class="fas fa-check-circle"></i> Verifikasi & Login</span>
                <i class="fas fa-spinner fa-spin" id="loadingIcon" style="display: none;"></i>
            </button>
            
            <div class="success-message" id="successMessage">Login berhasil! Mengalihkan...</div>
            
        </form>
        
        <div class="footer">
            &copy; 2023 Login System. Hak cipta dilindungi undang-undang.
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.getElementById('loginForm');
            const whatsappInput = document.getElementById('whatsapp');
            const otpGroup = document.getElementById('otpGroup');
            const otpInput = document.getElementById('otp');
            const whatsappError = document.getElementById('whatsappError');
            const otpError = document.getElementById('otpError');
            const successMessage = document.getElementById('successMessage');
            const loginButton = document.getElementById('loginButton');
            const sendLinkBtn = document.getElementById('sendLinkBtn');
            const buttonText = document.getElementById('buttonText');
            const loadingIcon = document.getElementById('loadingIcon');
            
            // Validasi form
            loginForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Reset error messages
                whatsappError.style.display = 'none';
                otpError.style.display = 'none';
                successMessage.style.display = 'none';
                
                let isValid = true;
                
                // Validasi WhatsApp
                const whatsapp = whatsappInput.value.trim();
                if (!whatsapp) {
                    whatsappError.textContent = 'Nomor WhatsApp wajib diisi';
                    whatsappError.style.display = 'block';
                    isValid = false;
                    shakeElement(whatsappInput);
                } else if (!isValidWhatsapp(whatsapp)) {
                    whatsappError.textContent = 'Format nomor WhatsApp tidak valid (contoh: 081234567890)';
                    whatsappError.style.display = 'block';
                    isValid = false;
                    shakeElement(whatsappInput);
                }
                
                // Validasi OTP
                const otp = otpInput.value.trim();
                if (otpGroup.style.display !== 'none' && !otp) {
                    otpError.textContent = 'Kode OTP wajib diisi';
                    otpError.style.display = 'block';
                    isValid = false;
                    shakeElement(otpInput);
                } else if (otpGroup.style.display !== 'none' && otp.length !== 6) {
                    otpError.textContent = 'Kode OTP harus 6 digit';
                    otpError.style.display = 'block';
                    isValid = false;
                    shakeElement(otpInput);
                }
                
                // Jika valid, proses login
                if (isValid) {
                    simulateLogin();
                }
            });
            
            // Fungsi untuk validasi nomor WhatsApp
            function isValidWhatsapp(number) {
                // Hapus semua karakter non-digit
                const cleanNumber = number.replace(/\D/g, '');
                
                // Cek apakah nomor valid (10-13 digit, dimulai dengan 0 atau 62)
                if (cleanNumber.length < 10 || cleanNumber.length > 13) {
                    return false;
                }
                
                // Harus dimulai dengan 0 (format lokal) atau 62 (format internasional)
                if (!cleanNumber.startsWith('0') && !cleanNumber.startsWith('62')) {
                    return false;
                }
                
                return true;
            }
            
            // Fungsi untuk format nomor WhatsApp
            function formatWhatsapp(number) {
                const cleanNumber = number.replace(/\D/g, '');
                
                // Jika dimulai dengan 0, ubah ke format 62
                if (cleanNumber.startsWith('0')) {
                    return '62' + cleanNumber.substring(1);
                }
                
                return cleanNumber;
            }
            
            // Fungsi untuk animasi shake pada input error
            function shakeElement(element) {
                element.style.animation = 'shake 0.5s ease-in-out';
                setTimeout(() => {
                    element.style.animation = '';
                }, 500);
            }
            
            // Simulasi proses login
            function simulateLogin() {
                // Tampilkan loading
                buttonText.style.display = 'none';
                loadingIcon.style.display = 'inline-block';
                loginButton.disabled = true;
                
                // Simulasi delay jaringan
                setTimeout(() => {
                    // Berhasil login
                    successMessage.textContent = 'Login berhasil! Mengalihkan...';
                    successMessage.style.display = 'block';
                    
                    // Reset form dan button
                    setTimeout(() => {
                        buttonText.style.display = 'inline';
                        loadingIcon.style.display = 'none';
                        loginButton.disabled = false;
                        
                        // Di aplikasi nyata, ini akan mengarahkan ke dashboard
                        // window.location.href = 'dashboard.html';
                        
                        // Untuk demo, kita reset form setelah 1.5 detik
                        setTimeout(() => {
                            loginForm.reset();
                            successMessage.style.display = 'none';
                            otpGroup.style.display = 'none';
                            loginButton.style.display = 'none';
                            sendLinkBtn.style.display = 'block';
                            
                            // Tampilkan pesan demo
                            alert('Login berhasil!\n\nUntuk demo ini, tidak ada pengalihan lebih lanjut.');
                        }, 1500);
                    }, 1000);
                }, 1500);
            }
            
            
            // Event listener untuk Kirim Link Login ke WhatsApp
            sendLinkBtn.addEventListener('click', () => {
                const whatsapp = whatsappInput.value.trim();

                if (!whatsapp) {
                    alert('Nomor WhatsApp wajib diisi');
                    return;
                }

                if (!isValidWhatsapp(whatsapp)) {
                    alert('Format nomor WhatsApp tidak valid.\nContoh format yang benar: 081234567890');
                    return;
                }

                // Format nomor untuk API
                const formattedWhatsapp = formatWhatsapp(whatsapp);

                fetch('send_login_link.php', {
                    method: 'POST',
                    headers: {'Content-Type':'application/json'},
                    body: JSON.stringify({ whatsapp: formattedWhatsapp })
                })
                .then(res => res.json())
                .then(res => {
                    alert(res.message);
                    
                    // Jika sukses, tampilkan field OTP dan tombol verifikasi
                    if (res.success) {
                        otpGroup.style.display = 'block';
                        loginButton.style.display = 'block';
                        sendLinkBtn.style.display = 'none';
                        otpInput.focus();
                    }
                })
                .catch(err => {
                    alert('Terjadi kesalahan. Silakan coba lagi.');
                    console.error(err);
                });
            });
            
            // ========== REAL-TIME VALIDATION ==========
            
            // Real-time validation untuk WhatsApp saat blur
            whatsappInput.addEventListener('blur', function() {
                const value = this.value.trim();
                
                if (value) {
                    if (!isValidWhatsapp(value)) {
                        whatsappError.textContent = 'Format nomor tidak valid (contoh: 081234567890)';
                        whatsappError.style.display = 'block';
                        shakeElement(this);
                    } else {
                        whatsappError.style.display = 'none';
                    }
                } else {
                    whatsappError.textContent = 'Nomor WhatsApp wajib diisi';
                    whatsappError.style.display = 'block';
                    shakeElement(this);
                }
            });
            
            // Real-time validation untuk OTP saat blur
            otpInput.addEventListener('blur', function() {
                const value = this.value.trim();
                
                if (value && value.length !== 6) {
                    otpError.textContent = 'Kode OTP harus 6 digit';
                    otpError.style.display = 'block';
                    shakeElement(this);
                } else {
                    otpError.style.display = 'none';
                }
            });
            
            // Clear error message saat user mulai mengetik
            whatsappInput.addEventListener('input', function() {
                whatsappError.style.display = 'none';
            });
            
            otpInput.addEventListener('input', function() {
                otpError.style.display = 'none';
            });
            
            // Format input WhatsApp secara otomatis
            whatsappInput.addEventListener('input', function() {
                // Hanya izinkan digit
                this.value = this.value.replace(/\D/g, '');
            });
            
            // ========== END REAL-TIME VALIDATION ==========
        });
    </script>
</body>
</html>

