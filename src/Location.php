<?php

namespace Corbinjurgens\Quaip;

use Closure;
use Illuminate\Http\Request;

use Corbinjurgens\Quaip\Concerns\ProcessIp;
use Corbinjurgens\Quaip\Concerns\ProcessUa;

use Corbinjurgens\Quaip\Models\Ip;

use Corbinjurgens\Quaip\Models\Ua;
use Corbinjurgens\Quaip\Models\UaOs;
use Corbinjurgens\Quaip\Models\UaDevice;
use Corbinjurgens\Quaip\Models\UaBrowser;

use Illuminate\Database\Eloquent\Model;

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
		
		$ip = ProcessIp::fetch();
		
		if ($ip){
			// No session 
			$ip_session = null;
			if ($request->hasSession() && !$request->session()->has($this->ip_key)){
				$ip_session = $this->init_ip();
			}else{
				// Has session, has ip. Check if its still the same
				$ip_session = $request->hasSession() ? $request->session()->get($this->ip_key) : null;
				if (($ip_session && $ip !== $ip_session['ip']) || is_null($ip_session)){
					$ip_session = $this->init_ip();
				}
			}
			
			$this->load_ip($ip_session);
		}
		
		
		$ua = ProcessUa::fetch();
				//$ua = 'Mozilla/5.0 (Linux; Android 6.0.1; SM-G920V Build/MMB29K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.98 Mobile Safari/537.36';
		
		if ($ua){
			// No session 
			$ua_session = null;
			if ($request->hasSession() && !$request->session()->has($this->ua_key)){
				$ua_session = $this->init_ua();
			}else{
				// Has session, has ua. Check if its still the same
				$ua_session = $request->hasSession() ? $request->session()->get($this->ua_key) : null;
				if (($ua_session && $ip !== $ua_session['ua']) || is_null($ua_session)){
					$ua_session = $this->init_ua();
				}
			}
			$this->load_ua($ua_session);
		}
        return $next($request);
    }
	
	function init_ip(){
		$data = ProcessIp::get();
		$database = (new Ip())->find_or_build($data);
		if (request()->hasSession()){
			$result = $database ? $database->getAttributes() + ['ip' => $data['ip']] : null;
			request()->session()->put($this->ip_key, $result);	
		}
		
		return $database;
	}
	
	function load_ip($data){
		if ($data instanceof Model){
			Quaip::set('ip', $data );
			return $data;
		}
		unset($data['ip']);
		$model = Ip::hydrate([$data])->first();
		Quaip::set('ip', $model );
		return $model;
	}
	
	
	
	
	function init_ua(){
		$ua = ProcessUa::fetch();
		$data = ProcessUa::get();
		$database = (new Ua())->build_ua($ua, $data);

		if (request()->hasSession()){
			request()->session()->put($this->ua_key, $database['ua'] ? $database['ua']->getAttributes() : ['ua' => $ua, 'id' => null]);
			foreach($this->ua_list as $ua_key => $ua_list){
				request()->session()->put($this->{$ua_key . '_key'}, $database[$ua_key] ? $database[$ua_key]->getAttributes() : null);
			}
		}
		return $database;
	}
	
	function load_ua($all_data){
		foreach($this->ua_list as $ua_key => $ua_list){
			$data = $all_data[$ua_key];
			if ($data){
				$$ua_key = $data instanceof Model ? $data : $ua_list::hydrate([$data])->first();
				Quaip::set($ua_key, $$ua_key );
			}
		}
		
		
		$data = $all_data[$this->ua_key];
		$ua = null;
		if (!empty($data['id'])){
			$ua = $data instanceof Model ? $data : Ua::hydrate([$data])->first();
			if (!$data instanceof Model){
				
				foreach($this->ua_list as $ua_key => $ua_list){
					if (isset($$ua_key)){
						$relation = substr($ua_key, 3);
						$ua->setRelation($relation, $$ua_key);
					}
					
					
				}
			}
			Quaip::set('ua', $ua );
		}
		return $ua;
	}
	
}
