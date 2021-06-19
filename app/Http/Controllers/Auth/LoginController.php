<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

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
    protected $redirectTo = RouteServiceProvider::HOME;

    public function authenticate(Request $request)
    {
        if (preg_match("/^((\+7)+([0-9]){10})$/", $request->login)){
            if (\Auth::attempt(['phone' => $request->login, 'password' => $request->password], $request->remember)) {
                $user = \Auth::user(); 
                $tokenResult = $user->createToken('Personal Access Token'); 
                $token = $tokenResult->token; 
                $token->save(); 
                return $tokenResult->accessToken;
            }
            else{
                return $message = 'Ошибка авторизации! Неправильно введен логин или пароль!';
            }
        }
        else{
            if (\Auth::attempt(['email' => $request->login, 'password' => $request->password], $request->remember)) {
                $user = \Auth::user(); 
                $tokenResult = $user->createToken('Personal Access Token'); 
                $token = $tokenResult->token; 
                $token->save(); 
                return $tokenResult->accessToken;
            }
            else{
                return $message = 'Ошибка авторизации! Неправильно введен логин или пароль!';
            }
        }
        
    }

    public function logout(Request $request)
    {
        if (\Auth::check()) {
            \Auth::user()->OauthAcessToken()->delete();
            \Auth::logout();
            return redirect()->intended('/');
         }
        return response()->json(['message' => 'Пользователя не существует!']);
    
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
