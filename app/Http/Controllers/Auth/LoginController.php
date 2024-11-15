<?php

namespace MGLara\Http\Controllers\Auth;

use GuzzleHttp\Client;
use MGLara\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

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
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    public function username()
    {
        return 'usuario';
    }

    public function showLoginForm()
    {
        return redirect()->to(env('AUTH_API_URL') . '/login?redirect_uri=' . urlencode(url('/')));
    }
    
    protected function getCredentials(Request $request)
    {
        $credentials=$request->only($this->loginUsername(), 'password');
        $credentials['inativo'] = null;
        return $credentials;
    }

    public function logout(Request $request)
    {

        $access_token = Request::capture()->cookies->get('access_token');

        $client = new Client();

        try {
            $responseAuth = $client->post(env('AUTH_API_URL') . '/api/logout', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $access_token,
                ],
                'verify' => false
            ]); 

            if ($responseAuth->getStatusCode() === 200) {
                $this->guard()->logout();


                return redirect('/');
            }
        } catch (\Exception $e) {
            if($e->getCode() == 401) {
                $this->guard()->logout();
        
                return redirect('/');
            }
        }

        $this->guard()->logout();

        return redirect('/');
    }
}
