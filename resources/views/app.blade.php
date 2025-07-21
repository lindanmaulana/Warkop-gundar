<!doctype html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @auth
    <meta name="user-id" content="{{ auth()->user()->id }}">
    @endauth
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-poppins-regular">
    @if(session('error'))
    <div id="alert-error" class="bg-red-100 border text-red-700 p-3 rounded fixed top-5 translate-x-1/2 right-1/2 z-50">
        {{ session('error') }}
    </div>
    @endif

    @yield('layout')

    @yield('script')

    <script>
        const handleAlert = () => {
            const alertError = document.getElementById("alert-error")
            const alertSuccess = document.getElementById('alert-success')

            if (alertError) {
                setTimeout(() => {
                    alertError.remove()
                }, 2000);
            }

            if (alertSuccess) {
                setTimeout(() => {
                    alertSuccess.remove()
                }, 1500);
            }
        }

        handleAlert()
    </script>
</body>

</html>