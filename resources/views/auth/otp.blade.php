@extends('layouts.auth')

@section('content')
<div class="shadow-md shadow-gray-300 bg-secondary px-4 py-6 w-[400px] min-h-[400px] flex flex-col items-center justify-center rounded-md">
    <div class="bg-white rounded-full p-4 mb-2">
        <x-icon name="fingerprint" class="text-center text-royal-blue" />
    </div>
    <h2 class="text-xl text-white font-semibold">Verify Your Account</h2>
    <p class="text-xs text-white/60">OTP telah dikirim ke email Anda.</p>

    @if(session('success'))
    <div id="alert-success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
        <p class="text-green-700 ">
            <strong class="bold">Success!</strong> {{session('success')}}
        </p>
    </div>
    @endif

    <form action="{{ route('otp') }}" method="POST" class="w-full py-4 space-y-4 px-12">
        @csrf
        <input type="hidden" id="user-id" name="user_id" value="{{ Auth::user()->id}}">
        <input type="hidden" name="otp" id="otp-hidden">
        <div class="w-[54%] grid grid-cols-4 text-white gap-2 place-self-center *:text-center">
            <input type="text" maxlength="1" class="otp-box border border-gray-300 size-9 rounded">
            <input type="text" maxlength="1" class="otp-box border border-gray-300 size-9 rounded">
            <input type="text" maxlength="1" class="otp-box border border-gray-300 size-9 rounded">
            <input type="text" maxlength="1" class="otp-box border border-gray-300 size-9 rounded">
        </div>
        <p class="text-center text-xs text-gray-500">Didn't receive code? <button type="button" onclick="handleResendOtp(event)" class="text-primary">Resend now</button></p>

        <button type="submit" class="w-full text-center font-semibold  bg-white rounded p-3 text-sm text-secondary hover:scale-105">Verify Account</button>
    </form>

    <form id="otp-resend" action="{{ route('otp.resend') }}" method="POST">
        @csrf
    </form>
</div>
@endsection


@section('script')
<script>
    const inputs = document.querySelectorAll(".otp-box");

    inputs.forEach((input, index) => {
        input.addEventListener("input", () => {
            if (input.value.length === 1 && index < inputs.length - 1) {
                inputs[index + 1].focus();
            }
        });

        input.addEventListener("keydown", (e) => {
            if (e.key === "Backspace" && input.value === '' && index > 0) {
                inputs[index - 1].focus();
            }
        });
    });

    const getOtpValue = () => {
        const otp = Array.from(document.querySelectorAll('.otp-box')).map(input => input.value).join('')

        return otp
    }

    document.querySelector('form').addEventListener('submit', (e) => {
        const otp = document.getElementById('otp-hidden').value = getOtpValue()
        const userId = document.getElementById('user-id').value

        if (otp.length < 4) {
            e.preventDefault();
            alert('Silakan isi semua digit OTP!');
        }
    })


    const handleResendOtp = (event) => {
        const otpResend = document.getElementById("otp-resend")

        const btn = event.target;
        btn.disabled = true;
        btn.textContent = "Sending...";

        otpResend.submit()
    }
</script>
@endsection