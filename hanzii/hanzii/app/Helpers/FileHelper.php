<?php

namespace App\Helpers;

class FileHelper
{
    public static function formatFileSize($size)
    {
        if (!$size) return '0 B';
        if ($size < 1024) {
            return $size . ' B';
        } elseif ($size < 1024*1024) {
            return round($size/1024, 2) . ' KB';
        } elseif ($size < 1024*1024*1024) {
            return round($size/(1024*1024), 2) . ' MB';
        } else {
            return round($size/(1024*1024*1024), 2) . ' GB';
        }
    }
} 