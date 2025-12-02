<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserLog;
use App\Models\FailedLoginAttempt;
use Illuminate\Validation\ValidationException;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    /**
     * Maximum number of login attempts before blocking the user
     */
    protected $maxAttempts = 3;

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
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return array_merge($request->only($this->username(), 'password'), ['estado' => 1]);
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        
        $this->validateLogin($request);

        // Check if the user is already blocked
        $user = User::where('email', $request->email)->first();

        if ($user && $user->estado == 0) {
            return $this->sendBlockedResponse($request);
        }

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            // Block the user after too many attempts
            if ($user) {
                $user->update(['estado' => 0]);
                FailedLoginAttempt::create([
                    'user_id' => $user->id,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'attempted_at' => now(),
                ]);
            }

            return $this->sendBlockedResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        // Record failed attempt
        if ($user) {
            FailedLoginAttempt::create([
                'user_id' => $user->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'attempted_at' => now(),
            ]);

            // Check if user should be blocked (3 or more failed attempts in last 24 hours)
            $failedAttempts = FailedLoginAttempt::where('user_id', $user->id)
                ->where('attempted_at', '>=', now()->subDay())
                ->count();

            if ($failedAttempts >= $this->maxAttempts) {
                $user->update(['estado' => 0]);
                return $this->sendBlockedResponse($request);
            }
        }

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Send the response after the user was blocked.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendBlockedResponse(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => ['Su cuenta ha sido bloqueada debido a múltiples intentos fallidos. Por favor contacte al administrador.'],
        ]);
    }

    /**
     * Send the response after the user failed to authenticate.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        // Clear failed attempts on successful login
        FailedLoginAttempt::where('user_id', $user->id)->delete();

        // Registro de auditoría
        UserLog::create([
            'user_id' => $user->id,
            'login_at' => now(),
        ]);

        return redirect()->intended($this->redirectPath());
    }
}
