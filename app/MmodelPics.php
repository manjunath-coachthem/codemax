<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MmodelPics extends Model
{
	protected $table = 'mmodel_pics';
	protected $primaryKey = 'id';

    protected $fillable = [
        'mmodel_id',
        'pic'
    ];
}
