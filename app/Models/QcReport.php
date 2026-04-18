<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Accessor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class QcReport extends Model
{
    use HasFactory, SoftDeletes;

    #[\Illuminate\Database\Eloquent\Attributes\Fillable([
        'vehicle_id',
        'supervisor_name',
        'total_tests',
        'failed_tests',
        'final_decision',
        'report_file_url',
    ])]
    protected $fillable = [
        'vehicle_id',
        'supervisor_name',
        'total_tests',
        'failed_tests',
        'final_decision',
        'report_file_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'total_tests' => 'integer',
            'failed_tests' => 'integer',
            'final_decision' => 'string',
        ];
    }

    /**
     * Get the vehicle that owns this QC report.
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    /**
     * Get the pass rate for this report.
     */
    #[Accessor]
    public function passRate(): float|null
    {
        if ($this->total_tests === 0) {
            return null;
        }

        $passedTests = $this->total_tests - $this->failed_tests;

        return ($passedTests / $this->total_tests) * 100;
    }
}
