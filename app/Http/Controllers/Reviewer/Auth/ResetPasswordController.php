<?php

namespace App\Http\Controllers\Reviewer\Auth;

use App\Admin;
use App\AdminPasswordReset;
use Illuminate\Http\Request;
use App\ReviewerPasswordReset;
use App\Http\Controllers\Controller;
use App\Reviewer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Foundation\Auth\ResetsPasswords;


class ResetPasswordController extends Controller
{
    /*
        |--------------------------------------------------------------------------
        | Password Reset Controller
        |--------------------------------------------------------------------------
        |
        | This controller is responsible for handling password reset requests
        | and uses a simple trait to include this behavior. You're free to
        | explore this trait and override any methods you wish to tweak.
        |
        */

    use ResetsPasswords;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    public $redirectTo = '/reviewer/dashboard';


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('reviewer.guest');
    }

    /**
     * Display the password reset view for the given token.
     *
     * If no token is present, display the link request form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $token
     * @return \Illuminate\Http\Response
     */
    public function showResetForm(Request $request, $token)
    {
        $page_title = "Account Recovery";
        $tk = ReviewerPasswordReset::where('token', $token)->where('status', 0)->first();

        if (empty($tk)) {
            $notify[] = ['error', 'Token Not Found!'];
            return redirect()->route('reviewer.password.reset')->withNotify($notify);
        }
        $email = $tk->email;
        return view('reviewer.auth.passwords.reset', compact('page_title', 'email', 'token'));
    }


    public function reset(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|confirmed|min:4',
        ]);

        $reset = ReviewerPasswordReset::where('token', $request->token)->orderBy('created_at', 'desc')->first();
        $user = Reviewer::where('email', $reset->email)->first();
        if ($reset->status == 1) {
            $notify[] = ['error', 'Invalid code'];
            return redirect()->route('reviewer.login')->withNotify($notify);
        }

        $user->password = bcrypt($request->password);
        $user->save();
        ReviewerPasswordReset::where('email', $user->email)->update(['status' => 1]);

        $userAgent = getIpInfo();
        $userOS = osBrowser();
        send_email($user, 'PASS_RESET_DONE', [
            'operating_system' => $userOS['os_platform'],
            'browser' => $userOS['browser'],
            'ip' => $userAgent['ip'],
            'time' => $userAgent['time']
        ]);

        $notify[] = ['success', 'Password Changed'];
        return redirect()->route('reviewer.login')->withNotify($notify);
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker()
    {
        return Password::broker('reviewers');
    }

    /**
     * Get the guard to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('reviewer');
    }
}
