<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Models\Classtable;

class Brand extends Model
{
    use HasFactory;
    protected $table ="brands";
    protected $fillable = [
        'brand', 
        'stage_url',
        'enabled',
    ];

}
