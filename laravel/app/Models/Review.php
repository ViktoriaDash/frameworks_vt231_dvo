<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    /**
     * Масив fillable
     * @var string[]
     */
    protected $fillable = ['reader_name', 'content', 'rating'];
}