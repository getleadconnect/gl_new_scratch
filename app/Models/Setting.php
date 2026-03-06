<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $guarded = [];
    protected $table   = 'settings';

    /**
     * Get a setting value for the given user and type.
     */
    public static function getValue(int $userId, string $type, $default = null)
    {
        $row = static::where('user_id', $userId)->where('settings_type', $type)->first();
        return $row ? $row->settings_value : $default;
    }

    /**
     * Set (upsert) a setting value for the given user and type.
     */
    public static function setValue(int $userId, string $type, $value): void
    {
        static::updateOrCreate(
            ['user_id' => $userId, 'settings_type' => $type],
            ['settings_value' => $value]
        );
    }
}
