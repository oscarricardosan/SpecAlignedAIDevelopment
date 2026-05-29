<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Application extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'project_id',
        'name',
        'description',
        'path',
        'disk',
        'notes',
        'platform',
        'language',
        'language_is_custom',
        'language_version',
        'language_version_is_custom',
        'technology',
        'technology_is_custom',
        'framework_version',
        'framework_version_is_custom',
        'package_manager',
        'paradigm',
        'architecture',
        'design_principles_codes',
        'databases_codes',
        'database_access',
        'storage_codes',
        'code_repository',
        'code_repository_url',
        'testing_frameworks_codes',
        'code_style',
        'ci_cd',
        'executor',
        'context_stack',
        'context_architecture',
        'context_guidelines',
        'user_who_created_id',
        'user_who_updated_id',
        'user_who_deleted_id',
    ];

    protected function casts(): array
    {
        return [
            'language_is_custom'            => 'boolean',
            'language_version_is_custom'    => 'boolean',
            'technology_is_custom'          => 'boolean',
            'framework_version_is_custom'   => 'boolean',
            'design_principles_codes'       => 'array',
            'databases_codes'               => 'array',
            'storage_codes'                 => 'array',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
