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
                        ->where('name', 'like', '%'.$request->txt.'%')
                        ->get('name');
            $total_row = $sql->count();
            if($total_row > 0)
            {
                foreach($sql as $row)
                {
                    $output = '';
                    $output = '<p> ' . $row->name . ' </p>';
                    echo $output;
                }
            }
            else {
                echo "Nobody's fine";
            }
        }
    }
}
