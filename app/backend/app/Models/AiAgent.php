<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AiAgent extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'type',
        'name',
        'provider_id_low', 'model_low',
        'provider_id_medium', 'model_medium',
        'provider_id_high', 'model_high',
        'user_who_created_id',
        'user_who_updated_id',
        'user_who_deleted_id',
    ];

    public function providerLow(): BelongsTo
    {
        return $this->belongsTo(AiProvider::class, 'provider_id_low');
    }

    public function providerMedium(): BelongsTo
    {
        return $this->belongsTo(AiProvider::class, 'provider_id_medium');
    }

    public function providerHigh(): BelongsTo
    {
        return $this->belongsTo(AiProvider::class, 'provider_id_high');
    }

    public function isConfigured(): bool
    {
        return $this->provider_id_low && $this->model_low
            && $this->provider_id_medium && $this->model_medium
            && $this->provider_id_high && $this->model_high;
    }

    public static function allConfigured(): bool
    {
        return static::count() > 0 && static::all()->every(fn ($agent) => $agent->isConfigured());
    }
}
