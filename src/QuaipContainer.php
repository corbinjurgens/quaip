<?php

namespace Corbinjurgens\Quaip;

use Closure;

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
	
	/**
	 * Configuration
	 */
	
	/**
	 * By default it will get ip via request()->ip();
	 * If you want to retreive ip by a different method
	 * pass a closure to setCustomIpGetter in your AppServiceProvider
	 * 
	 * @param Closure|null $_custom_ip
	 */
	private static $_custom_ip = null;
	
	public static function setCustomIpGetter(Closure $closure = null){
		self::$_custom_ip = $closure;
	}
	public static function getCustomIpGetter(){
		return self::$_custom_ip;
	}
	/**
	 * By default it will get ip via request()->server('HTTP_USER_AGENT');
	 * If you want to retreive ua by a different method
	 * pass a closure to setCustomUaGetter in your AppServiceProvider
	 * 
	 * @param Closure|null $_custom_ua
	 */
	private static $_custom_ua = null;
	
	public static function setCustomUaGetter(Closure $closure = null){
		self::$_custom_ua = $closure;
	}
	public static function getCustomUaGetter(){
		return self::$_custom_ua;
	}
	
	/**
	 * By default ip lookup uses stevebauman/location
	 * If you want to use a different package or function,
	 * pass a closure with ip as 1st parameter in your AppServiceProvider
	 * 
	 * @param Closure|null $_custom_ip_lookup
	 */
	private static $_custom_ip_lookup = null;
	
	public static function setCustomIpLookup(Closure $closure = null){
		self::$_custom_ip_lookup = $closure;
	}
	public static function getCustomIpLookup(){
		return self::$_custom_ip_lookup;
	}
	
	/**
	 * By default ua lookup uses WhichBrowser
	 * If you want to use a different package or function,
	 * pass a closure with ip as 1st parameter in your AppServiceProvider
	 * 
	 * @param Closure|null $_custom_ua_lookup
	 */
	private static $_custom_ua_lookup = null;
	
	public static function setCustomUaLookup(Closure $closure = null){
		self::$_custom_ua_lookup = $closure;
	}
	public static function getCustomUaLookup(){
		return self::$_custom_ua_lookup;
	}
}
