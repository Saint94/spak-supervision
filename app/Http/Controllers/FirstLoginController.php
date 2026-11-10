<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class FirstLoginController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('auth.reset-password');
    }

    public function store(PasswordRequest $request)
    {
        DB::table('users')
            ->where('users.id',Auth::user()->id)
            ->update([
                    'users.password' => Hash::make($request->password),
                    'users.reset_password' => 'N'
                ]);

        return redirect()->route('logout')->with(['alert' => 'Mot de passe réiniatialiser avec succès']);
    }
}
