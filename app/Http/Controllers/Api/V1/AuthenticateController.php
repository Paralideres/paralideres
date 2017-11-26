<?php

namespace App\Http\Controllers\Api\V1;


use App\Service\CommonService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use App\Http\Requests\Auth\AuthenticateRequest;

class AuthenticateController extends Controller
{
    use ThrottlesLogins, CommonService;

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['authenticate']]);
    }


    public function authenticate(AuthenticateRequest $request)
    {
        if($request->has('login')) {
            // User is locked out
            if ($lockedOut = $this->hasTooManyLoginAttempts($request)) {
                $this->fireLockoutEvent($request);
                return $this->sendLockoutResponse($request);
            }

            // grab credentials from the request
            if ($request->has('username')) {
                $username = 'username';
            } else {
                $username = 'email';
            }
            $credentials = $request->only($username, 'password');

            if ($this->guard()->attempt($credentials, true)) {
                $user = Auth::user();
                $success['access_token'] = $user->createToken(env('PASSPORT_CLIENT_SECRET'))->accessToken;
                return $this->setResponse($success, 'success', 'OK', '200', 'Success!', 'Welcome ' . env('APP_NAME'));
            } else {
                $this->incrementLoginAttempts($request);
                return $this->setResponse([], 'error', 'NotOK', '500', 'Error!', $username . '/password invalid');
            }
        }
    }


    /**
     * Redirect the user after determining they are locked out.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendLockoutResponse(Request $request)
    {
        $seconds = $this->secondsRemainingOnLockout($request);

        return response()->json($this->getLockoutErrorMessage($seconds), 403);
    }


    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return property_exists($this, 'username') ? $this->username : 'email';
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        $this->guard()->logout();

        $request->session()->flush();

        $request->session()->regenerate();

        return $this->setResponse([], 'success', 'OK', '200', 'Success!', 'Logout Success.');
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('web');
    }

}
