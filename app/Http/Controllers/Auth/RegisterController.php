<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Distrito;
use Mail;
use App\Mail\EmailVerification;
use App\Http\Controllers\GuestController;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends GuestController
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm()
    {
        $distritos=Distrito::all();
        return view('auth.register', compact('distritos'));
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'username' => 'required|unique:users|max:90',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'distrito' => $data['distrito'],
            'password' => bcrypt($data['password']),
        ]);
    }

    public function register(Request $request)
    {
        $validator = $this->validator($request->all());
        if ($validator->fails()) {
            $this->throwValidationException($request, $validator);
        }
        $user = $this->create($request->all());
        $user->email_token = str_random(50);
        $user->save();
        $email = new EmailVerification($user);
        Mail::to($user->email)->send($email);
        return redirect()->route('home')->with('success', 'E-mail de verificação enviado');
    }

    public function verify($userid, $token)
    {
        $user = User::where('id', $userid)->first();
        if ($user->email_token == $token) {
            $user->verified();
            return redirect()->route('login')->with('success', 'E-mail verificado com sucesso');
        }

        return back();
    }
}
