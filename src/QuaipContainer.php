<?php

namespace Corbinjurgens\Quaip;

use Illuminate\Support\Str;

class QuaipContainer
{

	protected static $data = [];

	public function __call($name, array $arguments){
		$key = Str::snake($name);
		return static::$data[$key] ?? null;
	}
	
	function set($property, $model){
		$key = Str::snake($property);
		static::$data[$key] = $model;
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
