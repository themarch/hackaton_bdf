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
            $sql = DB::select("SELECT prenom_user, nom_user, nom_etablissement, user.uniqid from user, etablissement where user.uniqid = etablissement.uniqid and user.prenom_user LIKE '". $request->txt . "%' or user.nom_user LIKE '". $request->txt . "%'");
            /*DB::table('user')
                        ->where('prenom', 'like', $request->txt.'%')
                        ->orWhere('nom', 'like', $request->txt.'%')
                        ->get(['prenom', 'nom', 'uniqid']); */
            /*$total_row = $sql->count();
            /if($total_row > 0)
            {*/
                foreach($sql as $row)
                {
                    $output = '';
                    $output = '<p> ' . $row->prenom_user . ' ' . $row->nom_user . ', University: ' . $row->nom_etablissement . ' </p>';
                    echo $output;
                }
            /*}
            else {
                echo "Nobody's fine";
            }*/
        }
    }
}
