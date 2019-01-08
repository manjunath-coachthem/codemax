<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mmodel extends Model
{
	protected $table = 'mmodel';
	protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'manufacturer_id'
    ];
}
