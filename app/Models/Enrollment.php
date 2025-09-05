<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'status',
        'remarks',
        'approved_by',
        'approved_at',
        'enrollment_approved_by',
        'enrollment_approved_at',
        'payment_approved_by',
        'payment_approved_at',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    public function enrollmentApprover()
    {
        return $this->belongsTo(User::class, 'enrollment_approved_by');
    }
    public function paymentApprover()
    {
        return $this->belongsTo(User::class, 'payment_approved_by');
    }
}
