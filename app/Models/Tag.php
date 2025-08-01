<?php

/* ----------------------------------------------------------------------------
 * Apphold - Online Software Telemetry
 *
 * @package     Apphold
 * @author      A.Tselegidis <alextselegidis@gmail.com>
 * @copyright   Copyright (c) Alex Tselegidis
 * @license     https://opensource.org/licenses/GPL-3.0 - GPLv3
 * @link        https://apphold.org
 * ---------------------------------------------------------------------------- */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['name', 'user_id'];

    protected $casts = [];

    public function observers()
    {
        return $this->belongsToMany(Observer::class, 'observer_tag');
    }

    public function getCountAttribute()
    {
        return $this->observers()->count();
    }

    public static function toOptions($where = null)
    {
        $query = self::query();

        if ($where) {
            $query->where($where);
        }

        return $query->selectRaw('name AS label, id AS value')->get();
    }
}
