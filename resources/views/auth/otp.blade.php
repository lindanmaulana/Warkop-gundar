@extends('layouts.auth')

@section('content')
<div class="shadow-md shadow-gray-300 px-4 py-6 w-[400px] min-h-[400px] flex flex-col items-center justify-center rounded-md">
    <div class="bg-royal-blue/10 rounded-full p-4 mb-2">
        <x-icon name="fingerprint" class="text-center text-royal-blue" />
    </div>
    <h2 class="text-lg font-semibold">Verify Your Account</h2>
    <p class="text-xs text-gray-500">OTP telah dikirim ke email Anda.</p>

    <form action="{{ route('otp') }}" method="POST" class="w-full py-4 space-y-4 px-12">
        @csrf
        <input type="hidden" id="user-id" name="user_id" value="{{ Auth::user()->id}}">
        <input type="hidden" name="otp" id="otp-hidden">
        <div class="w-[54%] grid grid-cols-4 gap-2 place-self-center *:text-center">
            <input type="text" maxlength="1" class="otp-box border border-gray-300 size-9 rounded">
            <input type="text" maxlength="1" class="otp-box border border-gray-300 size-9 rounded">
            <input type="text" maxlength="1" class="otp-box border border-gray-300 size-9 rounded">
            <input type="text" maxlength="1" class="otp-box border border-gray-300 size-9 rounded">
        </div>
        <p class="text-center text-xs text-gray-500">Didn't receive code? <span class="text-royal-blue">Resend now</span></p>

        <button type="submit" class="w-full text-center bg-royal-blue/80 rounded px-4 py-1 text-sm text-white">Verify Account</button>
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
</script>
@endsection