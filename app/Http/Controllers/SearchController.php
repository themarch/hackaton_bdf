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
                    $output = '<p> ' . $row->prenom_user . ' ' . $row->nom_user . ', University: ' . $row->nom_etablissement .' </p>';
                    echo $output;
                }
            }
            else {
                echo "Nobody's fine";
            }
        }
    }
}
