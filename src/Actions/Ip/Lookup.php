<?php

namespace Corbinjurgens\Quaip\Actions\Ip;

use Corbinjurgens\Quaip\Actions\Interfaces;

use Location;

class Lookup implements Interfaces\Lookup
{
	/**
	 * Take result of get, whether a ip or null, and parse it with GeoIP
	 * to get info
	 *
	 * @param string|null $ip_address
	 *
	 * @return array
	 */
	public static function action(string $ip_address = null) : array
	{
		$location = Location::get($ip_address);
		return $location ? $location->toArray() : ['ip' => $ip_address];
	}
}
