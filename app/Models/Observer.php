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

class Observer extends Model
{
    protected $fillable = [
        'user_id',
        'project_id',
        'title',
        'url',
        'interval',
        'notes',
        'emails',
        'favicon',
        'og_image',
        'is_active',
        'with_ssl_verification',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'with_ssl_verification' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'observer_tag');
    }

    public function getFormattedTagsAttribute()
    {
        return $this->tags()->pluck('name')->implode(', ');
    }

    public function getFormattedUrlAttribute()
    {
        return str_replace(['https://', 'http://'], '', rtrim($this->url, '/'));
    }

    public static function toOptions($where = null)
    {
        $query = self::query();

        if ($where) {
            $query->where($where);
        }

        return $query->selectRaw('title AS label, id AS value')->get();
    }
}
