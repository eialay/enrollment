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
        'address',
        'birthCertificate',
        'form137',
        'goodMoral',
        'reportCard',
        'guardianFName',
        'guardianMName',
        'guardianLName',
        'guardianEmail',
        'guardianContact',
        'guardianRelationship',
        'guardianAddress',
    ];
    public function enrollment()
    {
        return $this->hasOne(Enrollment::class);
    }
    public function getFormattedIdAttribute()
    {
        $year = $this->created_at ? $this->created_at->format('y') : now()->format('y');
        return $year . '-' . str_pad($this->id, 4, '0', STR_PAD_LEFT);
    }
}
