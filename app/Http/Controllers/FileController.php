<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

    private static function getDefaultExtension(String $type) {
        return reset(self::$systemTypes[$type]);
    }

    private static function isValidExtension(String $type, String $extension) {
        $allowedExtensions = self::$systemTypes[$type];

        // Note the toLowerCase() method, it is important to allow .JPG and .jpg extensions as well
        return in_array(strtolower($extension), $allowedExtensions);
    }


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
    
    private static function delete(String $type, int $id) {
        $existingFileName = self::getFileName($type, $id);
        if ($existingFileName) {
            Storage::disk(self::$diskName)->delete($type . '/' . $existingFileName);

            switch($type) {
                case 'profile':
                    User::find($id)->img = null;
                    break;
                case 'post':
                    Post::find($id)->img = null;
                    break;
            }
        }
    }

    function upload(Request $request) {

        // Validation: has file
        if (!$request->hasFile('file')) {
            return redirect()->back()->with('error', 'Error: File not found');
        }

        // Validation: upload type
        if (!$this->isValidType($request->type)) {
            return redirect()->back()->with('error', 'Error: Unsupported upload type');
        }

        // Validation: upload extension
        $file = $request->file('file');
        
        $type = $request->input('type');
        $extension = $file->extension();
        if (!$this->isValidExtension($type, $extension)) {
            return redirect()->back()->with('error', 'Error: Unsupported upload extension');
        }
        
        // Prevent existing old files
        $this->delete($type, $request->id);

        // Generate unique filename
        $fileName = $file->hashName();
    
        // Validation: model
        $error = null;
        switch($request->input('type')) {
            case 'profile':
                $user = User::findOrFail($request->id);
                if ($user) {
                    $user->img = $fileName;
                    $user->save();
                } else {
                    $error = "unknown user";
                }
                break;

            case 'post':
                $post = Post::findOrFail($request->id);
                if ($post) {
                    $post->img = $fileName;
                    $post->save();
                } else {
                    $error = "unknown post";
                }
                break;

            default:
                redirect()->back()->with('error', 'Error: Unsupported upload object');
        }

        if ($error) {
            redirect()->back()->with('error', `Error: {$error}`);
        }

        $file->storeAs($type, $fileName, self::$diskName);
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
