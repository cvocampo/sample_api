<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use Illuminate\Http\Request;
use App\Notifications\sixDigitCodeNotification;
use Exception;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class InvitationController extends Controller
{

    public function store(Request $request)
    {


            $validator = Validator::make($request->all(), [
                'email' => 'required|email|unique:users',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
            }

            $invitation = Invitation::create(['email' => $request->email]);
            $response = ['success' => 'Invitation to ' . $request->email . ' is sent.'];
            return response($response,200);


    }


    public function sendSixDigitCode(Request $request,$email)
    {

        if(Invitation::where(['email' => $email])->exists() && Invitation::where(['email' => $request->email])->exists()){

            $validator = Validator::make($request->all(), [
                'user_name' => 'required|unique:users|max:20|min:4',
                'password' => 'required|confirmed',
                'email' => 'required|email|unique:users',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
            }


            $user = $this->createUser($request);

            $response = ['success' => 'Successfully sent 6 digit code to '. $request->email,
                        'url to activate' => 'localhost:8000/api/activate/' . $request->email
            ];

            return response($response,200);

        }



        else
            return response(['error' => 'Your link is already expired or the email you use is not invited.'],500);


    }


    protected function createUser($request)
    {

        $user = User::create([
            'user_name' => $request->user_name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $user = User::where('email',$request->email)->first();

        $invitation = Invitation::where('email',$request->email)->first();

        $user->notify(new sixDigitCodeNotification($invitation->six_digit_code,$request->email));

        return $user;
    }


    public function activateAccount(Request $request,$email)
    {
        if(Invitation::where('six_digit_code',$request->code)->exists()){

            $user = User::where('email',$email)->first();

            $user->assignRole('user');

            $update = User::where('email',$email)->update(['registered_at' => Carbon::now()]);

            $delete = Invitation::where('email',$email)->delete();

            return response(['success' => $email . ' is now activated.'],200);

        }
        else
        {
            return response(['error' => '6 digit code does not exists.']);
        }
    }




}
