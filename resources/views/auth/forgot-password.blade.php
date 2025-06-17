@extends('layouts.auth')

@section('title', 'Lupa Password')

@section('content')
    <div class="flex justify-center items-center h-screen">
        <div class="w-full max-w-md">
            <div class="bg-white rounded-lg shadow-lg p-8">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-800">KopiTrack</h2>
                    <p class="text-gray-600 mt-2" id="subtitle">
                        {{ session('email') ? 'Masukkan kode OTP yang dikirim ke email Anda' : 'Masukkan email untuk reset password' }}
                    </p>
                    @if(session('email'))
                        <p class="text-sm text-gray-500 mt-1">{{ session('email') }}</p>
                    @endif
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

                <form method="POST" action="{{ route('password.send-otp') }}" id="email-form"
                      style="{{ session('email') ? 'display: none;' : '' }}">
                    @csrf

                    <div class="mb-6">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email') ?? session('email') }}"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-brown-500 focus:border-brown-500"
                            required autofocus>
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" id="send-otp-btn"
                        class="w-full bg-brown-600 hover:bg-brown-700 text-black font-bold py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline transition duration-150">
                        <span class="btn-text">Kirim Kode OTP</span>
                        <span class="btn-loading hidden">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Mengirim...
                        </span>
                    </button>
                </form>

                <form method="POST" action="{{ route('password.verify-otp') }}" id="otp-form"
                      style="{{ session('email') ? '' : 'display: none;' }}">
                    @csrf
                    <input type="hidden" name="email" value="{{ session('email') }}" id="hidden-email">

                    <div class="mb-6">
                        <label for="otp" class="block text-sm font-medium text-gray-700 mb-1">Kode OTP</label>
                        <input type="text" name="otp" id="otp" maxlength="6"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-brown-500 focus:border-brown-500 text-center text-2xl tracking-widest"
                            required placeholder="000000">
                        @error('otp')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" id="verify-otp-btn"
                        class="w-full bg-brown-600 hover:bg-brown-700 text-black font-bold py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline transition duration-150">
                        <span class="btn-text">Verifikasi OTP</span>
                        <span class="btn-loading hidden">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Memverifikasi...
                        </span>
                    </button>

                    <div class="text-center mt-6">
                        <p class="text-sm text-gray-600">Tidak menerima kode?</p>
                        <button type="button" id="resend-otp-btn" class="text-sm text-brown-600 hover:text-brown-800 underline">
                            <span class="btn-text">Kirim ulang kode</span>
                            <span class="btn-loading hidden">Mengirim ulang...</span>
                        </button>
                        <p class="text-xs text-gray-500 mt-1" id="resend-timer" style="display: none;">
                            Kirim ulang dalam <span id="countdown">60</span> detik
                        </p>
                    </div>

                    <div class="text-center mt-4">
                        <button type="button" id="back-to-email" class="text-sm text-gray-600 hover:text-gray-800 underline">
                            Ubah email
                        </button>
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
        let countdownTimer;
        let resendTimeout = 60;

        document.addEventListener('DOMContentLoaded', function() {
            const emailForm = document.getElementById('email-form');
            const otpForm = document.getElementById('otp-form');
            const otpInput = document.getElementById('otp');
            const backToEmailBtn = document.getElementById('back-to-email');
            const resendOtpBtn = document.getElementById('resend-otp-btn');
            const subtitle = document.getElementById('subtitle');

            otpInput?.addEventListener('input', function() {
                if (this.value.length === 6) {
                    setTimeout(() => {
                        this.form.submit();
                    }, 500);
                }
            });

            emailForm?.addEventListener('submit', function(e) {
                e.preventDefault();

                const email = document.getElementById('email').value;
                const sendBtn = document.getElementById('send-otp-btn');

                toggleButtonLoading(sendBtn, true);

                fetch(this.action, {
                    method: 'POST',
                    body: new FormData(this),
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        emailForm.style.display = 'none';
                        otpForm.style.display = 'block';

                        subtitle.textContent = 'Masukkan kode OTP yang dikirim ke email Anda';
                        document.getElementById('hidden-email').value = email;

                        const emailDisplay = document.createElement('p');
                        emailDisplay.className = 'text-sm text-gray-500 mt-1';
                        emailDisplay.textContent = email;
                        subtitle.parentNode.appendChild(emailDisplay);

                        showAlert(data.message, 'success');

                        otpInput.focus();

                        startResendTimer();
                    } else {
                        showAlert(data.message, 'error');
                    }
                })
                .catch(error => {
                    showAlert('Terjadi kesalahan. Silakan coba lagi.', 'error');
                })
                .finally(() => {
                    toggleButtonLoading(sendBtn, false);
                });
            });

            backToEmailBtn?.addEventListener('click', function() {
                otpForm.style.display = 'none';
                emailForm.style.display = 'block';
                subtitle.textContent = 'Masukkan email untuk reset password';

                const emailDisplay = subtitle.parentNode.querySelector('.text-sm.text-gray-500');
                if (emailDisplay) {
                    emailDisplay.remove();
                }

                document.getElementById('email').focus();

                clearAlerts();

                if (countdownTimer) {
                    clearInterval(countdownTimer);
                }
            });
            resendOtpBtn?.addEventListener('click', function() {
                const email = document.getElementById('hidden-email').value;

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
                        startResendTimer();
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

            if (otpForm.style.display !== 'none') {
                startResendTimer();
            }
        });

        function toggleButtonLoading(button, isLoading) {
            const btnText = button.querySelector('.btn-text');
            const btnLoading = button.querySelector('.btn-loading');

            if (isLoading) {
                btnText.classList.add('hidden');
                btnLoading.classList.remove('hidden');
                button.disabled = true;
            } else {
                btnText.classList.remove('hidden');
                btnLoading.classList.add('hidden');
                button.disabled = false;
            }
        }

        function showAlert(message, type) {
            clearAlerts();

            const alertDiv = document.createElement('div');
            alertDiv.className = `${type === 'success' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700'} border px-4 py-3 rounded mb-4`;
            alertDiv.textContent = message;

            const form = document.getElementById('email-form').style.display === 'none' ?
                document.getElementById('otp-form') : document.getElementById('email-form');

            form.parentNode.insertBefore(alertDiv, form);

            setTimeout(() => {
                alertDiv.remove();
            }, 5000);
        }

        function clearAlerts() {
            const alerts = document.querySelectorAll('.bg-green-100, .bg-red-100');
            alerts.forEach(alert => alert.remove());
        }

        function startResendTimer() {
            const resendBtn = document.getElementById('resend-otp-btn');
            const resendTimer = document.getElementById('resend-timer');
            const countdown = document.getElementById('countdown');

            let timeLeft = resendTimeout;

            resendBtn.disabled = true;
            resendBtn.classList.add('opacity-50', 'cursor-not-allowed');
            resendTimer.style.display = 'block';

            countdownTimer = setInterval(() => {
                timeLeft--;
                countdown.textContent = timeLeft;

                if (timeLeft <= 0) {
                    clearInterval(countdownTimer);
                    resendBtn.disabled = false;
                    resendBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    resendTimer.style.display = 'none';
                }
            }, 1000);
        }
    </script>
@endsection
