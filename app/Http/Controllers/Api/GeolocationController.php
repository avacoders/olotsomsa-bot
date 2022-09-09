<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;

class GeolocationController extends Controller
{



    public function index()
    {
        $branches = cache()->remember('geolocations', 60*60*24*365 , function (){
            return Branch::select('id','latitude','longitude')->get();
        });

        return response()->json(['ok' => true, 'data' => $branches]);
    }


    public function show()
    {
        $branches = cache()->remember('branches', 60*60*24*365 , function (){
            return Branch::select('id','latitude','longitude','title','starts','ends','origin')->get();
        });
        return response()->json(['ok' => true, 'data' => $branches]);
    }


}
