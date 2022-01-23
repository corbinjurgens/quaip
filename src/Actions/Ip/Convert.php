<?php

namespace Corbinjurgens\Quaip\Actions\Ip;

use Corbinjurgens\Quaip\Actions\Interfaces;

class Convert implements Interfaces\Convert
{
	/**
	 * Convert result of Lookup to a format to easily search table
	 *
	 * @param array $data
	 *
	 * @return array
	 */
	public static function convert(array $data) : array{
		return [
			'ip' => $data['ip'],
			'country_code' => $data['countryCode'] ?? null,
			'latitude' => $data['latitude'] ?? null,
			'longitude' => $data['longitude'] ?? null,
		];
	}
}
