<?php

namespace Corbinjurgens\Quaip\Models;

class UaBrowser extends Base
{
    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [
		'id',
    ];
	
	public $timestamps = false;
	
	
}
