<?php

namespace Corbinjurgens\Quaip\Concerns\Contracts;

use Closure;

use Corbinjurgens\Quaip\QuaipContainer;

abstract class Ip extends Base
{
	
	/**
	 * Retrieve the current requests ip
	 *
	 * @return null|string
	 */
	public static function fetch(){
		// If using custom ip getter via QuaipContainer::setCustomIpGetter
		// return it
		if ($custom_closure = QuaipContainer::getCustomIpGetter()){
			return $custom_closure();
		}
		// Otherwise use laravel default
		return request()->ip();
	}
}
