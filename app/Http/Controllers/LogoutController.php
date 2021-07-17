<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LogoutController extends Controller
{

    public function logout(Request $request){

        $user = request()->user();

        $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();


        $response = ['success' => 'You have been logout.'];

        return response($response,200);
    }

}
