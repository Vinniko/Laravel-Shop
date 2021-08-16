<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\TokenResource;
use App\Providers\RouteServiceProvider;
use Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;
    private $authenticateErrorMessage = 'Ошибка авторизации! Неправильно введен логин или пароль!';
    private $logoutErrorMessage = 'Пользователя не существует!';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function authenticate(Request $request)
    {
        if (preg_match("/^((\+7)+([0-9]){10})$/", $request->login)) {
            if (Auth::attempt([
                'phone' => $request->login,
                'password' => $request->password,
            ], $request->remember)) {
                return new TokenResource([
                    'token' => $this->generateToken(),
                ]);
            } else {
                return new ErrorResource([
                    'error' => $this->authenticateErrorMessage,
                ]);
            }
        } else {
            if (Auth::attempt([
                'email' => $request->login,
                'password' => $request->password
            ], $request->remember)) {
                return new TokenResource([
                    'token' => $this->generateToken(),
                ]);
            } else {
                return new ErrorResource([
                    'error' => $this->authenticateErrorMessage,
                ]);
            }
        }

    }

    private function generateToken()
    {
        $user = Auth::user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        $token->save();

        return $tokenResult->accessToken;
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            Auth::user()->OauthAcessToken()->delete();
            Auth::logout();

            return redirect()->intended('/');
        }

        return new ErrorResource([
            'error' => $this->logoutErrorMessage,
        ]);
    }
}
