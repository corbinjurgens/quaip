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
	 * Convert default class to custom class if it exists
	 */
	public static function fetchClass(string $class){
		$mapping = config('quaip.actions', []);
		return $mapping[$class] ?? $class;
	}
}
