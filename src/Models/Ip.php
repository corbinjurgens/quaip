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
	// INET6_ATON
	public function from_ip($ip){
		return inet_pton($ip);
	}
	
	// INET6_NTOA
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
	// latitude
	public function setLatitudeAttribute($value){
		$this->set_lat_lon('lat', $value);
		//dd($this);
		$this->refresh_coordinates();
	}
	// longitude
	public function setLongitudeAttribute($value){
		$this->set_lat_lon('lon', $value);
		$this->refresh_coordinates();
	}
	
	// coordinates
	public function setCoordinatesAttribute(Point $value = null){
		
		$this->attributes['coordinates'] = $value;
		$this->lat = null;
		$this->lon = null;
	}
	
	
	
	public $lat = null;
	public $lon = null;
	
	function get_lat_lon($key){
		if (!is_null($this->{$key})){
			return $this->{$key};
		}
		//$coordinates = $this->getOriginal('coordinates');
		
		
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
	
	function set_lat_lon($key, $value){
		$this->{$key} = $value;
		
	}
	
	function refresh_coordinates(){
		$lat = $this->get_lat_lon('lat');
		$lon = $this->get_lat_lon('lon');
		if ($lat && $lon){
			//$coordinates = $this->from_lat_lon($lat, $lon);
			//dd($coordinates);
			//$this->attributes['coordinates'] = $coordinates;
			$this->attributes['coordinates'] = new Point($lat, $lon);
		}else{
			$this->attributes['coordinates'] = null;
		}
	}
	
	
	public function to_lat_lon($data){
		if (is_null($data) || $data === ''){
			return null;
		}
		//dd($this->coordinates);
		//echo "---- ";print_r($data);
		//$wb = new \WKB();
		//dd( $wb->read($data, true) );
		
		//dd( \geoPHP::load(bin2hex($data),'wkb') );
		return unpack('x/x/x/x/corder/Ltype/dlat/dlon', $data);
	}
	
	
	public function from_lat_lon($lat, $lon){
		if (!($lon) || !($lat)){
			return null;
		}
		/*
		$old_wkb = pack('c',1);
		$old_wkb .= pack('L',1);
		$old_wkb .= pack('dd',floatval($lat), floatval($lon));
		
		$old = pack('xxxxcLdd', '0', 1, (float) $lat, (float) $lon);
		*/
		
		
		$point = $polygon = \geoPHP::load(
			sprintf('POINT(%s %s)', $lat, $lon)
		,'wkt');
		
		$wkb_reader = new \WKB();
		$wkb = $wkb_reader->write($point);
		return $wkb;
		
		
		//return \DB::raw(sprintf('POINT(%s, %s)', $lat, $lon));
		
		
		
	}
	/*
	static function booted(){
		static::saving(function($model){
			if (!is_null($model->coordinates)){
				//$model->coordinates = \DB::raw("0x".bin2hex($model->coordinates));
				$model->coordinates = \DB::raw("ST_GEOMFROMWKB('".($model->coordinates)."')");
			}
dd($model);
		});
		
	}
	*/
	
	
}
