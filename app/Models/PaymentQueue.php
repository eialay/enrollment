<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentQueue extends Model
{
    protected $table = 'payment_queue';
    protected $fillable = [
        'student_id', 'payment_reference_code', 'queue_number', 'status'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_reference_code', 'reference_code');
    }
}
