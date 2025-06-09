<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploadController extends Controller
{
    /**
     * Xử lý upload ảnh cho CKEditor 5
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function uploadImage(Request $request)
    {
        if ($request->hasFile('upload')) {
            $file = $request->file('upload');
            
            // Validate file
            $request->validate([
                'upload' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
            
            // Tạo tên file an toàn
            $fileName = time() . '_' . $file->getClientOriginalName();
            $fileName = Str::slug(pathinfo($fileName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            
            // Lưu file vào thư mục
            $path = $file->storeAs('uploads/images', $fileName, 'public');
            
            // Trả về URL cho CKEditor
            return response()->json([
                'uploaded' => true,
                'url' => Storage::url($path)
            ]);
        }
        
        return response()->json([
            'uploaded' => false,
            'error' => [
                'message' => 'Không thể tải lên hình ảnh.'
            ]
        ], 400);
    }
    
    /**
     * Xử lý upload ảnh cho CKEditor 4
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function ckeditorUpload(Request $request)
    {
        if ($request->hasFile('upload')) {
            $file = $request->file('upload');
            
            // Validate file
            $request->validate([
                'upload' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
            
            // Tạo tên file an toàn
            $fileName = time() . '_' . $file->getClientOriginalName();
            $fileName = Str::slug(pathinfo($fileName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            
            // Lưu file vào thư mục
            $path = $file->storeAs('uploads/images', $fileName, 'public');
            
            // URL của hình ảnh
            $url = Storage::url($path);
            
            // CKEditor 4 yêu cầu response dạng khác
            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
            
            // Trả về script cho CKEditor 4
            return response("<script>window.parent.CKEDITOR.tools.callFunction({$CKEditorFuncNum}, '{$url}', 'Tải lên thành công');</script>")
                ->header('Content-Type', 'text/html');
        }
        
        return response("<script>window.parent.CKEDITOR.tools.callFunction(0, '', 'Không thể tải lên hình ảnh');</script>")
            ->header('Content-Type', 'text/html');
    }
} 