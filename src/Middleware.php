<?php

namespace Corbinjurgens\Quaip;

use Closure;

use Illuminate\Http\Request;

use Quaip;

use Corbinjurgens\Quaip\QuaipContainer;;

use Illuminate\Support\Str;


class Middleware

{
    /**
     * Handle an incoming request.
	 * Save user country to location session
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
	
    public function handle(Request $request, Closure $next)
    {
		
		//$request->session()->forget($this->ip_key);
		//$request->session()->forget($this->ua_key);
		foreach(config('quaip.loader', []) as $load){
			$session_key = "quaip_" . strtolower($load);
			$fetch = QuaipContainer::fetchClass($load, 'Fetch')::action();
			if (is_null($fetch) || $fetch === ""){
				continue;
			}

			$session = null;
			
			if ($this->requiresSession($request, $fetch, $session_key, $session)){
				$data = QuaipContainer::fetchClass($load, 'Lookup')::action($fetch);
				$prepare = QuaipContainer::fetchClass($load, 'Convert')::action($data);
				$model = QuaipContainer::fetchClass($load, 'FindOrCreate')::action($prepare);
				$session = $this->createSession($request, $fetch, $session_key, $model);
				
			}
			// Eager loading
			$result = $session['instance'] ?? QuaipContainer::fetchClass($load, 'Find')::action($session['key']);
			Quaip::set($load, $result);

		}
		
        return $next($request);
    }

	function requiresSession($request, $fetch, $session_key, &$session){
		if (!$request->hasSession()) return true;
		if (!$request->session()->has($session_key)) return true;
		$session = $request->session()->get($session_key);

		return ($session['value'] ?? null) !== $fetch;
	}

	function createSession($request, $fetch, $session_key, $model){
		$session = [
			'value' => $fetch,
			'key' => $model->getKey()
		];
		$request->session()->put($session_key, $session);
		$session['instance'] = $model;// prevent fetching from model a second time
		return $session;
	}
}
