<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestProfile extends Model
{
    use HasFactory;

    #[\Illuminate\Database\Eloquent\Attributes\Fillable([
        'vehicle_model',
        'component_name',
        'min_voltage',
        'max_voltage',
    ])]
    protected $fillable = [
        'vehicle_model',
        'component_name',
        'min_voltage',
        'max_voltage',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'min_voltage' => 'decimal:2',
            'max_voltage' => 'decimal:2',
        ];
    }
}
