<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class GitController extends Controller
{

    public function index()
    {
        if (request()->ip() == env('APP_IP')) ;
        {
            shell_exec("git pull");
            shell_exec("composer update");
            echo "success";
        }
    }


}
