<?php
/**
 * Created by PhpStorm.
 * User: PINYER Co ltd
 * Date: 2018/8/21
 * Time: 16:57
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        if ($request->user()->name == 'test') {
            return view('admin.layout.index');
        } else if ($request->user()->name == 'clerk'){
            return view('admin.layout.clerk');
        } else {
            return view('admin.layout.index');
        }
    }

    public function home()
    {
        return view('admin.layout.home');
    }
}