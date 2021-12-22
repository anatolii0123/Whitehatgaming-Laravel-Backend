<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Models\Classtable;

class Country extends Model
{
    use HasFactory;
    protected $table ="countries";
    protected $fillable = [
        'code', 
        'country',
    ];

}
