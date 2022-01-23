<?php

namespace Corbinjurgens\Quaip\Models;

class Ua extends Base
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
	
	
	/**
	 * Relationships
	 */
	public function browser(){
		return $this->belongsTo(UaBrowser::class);
	}
	public function device(){
		return $this->belongsTo(UaDevice::class);
	}
	public function os(){
		return $this->belongsTo(UaOs::class);
	}
	
	
}
