<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('header-title', 'Seventhsoft Ticketing')</title>
    <link rel="icon" href="{{asset('storage/logo.png')}}">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- Scripts -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <script src="{{ mix('js/app.js') }}" defer></script>

    <style>
        body {
            margin: 0;
            background-color: #CDCDCD;
            font-family: 'Inter', 'Arial', sans-serif;
        }
        .custom-navbar {
            background-color: #0b234a;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 32px;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            box-shadow:
                inset 0 -3px 0 0 #ECECEC,
                0 4px 4px rgba(0, 0, 0, 0.25);
        }
        .custom-navbar .logo {
            font-size: 36px;
            font-weight: 900;
            color: white;
        }
        .nav-tabs-custom {
            display: flex;
            gap: 32px;
            align-items: center;
        }
        .nav-tabs-custom a {
            color: white;
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
        }

        .nav-tabs-custom a:hover {
            text-decoration: underline;
            text-decoration-thickness: 2px;
        }

        .nav-tabs-custom a:active {
            color: #AF8080;
        }
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-toggle {
            color: white;
            font-weight: 600;
            text-decoration: none;
            font-size: 16px;
            cursor: pointer;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            right: 0;
            top: 28px;
            background-color: white;
            border-radius: 4px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.15);
            z-index: 1001;
            min-width: 120px;
            padding: 4px 0;
        }

        .dropdown-menu a {
            display: block;
            padding: 8px 16px;
            text-decoration: none;
            color: #0b234a;
            font-weight: 500;
        }

        .dropdown-menu a:hover {
            background-color: #F1F1F1;
        }

        .dropdown-menu a:active {
            background-color: #D8D8D8;
        }

        .profile-picture {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 8px;
            vertical-align: middle;
        }

        #picture-view {
            position: fixed;
            background-color: #0000003F;
            width: 100%;
            height: 100%;
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1100;
            padding: 16px; /* smallers screens responsivity */
        }

        #picture-view.show {
            display: flex;
        }

        #picture-show {
            max-width: 80vw;
            max-height: 80vh;
            width: auto;
            height: auto;
            object-fit: contain;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
        }
        /* Disables input number UI */
        /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>
    @yield('style')
</head>
<body>
    <div id="app">
        <div id="picture-view" onclick="togglePictureView(event)">
            <img src="" id="picture-show"/>
        </div>
        <nav class="custom-navbar">
            <div class="logo">
                LOGO
            </div>

            <div class="nav-tabs-custom">
                @guest
                    @if (Route::has('login'))
                        <a href="{{ route('login') }}">{{ __('Login') }}</a>
                    @endif
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}">{{ __('Register') }}</a>
                    @endif
                @else
                    <div class="dropdown">
                        <a href="#" class="dropdown-toggle" id="dropdownToggle" onclick="toggleDropdown(event)">
                            <img src="{{ Auth::user()->profile_picture ? asset('storage/app/users/' . Auth::user()->profile_picture) : asset('storage/users/def.jpg') }}"
                            class="profile-picture"
                            alt="Profile">
                            <span>{{ Auth::user()->username }}</span>
                        </a>
                        <div class="dropdown-menu" id="dropdownMenu">
                            <a class="dropdown-item" href="{{route('updateUserInfo')}}">Update User</a>
                            <a class="dropdown-item" style="color: #E53B3B;" href="#"
                            onclick="duplicationPrevention(event,'logout-form')">
                                {{ __('Logout') }}
                            </a>
                        </div>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                @endguest
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
<script>
    function duplicationPrevention(event, id) { //prevents double submission and submitting on pages you already submitted.
        event.preventDefault();
        const form = document.getElementById(id);
        if (!form.dataset.submitted) {
            form.dataset.submitted = 'true';
            form.requestSubmit();
        }
    }
    function toggleDropdown(event) {
        event.preventDefault();
        const menu = document.getElementById('dropdownMenu');
        menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
    }

    // Close dropdown if clicked outside
    document.addEventListener('click', function(event) {
        const toggle = document.getElementById('dropdownToggle');
        const menu = document.getElementById('dropdownMenu');

        if (!toggle.contains(event.target) && !menu.contains(event.target)) {
            menu.style.display = 'none';
        }
    });

    function togglePictureView(event) {
        if (event.target.id === 'picture-view') { //makes sure it toggles off when clicked outside the image.
            document.getElementById("picture-view").classList.remove('show');
        }
    }

    function updatePictureLink(src) {
        const pictureShow = document.getElementById('picture-show');
        pictureShow.setAttribute('src', src);
        document.getElementById('picture-view').classList.add('show');
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('img[class^="image"]').forEach(img => {
            img.addEventListener('click', function () {
                updatePictureLink(img.getAttribute('src'));
            });
        });
    });
    //Disables decrement and increment for number inputs.
    document.querySelectorAll('input[type=number]').forEach(function(input) {
        input.addEventListener('keydown', function(e) {
            const badKeys = ['e', 'E', '+', '-']; //We don't have any exponential values, so this blocks it.
            if (badKeys.includes(e.key)) {
                e.preventDefault();
            }
            if (e.key === 'ArrowUp' || e.key === 'ArrowDown') {
                e.preventDefault();
            }
        });

        input.addEventListener('wheel', function(e) {
            e.preventDefault();
        });
    });
</script>
</html>
