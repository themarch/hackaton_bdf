<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="{{ asset('js/search.js') }}"></script>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
        <script src="{{ asset('js/profile.js') }}"></script>
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">


        <title>Banque De France</title>

    </head>
    <body>
        @foreach ($infos as $info)
        <div id="wrapper">
            <div class="left">
                <span class="material-icons icon_central">
                    person_outline
                    </span>
                <p class="contact"> MES INFORMATIONS </p>
                <div class="perso">
                    @if (isset($info->telephone_user))
                    <p>
                        <span class="material-icons icon">
                            contact_phone
                        </span>
                        <span class="align"> {{$info->telephone_user}}  </span> </p>
                    @endif
                    @if (isset($info->adresse_user))
                    <p>
                        <span class="material-icons icon">
                            place
                        </span>
                        <span class="align place_user"> {{$info->adresse_user}}  </span> </p>
                    @endif
                    @if (isset($info->email_user))
                    <p>
                        <span class="material-icons icon">
                            email
                        </span>
                        <span class="align email_user"> {{$info->email_user}}  </span> </p>
                    @endif
                    @if (isset($info->link_user))
                    <p>
                        <span class="material-icons icon">
                            link
                        </span>
                        <span class="align"> <a class="link" target="blank" href="{{$info->link_user}}"> Link to Repec </a>  </span> </p>
                    @endif
                    @if (isset($info->repec_shortid))
                    <p>
                        <span class="material-icons icon">
                            perm_identity
                        </span>
                        <span class="align"> <strong> Repec : </strong>{{$info->repec_shortid}} </span> </p>
                    @endif
                </div>
                <p class="contact"> ETABLISSEMENT </p>
                <div class="perso">
                @if (isset($info->nom_etablissement))
                <p>
                    <span class="material-icons icon">
                        school
                    </span>
                    <span class="align"> {{$info->nom_etablissement}}  </span> </p>
                @endif
                @if (isset($info->pays_ville_etablissement))
                <p>
                    <span class="material-icons icon">
                        location_city
                    </span>
                    <span class="align"> {{$info->pays_ville_etablissement}}  </span> </p>
                @endif
                @if (isset($info->email_etablissement))
                <p>
                    <span class="material-icons icon">
                        email
                    </span>
                    <span class="align"> {{$info->email_etablissement}}  </span> </p>
                @endif
                @if (isset($info->phone_etablissement))
                <p>
                    <span class="material-icons icon">
                        contact_phone
                    </span>
                    <span class="align"> {{$info->phone_etablissement}}  </span> </p>
                @endif
                @if (isset($info->adresse_etablissement))
                <p>
                    <span class="material-icons icon">
                        place
                    </span>
                    <span class="align"> {{$info->adresse_etablissement}}  </span> </p>
                @endif
                @if (isset($info->site_etablissement))
                <p>
                    <span class="material-icons icon">
                        phone_iphone
                    </span>
                    <span class="align"> <a class="link" target="blank" href="{{$info->site_etablissement}}"> Go to Website </a> </span> </p>
                @endif
                @if (isset($info->fax_etablissement))
                <p>
                    <span class="material-icons icon">
                        print
                    </span>
                    <span class="align"> {{$info->fax_etablissement}}  </span> </p>
                @endif
                </div>
            </div>
            <div class="right">
                <span class="search"> <a href="/"> <button class="button_back"> Retour au recherches </button> </a> </span>
                <h1 class="center"> {{$info->prenom_user}} {{$info->nom_user}} </h1>
                <hr class="hr"><br>
                <h3 class="speciality"> {{$info->homepage_user}}</h3>
                <h4 class="speciality" id="article"> Articles </h4>
                <div class="article_div">
                @foreach ($article as $arti)
                    <div class="all_text">
                    <p> 
                        <span class="material-icons icon_article">
                            menu_book
                            </span>
                        <span class="title_article"> <a target="blank" href="{{$arti->link_paper}}"> {{$arti->name_paper}} </a></span> </p>
                    @if (isset($arti->JEL_1))
                    <p class="information"> 
                        <span class="material-icons icon_article ">
                            info
                            </span>
                        <span class="title_article"> {{$arti->JEL_1}} @if (isset($arti->JEL_2)) <strong>&</strong> {{$arti->JEL_2}} @endif @if (isset($arti->JEL_3))<strong>&</strong> {{$arti->JEL_3}} @endif</span> </p>
                            @endif
                    </div>
                    @endforeach
                </div>
                </div>
            </div>
        @endforeach
    </body>
</html>
