<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AiProvider extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'provider_code',
        'api_key',
        'api_endpoint',
        'user_who_created_id',
        'user_who_updated_id',
        'user_who_deleted_id',
    ];

    protected $hidden = ['api_key'];

    protected function casts(): array
    {
        return [
            'api_key' => 'encrypted',
        ];
    }
}
