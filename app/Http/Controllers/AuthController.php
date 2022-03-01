<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //

    public function index()
    {
        $data = [
            'action' => '#',
        ];
        return view('login', $data);
    }

    public function doLogin(Request $request)
    {

        $messages = [
            'required' => ':attribute harus diisi',
        ];

        $rules = [
            'username' => 'required',
            'password' => 'required',
        ];

        $errors = Validator::make($request->all(), $rules, $messages);

        if ($errors->fails()) {
            return response()->json([
                'status' => 422,
                'messages' => $errors->errors()->all()
            ]);
        }

        try {
            $user = User::where('username', $request->username)->first();

            if($user) {
                if($user->username == $request->username && $user->password == Hash::check(md5($request->password), $user->password)) {
                    Auth::login($user);
                } else {
                    return response()->json([
                        'status' => 422,
                        'messages' => ['username dan password tidak cocok '],
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 422,
                    'messages' => ['username dan password tidak ditemukan '],
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => 422,
                'messages' => ['Login Gagal '. $e->getMessage()],
            ]);
        }

        return response()->json([
            'status' => 200,
            'messages' => 'Login Sukses',
            'url' => route('barang.index'),
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
