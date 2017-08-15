<?php

namespace App\Api\v1\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Http\Requests\Auth\ResetPasswordRequest;
class PasswordController extends Controller
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

    /**
     * Create a new password controller instance.
     *
     * @return void
     */
    public $subject = 'Atlas: Password Reset Link';
    public function __construct()
    {
        $this->subject  = 'Atlas: Password Reset Link';
        $this->middleware('guest');
    }

    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function forgotPassword(Request $request)
    {
        $response = Password::sendResetLink($request->only('email'), function($message)
        {
            $message->subject('Atlas: Password Reset Link');
        });

        if($response == Password::INVALID_USER){
            return $this->makeResponse('forgotpassword_email_error',422);
        }else{
             return $this->makeResponse('success',200);
        }
    }

    /**
     * Get the e-mail subject line to be used for the reset link email.
     *
     * @return string
     */
    protected function getEmailSubject()
    {
        return property_exists($this, 'subject') ? $this->subject : 'Your Password Reset Link';
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
    public function getResetpassword(Request $request, $token = null)
    { 
        return $this->showResetForm($request, $token);
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
    public function showResetForm(Request $request, $token = null)
    {
        

        $email = $request->input('email');

        if (property_exists($this, 'resetView')) {
            return view($this->resetView)->with(compact('token', 'email'));
        }

        if (view()->exists('auth.passwords.reset')) {
            return view('auth.passwords.reset')->with(compact('token', 'email'));
        }

        return view('auth.reset')->with(compact('token', 'email'));
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postResetpassword(ResetPasswordRequest $request)
    {
        $credentials = $request->only(
            'email','password', 'password_confirmation', 'token'
        );

        $response = Password::reset($credentials, function ($user, $password) {
            $this->resetPassword($user, $password);
        });
        if($response == Password::PASSWORD_RESET){
            $response = array(
                'message' => 'Your Password has been updated successfully. Please login to continue'
            );
            return $this->makeResponse($response); 
        }
        else if($response == Password::INVALID_USER){
            return $this->makeResponse('resetpassword_invalid_email', 422); 
        }else{
            return $this->makeResponse('resetpassword_invalid_token', 422); 
        }
    }

    /**
     * Get the password reset validation rules.
     *
     * @return array
     */
    protected function getResetValidationRules()
    {
        return [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:5|max:20',
        ];
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
     * @param  string  $password
     * @return void
     */
    protected function resetPassword($user, $password)
    { 
        $user->password = bcrypt($password);

        $user->save();
    }

    /**
     * Get the response for after a successful password reset.
     *
     * @param  string  $response
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function getResetSuccessResponse($response)
    {
        return redirect($this->redirectPath())->with('status', trans($response));
    }

    /**
     * Get the response for after a failing password reset.
     *
     * @param  Request  $request
     * @param  string  $response
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function getResetFailureResponse(Request $request, $response)
    {
        return redirect()->back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => trans($response)]);
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return string|null
     */
    public function getBroker()
    {
        return property_exists($this, 'broker') ? $this->broker : null;
    }

    /**
     * Get the guard to be used during password reset.
     *
     * @return string|null
     */
    protected function getGuard()
    {
        return property_exists($this, 'guard') ? $this->guard : null;
    }
}
