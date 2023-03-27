<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public $successStatus = 200;

    public function login()
    {
        if (Auth::attempt([
            'email' => request('email'),
            'password' => request('password')
        ])) {
            $user = Auth::user();
            $success['token']  = $user->createToken('nApp')->accessToken;
            return response()->json(['success' => $success], 401);
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'password-confirm' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] = $user->createToken('nApp')->accessToken;
        $success['name'] = $user->name;

        return response()->json(['success' => $success], $this->successStatus);
    }

    public function update_user(Request $request)
    {
        $update = Auth::user();
        if ($request->file('foto')) {
            $file = $request->file('foto')->store('profile', 'public');
            if ($update->foto && file_exists(storage_path('app/public/' . $update->foto))) {
                Storage::delete('public/' . $update->foto);
                $file = $request->file('foto')->store('profile', 'public');
            }
        }

        $update->update([
            "name" => request('name'),
            "jabatan" => request('jabatan'),
            "email" => request('email'),
            "foto" => $file,
            'password' => bcrypt($request->password),
            'password-confirmation' => bcrypt($request->password),
        ]);

        return response()->json(['success' => $update]);
    }

    public function details_user()
    {
        $details = Auth::user();
        // if ($details->foto) {
        //     $details['foto']
        // }

        return response()->json(['success' => $details]);
    }
}
