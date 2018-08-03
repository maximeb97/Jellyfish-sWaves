<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    const TYPE_ERROR = "error";
    const TYPE_SUCCESS = "success";

    protected $fillable = [
        'class',
        'category',
        'message',
        'type'
    ];

    /**
     * Log
     *
     * @param string $type 
     * @param string $message 
     * @param string $class 
     * @param string $category 
     * @return void
     */
    public static function log($type, $message, $class, $category) {
        Log::create([
            'class' => get_class($class),
            'category' => $category,
            'message' => $message,
            'type' => $type,
        ]);
    }
}
