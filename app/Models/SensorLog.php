<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SensorLog extends Model
{
    use HasFactory, SoftDeletes;

    #[\Illuminate\Database\Eloquent\Attributes\Fillable([
        'vehicle_id',
        'component_name',
        'recorded_voltage',
        'is_anomaly',
        'status',
    ])]
    protected $fillable = [
        'vehicle_id',
        'component_name',
        'recorded_voltage',
        'is_anomaly',
        'status',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'recorded_voltage' => 'decimal:2',
            'is_anomaly' => 'boolean',
            'status' => 'string',
        ];
    }

    /**
     * Get the vehicle that owns this sensor log.
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }
}
