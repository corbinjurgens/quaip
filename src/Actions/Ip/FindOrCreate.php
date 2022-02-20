<?php

namespace Corbinjurgens\Quaip\Actions\Ip;

use Corbinjurgens\Quaip\Actions\Interfaces;

use Illuminate\Database\Eloquent\Model;

use Corbinjurgens\Quaip\Models\Ip;

class FindOrCreate implements Interfaces\FindOrCreate
{
	/**
	 * Take result of get, whether a ip or null, and parse it with GeoIP
	 * to get info
	 *
	 * @param string|null $ip_address
	 *
	 * @return array
	 */
	public static function action(array $data) : Model
	{
		
		return (new Ip())->find_or_build($data);
	}
}
