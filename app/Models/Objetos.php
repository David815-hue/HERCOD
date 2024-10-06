<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Objetos extends Model
{
    use HasFactory;

    
    protected $table = 'objetos';

    protected $primaryKey = 'id';
}
