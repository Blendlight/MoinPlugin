<?php

namespace MoinPlugin\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plugin extends Model
{
//    use HasFactory;
    protected $fillable = ['name', 'enabled'];
}
