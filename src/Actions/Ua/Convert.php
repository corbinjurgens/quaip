<?php

namespace Corbinjurgens\Quaip\Actions\Ua;

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
	public static function convert(array $data) : array {
		$ua_browser = $data['browser'] ?? [];
		$ua_device = $data['device'] ?? [];
		$ua_os = $data['os'] ?? [];
		
		return [
			'browser' => static::flatten($ua_browser),
			'device' => static::flatten($ua_device),
			'os' => static::flatten($ua_os),
			'ua' => $data['ua'] ?? null
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
