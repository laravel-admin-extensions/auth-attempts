<?php

namespace Manzhouya\AuthAttempts\Http\Controllers;

use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Encore\Admin\Controllers\AuthController as BaseAuthController;
use Encore\Admin\Extension as E;

class AuthAttemptsController extends BaseAuthController
{
    use ThrottlesLogins;

    public $maxAttempts;
    public $decayMinutes;

    public function __construct()
    {
        $this->maxAttempts  = E::config('maxAttempts', 5);
        $this->decayMinutes = E::config('decayMinutes', 1);
    }

    public function getLogin()
    {
        if ($this->guard()->check()) {
            return redirect($this->redirectPath());
        }

        return view('auth-attempts::login');
    }

    public function postLogin(Request $request)
    {
        $credentials = $request->only([$this->username(), 'password', 'captcha']);

        /** @var \Illuminate\Validation\Validator $validator */
        $validator = Validator::make($credentials, [
            $this->username()   => 'required',
            'password'          => 'required',
            'captcha'           => 'required|captcha',
        ]);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        unset($credentials['captcha']);

        if ($this->guard()->attempt($credentials)) {
            return $this->sendLoginResponse($request);
        }

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        $this->incrementLoginAttempts($request);

        return back()->withInput()->withErrors([
            $this->username() => $this->getFailedLoginMessage(),
        ]);
    }
}
