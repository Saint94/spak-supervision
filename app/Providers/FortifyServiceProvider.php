<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->email;

            return Limit::perMinute(5)->by($email.$request->ip());
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        //Login View
        Fortify::loginView(function () {
            return view('auth.login');
        });

        //Forgot password View
        Fortify::requestPasswordResetLinkView(function () {
            return view('auth.forgot-password');
        });

        //reset password View
        Fortify::resetPasswordView(function ($request) {
            return view('auth.reset-password', ['request' => $request]);
        });

        Fortify::authenticateUsing(function (Request $request) {

            $count_users = DB::table('users')->count();

            if($count_users == 0) {
                DB::table('users')->insert([
                    'noms' => 'super admin',
                    'prenoms' => NULL,
                    'tel1' => NULL,
                    'tel2' => NULL,
                    'email' => NULL,
                    'fonction' => 'Super Administrateur',
                    'type' => 'A',
                    'entite' => NULL,
                    'details' => NULL,
                    'username' => 'admin',
                    'password' => Hash::make('admin'),
                    'mot_de_passe' => 'admin',
                    'statut' => 'Y',
                    'reset_password' => 'N',
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }

            $user = User::where([
                ['username', $request->username],
                ['statut', 'Y']
            ])->first();

            if ($user && Hash::check($request->password, $user->password)) {
                return $user;
            }
        });
    }
}
