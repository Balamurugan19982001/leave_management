<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'role'];

    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }
}
