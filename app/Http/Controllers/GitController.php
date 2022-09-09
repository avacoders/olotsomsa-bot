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
            shell_exec("git pull origin master");
            shell_exec("php artisan migrate");
            shell_exec("php artisan octane:reload");
            echo "success";
        }
    }


}
