<?php

namespace Corbinjurgens\Quaip\Actions\Ip;

use Corbinjurgens\Quaip\Actions\Interfaces;

class Fetch implements Interfaces\Fetch
{
	/**
	 * return ip address
	 *
	 * @return string
	 */
	public static function fetch()
	{
		return request()->ip();
	}
}
