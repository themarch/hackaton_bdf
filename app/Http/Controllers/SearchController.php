<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class SearchController extends Controller
{
    public function index(Request $request) {
        return view('welcome');
    }

    public function search1(Request $request) {
        
        if($request->ajax())
        {
            if (preg_match('/\s/', $request->name)) {
                $output = '';
                if ($request->name == NULL) {
                    if ($request->etablissement == NULL && $request->competence != NULL) {
                        //Faire recherche avec uniquement filtre compétence
                        $sql = DB::table('user')
                                ->join('etablissement', 'user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                ->join('article', 'user.uniqid', '=', 'article.id_auteur')
                                ->where(function($q) use ($request) {
                                    $q->where('article.JEL_1', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_2', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_3', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_name', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_4', 'like', '%' . $request->competence.'%');
                                })
                                ->distinct()
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                    else if ($request->etablissement != NULL && $request->competence == NULL) {
                        //Faire recherche avec uniquement filtre etablissement
                        $sql = DB::table('user')
                        ->join('etablissement', function ($join) {
                                $join->on('user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                    ->orOn('user.id_etablissement_user2', '=', 'etablissement.uniqid')
                                    ->orOn('user.id_etablissement_user3', '=', 'etablissement.uniqid')
                                    ->orOn('user.id_etablissement_user4', '=', 'etablissement.uniqid');
                                })
                                ->where('etablissement.nom_etablissement', 'like', '%' . $request->etablissement.'%')
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                    else if ($request->etablissement != NULL && $request->competence != NULL) {
                        //Faire recherche avec filtre etablissement AND filtre compétence
                        $sql = DB::table('user')
                            ->join('article', 'user.uniqid', '=', 'article.id_auteur')
                            ->where('etablissement.nom_etablissement', 'like', '%' . $request->etablissement.'%')
                            ->join('etablissement', function ($join) {
                                $join->on('user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                    ->orOn('user.id_etablissement_user2', '=', 'etablissement.uniqid')
                                    ->orOn('user.id_etablissement_user3', '=', 'etablissement.uniqid')
                                    ->orOn('user.id_etablissement_user4', '=', 'etablissement.uniqid');
                                })
                                ->where(function($q) use ($request) {
                                    $q->where('article.JEL_1', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_2', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_3', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_name', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_4', 'like', '%' . $request->competence.'%');
                                })
                                ->distinct()
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                }
                else {
                    if ($request->etablissement == NULL && $request->competence != NULL) {
                        //Faire recherche avec filtre compétence + filtre nom 
                        $sql = DB::table('user')
                                ->join('etablissement', 'user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                ->join('article', 'user.uniqid', '=', 'article.id_auteur')
                                ->where(function($q) use ($request) {
                                    $q->where('user.all_name', 'like', '%' . $request->name.'%')
                                    ->orWhere('user.all_name_invers', 'like', '%' . $request->name.'%');
                                })  
                                ->where(function($q) use ($request) {
                                    $q->where('article.JEL_1', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_2', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_3', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_name', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_4', 'like', '%' . $request->competence.'%');
                                })
                                ->distinct()
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                    else if ($request->etablissement != NULL && $request->competence == NULL) {
                        //Faire recherche avec filtre etablissement + filtre nom
                        $sql = DB::table('user')
                                ->join('etablissement', 'user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                ->where('etablissement.nom_etablissement', 'like', '%' . $request->etablissement.'%')
                                ->where(function($q) use ($request) {
                                    $q->where('user.all_name', 'like', '%' . $request->name.'%')
                                    ->orWhere('user.all_name_invers', 'like', '%' . $request->name.'%');
                                })  
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                    else if ($request->etablissement != NULL && $request->competence != NULL) {
                        //Faire recherche avec filtre etablissement AND filtre compétence AND filtre nom
                        $sql = DB::table('user')
                                ->join('etablissement', 'user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                ->join('article', 'user.uniqid', '=', 'article.id_auteur')
                                ->where('etablissement.nom_etablissement', 'like', '%' . $request->etablissement.'%')
                                ->where(function($q) use ($request) {
                                    $q->where('article.JEL_1', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_2', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_3', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_name', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_4', 'like', '%' . $request->competence.'%');
                                })
                                ->where(function($q) use ($request) {
                                    $q->where('user.all_name', 'like', '%' . $request->name.'%')
                                    ->orWhere('user.all_name_invers', 'like', '%' . $request->name.'%');
                                })
                                ->distinct()  
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                    else {
                        //Faire recherche avec uniquement filtre nom
                        $sql = DB::table('user')
                                ->join('etablissement', 'user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                ->where(function($q) use ($request) {
                                    $q->where('user.all_name', 'like', '%' . $request->name.'%')
                                    ->orWhere('user.all_name_invers', 'like', '%' . $request->name.'%');
                                })  
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                }
            }
            else {
                $output = '';
                if ($request->name == NULL) {
                    if ($request->etablissement == NULL && $request->competence != NULL) {
                        //Faire recherche avec uniquement filtre compétence
                        $sql = DB::table('user')
                                ->join('article', 'user.uniqid', '=', 'article.id_auteur')
                                ->join('etablissement', function ($join) {
                                    $join->on('user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                        ->orOn('user.id_etablissement_user2', '=', 'etablissement.uniqid')
                                        ->orOn('user.id_etablissement_user3', '=', 'etablissement.uniqid')
                                        ->orOn('user.id_etablissement_user4', '=', 'etablissement.uniqid');
                                    })
                                ->where(function($q) use ($request) {
                                    $q->where('article.JEL_1', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_2', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_3', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_name', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_4', 'like', '%' . $request->competence.'%');
                                })
                                ->distinct()
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                    else if ($request->etablissement != NULL && $request->competence == NULL) {
                        //Faire recherche avec uniquement filtre etablissement
                        $sql = DB::table('user')
                            ->join('etablissement', function ($join) {
                                $join->on('user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                    ->orOn('user.id_etablissement_user2', '=', 'etablissement.uniqid')
                                    ->orOn('user.id_etablissement_user3', '=', 'etablissement.uniqid')
                                    ->orOn('user.id_etablissement_user4', '=', 'etablissement.uniqid');
                                })
                                ->where('etablissement.nom_etablissement', 'like', '%' . $request->etablissement.'%')
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                    else if ($request->etablissement != NULL && $request->competence != NULL) {
                        //Faire recherche avec filtre etablissement AND filtre compétence
                        $sql = DB::table('user')
                                ->join('article', 'user.uniqid', '=', 'article.id_auteur')
                                ->join('etablissement', function ($join) {
                                    $join->on('user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                        ->orOn('user.id_etablissement_user2', '=', 'etablissement.uniqid')
                                        ->orOn('user.id_etablissement_user3', '=', 'etablissement.uniqid')
                                        ->orOn('user.id_etablissement_user4', '=', 'etablissement.uniqid');
                                    })
                                ->where('etablissement.nom_etablissement', 'like', '%' . $request->etablissement.'%')
                                ->where(function($q) use ($request) {
                                    $q->where('article.JEL_1', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_2', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_3', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_name', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_4', 'like', '%' . $request->competence.'%');
                                })
                                ->distinct()
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                }
                else {
                    if ($request->etablissement == NULL && $request->competence != NULL) {
                        //Faire recherche avec filtre compétence + filtre nom 
                        $sql = DB::table('user')
                                ->join('etablissement', 'user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                ->join('article', 'user.uniqid', '=', 'article.id_auteur')
                                ->where(function($q) use ($request) {
                                    $q->where('user.prenom_user', 'like', '%' . $request->name.'%')
                                    ->where(function($q) use ($request) {
                                        $q->where('article.JEL_1', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_2', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_3', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_name', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_4', 'like', '%' . $request->competence.'%');
                                    });
                                })
                                ->orWhere(function($q)  use ($request){
                                    $q->where('user.nom_user', 'like', '%' . $request->name.'%')
                                    ->where(function($q) use ($request) {
                                        $q->where('article.JEL_1', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_2', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_3', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_name', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_4', 'like', '%' . $request->competence.'%');
                                    });
                                })
                                ->distinct()
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                    else if ($request->etablissement != NULL && $request->competence == NULL) {
                        //Faire recherche avec filtre etablissement + filtre nom
                        $sql = DB::table('user')
                            ->join('etablissement', function ($join) {
                                $join->on('user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                    ->orOn('user.id_etablissement_user2', '=', 'etablissement.uniqid')
                                    ->orOn('user.id_etablissement_user3', '=', 'etablissement.uniqid')
                                    ->orOn('user.id_etablissement_user4', '=', 'etablissement.uniqid');
                                })
                                ->where(function($q) use ($request) {
                                    $q->where('etablissement.nom_etablissement', 'like', '%' . $request->etablissement.'%')
                                    ->where('user.prenom_user', 'like', '%' . $request->name.'%');
                                })
                                ->orWhere(function($q)  use ($request){
                                    $q->where('etablissement.nom_etablissement', 'like', '%' . $request->etablissement.'%')
                                    ->where('user.nom_user', 'like','%' . $request->name.'%');
                                })
                                ->distinct()
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                    else if ($request->etablissement != NULL && $request->competence != NULL) {
                        //Faire recherche avec filtre etablissement AND filtre compétence AND filtre nom
                        $sql = DB::table('user')
                                ->join('article', 'user.uniqid', '=', 'article.id_auteur')
                                ->join('etablissement', function ($join) {
                                    $join->on('user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                        ->orOn('user.id_etablissement_user2', '=', 'etablissement.uniqid')
                                        ->orOn('user.id_etablissement_user3', '=', 'etablissement.uniqid')
                                        ->orOn('user.id_etablissement_user4', '=', 'etablissement.uniqid');
                                    })
                                ->where(function($q) use ($request) {
                                    $q->where('etablissement.nom_etablissement', 'like', '%' . $request->etablissement.'%')
                                    ->where('user.prenom_user', 'like','%' .  $request->name.'%')
                                    ->join('article', 'user.uniqid', '=', 'article.id_auteur')
                                    ->where(function($q) use ($request) {
                                        $q->where('article.JEL_1', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_2', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_3', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_name', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_4', 'like', '%' . $request->competence.'%');
                                    });
                                })
                                ->orWhere(function($q)  use ($request){
                                    $q->where('etablissement.nom_etablissement', 'like', '%' . $request->etablissement.'%')
                                    ->where('user.nom_user', 'like', '%' . $request->name.'%')
                                    ->where(function($q) use ($request) {
                                        $q->where('article.JEL_1', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_2', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_3', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_name', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_4', 'like', '%' . $request->competence.'%');
                                    });
                                })
                                ->distinct()
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                    else {
                        //Faire recherche avec uniquement filtre nom
                        $sql = DB::table('user')
                                ->join('etablissement', 'user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                ->where('user.prenom_user', 'like', '%' . $request->name.'%')
                                ->orWhere('user.nom_user', 'like', '%' . $request->name.'%')
                                ->distinct()
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                }
            }
            $total_row = $sql->count();
            if($total_row > 0)
            {  
                if (isset($sqq)) {
                    foreach($sql as $row)
                    {
                        foreach ($sqq as $sqqq) {
                            $output = '';
                            $link = "/profile/" . $row->uniqid;
                            $output = "<div class='round'> <p class='text'> <span class='material-icons icon'> perm_identity </span>
                            <a class='profile' href=" . $link . ">" . $row->prenom_user . " " . $row->nom_user . " </a> </p>
                                    <p class='text text1'>
                                        <span class='material-icons icon'>
                                            school
                                        </span>
                                            " . $sqqq->nom_etablissement . " </p>
                                </div>";
                            echo $output;
                        }
                    }
                }
                else {
                    foreach($sql as $row)
                    {
                        $output = '';
                        $link = "/profile/" . $row->uniqid;
                        $output = "<div class='round'> <p class='text'> <span class='material-icons icon'> perm_identity </span>
                        <a class='profile' href=" . $link . ">" . $row->prenom_user . " " . $row->nom_user . " </a> </p>
                                <p class='text text1'>
                                    <span class='material-icons icon'>
                                        school
                                    </span>
                                        " . $row->nom_etablissement . " </p>
                            </div>";
                        echo $output;
                    }
                }
            }
            else {
                echo "rip";
            }
        }
    }

    public function search2(Request $request) {
        
        if($request->ajax())
        {
            if (preg_match('/\s/', $request->name)) {
                $output = '';
                if ($request->etablissement == NULL) {
                    if ($request->name == NULL && $request->competence != NULL) {
                        //Faire recherche avec uniquement filtre compétence
                        $sql = DB::table('user')
                                ->join('etablissement', 'user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                ->join('article', 'user.uniqid', '=', 'article.id_auteur')
                                ->where(function($q) use ($request) {
                                    $q->where('article.JEL_1', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_2', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_3', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_name', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_4', 'like', '%' . $request->competence.'%');
                                })
                                ->distinct()
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                    else if ($request->name != NULL && $request->competence == NULL) {
                        //Faire recherche avec uniquement filtre name
                        $sql = DB::table('user')
                                ->join('etablissement', 'user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                ->where(function($q) use ($request) {
                                    $q->where('user.all_name', 'like', '%' . $request->name.'%')
                                    ->orWhere('user.all_name_invers', 'like', '%' . $request->name.'%');
                                })
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                    else if ($request->name != NULL && $request->competence != NULL) {
                        //Faire recherche avec filtre name AND filtre compétence
                        $sql = DB::table('user')
                                ->join('etablissement', 'user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                ->join('article', 'user.uniqid', '=', 'article.id_auteur')
                                ->where(function($q) use ($request) {
                                    $q->where('article.JEL_1', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_2', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_3', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_name', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_4', 'like', '%' . $request->competence.'%');
                                })
                                ->where(function($q) use ($request) {
                                    $q->where('user.all_name', 'like', '%' . $request->name.'%')
                                    ->orWhere('user.all_name_invers', 'like', '%' . $request->name.'%');
                                })
                                ->distinct()
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                }
                else {
                    if ($request->name == NULL && $request->competence != NULL) {
                        //Faire recherche avec filtre compétence + filtre etablissement 
                        $sql = DB::table('user')
                                ->join('article', 'user.uniqid', '=', 'article.id_auteur')
                                ->join('etablissement', function ($join) {
                                    $join->on('user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                        ->orOn('user.id_etablissement_user2', '=', 'etablissement.uniqid')
                                        ->orOn('user.id_etablissement_user3', '=', 'etablissement.uniqid')
                                        ->orOn('user.id_etablissement_user4', '=', 'etablissement.uniqid');
                                    })
                                ->where('etablissement.nom_etablissement', 'like', '%' . $request->etablissement.'%')
                                ->where(function($q) use ($request) {
                                    $q->where('article.JEL_1', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_2', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_3', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_name', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_4', 'like', '%' . $request->competence.'%');
                                })
                                ->distinct()
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                    else if ($request->name != NULL && $request->competence == NULL) {
                        //Faire recherche avec filtre name + filtre etablissement
                        $sql = DB::table('user')
                                ->join('etablissement', function ($join) {
                                    $join->on('user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                        ->orOn('user.id_etablissement_user2', '=', 'etablissement.uniqid')
                                        ->orOn('user.id_etablissement_user3', '=', 'etablissement.uniqid')
                                        ->orOn('user.id_etablissement_user4', '=', 'etablissement.uniqid');
                                    })
                                ->where('etablissement.nom_etablissement', 'like', '%' . $request->etablissement.'%')
                                ->where(function($q) use ($request) {
                                    $q->where('user.all_name', 'like', '%' . $request->name.'%')
                                    ->orWhere('user.all_name_invers', 'like', '%' . $request->name.'%');
                                }) 
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                    else if ($request->name != NULL && $request->competence != NULL) {
                        //Faire recherche avec filtre etablissement AND filtre compétence AND filtre nom
                        $sql = DB::table('user')
                            ->join('article', 'user.uniqid', '=', 'article.id_auteur')
                            ->join('etablissement', function ($join) {
                                $join->on('user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                    ->orOn('user.id_etablissement_user2', '=', 'etablissement.uniqid')
                                    ->orOn('user.id_etablissement_user3', '=', 'etablissement.uniqid')
                                    ->orOn('user.id_etablissement_user4', '=', 'etablissement.uniqid');
                                })
                                ->where('etablissement.nom_etablissement', 'like', '%' . $request->etablissement.'%')
                                ->where(function($q) use ($request) {
                                    $q->where('article.JEL_1', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_2', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_3', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_name', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_4', 'like', '%' . $request->competence.'%');
                                })
                                ->where(function($q) use ($request) {
                                    $q->where('user.all_name', 'like', '%' . $request->name.'%')
                                    ->orWhere('user.all_name_invers', 'like', '%' . $request->name.'%');
                                }) 
                                ->distinct()
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                    else {
                        //Faire recherche avec uniquement filtre etablissement
                        $sql = DB::table('user')
                            ->join('etablissement', function ($join) {
                                $join->on('user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                    ->orOn('user.id_etablissement_user2', '=', 'etablissement.uniqid')
                                    ->orOn('user.id_etablissement_user3', '=', 'etablissement.uniqid')
                                    ->orOn('user.id_etablissement_user4', '=', 'etablissement.uniqid');
                                })
                                ->where('etablissement.nom_etablissement', 'like', '%' . $request->etablissement.'%')                        
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                }
            }
            else {
                $output = '';
                if ($request->etablissement == NULL) {
                    if ($request->name == NULL && $request->competence != NULL) {
                        //Faire recherche avec uniquement filtre compétence
                        $sql = DB::table('user')
                                ->join('etablissement', 'user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                ->join('article', 'user.uniqid', '=', 'article.id_auteur')
                                ->where(function($q) use ($request) {
                                    $q->where('article.JEL_1', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_2', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_3', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_name', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_4', 'like', '%' . $request->competence.'%');
                                })
                                ->distinct()
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                    else if ($request->name != NULL && $request->competence == NULL) {
                        //Faire recherche avec uniquement filtre name
                        $sql = DB::table('user')
                                ->join('etablissement', 'user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                ->where('user.prenom_user', 'like', '%' . $request->name.'%')
                                ->orWhere('user.nom_user', 'like', '%' . $request->name.'%')
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                    else if ($request->name != NULL && $request->competence != NULL) {
                        //Faire recherche avec filtre name AND filtre compétence
                        $sql = DB::table('user')
                                ->join('etablissement', 'user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                ->join('article', 'user.uniqid', '=', 'article.id_auteur')
                                ->where(function($q) use ($request) {
                                    $q->where('user.prenom_user', 'like','%' .  $request->name.'%')
                                    ->where(function($q) use ($request) {
                                        $q->where('article.JEL_1', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_2', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_3', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_name', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_4', 'like', '%' . $request->competence.'%');
                                    });
                                })
                                ->orWhere(function($q)  use ($request){
                                    $q->where('user.nom_user', 'like', '%' . $request->name.'%')
                                    ->join('article', 'user.uniqid', '=', 'article.id_auteur')
                                    ->where(function($q) use ($request) {
                                        $q->where('article.JEL_1', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_2', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_3', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_name', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_4', 'like', '%' . $request->competence.'%');
                                    });
                                })
                                ->distinct()
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                }
                else {
                    if ($request->name == NULL && $request->competence != NULL) {
                        //Faire recherche avec filtre compétence + filtre etablissement 
                        $sql = DB::table('user')
                                ->join('article', 'user.uniqid', '=', 'article.id_auteur')
                                ->join('etablissement', function ($join) {
                                    $join->on('user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                        ->orOn('user.id_etablissement_user2', '=', 'etablissement.uniqid')
                                        ->orOn('user.id_etablissement_user3', '=', 'etablissement.uniqid')
                                        ->orOn('user.id_etablissement_user4', '=', 'etablissement.uniqid');
                                    })
                                ->where('etablissement.nom_etablissement', 'like', '%' . $request->etablissement.'%')
                                ->where(function($q) use ($request) {
                                    $q->where('article.JEL_1', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_2', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_3', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_name', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_4', 'like', '%' . $request->competence.'%');
                                })
                                ->distinct()
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                    else if ($request->name != NULL && $request->competence == NULL) {
                        //Faire recherche avec filtre name + filtre etablissement
                        $sql = DB::table('user')
                            ->join('etablissement', function ($join) {
                                $join->on('user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                    ->orOn('user.id_etablissement_user2', '=', 'etablissement.uniqid')
                                    ->orOn('user.id_etablissement_user3', '=', 'etablissement.uniqid')
                                    ->orOn('user.id_etablissement_user4', '=', 'etablissement.uniqid');
                                })
                                ->where(function($q) use ($request) {
                                    $q->where('etablissement.nom_etablissement', 'like', '%' . $request->etablissement.'%')
                                    ->where('user.prenom_user', 'like', '%' . $request->name.'%');
                                })
                                ->orWhere(function($q)  use ($request){
                                    $q->where('etablissement.nom_etablissement', 'like', '%' . $request->etablissement.'%')
                                    ->where('user.nom_user', 'like', '%' . $request->name.'%');
                                })
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                    else if ($request->name != NULL && $request->competence != NULL) {
                        //Faire recherche avec filtre etablissement AND filtre compétence AND filtre nom
                        $sql = DB::table('user')
                                ->join('article', 'user.uniqid', '=', 'article.id_auteur')
                                ->join('etablissement', function ($join) {
                                    $join->on('user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                        ->orOn('user.id_etablissement_user2', '=', 'etablissement.uniqid')
                                        ->orOn('user.id_etablissement_user3', '=', 'etablissement.uniqid')
                                        ->orOn('user.id_etablissement_user4', '=', 'etablissement.uniqid');
                                    })
                                ->where(function($q) use ($request) {
                                    $q->where('etablissement.nom_etablissement', 'like', '%' . $request->etablissement.'%')
                                    ->where('user.prenom_user', 'like', '%' . $request->name.'%')
                                    ->where(function($q) use ($request) {
                                        $q->where('article.JEL_1', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_2', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_3', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_name', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_4', 'like', '%' . $request->competence.'%');
                                    });
                                })
                                ->orWhere(function($q)  use ($request){
                                    $q->where('etablissement.nom_etablissement', 'like', '%' . $request->etablissement.'%')
                                    ->where('user.nom_user', 'like', '%' . $request->name.'%')
                                    ->where(function($q) use ($request) {
                                        $q->where('article.JEL_1', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_2', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_3', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_name', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_4', 'like', '%' . $request->competence.'%');
                                    });
                                })
                                ->distinct()
                                //IL manque WHERE ETABLISSEMENT WHERE COMPETENCE (WHERE PRENOM OR WHERE NAME)
                                //->orWhere('user.nom_user', 'like', $request->name.'%')
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                    else {
                        //Faire recherche avec uniquement filtre etablissement
                        $sql = DB::table('user')
                                ->join('etablissement', function ($join) {
                                    $join->on('user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                        ->orOn('user.id_etablissement_user2', '=', 'etablissement.uniqid')
                                        ->orOn('user.id_etablissement_user3', '=', 'etablissement.uniqid')
                                        ->orOn('user.id_etablissement_user4', '=', 'etablissement.uniqid');
                                    })
                                ->where('etablissement.nom_etablissement', 'like', '%' . $request->etablissement.'%')                        
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                }
            }
            $total_row = $sql->count();
            if($total_row > 0)
            {
                foreach($sql as $row)
                {
                    $output = '';
                    $link = "/profile/" . $row->uniqid;
                    $output = "<div class='round'> <p class='text'> <span class='material-icons icon'> perm_identity </span>
                    <a class='profile' href=" . $link . ">" . $row->prenom_user . " " . $row->nom_user . " </a> </p>
                            <p class='text text1'>
                                <span class='material-icons icon'>
                                    school
                                </span>
                                    " . $row->nom_etablissement . " </p>
                        </div>";
                    echo $output;
                }
            }
            else {
                echo "rip";
            }
        }
    }

    public function search3(Request $request) {
        if($request->ajax())
        {
            if (preg_match('/\s/', $request->name)) {
                $output = '';
                if ($request->competence == NULL) {
                    if ($request->name == NULL && $request->etablissement != NULL) {
                        //Faire recherche avec uniquement filtre etablissement
                        $sql = DB::table('user')
                            ->join('etablissement', function ($join) {
                                $join->on('user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                    ->orOn('user.id_etablissement_user2', '=', 'etablissement.uniqid')
                                    ->orOn('user.id_etablissement_user3', '=', 'etablissement.uniqid')
                                    ->orOn('user.id_etablissement_user4', '=', 'etablissement.uniqid');
                                })
                                ->where('etablissement.nom_etablissement', 'like', '%' . $request->etablissement.'%')                        
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                    else if ($request->name != NULL && $request->etablissement == NULL) {
                        //Faire recherche avec uniquement filtre name
                        $sql = DB::table('user')
                                ->join('etablissement', 'user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                ->where(function($q) use ($request) {
                                    $q->where('user.all_name', 'like', '%' . $request->name.'%')
                                    ->orWhere('user.all_name_invers', 'like', '%' . $request->name.'%');
                                })
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                    else if ($request->name != NULL && $request->etablissement != NULL) {
                        //Faire recherche avec filtre name AND filtre etablissement
                        $sql = DB::table('user')
                            ->join('etablissement', function ($join) {
                                $join->on('user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                    ->orOn('user.id_etablissement_user2', '=', 'etablissement.uniqid')
                                    ->orOn('user.id_etablissement_user3', '=', 'etablissement.uniqid')
                                    ->orOn('user.id_etablissement_user4', '=', 'etablissement.uniqid');
                                })
                                ->where('etablissement.nom_etablissement', 'like', '%' . $request->etablissement.'%')
                                ->where(function($q) use ($request) {
                                    $q->where('user.all_name', 'like', '%' . $request->name.'%')
                                    ->orWhere('user.all_name_invers', 'like', '%' . $request->name.'%');
                                })                    
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                }
                else {
                    if ($request->name == NULL && $request->etablissement != NULL) {
                        //Faire recherche avec filtre compétence + filtre etablissement 
                        $sql = DB::table('user')
                                ->join('article', 'user.uniqid', '=', 'article.id_auteur')
                                ->join('etablissement', function ($join) {
                                    $join->on('user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                        ->orOn('user.id_etablissement_user2', '=', 'etablissement.uniqid')
                                        ->orOn('user.id_etablissement_user3', '=', 'etablissement.uniqid')
                                        ->orOn('user.id_etablissement_user4', '=', 'etablissement.uniqid');
                                    })
                                ->where('etablissement.nom_etablissement', 'like', '%' . $request->etablissement.'%')
                                ->where(function($q) use ($request) {
                                    $q->where('article.JEL_1', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_2', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_3', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_name', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_4', 'like', '%' . $request->competence.'%');
                                })
                                ->distinct()
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                    else if ($request->name != NULL && $request->etablissement == NULL) {
                        //Faire recherche avec filtre name + filtre competence
                        $sql = DB::table('user')
                                ->join('etablissement', 'user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                ->join('article', 'user.uniqid', '=', 'article.id_auteur')
                                ->where(function($q) use ($request) {
                                    $q->where('article.JEL_1', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_2', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_3', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_name', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_4', 'like', '%' . $request->competence.'%');
                                })
                                ->where(function($q) use ($request) {
                                    $q->where('user.all_name', 'like', '%' . $request->name.'%')
                                    ->orWhere('user.all_name_invers', 'like', '%' . $request->name.'%');
                                }) 
                                ->distinct()
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                    else if ($request->name != NULL && $request->etablissement != NULL) {
                        //Faire recherche avec filtre etablissement AND filtre compétence AND filtre nom
                        $sql = DB::table('user')
                                ->join('article', 'user.uniqid', '=', 'article.id_auteur')
                                ->join('etablissement', function ($join) {
                                    $join->on('user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                        ->orOn('user.id_etablissement_user2', '=', 'etablissement.uniqid')
                                        ->orOn('user.id_etablissement_user3', '=', 'etablissement.uniqid')
                                        ->orOn('user.id_etablissement_user4', '=', 'etablissement.uniqid');
                                    })
                                ->where('etablissement.nom_etablissement', 'like', '%' . $request->etablissement.'%')
                                ->where(function($q) use ($request) {
                                    $q->where('article.JEL_1', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_2', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_3', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_name', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_4', 'like', '%' . $request->competence.'%');
                                })
                                ->where(function($q) use ($request) {
                                    $q->where('user.all_name', 'like', '%' . $request->name.'%')
                                    ->orWhere('user.all_name_invers', 'like', '%' . $request->name.'%');
                                }) 
                                ->distinct()
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                    else {
                        //Faire recherche avec uniquement filtre competence
                        $sql = DB::table('user')
                                ->join('article', 'user.uniqid', '=', 'article.id_auteur')
                                ->join('etablissement', function ($join) {
                                    $join->on('user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                        ->orOn('user.id_etablissement_user2', '=', 'etablissement.uniqid')
                                        ->orOn('user.id_etablissement_user3', '=', 'etablissement.uniqid')
                                        ->orOn('user.id_etablissement_user4', '=', 'etablissement.uniqid');
                                    })
                                ->where('article.JEL_1', 'like', '%' . $request->competence.'%')
                                ->orWhere('article.JEL_2', 'like', '%' . $request->competence.'%')
                                ->orWhere('article.JEL_3', 'like', '%' . $request->competence.'%')
                                ->orWhere('article.JEL_name', 'like', '%' . $request->competence.'%')
                                ->orWhere('article.JEL_4', 'like', '%' . $request->competence.'%')
                                ->distinct()
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                }
            }
            else {
                $output = '';
                if ($request->competence == NULL) {
                    if ($request->name == NULL && $request->etablissement != NULL) {
                        //Faire recherche avec uniquement filtre etablissement
                        $sql = DB::table('user')
                            ->join('etablissement', function ($join) {
                                $join->on('user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                    ->orOn('user.id_etablissement_user2', '=', 'etablissement.uniqid')
                                    ->orOn('user.id_etablissement_user3', '=', 'etablissement.uniqid')
                                    ->orOn('user.id_etablissement_user4', '=', 'etablissement.uniqid');
                                })
                                ->where('etablissement.nom_etablissement', 'like', '%' . $request->etablissement.'%')                        
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                    else if ($request->name != NULL && $request->etablissement == NULL) {
                        //Faire recherche avec uniquement filtre name
                        $sql = DB::table('user')
                                ->join('etablissement', 'user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                ->where('user.prenom_user', 'like', '%' . $request->name.'%')
                                ->orWhere('user.nom_user', 'like', '%' . $request->name.'%')
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                    else if ($request->name != NULL && $request->etablissement != NULL) {
                        //Faire recherche avec filtre name AND filtre etablissement
                        $sql = DB::table('user')
                            ->join('etablissement', function ($join) {
                                $join->on('user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                    ->orOn('user.id_etablissement_user2', '=', 'etablissement.uniqid')
                                    ->orOn('user.id_etablissement_user3', '=', 'etablissement.uniqid')
                                    ->orOn('user.id_etablissement_user4', '=', 'etablissement.uniqid');
                                })
                                ->where(function($q) use ($request) {
                                    $q->where('user.prenom_user', 'like', '%' . $request->name.'%')
                                    ->where('etablissement.nom_etablissement', 'like', '%' . $request->etablissement.'%');
                                })
                                ->orWhere(function($q)  use ($request){
                                    $q->where('user.nom_user', 'like', $request->name.'%')
                                    ->where('etablissement.nom_etablissement', 'like', '%' . $request->etablissement.'%');
                                })                       
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                }
                else {
                    if ($request->name == NULL && $request->etablissement != NULL) {
                        //Faire recherche avec filtre compétence + filtre etablissement 
                        $sql = DB::table('user')
                                ->join('article', 'user.uniqid', '=', 'article.id_auteur')
                                ->join('etablissement', function ($join) {
                                    $join->on('user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                        ->orOn('user.id_etablissement_user2', '=', 'etablissement.uniqid')
                                        ->orOn('user.id_etablissement_user3', '=', 'etablissement.uniqid')
                                        ->orOn('user.id_etablissement_user4', '=', 'etablissement.uniqid');
                                    })
                                ->where('etablissement.nom_etablissement', 'like', '%' . $request->etablissement.'%')
                                ->where(function($q) use ($request) {
                                    $q->where('article.JEL_1', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_2', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_3', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_name', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_4', 'like', '%' . $request->competence.'%');
                                })
                                ->distinct()
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                    else if ($request->name != NULL && $request->etablissement == NULL) {
                        //Faire recherche avec filtre name + filtre competence
                        $sql = DB::table('user')
                                ->join('etablissement', 'user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                ->join('article', 'user.uniqid', '=', 'article.id_auteur')
                                ->where(function($q) use ($request) {
                                    $q->where('user.prenom_user', 'like', '%' . $request->name.'%')
                                    ->where(function($q) use ($request) {
                                        $q->where('article.JEL_1', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_2', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_3', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_name', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_4', 'like', '%' . $request->competence.'%');
                                    });
                                })
                                ->orWhere(function($q)  use ($request){
                                    $q->where('user.nom_user', 'like', '%' . $request->name.'%')
                                    ->where(function($q) use ($request) {
                                        $q->where('article.JEL_1', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_2', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_3', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_name', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_4', 'like', '%' . $request->competence.'%');
                                    });
                                }) 
                                ->distinct()
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                    else if ($request->name != NULL && $request->etablissement != NULL) {
                        //Faire recherche avec filtre etablissement AND filtre compétence AND filtre nom
                        $sql = DB::table('user')
                                    ->join('article', 'user.uniqid', '=', 'article.id_auteur')
                                    ->join('etablissement', function ($join) {
                                        $join->on('user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                            ->orOn('user.id_etablissement_user2', '=', 'etablissement.uniqid')
                                            ->orOn('user.id_etablissement_user3', '=', 'etablissement.uniqid')
                                            ->orOn('user.id_etablissement_user4', '=', 'etablissement.uniqid');
                                        })
                                ->where(function($q) use ($request) {
                                    $q->where('etablissement.nom_etablissement', 'like', '%' . $request->etablissement.'%')
                                    ->where('user.prenom_user', 'like', '%' . $request->name.'%')
                                    ->where(function($q) use ($request) {
                                        $q->where('article.JEL_1', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_2', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_3', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_name', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_4', 'like', '%' . $request->competence.'%');
                                    });
                                })
                                ->orWhere(function($q)  use ($request){
                                    $q->where('etablissement.nom_etablissement', 'like', '%' . $request->etablissement.'%')
                                    ->where('user.nom_user', 'like', '%' . $request->name.'%')
                                    ->where(function($q) use ($request) {
                                        $q->where('article.JEL_1', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_2', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_3', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_name', 'like', '%' . $request->competence.'%')
                                        ->orWhere('article.JEL_4', 'like', '%' . $request->competence.'%');
                                    });
                                }) 
                                ->distinct()
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                    else {
                        //Faire recherche avec uniquement filtre competence
                        $sql = DB::table('user')
                                ->join('etablissement', 'user.id_etablissement_user1', '=', 'etablissement.uniqid')
                                ->join('article', 'user.uniqid', '=', 'article.id_auteur')
                                ->where(function($q) use ($request) {
                                    $q->where('article.JEL_1', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_2', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_3', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_name', 'like', '%' . $request->competence.'%')
                                    ->orWhere('article.JEL_4', 'like', '%' . $request->competence.'%');
                                })
                                ->distinct()
                                ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                    }
                }
            }
            $total_row = $sql->count();
            if($total_row > 0)
            {
                foreach($sql as $row)
                {
                    $output = '';
                    $link = "/profile/" . $row->uniqid;
                    $output = "<div class='round'> <p class='text'> <span class='material-icons icon'> perm_identity </span>
                    <a class='profile' href=" . $link . ">" . $row->prenom_user . " " . $row->nom_user . " </a> </p>
                            <p class='text text1'>
                                <span class='material-icons icon'>
                                    school
                                </span>
                                    " . $row->nom_etablissement . " </p>
                        </div>";
                    //$output = "<div class='round'> <a class='profile' href=" . $link . ">" . $row->prenom_user . " " . $row->nom_user . ", University: " . $row->nom_etablissement ."</a> </div>";
                    echo $output;
                }
            }
            else {
                echo "rip";
            }
        }
    }

    public function profile(Request $request, $id) {
        $classification = [];
        $query = DB::table('user')
            ->join('etablissement', 'user.id_etablissement_user1', '=', 'etablissement.uniqid')
            ->where('user.uniqid', '=', $id)
            ->get();
        $query1 = DB::table('user')
                    ->where('user.uniqid', '=', $id)
                    ->get();
        $article = DB::table('article')
            ->join('user', 'article.id_auteur', '=', 'user.uniqid')
            ->where('user.uniqid', '=', $id)
            ->distinct('article.name_paper')
            ->get(['article.name_paper', 'article.link_paper'])
            ->toArray();
        $new_article = [];
        $i = 0;
        while ($i < count($article)) {
            $to_remove = false;
            foreach (array_slice($article, 0, $i) as $el) {
                if (strcmp($el->name_paper, $article[$i]->name_paper) == 0) {
                    $to_remove = true;
                    break ;
                }
            }
            if ($to_remove) {
                array_splice($article, $i, $i + 1);
            } else {
                $i += 1;
            }
        }
        foreach ($article as $arti) {
            array_push($classification,DB::table('article')
            ->where('article.name_paper', '=', $arti->name_paper)
            ->distinct()
            ->get(['article.name_paper', 'article.JEL_1', 'article.JEL_2', 'article.JEL_3', 'article.JEL_4']));
        }
        $articles = DB::table('article')
        ->join('user', 'article.id_auteur', '=', 'user.uniqid')
        ->where('user.uniqid', '=', $id)
        ->get();
        $etablissement = DB::table('user')
            ->join('etablissement', function ($join) {
            $join->on('user.id_etablissement_user1', '=', 'etablissement.uniqid')
                ->orOn('user.id_etablissement_user2', '=', 'etablissement.uniqid')
                ->orOn('user.id_etablissement_user3', '=', 'etablissement.uniqid')
                ->orOn('user.id_etablissement_user4', '=', 'etablissement.uniqid');
            })
            ->where('user.uniqid', '=', $id)
            ->get('etablissement.nom_etablissement');
        $ind = 0;
        $string = '';
        while ($ind < count($articles) - 1) {
            if ($ind == (count($articles) - 2))
                $string = $string . $articles[$ind]->JEL_2.' | '.$articles[$ind + 1]->JEL_2;
            else {
                $string = $string . $articles[$ind]->JEL_2.' | '.$articles[$ind + 1]->JEL_2.' | ';
            }
            $ind = $ind + 1;
        }
        $ind1 = 0;
        $string1 = '';
        while ($ind1 < count($articles) - 1) {
            if ($ind1 == (count($articles) - 2))
                $string1 = $string1 . $articles[$ind1]->JEL_2.' | '.$articles[$ind1 + 1]->JEL_2.' | '.$articles[$ind1]->JEL_3.' | '.$articles[$ind1 + 1]->JEL_3;
            else {
                $string1 = $string1 . $articles[$ind1]->JEL_2.' | '.$articles[$ind1 + 1]->JEL_2.' | '.$articles[$ind1]->JEL_3.' | '.$articles[$ind1 + 1]->JEL_3.' | ';
            }
            $ind1 = $ind1 + 1;
        }
        $ind2 = 0;
        $string2 = '';
        while ($ind2 < count($articles) - 1) {
            if ($ind2 == (count($articles) - 2))
                $string2 = $string2 . $articles[$ind2]->JEL_1.' | '.$articles[$ind2 + 1]->JEL_1.' | '.$articles[$ind2]->JEL_2.' | '.$articles[$ind2 + 1]->JEL_2.' | '.$articles[$ind2]->JEL_3.' | '.$articles[$ind2 + 1]->JEL_3;
            else {
                $string2 = $string2 . $articles[$ind2]->JEL_1.' | '.$articles[$ind2 + 1]->JEL_1.' | '.$articles[$ind2]->JEL_2.' | '.$articles[$ind2 + 1]->JEL_2.' | '.$articles[$ind2]->JEL_3.' | '.$articles[$ind2 + 1]->JEL_3.' | ';
            }
            $ind2 = $ind2 + 1;
        }
        $ind3 = 0;
        $string3 = '';
        while ($ind3 < count($articles) - 1) {
            if ($ind3 == (count($articles) - 2))
                $string3 = $string3 . $articles[$ind3]->JEL_1.' | '.$articles[$ind3 + 1]->JEL_1.' | '.$articles[$ind3]->JEL_2.' | '.$articles[$ind3 + 1]->JEL_2.' | '.$articles[$ind3]->JEL_3.' | '.$articles[$ind3 + 1]->JEL_3.' | '.$articles[$ind3]->JEL_4.' | '.$articles[$ind3 + 1]->JEL_4;
            else {
                $string3 = $string3 . $articles[$ind3]->JEL_1.' | '.$articles[$ind3 + 1]->JEL_1.' | '.$articles[$ind3]->JEL_2.' | '.$articles[$ind3 + 1]->JEL_2.' | '.$articles[$ind3]->JEL_3.' | '.$articles[$ind3 + 1]->JEL_3.' | '.$articles[$ind3]->JEL_4.' | '.$articles[$ind3 + 1]->JEL_4.' | ';
            }
            $ind3 = $ind3 + 1;
        }
        $str = implode(' | ', array_unique(explode(' | ', $string)));
        $str1 = implode(' | ', array_unique(explode(' | ', $string1)));
        $str2 = implode(' | ', array_unique(explode(' | ', $string2)));
        $str3 = implode(' | ', array_unique(explode(' | ', $string3)));
        if (count($query1) == 0)
            return redirect('/');
        $data = array (
            'infos' => $query,
            'query1' => $query1,
            'article' => $article,
            'str' => $str,
            'str1' => $str1,
            'str2' => $str2,
            'str3' => $str3,
            'classification' => $classification,
            'etablissement' => $etablissement,
        );
        return view ('profile')->with($data);
    }

    public function profilesearch(Request $request) {
        $article = DB::table('article')
        ->join('user', 'article.id_auteur', '=', 'user.uniqid')
        ->where('user.uniqid', '=', $request->id)
        ->where(function($q) use ($request) {
            $q->where('article.name_paper', 'like', '%' . $request->competence.'%')
            ->orWhere('article.JEL_1', 'like', '%' . $request->competence.'%')
            ->orWhere('article.JEL_2', 'like', '%' . $request->competence.'%')
            ->orWhere('article.JEL_3', 'like', '%' . $request->competence.'%')
            ->orWhere('article.JEL_4', 'like', '%' . $request->competence.'%')
            ->orWhere('article.JEL_name', 'like', '%' . $request->competence.'%');
        })
        ->get();
        $total_row = $article->count();
            if($total_row > 0)
            {
                foreach($article as $row)
                {
                    $output = '';
                    $link = "/profile/" . $row->uniqid;
                    $output =  "<p> 
                    <span class='material-icons icon_article'>
                        menu_book
                        </span>
                    <span class='title_article'> <a target='blank' href=" . $row->link_paper . ">" . $row->name_paper . "</a></span> </p>
                <p class='information'> 
                    <span class='material-icons icon_article '>
                        info
                        </span>
                    <span class='title_article'>" . $row->JEL_1 . "<strong> & </strong>" . $row->JEL_2 . "<strong> & </strong>" . $row->JEL_3 . "</span> </p>";
                    echo ($output);
                }
            }
            else {
                $output = "Aucun article trouvé !";
                echo ($output);
            }
        }
}
