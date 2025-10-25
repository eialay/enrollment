<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnrollmentQueue extends Model
{
    protected $table = 'enrollment_queue';
    protected $fillable = [
        'enrollment_code', 'queue_number',
    ];
    
    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class, 'enrollment_code', 'reference_code');
    }
}
