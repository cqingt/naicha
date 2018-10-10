<?php
namespace App\Http\Controllers\Manager;

use App\Http\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommonController extends Controller
{
    public function __construct(Request $request)
    {
        // 共性操作
        $roleId = $request->user()->role_id;
        $roleArray = Role::find($roleId, ['name']);
        $role = $roleArray ? $roleArray['name'] : '';

        if (strcasecmp('manager', $role) !== 0) {
            $redirectPath = '/';
            echo '<script>window.location.href="' . $redirectPath . '"</script>'; exit;
        }
    }
}