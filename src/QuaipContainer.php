<?php

namespace Corbinjurgens\Quaip;

use Illuminate\Support\Str;

/**
 * Get and set a loaded item based on its name as in config quaip.loader
 * Be sure to use the correct capitalization
 */
class QuaipContainer
{

	public static $data = [];

	public static $keys = [];

	public function __call($name, array $arguments){
		return static::get($name);
	}

	public static function setKey($property, $key){
		static::$keys[$property] = $key;
	}

	public static function getKey($property, $fallback = null){
		return static::$keys[$property] ?? $fallback;
	}

	/**
	 * If not yet loaded, it will return false. Use has key instead to check if it can be resolved
	 */
	public static function has($property){
		return array_key_exists($property, static::$data);
	}
	public static function hasKey($property){
		return array_key_exists($property, static::$keys);
	}

	public static function get($property){
		if (!static::has($property)){
			static::resolve($property);
		}
		return static::$data[$property] ?? null;
	}
	
	public static function set($property, $model){
		static::$data[$property] = $model;
	}

	public static function resolve($property){
		if (!static::hasKey($property)){
			throw new \Exception("Key for $property has not been set. Is it loaded?");
		}

		$find = static::fetchClass($property, 'Find')::action(static::getKey($property));
		static::set($property, $find);
	}

	/**
	 * Load all 
	 */
	public static function load(){
		foreach(static::$keys as $property => $key){
			if (!static::has($property)){
				static::resolve($property);
			}
		}
	}

	public static function all(){
		static::load();
		return static::$data;
	}


	/**
	 * Convert default class to custom class if it exists, or look for locaation of custom classes
	 */
	public static function fetchClass(string $loader, string $action){
		$mapping = config('quaip.actions', []);
		if (isset($mapping[$loader . '\\' . $action])){
			return $mapping[$loader . '\\' . $action];
		}
		if (isset($mapping[$loader])){
			return $mapping[$loader] . "\\" . $action;
		}
		return "Corbinjurgens\\Quaip\\Actions\\$loader\\$action";
	}
}
