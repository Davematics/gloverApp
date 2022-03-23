<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class ChangeRequest extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $casts = [
        'information_to_be_updated' => 'array'
    ];

    protected $fillable = [
        'requester_id',
        'request_type',
        'information_to_be_updated',
        'password',
    ];
}
