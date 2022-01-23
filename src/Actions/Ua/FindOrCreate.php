<?php

namespace Corbinjurgens\Quaip\Actions\Ua;

use Corbinjurgens\Quaip\Actions\Interfaces;

use Illuminate\Database\Eloquent\Model;

use Corbinjurgens\Quaip\Models\Ua;
use Corbinjurgens\Quaip\Models\UaBrowser;
use Corbinjurgens\Quaip\Models\UaDevice;
use Corbinjurgens\Quaip\Models\UaOs;

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
	public static function findOrCreate(array $data) : Model
	{
		
		$ua_browser = !empty($data['browser']) ? (new UaBrowser())->find_or_build($data['browser']) : null;
		$ua_device = !empty($data['device']) ? (new UaDevice())->find_or_build($data['device']) : null;
		$ua_os = !empty($data['os']) ? (new UaOs())->find_or_build($data['os']) : null;
		
		$ua = (new Ua())->find_or_build([
			'ua_browser_id' => $ua_browser ? $ua_browser->id : null,
			'ua_device_id' => $ua_device ? $ua_device->id : null,
			'ua_os_id' => $ua_os ? $ua_os->id : null,
			'ua' => $data['ua']
			
		]);
		
		$ua->setRelation('browser', $ua_browser);
		$ua->setRelation('device', $ua_device);
		$ua->setRelation('os', $ua_os);

		return $ua;
	}
}
