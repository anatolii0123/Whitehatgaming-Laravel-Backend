<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Models\Classtable;

class Game extends Model
{
    use HasFactory;
    protected $table ="game";
    protected $fillable = [
        'name', 
        'publisher',
        'launchcode',
        'image',
        'active',
    ];

}
