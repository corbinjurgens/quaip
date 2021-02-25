<?php

namespace Corbinjurgens\Quaip;

use Corbinjurgens\Quaip\Models\Ua;

use Corbinjurgens\Quaip\Models\UaBrowser;
use Corbinjurgens\Quaip\Models\UaDevice;
use Corbinjurgens\Quaip\Models\UaOs;

trait UaTrait {
	
   public function ua(){
	   return $this->belongsTo(Ua::class);
   }
   
   /**
    * You can use the hasOneThrough relatioships such as, eg in the case that UserLog uses this trait
	*
		$log = UserLog::query()->whereHas('ua_os', function(Builder $query){
			$query->where('name', 'Windows');
		})->get();
	*
	*/
   
   public function ua_browser(){
	     return $this->hasOneThrough(
            UaBrowser::class,
            Ua::class,
            'id', // Foreign key on the Ua table...
            'id', // Foreign key on the UaBrowser table...
            'ua_id', // Local key on the target table...
            'ua_browser_id' // Local key on the Ua table...
        );
   }
   public function ua_device(){
	     return $this->hasOneThrough(
            UaDevice::class,
            Ua::class,
            'id', // Foreign key on the Ua table...
            'id', // Foreign key on the UaBrowser table...
            'ua_id', // Local key on the target table...
            'ua_device_id' // Local key on the Ua table...
        );
   }
   public function ua_os(){
	     return $this->hasOneThrough(
            UaOs::class,
            Ua::class,
            'id', // Foreign key on the Ua table...
            'id', // Foreign key on the UaBrowser table...
            'ua_id', // Local key on the target table...
            'ua_os_id' // Local key on the Ua table...
        );
   }
   
   
   // noBots
   public function scopeNoBots($query){
	   $query->whereDoesntHave('ua_device', function($query){
			$query->where('type', 'bot');
			
		});
   }
}