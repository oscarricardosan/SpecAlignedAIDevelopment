<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectCompliance extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'project_id',
        'compliance',
        'user_who_created_id',
        'user_who_updated_id',
        'user_who_deleted_id',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
