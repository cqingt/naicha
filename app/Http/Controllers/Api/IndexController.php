<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    public function index()
    {
        $data = ['code' => '200', 'msg' => 'success', 'data' => []];

        return response()->json($data, 200);
    }
}