<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="{{ asset('js/search.js') }}"></script>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">


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

            .phone1 {
                vertical-align: super !important;
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
            .telephone {
                float: left;
            }



            .adresse {
                margin-top: 0px;
                float: left;
            }
            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <a href="/"> Go home </a>
        <div class="flex-center position-ref full-height">

            <div class="content">
                @foreach ($infos as $info)
                    <h1> {{$info->prenom_user}} {{$info->nom_user}} </h1>
                    @if (isset($info->telephone_user))
                    <div class="phone">
                        <p class="telephone">
                            <span class="material-icons">
                                phone
                            </span> 
                            <span class="phone1"> {{$info->telephone_user}} </span></p>
                    </div>
                    @endif
                    @if (isset($info->adresse_user))
                    <div class="phone">
                        <p class="adresse">
                            <span class="material-icons">
                                map
                            </span> 
                            <span class="phone1"> {{$info->adresse_user}} </span></p>
                    </div>
                    @endif
                    @if (isset($info->homepage_user))
                    <div class="phone">
                        <p class="new">
                            <span class="material-icons">
                                assignment_ind
                            </span>
                            <span class="phone1"> {{$info->nom_etablissement}} </span></p>
                    </div>
                    @endif
                    @if (isset($info->homepage_user))
                    <div class="phone">
                        <p class="adresse">
                            <span class="material-icons">
                                assignment_ind
                            </span>
                            <span class="phone1"> {{$info->homepage_user}} </span></p>
                    </div>
                    @endif
                @endforeach
                <div class="title m-b-md">
                </div>
            </div>
        </div>
        </div>
    </body>
</html>
