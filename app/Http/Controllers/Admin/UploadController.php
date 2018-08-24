<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Storage;

class UploadController extends Controller
{
    // 上传图片
    public function image(Request $request)
    {
        if ($request->isMethod('post')) {

            $file = $request->file('file');

            // 文件是否上传成功
            if ($file->isValid()) {

                //$originalName = $file->getClientOriginalName(); // 文件原名
                $ext = $file->getClientOriginalExtension();     // 扩展名
                $realPath = $file->getRealPath();               //临时文件的绝对路径
                //$type = $file->getClientMimeType();             // image/jpeg

                // 上传文件
                $filename = date('YmdHis') . '-' . uniqid() . '.' . $ext;

                // 这里的uploads是配置文件的名称, 使用我们新建的uploads本地存储空间（目录）
                $bool = Storage::disk('uploads')->put($filename, file_get_contents($realPath));

                if ($bool) {
                    return $this->success(['filename' =>  '/uploads/' . $filename]);
                } else {
                    return $this->error();
                }
            }
        }
    }
}
