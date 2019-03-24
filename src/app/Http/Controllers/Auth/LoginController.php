<?php

namespace App\Http\Controllers\Auth;

use Auth;
use App\User;
// use App\Model\Account;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Socialite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Redirect user to GitHub authentification page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('github')->scopes(['read:user', 'public_repo'])->redirect(); 
    }

    /**
     * Get user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback(Request $request)
    {
        // Get user information from github
        $github_user = Socialite::driver('github')->user();

        // Check existence
        $now = date("Y/m/d H:i:s");
        $app_user = User::select()
                        ->where("github_id", $github_user->user['login'])
                        ->first();
        if (empty($app_user)) {
            // Add new user
            User::insert([
                "github_id" => $github_user->user['login'],
                "created_at" => $now,
                "updated_at" => $now
            ]);

            $app_user = User::select()
                            ->where("github_id", $github_user->user['login'])
                            ->first();
        }
        $request->session()->put('github_token', $github_user->token);

        // Login using laravel
        Auth::login($app_user, true);

        // [Debug] Check login status
        // $info = $request->session();
        // $info = $request->user();
        // // $info = Auth::user();
        // // $info = Auth::check();

        return redirect('home');
        // return view('main/login', ['info' => var_dump($info)]);
    }

    public function logout(Request $request)
    {
        // Logout using laravel
        Auth::logout();

        // $info = $request->session();
        // $info = $request->user();
        // // $info = Auth::user();
        // // $info = Auth::check();

        return redirect('/');
        // return view('main/login', ['info' => var_dump($info)]);
    }
}
