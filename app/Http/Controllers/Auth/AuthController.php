<?php

namespace App\Http\Controllers\Auth;

use App\Http\Models\Role;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Exception\HttpResponseException;
use DB;

class AuthController extends Controller
{
    protected $username = 'name'; // 用户登录指定字段

    protected $redirectPath = 'dashboard'; // 登录跳转地址
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers,ThrottlesLogins;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
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
            'name' => 'required|max:255',
           // 'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * 登录账户校验
     * @param Request $request
     * @param array $rules
     * @param array $messages
     * @param array $customAttributes
     * @return array
     */
    public function validate(Request $request, array $rules, array $messages = [], array $customAttributes = []){
        $username = $request->get($this->username);
        if (! empty(trim($username))) {
            $roleName = DB::table('users')
                ->join('roles', 'users.role_id', '=', 'roles.id')
                ->where('users.name', '=', $username)
                ->pluck('roles.name');
            $domain = $request->getHost();

            $subDomain = substr($domain, 0, strpos($domain, '.'));

            $domains = config('web.domain');

            if (isset($domains[$subDomain])) {
                $role = $domains[$subDomain];

                if (strcasecmp($roleName, $role) !== 0) {
                    throw new HttpResponseException($this->buildFailedValidationResponse(
                        $request, [$this->username => '非此平台账号，禁止登录']
                    ));
                }
            }
        }


        return parent::validate($request, $rules, $messages, $customAttributes);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            //'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    /**
     * 验证后跳转
     * @param Request $request
     * @param $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function authenticated(Request $request, $user)
    {
//        $roleId = $user->role_id;
//        $roleArray = Role::find($roleId, ['name']);
//        $role = $roleArray ? $roleArray['name'] : '';
//
//        if (strcasecmp('clerk', $role) === 0) {
//            $this->redirectPath = '/clerk/index';
//        } else if (strcasecmp('manager', $role) === 0) {
//            $this->redirectPath = '';
//        }

        return redirect()->intended($this->redirectPath());
    }
}
