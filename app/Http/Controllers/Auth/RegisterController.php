<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\ValidationErrorResource;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     *
     * @throws ValidationException
     */
    protected function validator(array $data)
    {
        $validator = Validator::make($data, [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:255', 'unique:users', "regex:/^((\+7)+([0-9]){10})$/"],
            'password' => [
                'required',
                'string',
                'min:6',
                'confirmed',
                "regex:/[a-z]/",
                "regex:/[A-Z]/",
                "regex:/[@$!%*#?&]/",
                "regex:/^\w+[@$!%*#?&]+$/",
            ],
        ]);

        if (count($validator->errors()->messages()) != 0) {
            $errorResponse = new ValidationErrorResource([
                'errors' => $validator->errors()->messages(),
            ]);

            throw new ValidationException($validator, $errorResponse);
        }

        return $validator;
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'middle_name' => $data['middle_name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
        ]);
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        $token->save();
        return User::with('OauthAcessToken')->find($user->id);
    }
}
