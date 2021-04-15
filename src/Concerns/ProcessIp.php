<?php

namespace Corbinjurgens\Quaip\Concerns;

use Closure;
use Illuminate\Http\Request;

use Location;

use Corbinjurgens\Quaip\QuaipContainer;


class ProcessIp extends Contracts\Ip
{
	/**
	 * Take result of get, whether a ip or null, and parse it with GeoIP
	 * to get info
	 *
	 * @param string|null $ip_address
	 *
	 * @return mixed
	 */
	protected static function lookup($ip_address = null){
		if ($closure = QuaipContainer::getCustomIpLookup()){
			return $closure($ip_address);
		}
		$location = Location::get($ip_address);
		return $location ? $location->toArray() : ['ip' => $ip_address];
	}
	
	/**
	 * Take the result of GeoIP or other ip tool and return the desired array or value
	 * For your database or other use. Or just return as is
	 *
	 * @param mixed $ip_address
	 *
	 * @return mixed
	 */
	protected static function process($data = null){
		$data = static::prepare($data, 'ips');
		return $data;
	}
	
}
