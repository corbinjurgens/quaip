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
	public static function action(array $data) : array{
		return [
			'ip' => $data['ip'],
			'country_code' => $data['countryCode'] ?? null,
			'coordinates' => isset($data['latitude']) && isset($data['longitude']) ? [$data['latitude'], $data['longitude']] : null
		];
	}
}
