<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employer extends Model
{
    protected $fillable = [
        'user_id',
        'company_name',
        'company_description',
        'company_address',
        'company_website',
        'contact_number',
    ];

    public function jobs()
    {
        // 👇 Explicit foreign key
        return $this->hasMany(Job::class, 'employer_id');
    }
}
