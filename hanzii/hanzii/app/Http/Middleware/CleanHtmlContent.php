<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CleanHtmlContent
{
    /**
     * Xử lý request đến ứng dụng và lọc nội dung HTML không an toàn.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->has('noi_dung')) {
            // Danh sách các thẻ HTML cho phép
            $allowedTags = '<p><br><h1><h2><h3><h4><h5><h6><strong><em><u><s><ul><ol><li><table><thead><tbody><tr><td><th><img><a><blockquote><hr><pre><code><iframe><figcaption><figure>';
            
            // Lọc nội dung không an toàn
            $cleanContent = strip_tags($request->noi_dung, $allowedTags);
            
            // Cập nhật request
            $request->merge([
                'noi_dung' => $cleanContent
            ]);
        }
        
        return $next($request);
    }
} 