<?php

namespace Corbinjurgens\Quaip\Actions\Ua;

use Corbinjurgens\Quaip\Actions\Interfaces;

use WhichBrowser\Parser;

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
	public static function action(string $ua = null) : array{
		$user_agent = new Parser($ua);
		return $user_agent ? ($user_agent->toArray() + ['ua' => $ua]) : [];
	}
}
