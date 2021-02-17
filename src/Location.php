<?php

namespace Corbinjurgens\Quaip;

use Closure;
use Illuminate\Http\Request;

use Location as GetIP;
use UAParser\Parser as GetUA;

use Corbinjurgens\Quaip\Models\Ip;

use Corbinjurgens\Quaip\Models\Ua;
use Corbinjurgens\Quaip\Models\UaOs;
use Corbinjurgens\Quaip\Models\UaDevice;
use Corbinjurgens\Quaip\Models\UaBrowser;

use Quaip;


class Location
{
    /**
     * Handle an incoming request.
	 * Save user country to location session
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
	public $ip_key = 'location';
	
	public $ua_key = 'ua';
	public $ua_browser_key = 'ua_browser';
	public $ua_device_key = 'ua_device';
	public $ua_os_key = 'ua_os';
	
	public $ua_list = [
		'ua_browser' => UaBrowser::class,
		'ua_device' => UaDevice::class,
		'ua_os' => UaOs::class,
	];
	
    public function handle(Request $request, Closure $next)
    {
		//$request->session()->forget($this->ip_key);
		//$request->session()->forget($this->ua_key);
		
		$ip = $request->ip();
		
				// test
				//$ip = '98.221.11.211';
				//$ip = '66.221.11.211';
				//$ip = '67.77.13.211';
				//$ip = '98.21.11.211';
				//$ip = '2001:0db8:85a3:0000:0000:8a2e:0370:7334';
		if ($ip){
			// No session 
			if (!$request->session()->has($this->ip_key)){
				$location = GetIP::get($ip);
				$ip_database = $this->init_ip( $location ? $location->toArray() : ['ip' => $ip] );
			}else{
				// Has session, has ip. Check if its still the same
				$ip_session = $request->session()->get($this->ip_key);
				if ($ip_session && $ip !== $ip_session['ip']){
					$location = GetIP::get($ip);
					$ip_database = $this->init_ip( $location ? $location->toArray() : ['ip' => $ip] );
				}
			}
			
			$this->load_ip();
		}
		
		
		$ua = $request->server('HTTP_USER_AGENT');
				//$ua = 'Mozilla/5.0 (Linux; Android 6.0.1; SM-G920V Build/MMB29K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.98 Mobile Safari/537.36';
		
		if ($ua){
			// No session 
			if (!$request->session()->has($this->ua_key)){
				$user_agent = new \WhichBrowser\Parser($ua);
				$ua_database = $this->init_ua( $ua, $user_agent ? $user_agent->toArray() : []);
			}else{
				// Has session, has ip. Check if its still the same
				$ua_session = $request->session()->get($this->ua_key);
				if ($ua_session && $ip !== $ua_session['ua']){
					$user_agent = new \WhichBrowser\Parser($ua);
					$ua_database = $this->init_ua( $ua, $user_agent ? $user_agent->toArray() : [] );
				}
			}
			$this->load_ua();
		}
		
		
        return $next($request);
    }
	
	function init_ip($data){
		$database = (new Ip())->find_or_build($data);
		request()->session()->put($this->ip_key, $database ? $database->getAttributes() + ['ip' => $data['ip']] : null);	
		return $database;
	}
	
	function load_ip(){
		$data = request()->session()->get($this->ip_key);
		unset($data['ip']);
		/*
		$model = (new Ip)->forceFill($data);
		$model->exists = true;
		*/
		$model = Ip::hydrate([$data])->first();
		Quaip::set('ip', $model );
		return $model;
	}
	
	
	
	
	function init_ua($ua, $data){
		$database = (new Ua())->build_ua($ua, $data);
		request()->session()->put($this->ua_key, $database['ua'] ? $database['ua']->getAttributes() : ['ua' => $ua, 'id' => null]);
		
		foreach($this->ua_list as $ua_key => $ua_list){
			request()->session()->put($this->{$ua_key . '_key'}, $database[$ua_key] ? $database[$ua_key]->getAttributes() : null);
		}
		
		return $database;
	}
	
	function load_ua(){
		foreach($this->ua_list as $ua_key => $ua_list){
			$data = request()->session()->get($this->{$ua_key . '_key'});
			if ($data){
				//$$ua_key = (new $ua_list)->forceFill($data);
				//$$ua_key->exists = true;
				$$ua_key = Ip::hydrate([$data])->first();
				Quaip::set($ua_key, $$ua_key );
			}
		}
		
		
		$data = request()->session()->get($this->ua_key);
		$ua = null;
		if (!empty($data['id'])){
			$ua = (new Ua)->forceFill($data);
			$ua->exists = true;
			
			foreach($this->ua_list as $ua_key => $ua_list){
				if (isset($$ua_key)){
					$relation = substr($ua_key, 3);
					$ua->setRelation($relation, $$ua_key);
				}
				
				
			}
			Quaip::set('ua', $ua );
		}
		//$model = (new Ip)->newInstance($data, true);// doesn't add id
		return $ua;
	}
	
}
