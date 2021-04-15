<?php

namespace Corbinjurgens\Quaip\Concerns\Contracts;

use Closure;

use Corbinjurgens\Quaip\QuaipContainer;

abstract class Ua extends Base
{
	
	/**
	 * Retrieve the current requests ua
	 *
	 * @return null|string
	 */
	public static function fetch(){
		// If using custom ya getter via QuaipContainer::setCustomUaGetter
		// return it
		if ($custom_closure = QuaipContainer::getCustomUaGetter()){
			return $custom_closure();
		}
		// Otherwise use laravel default
		return request()->server('HTTP_USER_AGENT');
	}
}
