<?php

namespace Corbinjurgens\Quaip;

class QuaipContainer
{
	
	protected $ua = null;
	protected $ip = null;
	protected $ua_device = null;
	protected $ua_browsr = null;
	protected $ua_os = null;
    
	function ua(){
		return $this->ua;
	}
	function ip(){
		return $this->ip;
	}
	function ua_device(){
		return $this->ua_device;
	}
	function ua_browsr(){
		return $this->ua_browsr;
	}
	function ua_os(){
		return $this->ua_os;
	}
	
	function set($property, $model){
		$this->$property = $model;
	}
}
