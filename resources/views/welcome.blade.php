<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="{{ asset('js/search.js') }}"></script>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">


        <title>Banque De France</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <div class="content">
                <img class="img" src="{{asset('image/adam.png')}}" alt="Cinque Terre">
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
        <p style="float: right; margin-right: 10px;">&copy; 2020 Aguthe<p>
    </body>
</html>

