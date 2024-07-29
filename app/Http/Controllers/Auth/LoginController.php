<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\ForgetPasswordOtp;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Cache\Console\ForgetCommand;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{

    public function forget_password(Request $request)
    {
        return view('auth.passwords.forget_password');
    }

    public function send_otp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $checkAccount = User::where('email', $request->email)->first();

        if ($checkAccount) {
            $checkOtp = ForgetPasswordOtp::where('user_id', $checkAccount->id)->first();
            $currentTime = Carbon::now();
            $expiryTime = $currentTime->copy()->addMinutes(10);
            $otp = rand(1000, 9999); // Generate a 4-digit random number

            if ($checkOtp) {
                $checkOtp->request_at = $currentTime;
                $checkOtp->expire_at = $expiryTime;
                $checkOtp->otp = $otp;
                $checkOtp->save();
            } else {
                $newOtp = new ForgetPasswordOtp();
                $newOtp->user_id = $checkAccount->id;
                $newOtp->request_at = $currentTime;
                $newOtp->expire_at = $expiryTime;
                $newOtp->otp = $otp;
                $newOtp->save();
            }

            // Send the OTP via email
            Mail::to($checkAccount->email)->send(new OtpMail($otp, $checkAccount));

            session()->flash('success', 'OTP sent successfully');
            return redirect()->route('verify_otp');
        } else {
            session()->flash('error', 'Something went wrong');
            return redirect()->back();
        }
    }

    public function verify_otp()
    {
        return view('auth.passwords.verify_otp');
    }
    public function verify_otp_post(Request $request)
    {
        $otp = implode('', $request->otp);
        $otpRecord = ForgetPasswordOtp::where('otp', $otp)->first();

        if (!$otpRecord) {
            session()->flash('error', 'Invalid OTP');
            return redirect()->back();
        }

        // Check if the OTP has expired
        $currentTime = Carbon::now();
        if ($currentTime->gt($otpRecord->expire_at)) {
            session()->flash('error', 'OTP has expired');
            return redirect()->back();
        }

        session()->flash('success', 'OTP verified successfully');
        session()->put('user_id', $otpRecord->user_id);
        return redirect()->route('password_reset_form');
    }
    public function password_reset_form()
    {
        return view('auth.passwords.password_reset_form');
    }
    public function change_password(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'new_password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            session()->flash('error',$validator->errors());
            return redirect()->back();
        }
        if($request->new_password != $request->confirm_password){
            session()->flash('error','Confirm password mismatch');
            return redirect()->back();
        }
        $user_id = session()->get('user_id');

        // Update the password for the user
        $user = User::find($user_id);
        $user->password = Hash::make($request->new_password);
        $user->save();

        // Redirect to the login page
        session()->flash('success','Password changed successfully. Please login with your new password.');
        return redirect()->route('login');
    }


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
    protected $redirectTo = '/';

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
