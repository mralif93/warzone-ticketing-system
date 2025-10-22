<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'description',
    ];

    /**
     * Get a setting value by key
     */
    public static function get($key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        
        if (!$setting) {
            return $default;
        }

        switch ($setting->type) {
            case 'integer':
                return (int) $setting->value;
            case 'boolean':
                return (bool) $setting->value;
            case 'json':
                return json_decode($setting->value, true);
            default:
                return $setting->value;
        }
    }

    /**
     * Set a setting value
     */
    public static function set($key, $value, $type = 'string', $description = null)
    {
        $setting = static::where('key', $key)->first();
        
        if (!$setting) {
            $setting = new static();
            $setting->key = $key;
            $setting->type = $type;
            $setting->description = $description;
        }

        $setting->value = is_array($value) ? json_encode($value) : (string) $value;
        $setting->save();

        return $setting;
    }

    /**
     * Get all settings as an array
     */
    public static function getAll()
    {
        return static::all()->mapWithKeys(function ($setting) {
            return [$setting->key => static::get($setting->key)];
        })->toArray();
    }
}