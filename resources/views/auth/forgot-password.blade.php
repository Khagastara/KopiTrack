@extends('layouts.auth')

@section('title', 'Lupa Password')

@section('content')
    <div class="flex justify-center items-center h-screen">
        <div class="w-full max-w-md">
            <div class="bg-white rounded-lg shadow-lg p-8">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-800">KopiTrack</h2>
                    <p class="text-gray-600 mt-2" id="subtitle">
                        Masukkan email untuk reset password
                    </p>
                </div>

                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" id="success-alert">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" id="error-alert">
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.verify-otp') }}" id="main-form">
                    @csrf
                    <div class="mb-6">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <div class="flex space-x-2">
                            <input type="email" name="email" id="email" value="{{ old('email') ?? session('email') }}"
                                class="flex-1 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-brown-500 focus:border-brown-500"
                                required autofocus>
                            <button type="button" id="send-otp-btn"
                                class="px-4 py-2 bg-brown-600 hover:bg-brown-700 text-white font-medium rounded-lg focus:outline-none focus:shadow-outline transition duration-150 whitespace-nowrap disabled:opacity-50 disabled:cursor-not-allowed">
                                <span class="btn-text">Kirim OTP</span>
                                <span class="btn-loading hidden">
                                    <svg class="animate-spin h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </span>
                                <span class="btn-cooldown hidden">
                                    <span id="send-countdown">60</span>s
                                </span>
                            </button>
                        </div>
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-6" id="otp-section">
                        <label for="otp" class="block text-sm font-medium text-gray-700 mb-1">Kode OTP</label>
                        <input type="text" name="otp" id="otp" maxlength="6"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-brown-500 focus:border-brown-500 text-center text-2xl tracking-widest"
                            placeholder="000000">
                        @error('otp')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1" id="email-sent-to" style="display: none;">
                            Kode OTP telah dikirim ke: <span id="sent-email"></span>
                        </p>
                    </div>

                    <button type="submit" id="verify-otp-btn"
                        class="w-full bg-brown-600 hover:bg-brown-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline transition duration-150">
                        <span class="btn-text">Verifikasi OTP</span>
                        <span class="btn-loading hidden">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Memverifikasi...
                        </span>
                    </button>
                    <div class="text-center mt-4" id="resend-section">
                        <p class="text-sm text-gray-600">Tidak menerima kode?</p>
                        <button type="button" id="resend-otp-btn" class="text-sm text-brown-600 hover:text-brown-800 underline disabled:opacity-50 disabled:cursor-not-allowed">
                            <span class="btn-text">Kirim ulang kode</span>
                            <span class="btn-loading hidden">Mengirim ulang...</span>
                        </button>
                        <p class="text-xs text-gray-500 mt-1" id="resend-timer" style="display: none;">
                            Kirim ulang dalam <span id="countdown">60</span> detik
                        </p>
                    </div>
                </form>

                <div class="text-center mt-6">
                    <a href="{{ route('login') }}" class="text-sm text-brown-600 hover:text-brown-800">
                        Kembali ke Login
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        let sendOtpTimer;
        let resendOtpTimer;
        let sendTimeout = 60;
        let resendTimeout = 60;
        let otpSent = false;

        document.addEventListener('DOMContentLoaded', function() {
            const mainForm = document.getElementById('main-form');
            const emailInput = document.getElementById('email');
            const otpInput = document.getElementById('otp');
            const sendOtpBtn = document.getElementById('send-otp-btn');
            const verifyOtpBtn = document.getElementById('verify-otp-btn');
            const resendOtpBtn = document.getElementById('resend-otp-btn');
            const emailSentTo = document.getElementById('email-sent-to');
            const sentEmail = document.getElementById('sent-email');

            @if(session('email'))
                otpSent = true;
                showEmailSent('{{ session('email') }}');
                startSendOtpCooldown();
                startResendOtpCooldown();
            @endif

            otpInput?.addEventListener('input', function() {
                if (this.value.length === 6 && otpSent) {
                    setTimeout(() => {
                        mainForm.submit();
                    }, 500);
                }
            });

            sendOtpBtn?.addEventListener('click', function() {
                const email = emailInput.value;

                if (!email) {
                    showAlert('Masukkan email terlebih dahulu.', 'error');
                    return;
                }

                if (!isValidEmail(email)) {
                    showAlert('Format email tidak valid.', 'error');
                    return;
                }

                toggleButtonLoading(sendOtpBtn, true);

                fetch('{{ route("password.send-otp") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || document.querySelector('input[name="_token"]').value,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ email: email })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        otpSent = true;
                        showEmailSent(email);
                        showAlert(data.message, 'success');
                        otpInput.focus();
                        startSendOtpCooldown();
                        startResendOtpCooldown();
                    } else {
                        showAlert(data.message, 'error');
                    }
                })
                .catch(error => {
                    showAlert('Terjadi kesalahan. Silakan coba lagi.', 'error');
                })
                .finally(() => {
                    toggleButtonLoading(sendOtpBtn, false);
                });
            });

            mainForm?.addEventListener('submit', function(e) {
                if (!otpSent) {
                    e.preventDefault();
                    showAlert('Kirim kode OTP terlebih dahulu.', 'error');
                    return;
                }

                if (!otpInput.value || otpInput.value.length !== 6) {
                    e.preventDefault();
                    showAlert('Masukkan kode OTP yang valid (6 digit).', 'error');
                    return;
                }

                toggleButtonLoading(verifyOtpBtn, true);
            });

            resendOtpBtn?.addEventListener('click', function() {
                const email = emailInput.value;

                if (!email) {
                    showAlert('Email tidak ditemukan.', 'error');
                    return;
                }

                toggleButtonLoading(resendOtpBtn, true);

                fetch('{{ route("password.resend-otp") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || document.querySelector('input[name="_token"]').value,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ email: email })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showAlert(data.message, 'success');
                        startResendOtpCooldown();
                    } else {
                        showAlert(data.message, 'error');
                    }
                })
                .catch(error => {
                    showAlert('Terjadi kesalahan. Silakan coba lagi.', 'error');
                })
                .finally(() => {
                    toggleButtonLoading(resendOtpBtn, false);
                });
            });

            emailInput?.addEventListener('input', function() {
                if (otpSent && this.value !== sentEmail.textContent) {
                    clearTimeout(this.resetTimeout);
                    this.resetTimeout = setTimeout(() => {
                        if (this.value !== sentEmail.textContent) {
                            resetOtpStatus();
                        }
                    }, 1000);
                }
            });
        });

        function showEmailSent(email) {
            const emailSentTo = document.getElementById('email-sent-to');
            const sentEmail = document.getElementById('sent-email');

            emailSentTo.style.display = 'block';
            sentEmail.textContent = email;
        }

        function resetOtpStatus() {
            const emailSentTo = document.getElementById('email-sent-to');
            const otpInput = document.getElementById('otp');

            emailSentTo.style.display = 'none';
            otpInput.value = '';
            otpSent = false;

            if (sendOtpTimer) {
                clearInterval(sendOtpTimer);
            }
            if (resendOtpTimer) {
                clearInterval(resendOtpTimer);
            }

            resetSendOtpButton();
            resetResendOtpButton();
        }

        function startSendOtpCooldown() {
            const sendOtpBtn = document.getElementById('send-otp-btn');
            const btnText = sendOtpBtn.querySelector('.btn-text');
            const btnCooldown = sendOtpBtn.querySelector('.btn-cooldown');
            const sendCountdown = document.getElementById('send-countdown');

            let timeLeft = sendTimeout;

            sendOtpBtn.disabled = true;
            btnText.classList.add('hidden');
            btnCooldown.classList.remove('hidden');

            sendOtpTimer = setInterval(() => {
                timeLeft--;
                sendCountdown.textContent = timeLeft;

                if (timeLeft <= 0) {
                    clearInterval(sendOtpTimer);
                    resetSendOtpButton();
                }
            }, 1000);
        }

        function resetSendOtpButton() {
            const sendOtpBtn = document.getElementById('send-otp-btn');
            const btnText = sendOtpBtn.querySelector('.btn-text');
            const btnCooldown = sendOtpBtn.querySelector('.btn-cooldown');

            sendOtpBtn.disabled = false;
            btnText.classList.remove('hidden');
            btnCooldown.classList.add('hidden');
        }

        function startResendOtpCooldown() {
            const resendBtn = document.getElementById('resend-otp-btn');
            const resendTimer = document.getElementById('resend-timer');
            const countdown = document.getElementById('countdown');

            let timeLeft = resendTimeout;

            resendBtn.disabled = true;
            resendBtn.classList.add('opacity-50', 'cursor-not-allowed');
            resendTimer.style.display = 'block';

            resendOtpTimer = setInterval(() => {
                timeLeft--;
                countdown.textContent = timeLeft;

                if (timeLeft <= 0) {
                    clearInterval(resendOtpTimer);
                    resetResendOtpButton();
                }
            }, 1000);
        }

        function resetResendOtpButton() {
            const resendBtn = document.getElementById('resend-otp-btn');
            const resendTimer = document.getElementById('resend-timer');

            resendBtn.disabled = false;
            resendBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            resendTimer.style.display = 'none';
        }

        function toggleButtonLoading(button, isLoading) {
            const btnText = button.querySelector('.btn-text');
            const btnLoading = button.querySelector('.btn-loading');
            const btnCooldown = button.querySelector('.btn-cooldown');

            if (isLoading) {
                btnText?.classList.add('hidden');
                btnCooldown?.classList.add('hidden');
                btnLoading?.classList.remove('hidden');
                button.disabled = true;
            } else {
                btnLoading?.classList.add('hidden');
                if (!btnCooldown || btnCooldown.classList.contains('hidden')) {
                    btnText?.classList.remove('hidden');
                    if (button.id !== 'send-otp-btn' || !otpSent) {
                        button.disabled = false;
                    }
                }
            }
        }

        function showAlert(message, type) {
            clearAlerts();

            const alertDiv = document.createElement('div');
            alertDiv.className = `${type === 'success' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700'} border px-4 py-3 rounded mb-4`;
            alertDiv.textContent = message;

            const form = document.getElementById('main-form');
            form.parentNode.insertBefore(alertDiv, form);

            setTimeout(() => {
                alertDiv.remove();
            }, 5000);
        }

        function clearAlerts() {
            const alerts = document.querySelectorAll('.bg-green-100, .bg-red-100');
            alerts.forEach(alert => alert.remove());
        }

        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }
    </script>
@endsection
