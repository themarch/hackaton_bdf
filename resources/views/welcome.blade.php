<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="{{ asset('js/search.js') }}"></script>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Banque De France</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .profile {
                text-decoration: none;
                color: #24246b;
            }
            
            .profile:hover {
                text-decoration: underline;
                text-decoration-color: rgb(36, 95, 197);
                color: rgb(36, 95, 197);
                font-weight: 400;
            }

            .round {
                margin-top: 10px;
                margin-bottom: 10px;
            }

            #result {
                border: 1px black solid;
                border-radius: 7px;
                width: 50%;
                margin-left: auto;
                margin-right: auto;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                margin-bottom: 5%;
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif
                    @endauth
                </div>
            @endif

            <div class="content">
                <h1> Recherche d'économistes spécialisés </h1>
                <div class="title m-b-md">
                    <input id="search1" style="height: 70px; width: 300px;"  autocomplete="off" placeholder="Recherche par nom" type="name">
                    <input id="search2" style="height: 70px; width: 300px;"  autocomplete="off" placeholder="Recherche par établissement" type="name">
                    <input id="search3" style="height: 70px; width: 300px;"  autocomplete="off" placeholder="Recherche par compétence" type="name">
                </div>
                <div style="display: none;" id="result">
                </div>
            </div>
        </div>
    </body>
</html>

