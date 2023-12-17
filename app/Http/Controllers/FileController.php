<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Post;

class FileController extends Controller
{
    static $default = 'default.jpg';
    static $diskName = 'uploads';

    static $systemTypes = [
        'profile' => ['png', 'jpg', 'jpeg', 'gif'],
        'post' => ['mp3', 'mp4', 'gif', 'png', 'jpg', 'jpeg'],
    ];

    private static function isValidType(String $type) {
        return array_key_exists($type, self::$systemTypes);
    }
    
    private static function defaultAsset(String $type) {
        return asset($type . '/' . self::$default);
    }
    
    private static function getFileName (String $type, int $id) {
            
        $fileName = null;
        switch($type) {
            case 'profile':
                $fileName = User::find($id)->img;
                break;
            case 'post':
                $fileName = Post::find($id)->img;
                break;
            }
    
        return $fileName;
    }
    
    static function get(String $type, int $userId) {
    
        // Validation: upload type
        if (!self::isValidType($type)) {
            return self::defaultAsset($type);
        }
    
        // Validation: file exists
        $fileName = self::getFileName($type, $userId);
        if ($fileName) {
            return asset($type . '/' . $fileName);
        }
    
        // Not found: returns default asset
        return self::defaultAsset($type);
    }
    

}
