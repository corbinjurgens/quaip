<?php

namespace Corbinjurgens\Quaip\Concerns;

use Closure;
use Illuminate\Http\Request;

use WhichBrowser\Parser;

use Corbinjurgens\Quaip\QuaipContainer;


class ProcessUa extends Contracts\Ua
{
	/**
	 * Take result of get, whether a ip or null, and parse it with GeoIP
	 * to get info
	 *
	 * @param string|null $ip_address
	 *
	 * @return mixed
	 */
	protected static function lookup($ua = null){
		if ($closure = QuaipContainer::getCustomUaLookup()){
			return $closure($ua);
		}
		$user_agent = new Parser($ua);
		return $user_agent ? $user_agent->toArray() : [];
	}
	
	/**
	 * Take the result of GeoIP or other ip tool and return the desired array or value
	 * For your database or other use. Or just return as is
	 *
	 * @param mixed $ip_address
	 *
	 * @return array
	 */
	protected static function process($data = null){
		$ua_browser = static::prepare($data['browser'] ?? [], 'ua_browsers');
		$ua_device = static::prepare($data['device'] ?? [], 'ua_devices');
		$ua_os = static::prepare($data['os'] ?? [], 'ua_os');
		
		return [
			'browser' => static::flatten($ua_browser),
			'device' => static::flatten($ua_device),
			'os' => static::flatten($ua_os)
		];
	}
	
	/**
	 * When using the default lookup, it will come as multidimensional array
	 * This needs to be flattended for database
	 *
	 * @param array $data
	 *
	 * @return array
	 */
	protected static function flatten($data = []){
		if ( isset($data['using']) && is_array($data['using']) && !empty($data['using']) ){
			$using = $data['using'];

			$data['using'] = @$using['name'];
			
			$using_version = @$using['version'];
			$data['using_version'] = $using_version;
			if (is_array($using_version)){
				$data['using_version'] = @$using_version['value'];
				$data['using_version_alias'] = @$using_version['alias'];
				$data['using_version_nickname'] = @$using_version['nickname'];
			}
		}
		
		if ( isset($data['family']) && is_array($data['family']) && !empty($data['family']) ){
			$family = $data['family'];
			
			$data['family'] = @$family['name'];

			$family_version = @$family['version'];
			$data['family_version'] = $family_version;
			if (is_array($family_version)){
				$data['family_version'] = @$family_version['value'];
				$data['family_version_alias'] = @$family_version['alias'];
				$data['family_version_nickname'] = @$family_version['nickname'];
			}
			
		   
		}
		
		if ( isset($data['version']) && is_array($data['version']) ){
			$version = $data['version'];
			
			$data['version'] = @$version['value'];
			$data['version_alias'] = @$version['alias'];
			$data['version_nickname'] = @$version['nickname'];
		}
		
		return $data;
	}
	
}
