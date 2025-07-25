<x-guest-layout>
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div id="whitebox" class="row border rounded-5 p-3 bg-white shadow box-area">

            <!-- Left Box (Login Form) -->
            <div class="col-md-6 right-box">
                <div class="logo d-flex align-items-center">
                    <a href="/">
                        <x-images.logo-circle class="w-20 h-20 fill-current text-gray-500 rounded-circle" />
                    </a>
                    <span class="ml-2 text-lg fw-bolder fs-4" style="text-shadow: 2px 2px 4px #99ff62;">Laptop Cafe Jogjakarta</span>
                </div>

                <div class="row align-items-center">
                    <div class="header-text mb-4">
                         <h2>Halo, Selamat Datang Kembali!</h2>
                         <p>Selamat Bekerja!</p>
                    </div>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="input-group mb-3 flex-column">
                        <x-text-input id="nohp_teknisi" class="form-control form-control-lg bg-light fs-6"
                            type="text" name="nohp_teknisi" :value="old('nohp_teknisi')" required autofocus
                            autocomplete="nohp_teknisi" placeholder="Nomor HP" />
                        @if ($errors->has('nohp_teknisi'))
                            <span class="text-danger">{{ $errors->first('nohp_teknisi') }}</span>
                        @endif
                    </div>
                    <div class="input-group mb-1 flex-column">
                        <x-text-input id="password" class="form-control form-control-lg bg-light fs-6 mb-3"
                            type="password" name="password" required autocomplete="current-password"
                            placeholder="Kata Sandi" />
                        @if ($errors->has('password'))
                            <span class="text-danger">{{ $errors->first('password') }}</span>
                        @endif
                    </div>
                    <div class="input-group mb-3">
                        <x-primary-button class="btn btn-lg btn-primary w-100 fs-6">{{ __('Masuk') }}</x-primary-button>
                    </div>
                </form>
            </div>

            <!-- Right Box (Background Image) -->
            <div class="col-md-6 rounded-4 d-flex justify-content-center align-items-center flex-column left-box"
                style="background: url('{{asset('images/login-bg.png')}}') no-repeat center center; background-size: 420px 720px;">
            </div>
        </div>
    </div>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            overflow: hidden;
            background: #ececec;
        }

        #whitebox {
            margin-top: -15px;
        }

        .box-area {
            width: 930px;
        }

        .logo {
            margin-top: -40px;
            padding-bottom: 40px;
        }

        .right-box {
            padding: 40px 30px 40px 40px;
        }

        ::placeholder {
            font-size: 16px;
        }

        .rounded-4 {
            border-radius: 20px;
        }

        .rounded-5 {
            border-radius: 30px;
        }

        .d-flex {
            display: flex;
        }

        .form-control {
            width: 100%;
        }

        .btn-primary {
            background-color: #067D40;
            border-color: #067D40;
        }

        .btn-primary:hover, .btn-primary:active, .btn-primary:focus {
            background-color: #109E36;
            border-color: #109E36;
        }

        .input-group {
            display: flex;
            flex-direction: column;
        }

        .input-group .form-control {
            width: 100%;
        }

        /* --- Intro Animation CSS --- */

        /* Define Keyframes */
        @keyframes fadeInScaleUp {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }

        @keyframes slideInFromRight {
            from { opacity: 0; transform: translateX(50px); }
            to { opacity: 1; transform: translateX(0); }
        }

        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* CORRECTED: Initial state for only the elements we intend to animate */
        #whitebox,
        .left-box,
        .right-box .logo,
        .right-box .header-text,
        .right-box .mb-4, /* This targets the auth-session-status */
        .right-box form .input-group {
            opacity: 0;
            animation-fill-mode: forwards; /* Keeps the final state of the animation */
        }

        /* Apply animations with delays */

        /* 1. Animate the main container */
        #whitebox {
            animation: fadeInScaleUp 0.6s ease-out forwards;
        }

        /* 2. Animate the right-side image box */
        .left-box {
            animation: slideInFromRight 0.8s ease-out 0.4s forwards;
        }

        /* 3. Stagger animations for the left-side form elements */
        .right-box .logo {
            animation: fadeInDown 0.5s ease-out 0.8s forwards;
        }

        .right-box .header-text {
            animation: fadeInDown 0.5s ease-out 1.0s forwards;
        }

        .right-box .mb-4 { /* Session status message fades in with the header */
            animation: fadeInDown 0.5s ease-out 1.0s forwards;
        }

        .right-box form .input-group:nth-of-type(1) {
            animation: fadeInDown 0.5s ease-out 1.2s forwards;
        }

        .right-box form .input-group:nth-of-type(2) {
            animation: fadeInDown 0.5s ease-out 1.4s forwards;
        }

        .right-box form .input-group:nth-of-type(3) { /* The button */
            animation: fadeInDown 0.5s ease-out 1.6s forwards;
        }

    </style>
</x-guest-layout>