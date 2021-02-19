<?php

namespace Corbinjurgens\Quaip\Models;
use Illuminate\Database\Eloquent\Model;
class Base extends Model
{
	
	/**
	 * Process data coming from the ua or ip packages
	 */
	function process_array(array $data, array $allowed = null, array $equivalent = null){
		if ( is_null($allowed) ) $allowed = config('quaip.'. $this->getTable() . '.allowed_columns');
		if ( is_null($equivalent) ) $equivalent = config('quaip.'. $this->getTable() . '.columnn_keys', []);
		
		$result = [];
		if (is_array($allowed)){
			$data = array_intersect_key($data, array_flip($allowed));
		}
		foreach($data as $k => $v){
			$current_key = $k;
			if (isset($equivalent[$k])){
				$result[ $equivalent[$k] ] = $v;
				$current_key = $equivalent[$k];
			}else{
				$result[ $k ] = $v;
			}
		}
		
		if ( isset($result['using']) && is_array($result['using']) && !empty($result['using']) ){
			$using = $result['using'];

			$result['using'] = @$using['name'];
			
			$using_version = @$using['version'];
			$result['using_version'] = $using_version;
			if (is_array($using_version)){
				$result['using_version'] = @$using_version['value'];
				$result['using_version_alias'] = @$using_version['alias'];
				$result['using_version_nickname'] = @$using_version['nickname'];
			}
		}
		
		if ( isset($result['family']) && is_array($result['family']) && !empty($result['family']) ){
			$family = $result['family'];
			
			$result['family'] = @$family['name'];

			$family_version = @$family['version'];
			$result['family_version'] = $family_version;
			if (is_array($family_version)){
				$result['family_version'] = @$family_version['value'];
				$result['family_version_alias'] = @$family_version['alias'];
				$result['family_version_nickname'] = @$family_version['nickname'];
			}
			
		   
		}
		
		
		
		if ( isset($result['version']) && is_array($result['version']) ){
			$version = $result['version'];
			
			$result['version'] = @$version['value'];
			$result['version_alias'] = @$version['alias'];
			$result['version_nickname'] = @$version['nickname'];
		}
		return $result;
	}
	
	/**
	 * Array used for multi where, to be used on array that has already process_array()
	 */
	function find_array(array $array, array $find = null){
		if ( is_null($find) ) $find = config('quaip.'. $this->getTable() . '.column_find');
		return array_replace($find,  array_intersect_key($array, $find) );
	}
	
	/**
	 * Use the process array and find array functions to esseentially findOrCreate
	 */
	 
	function find_or_build($data){
		
		$processed_data = $this->process_array($data);
		$find_data =  $this->find_array($processed_data);
		if ( method_exists($this, 'scopeCustomWhere') ){
			$find = $this::customWhere($find_data)->first();
		}else{
			$find = $this::where($find_data)->first();
		}
		
		if ($find){
			return $find;
		}
		
		$create = new $this();
		$create->forceFill($processed_data);
		
		
		$create->save();
		return $this::find($create->id);
	}
	
	
	
}
