<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    // Allow manual assignment of ID to match the PDF serial numbers
    public $incrementing = false;
    protected $fillable = ['id', 'name', 'value'];
}
