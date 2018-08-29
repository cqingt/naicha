<?php
namespace App\Http\Controllers\Admin;

use App\Http\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommonController extends Controller
{
    public function __construct(Request $request)
    {
        $action = \Route::current()->getActionName();
        list($class, $action)=explode('@',$action);

        $controller = substr(strrchr($class,'\\'),1);
        $controller = substr($controller,0,-10); // 控制器名

        $permission = config('permission'); // 权限

        $roles = array_keys($permission);

        $roleResult = Role::find($request->user()->role_id, ['name']);
        $role = $roleResult ? $roleResult['name'] : ''; // 角色

        $allow = false;

        if (in_array($role, $roles)) {
            $routes = $permission[$role];
            $controllers = array_keys($routes);

            foreach ($controllers as $con) {
                $actions = $routes[$con];

                if (strcasecmp($controller, $con) === 0 && in_array($action, $actions)) {
                    $allow = true;
                }
            }
        }

        if (strcasecmp('Administrator', $role) == 0) {
            $allow = true;
        }

        if (! $allow) {
            if($request->ajax()){
                $result = ['code' => 0, 'msg' => '您没有权限操作'];
                echo json_encode($result); exit;
            } else {
                echo "您没有权限操作"; exit;
            }
        }
    }
}