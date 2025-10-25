<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'firstname',
        'middlename',
        'lastname',
        'contact',
        'birthdate',
        'gender',
        'address',
        'baranggay',
        'city',
        'province',
        'studentImage',
        'birthCertificate',
        'form137',
        'goodMoral',
        'reportCard',
        'tor',
        'honDismissal',
        'brgyClearance',
        'guardianFName',
        'guardianMName',
        'guardianLName',
        'guardianEmail',
        'guardianContact',
        'guardianRelationship',
        'guardianAddress',
        'fatherFName',
        'fatherMName',
        'fatherLName',
        'motherFName',
        'motherMName',
        'motherLName',
        'primarySchool',
        'primarySchoolYearGraduated',
        'secondarySchool',
        'secondarySchoolYearGraduated',
        'lastSchoolAttended',
        'lastSchoolAttendedYearGraduated',
    ];
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
    public function enrollment()
    {
        return $this->hasOne(Enrollment::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function studentID()
    {
        return $this->hasOne(StudentId::class);
    }
    public function getFormattedIdAttribute()
    {
        $year = $this->created_at ? $this->created_at->format('y') : now()->format('y');
        return $year . '-' . str_pad($this->id, 4, '0', STR_PAD_LEFT);
    }
}
