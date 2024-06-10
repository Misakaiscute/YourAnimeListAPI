<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(){
        return response(json_encode([
            'user' => auth()->user()
        ]), Response::HTTP_OK);
    }
}
