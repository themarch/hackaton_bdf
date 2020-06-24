<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class SearchController extends Controller
{
    public function index(Request $request) {
        return view('welcome');
    }

    public function search(Request $request) {
        
        if($request->ajax())
        {
            $output = '';
            $sql = DB::table('user')
                        ->join('etablissement', 'user.uniqid', '=', 'etablissement.uniqid')
                        ->where('user.prenom_user', 'like', $request->txt.'%')
                        ->orWhere('user.nom_user', 'like', $request->txt.'%')
                        ->orWhere('etablissement.nom_etablissement', 'like', $request->txt.'%')
                        ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']); 
            $total_row = $sql->count();
            if($total_row > 0)
            {
                foreach($sql as $row)
                {
                    $output = '';
                    $link = "/profile/" . $row->uniqid;
                    $output = "<a href=" . $link . ">" . $row->prenom_user . " " . $row->nom_user . ", University: " . $row->nom_etablissement ." </p>";
                    echo $output;
                }
            }
            else {
                echo "Recherche impossible !";
            }
        }
    }

    public function search1(Request $request) {
        
        if($request->ajax())
        {
            $output = '';
            if ($request->name == NULL) {
                if ($request->etablissement == NULL && $request->competence != NULL) {
                    //Faire recherche avec uniquement filtre compétence
                    $sql = DB::table('user')
                            ->join('etablissement', 'user.uniqid', '=', 'etablissement.uniqid')
                            ->where('user.homepage_user', 'like', $request->competence.'%')
                            ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                }
                else if ($request->etablissement != NULL && $request->competence == NULL) {
                    //Faire recherche avec uniquement filtre etablissement
                    $sql = DB::table('user')
                            ->join('etablissement', 'user.uniqid', '=', 'etablissement.uniqid')
                            ->where('etablissement.nom_etablissement', 'like', $request->etablissement.'%')
                            ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                }
                else if ($request->etablissement != NULL && $request->competence != NULL) {
                    //Faire recherche avec filtre etablissement AND filtre compétence
                    $sql = DB::table('user')
                            ->join('etablissement', 'user.uniqid', '=', 'etablissement.uniqid')
                            ->where('etablissement.nom_etablissement', 'like', $request->etablissement.'%')
                            ->where('user.homepage_user', 'like', $request->competence.'%')
                            ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                }
            }
            else {
                if ($request->etablissement == NULL && $request->competence != NULL) {
                    //Faire recherche avec filtre compétence + filtre nom 
                    $sql = DB::table('user')
                            ->join('etablissement', 'user.uniqid', '=', 'etablissement.uniqid')
                            ->where(function($q) use ($request) {
                                $q->where('user.prenom_user', 'like', $request->name.'%')
                                ->where('user.homepage_user', 'like', $request->competence.'%');
                            })
                            ->orWhere(function($q)  use ($request){
                                $q->where('user.nom_user', 'like', $request->name.'%')
                                ->where('user.homepage_user', 'like', $request->competence.'%');
                            })
                            ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                }
                else if ($request->etablissement != NULL && $request->competence == NULL) {
                    //Faire recherche avec filtre etablissement + filtre nom
                    $sql = DB::table('user')
                            ->join('etablissement', 'user.uniqid', '=', 'etablissement.uniqid')
                            ->where(function($q) use ($request) {
                                $q->where('etablissement.nom_etablissement', 'like', $request->etablissement.'%')
                                ->where('user.prenom_user', 'like', $request->name.'%');
                            })
                            ->orWhere(function($q)  use ($request){
                                $q->where('etablissement.nom_etablissement', 'like', $request->etablissement.'%')
                                ->where('user.nom_user', 'like', $request->name.'%');
                            })
                            ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                }
                else if ($request->etablissement != NULL && $request->competence != NULL) {
                    //Faire recherche avec filtre etablissement AND filtre compétence AND filtre nom
                    $sql = DB::table('user')
                            ->join('etablissement', 'user.uniqid', '=', 'etablissement.uniqid')
                            ->where('etablissement.nom_etablissement', 'like', $request->etablissement.'%')
                            ->where('user.homepage_user', 'like', $request->competence.'%')
                            ->where('user.prenom_user', 'like', $request->name.'%')
                            //IL manque WHERE ETABLISSEMENT WHERE COMPETENCE (WHERE PRENOM OR WHERE NAME)
                            //->orWhere('user.nom_user', 'like', $request->name.'%')
                            ->where(function($q) use ($request) {
                                $q->where('etablissement.nom_etablissement', 'like', $request->etablissement.'%')
                                ->where('user.homepage_user', 'like', $request->competence.'%')
                                ->where('user.prenom_user', 'like', $request->name.'%');
                            })
                            ->orWhere(function($q)  use ($request){
                                $q->where('etablissement.nom_etablissement', 'like', $request->etablissement.'%')
                                ->where('user.homepage_user', 'like', $request->competence.'%')
                                ->where('user.nom_user', 'like', $request->name.'%');
                            })
                            ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                }
                else {
                    //Faire recherche avec uniquement filtre nom
                    $sql = DB::table('user')
                            ->join('etablissement', 'user.uniqid', '=', 'etablissement.uniqid')                        
                            ->where('user.prenom_user', 'like', $request->name.'%')
                            ->orWhere('user.nom_user', 'like', $request->name.'%')
                            ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                }
            }
            $total_row = $sql->count();
            if($total_row > 0)
            {
                foreach($sql as $row)
                {
                    $output = '';
                    $link = "/profile/" . $row->uniqid;
                    $output = "<a href=" . $link . ">" . $row->prenom_user . " " . $row->nom_user . ", University: " . $row->nom_etablissement ." </p>";
                    echo $output;
                }
            }
            else {
                echo "Recherche impossible !";
            }
        }
    }

    public function search2(Request $request) {
        
        if($request->ajax())
        {
            $output = '';
            if ($request->etablissement == NULL) {
                if ($request->name == NULL && $request->competence != NULL) {
                    //Faire recherche avec uniquement filtre compétence
                    $sql = DB::table('user')
                            ->join('etablissement', 'user.uniqid', '=', 'etablissement.uniqid')
                            ->where('user.homepage_user', 'like', $request->competence.'%')
                            ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                }
                else if ($request->name != NULL && $request->competence == NULL) {
                    //Faire recherche avec uniquement filtre name
                    $sql = DB::table('user')
                            ->join('etablissement', 'user.uniqid', '=', 'etablissement.uniqid')
                            ->where('user.prenom_user', 'like', $request->name.'%')
                            ->orWhere('user.nom_user', 'like', $request->name.'%')
                            ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                }
                else if ($request->name != NULL && $request->competence != NULL) {
                    //Faire recherche avec filtre name AND filtre compétence
                    $sql = DB::table('user')
                            ->join('etablissement', 'user.uniqid', '=', 'etablissement.uniqid')
                            ->where(function($q) use ($request) {
                                $q->where('user.prenom_user', 'like', $request->name.'%')
                                ->where('user.homepage_user', 'like', $request->competence.'%');
                            })
                            ->orWhere(function($q)  use ($request){
                                $q->where('user.nom_user', 'like', $request->name.'%')
                                ->where('user.homepage_user', 'like', $request->competence.'%');
                            })
                            ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                }
            }
            else {
                if ($request->name == NULL && $request->competence != NULL) {
                    //Faire recherche avec filtre compétence + filtre etablissement 
                    $sql = DB::table('user')
                            ->join('etablissement', 'user.uniqid', '=', 'etablissement.uniqid')
                            ->where('etablissement.nom_etablissement', 'like', $request->etablissement.'%')
                            ->where('user.homepage_user', 'like', $request->competence.'%')
                            ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                }
                else if ($request->name != NULL && $request->competence == NULL) {
                    //Faire recherche avec filtre name + filtre etablissement
                    $sql = DB::table('user')
                            ->join('etablissement', 'user.uniqid', '=', 'etablissement.uniqid')
                            ->where(function($q) use ($request) {
                                $q->where('etablissement.nom_etablissement', 'like', $request->etablissement.'%')
                                ->where('user.prenom_user', 'like', $request->name.'%');
                            })
                            ->orWhere(function($q)  use ($request){
                                $q->where('etablissement.nom_etablissement', 'like', $request->etablissement.'%')
                                ->where('user.nom_user', 'like', $request->name.'%');
                            })
                            ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                }
                else if ($request->name != NULL && $request->competence != NULL) {
                    //Faire recherche avec filtre etablissement AND filtre compétence AND filtre nom
                    $sql = DB::table('user')
                            ->join('etablissement', 'user.uniqid', '=', 'etablissement.uniqid')
                            ->where('etablissement.nom_etablissement', 'like', $request->etablissement.'%')
                            ->where('user.homepage_user', 'like', $request->competence.'%')
                            ->where('user.prenom_user', 'like', $request->name.'%')
                            ->where(function($q) use ($request) {
                                $q->where('etablissement.nom_etablissement', 'like', $request->etablissement.'%')
                                ->where('user.homepage_user', 'like', $request->competence.'%')
                                ->where('user.prenom_user', 'like', $request->name.'%');
                            })
                            ->orWhere(function($q)  use ($request){
                                $q->where('etablissement.nom_etablissement', 'like', $request->etablissement.'%')
                                ->where('user.homepage_user', 'like', $request->competence.'%')
                                ->where('user.nom_user', 'like', $request->name.'%');
                            })
                            //IL manque WHERE ETABLISSEMENT WHERE COMPETENCE (WHERE PRENOM OR WHERE NAME)
                            //->orWhere('user.nom_user', 'like', $request->name.'%')
                            ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                }
                else {
                    //Faire recherche avec uniquement filtre etablissement
                    $sql = DB::table('user')
                            ->join('etablissement', 'user.uniqid', '=', 'etablissement.uniqid')
                            ->where('etablissement.nom_etablissement', 'like', $request->etablissement.'%')                        
                            ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                }
            }
            $total_row = $sql->count();
            if($total_row > 0)
            {
                foreach($sql as $row)
                {
                    $output = '';
                    $link = "/profile/" . $row->uniqid;
                    $output = "<a href=" . $link . ">" . $row->prenom_user . " " . $row->nom_user . ", University: " . $row->nom_etablissement ." </p>";
                    echo $output;
                }
            }
            else {
                echo "Recherche impossible !";
            }
        }
    }

    public function search3(Request $request) {
        
        if($request->ajax())
        {
            $output = '';
            if ($request->competence == NULL) {
                if ($request->name == NULL && $request->etablissement != NULL) {
                    //Faire recherche avec uniquement filtre etablissement
                    $sql = DB::table('user')
                            ->join('etablissement', 'user.uniqid', '=', 'etablissement.uniqid')
                            ->where('etablissement.nom_etablissement', 'like', $request->etablissement.'%')                        
                            ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                }
                else if ($request->name != NULL && $request->etablissement == NULL) {
                    //Faire recherche avec uniquement filtre name
                    $sql = DB::table('user')
                            ->join('etablissement', 'user.uniqid', '=', 'etablissement.uniqid')
                            ->where('user.prenom_user', 'like', $request->name.'%')
                            ->orWhere('user.nom_user', 'like', $request->name.'%')
                            ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                }
                else if ($request->name != NULL && $request->etablissement != NULL) {
                    //Faire recherche avec filtre name AND filtre etablissement
                    $sql = DB::table('user')
                            ->join('etablissement', 'user.uniqid', '=', 'etablissement.uniqid')
                            ->where(function($q) use ($request) {
                                $q->where('user.prenom_user', 'like', $request->name.'%')
                                ->where('etablissement.nom_etablissement', 'like', $request->etablissement.'%');
                            })
                            ->orWhere(function($q)  use ($request){
                                $q->where('user.nom_user', 'like', $request->name.'%')
                                ->where('etablissement.nom_etablissement', 'like', $request->etablissement.'%');
                            })                       
                            ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                }
            }
            else {
                if ($request->name == NULL && $request->etablissement != NULL) {
                    //Faire recherche avec filtre compétence + filtre etablissement 
                    $sql = DB::table('user')
                            ->join('etablissement', 'user.uniqid', '=', 'etablissement.uniqid')
                            ->where('etablissement.nom_etablissement', 'like', $request->etablissement.'%')
                            ->where('user.homepage_user', 'like', $request->competence.'%')
                            ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                }
                else if ($request->name != NULL && $request->etablissement == NULL) {
                    //Faire recherche avec filtre name + filtre competence
                    $sql = DB::table('user')
                            ->join('etablissement', 'user.uniqid', '=', 'etablissement.uniqid')
                            ->where(function($q) use ($request) {
                                $q->where('user.homepage_user', 'like', $request->competence.'%')
                                ->where('user.prenom_user', 'like', $request->name.'%');
                            })
                            ->orWhere(function($q)  use ($request){
                                $q->where('user.homepage_user', 'like', $request->competence.'%')
                                ->where('user.nom_user', 'like', $request->name.'%');
                            }) 
                            ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                }
                else if ($request->name != NULL && $request->etablissement != NULL) {
                    //Faire recherche avec filtre etablissement AND filtre compétence AND filtre nom
                    $sql = DB::table('user')
                            ->join('etablissement', 'user.uniqid', '=', 'etablissement.uniqid')
                            ->where(function($q) use ($request) {
                                $q->where('etablissement.nom_etablissement', 'like', $request->etablissement.'%')
                                ->where('user.homepage_user', 'like', $request->competence.'%')
                                ->where('user.prenom_user', 'like', $request->name.'%');
                            })
                            ->orWhere(function($q)  use ($request){
                                $q->where('etablissement.nom_etablissement', 'like', $request->etablissement.'%')
                                ->where('user.homepage_user', 'like', $request->competence.'%')
                                ->where('user.nom_user', 'like', $request->name.'%');
                            }) 
                            ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                }
                else {
                    //Faire recherche avec uniquement filtre competence
                    $sql = DB::table('user')
                            ->join('etablissement', 'user.uniqid', '=', 'etablissement.uniqid')
                            ->where('user.homepage_user', 'like', $request->competence.'%')
                            ->get(['user.prenom_user', 'user.nom_user', 'user.uniqid', 'etablissement.nom_etablissement']);
                }
            }
            $total_row = $sql->count();
            if($total_row > 0)
            {
                foreach($sql as $row)
                {
                    $output = '';
                    $link = "/profile/" . $row->uniqid;
                    $output = "<a href=" . $link . ">" . $row->prenom_user . " " . $row->nom_user . ", University: " . $row->nom_etablissement ."</p>";
                    echo $output;
                }
            }
            else {
                echo "Recherche impossible !";
            }
        }
    }

    public function profile(Request $request, $id) {
        $query = DB::table('user')
            ->where('uniqid', '=', $id)
            ->get();
        if (count($query) == 0)
            return redirect('/');
        $data = array (
            'infos' => $query
        );
        return view ('profile')->with($data);
    }
}
