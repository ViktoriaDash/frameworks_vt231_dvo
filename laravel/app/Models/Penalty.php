<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penalty extends Model
{
    use HasFactory;

    /**
     * Масив fillable
     * @var string[]
     */
    protected $fillable = ['reader_name', 'amount', 'reason', 'is_paid'];
}