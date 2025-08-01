<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Constants\ExtensionConst;
use App\Constants\NotificationConst;
use App\Http\Controllers\Controller;
use App\Http\Helpers\PushNotificationHelper;
use App\Models\Admin\AdminLoginLogs;
use App\Models\Admin\AdminNotification;
use App\Providers\Admin\ExtensionProvider;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Jenssegers\Agent\Agent;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Display The Amdin Login From Page
     *
     * @return view
     */
    public function showLoginForm() {
        return view('admin.auth.login');
    }



    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $extension = ExtensionProvider::get()->where('slug', ExtensionConst::GOOGLE_RECAPTCHA_SLUG)->first();
        $captcha_rules = "nullable";
        if($extension && $extension->status == true) {
            $captcha_rules = 'required|string|g_recaptcha_verify';
        }
        $request->validate([
            'email'                => 'required|string',
            'password'             => 'required|string',
            'g-recaptcha-response'  => $captcha_rules
        ]);
    }

    /**
     * Get The Authenticated User Guard
     * @return instance
     */
    protected function guard()
    {
        return Auth::guard('admin');
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
        $user->update([
            'two_factor_verified'   => false,
        ]);
        $this->createLoginLog($user);
        $this->updateInfo($user);
        return redirect()->intended(route('admin.dashboard'));
    }


    protected function createLoginLog($admin) {

        $client_ip = request()->ip() ?? false;
        $location = geoip()->getLocation($client_ip);

        $agent = new Agent();

        // $mac = exec('getmac');
        // $mac = explode(" ",$mac);
        // $mac = array_shift($mac);
        $mac = "";

        $data = [
            'admin_id'      => $admin->id,
            'ip'            => $client_ip,
            'mac'           => $mac,
            'city'          => $location['city'] ?? "",
            'country'       => $location['country'] ?? "",
            'longitude'     => $location['lon'] ?? "",
            'latitude'      => $location['lat'] ?? "",
            'timezone'      => $location['timezone'] ?? "",
            'browser'       => $agent->browser() ?? "",
            'os'            => $agent->platform() ?? "",
        ];

        try{
            AdminLoginLogs::create($data);
            $notification_message = [
                'title'   => $admin->fullname . "(" . $admin->username . ")" . " logged in.",
                'time'      => Carbon::now()->diffForHumans(),
                'image'     => get_image($admin->image,'admin-profile'),
            ];
            AdminNotification::create([
                'type'      => NotificationConst::SIDE_NAV,
                'admin_id'  => $admin->id,
                'message'   => $notification_message,
            ]);
            // event(new NotificationEvent($notification_message));
            try{
                (new PushNotificationHelper())->prepare([$admin->id],[
                    'title' => $admin->fullname . "(" . $admin->username . ")" . " logged in.",
                    'desc'  => "",
                    'user_type' => 'admin',
                ])->send();
            }catch(Exception $e) {}
        }catch(Exception $e) {
            // return false;
        }
    }


    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            'credential' => [trans('auth.failed')],
        ]);
    }


    protected function updateInfo($admin) {
        try{
            $admin->update([
                'last_logged_in'    => now(),
                'login_status'      => true,
            ]);
        }catch(Exception $e) {
            // handle error
        }
    }


    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        $request->merge(['status' => true]);
        return $request->only($this->username(), 'password','status');
    }
}
