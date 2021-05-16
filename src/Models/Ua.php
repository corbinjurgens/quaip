<?php

namespace Corbinjurgens\Quaip\Models;

class Ua extends Base
{
    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [
		'id',
    ];
	
	public $timestamps = false;
	
	
	/**
	 * Relationships
	 */
	public function browser(){
		return $this->belongsTo(UaBrowser::class);
	}
	public function device(){
		return $this->belongsTo(UaDevice::class);
	}
	public function os(){
		return $this->belongsTo(UaOs::class);
	}
	
	public static function build_ua(string $ua_string = null, array $data = []){ // array $browser_data, array $device_data, array $os_data
		$ua_browser = !empty($data['browser']) ? (new UaBrowser())->find_or_build($data['browser']) : null;
		$ua_device = !empty($data['device']) ? (new UaDevice())->find_or_build($data['device']) : null;
		$ua_os = !empty($data['os']) ? (new UaOs())->find_or_build($data['os']) : null;
		
		$ua = (new Ua())->find_or_build([
			'ua_browser_id' => $ua_browser ? $ua_browser->id : null,
			'ua_device_id' => $ua_device ? $ua_device->id : null,
			'ua_os_id' => $ua_os ? $ua_os->id : null,
			'ua' => $ua_string
			
		]);
		
		$ua->setRelation('browser', $ua_browser);
		$ua->setRelation('device', $ua_device);
		$ua->setRelation('os', $ua_os);
		
		return compact('ua_browser', 'ua_device', 'ua_os', 'ua');
		
	}
	
	
}
