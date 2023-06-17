<?php

namespace Modules\Contact\Models;

use App\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends BaseModel
{
    use SoftDeletes;

    protected $table = 'bravo_contact';
    protected $fillable = [
        'name',
        'email',
        'message',
        'status'
    ];

//    protected $cleanFields = ['message'];
}