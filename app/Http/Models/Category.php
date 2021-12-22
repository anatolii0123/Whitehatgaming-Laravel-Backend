<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Models\Classtable;

class Category extends Model
{
    use HasFactory;
    protected $table ="category";
    protected $fillable = [
        'name', 
        'category',
        'brandid',
    ];

}
