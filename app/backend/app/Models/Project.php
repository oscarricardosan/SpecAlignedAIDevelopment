<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'path',
        'disk',
        'description',
        'criticality',
        'business_sector',
        'target_audience',
        'user_who_created_id',
        'user_who_updated_id',
        'user_who_deleted_id',
    ];

    public function compliances(): HasMany
    {
        return $this->hasMany(ProjectCompliance::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }
}
