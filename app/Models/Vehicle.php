<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    use HasFactory;

    #[\Illuminate\Database\Eloquent\Attributes\Fillable([
        'vin_number',
        'make',
        'model',
        'production_year',
        'production_status',
    ])]
    protected $fillable = [
        'vin_number',
        'make',
        'model',
        'production_year',
        'production_status',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'production_year' => 'integer',
            'production_status' => 'string',
        ];
    }

    /**
     * Get all sensor logs associated with this vehicle.
     */
    public function sensorLogs(): HasMany
    {
        return $this->hasMany(SensorLog::class);
    }

    /**
     * Get all QC reports associated with this vehicle.
     */
    public function qcReports(): HasMany
    {
        return $this->hasMany(QcReport::class);
    }
}
