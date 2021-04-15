<?php

namespace Corbinjurgens\Quaip\Concerns\Contracts;

use Closure;

use Corbinjurgens\Quaip\QuaipContainer;

abstract class Base
{
	/**
	 * Use ip or user-agent as key to see if it has already been fetched.
	 */
	private static $_cache = [];
	protected static function requestCache($key = null, Closure $callback = null){
		if (is_null($key)){
			return $key;
		}
		if ( array_key_exists($key, static::$_cache) ){
			return static::$_cache[$key];
		}
		static::$_cache[$key] = $callback($key);
		return static::$_cache[$key];
		
	}
	/**
	 * Get the config found at config\quaip  
	 *
	 * @param string $mode
	 */
	protected static function getConfig($mode = 'ips'){
		$allowed = config('quaip.'. $mode . '.allowed_columns');
		$equivalent = config('quaip.'. $mode . '.columnn_keys', []);
		return [$allowed, $equivalent];
	}
	
	/**
	 * Based on config, filter and rearrage data from lookup
	 *
	 * @param array $data
	 * @param string $mode
	 *
	 * @return array
	 * 
	 */
	protected static function prepare($data = [], $mode = 'ips'){
		[$allowed, $equivalent] = static::getConfig($mode);
		$result = [];
		if (is_array($allowed)){
			$data = array_intersect_key($data, array_flip($allowed));
		}
		foreach($data as $k => $v){
			$current_key = $k;
			if (isset($equivalent[$k])){
				$result[ $equivalent[$k] ] = $v;
				$current_key = $equivalent[$k];
			}else{
				$result[ $k ] = $v;
			}
		}
		return $result;
	}
	
	
	/**
	 * Return processed data
	 *
	 * @return mixed
	 */
	public static function get(){
		$item = static::fetch();
		$info = static::requestCache($item, function($item){
			return static::lookup($item);
		});
		return static::process($info);
	}
	
	/**
	 * Get the intended item, ip or user-agent
	 *
	 * @return string|null
	 */
	abstract protected static function fetch();
	
	/**
	 * Take result of get, whether a ip or null, and parse it with GeoIP or other package
	 * to get info
	 *
	 * @param string|null $ip_address
	 *
	 * @return mixed
	 */
	abstract protected static function lookup($ip_address = null);
	
	/**
	 * Take the result of GeoIP or other ip tool and return the desired array or value
	 * For your database or other use. Or just return as is
	 *
	 * @param mixed $ip_address
	 *
	 * @return mixed
	 */
	abstract protected static function process($data = null);
}
