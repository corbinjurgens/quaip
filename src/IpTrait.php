<?php

namespace Corbinjurgens\Quaip;
use Corbinjurgens\Quaip\Models\Ip;

trait IpTrait {
	
   public function ip(){
	   return $this->belongsTo(Ip::class);
   }
}