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
}
