<?php

namespace Corbinjurgens\Quaip\Models;
use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;
use Grimzy\LaravelMysqlSpatial\Types\Point;

class Ip extends Base
{
	use SpatialTrait;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [
		'id',
    ];
	protected $appends = [
		'ip',
		'latitude',
		'longitude',
	];
	protected $spatialFields = [
        'coordinates',
    ];
	protected $hidden = [
		'ip_binary'
	];
	
	// https://github.com/phayes/geoPHP/blob/master/lib/adapters/WKB.class.php
	// https://github.com/grimzy/laravel-mysql-spatial
	// https://developers.google.com/maps/documentation/javascript/marker-clustering
	
	public $timestamps = false;
	
	public function scopeCustomWhere($query, $data){
		$ip = $data['ip'];
		unset($data['ip']);
		$query->whereIp($ip);
		if ($data){
			$query->where($data);
		}
		return $query;
	}
	
	public function scopeWhereIp($query, $ip){
		return $query->where('ip_binary', $this->from_ip($ip));
	}
	
	// ip
	public function getIpAttribute(){
		return $this->to_ip($this->ip_binary);
	}
	
	// ip
	public function setIpAttribute($ip){
		$this->attributes['ip_binary'] = $this->from_ip($ip);
	}

	// Human readable IP to store INET6_ATON
	public function from_ip($ip){
		return inet_pton($ip);
	}
	
	// Stored IP to human readable INET6_NTOA
	public function to_ip($binary){
		$l = strlen($binary);
		if ($l == 4 or $l == 16) {
			return inet_ntop(pack('A' . $l, $binary));
		}
		return null;	
	}
	
	// latitude
	public function getLatitudeAttribute(){
		return $this->get_lat_lon('lat');
	}

	// longitude
	public function getLongitudeAttribute(){
		return $this->get_lat_lon('lon');
	}

	public function get_lat_lon($key){
		$coordinates = $this->coordinates;
		if ($coordinates instanceof Point){
			switch($key){
				case 'lat':
					return $coordinates->getLat();
					break;
				case 'lon':
					return $coordinates->getLng();
					break;
			}
		}
		return null;
	}
	
	// coordinates
	public function setCoordinatesAttribute($value = null){
		if (is_array($value)){
			$value = new Point($value[0], $value[1]);
		}else if (isset($value) && ($value instanceof Point) == false){
			throw new \Exception("Must set using a " . Point::class . " class, or array of [latitude, longitude]");
		}
		$this->attributes['coordinates'] = $value;
	}
	
}
