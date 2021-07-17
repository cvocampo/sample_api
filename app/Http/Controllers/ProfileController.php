<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class ProfileController extends Controller
{
    public function updateProfile(Request $request)
    {

        $user = auth()->user();

        $validator = Validator::make($request->all(), [
            'user_name' => 'max:20|min:4',
                'password' => 'confirmed',
                'email' => 'email',
                'avatar' => 'max:20000|mimes:jpg,png'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
        }


        $avatar_path =  $this->saveImage($request->avatar,$request->email);
        $password = null;

        if($request->password != null)
            $password = Hash::make($request->password);


        $update = User::where('id',$user->id)->update([
                'name' => $request->name,
                'user_name' => $request->user_name,
                'password' => $password,
                'email' => $request->email,
                'avatar' => $avatar_path,
        ]);


        $response = ['success' => 'Account Successfully updated.'
        ];

        return response($response,200);






    }



    protected function saveImage($image,$email)
    {
        $path = null;

        if($image != null)
            $extension = $image->extension();
            $filename = $email . '.' . $extension;
            $image->move(public_path('avatar'), $filename);
            $path = public_path('avatar') . '/' . $filename;

        return $path;
    }
}
