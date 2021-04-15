<?php

namespace Corbinjurgens\Quaip\Models;
use Illuminate\Database\Eloquent\Model;
class Base extends Model
{
	
	
	/**
	 * Take an array of some values, and force missing values to the defaults given in config column_find
	 * in order to be used to query database columns
	 *
	 * @param array $array
	 * @param array|null $find
	 *
	 * @return array
	 */
	function find_array(array $array, array $find = null){
		if ( is_null($find) ) $find = config('quaip.'. $this->getTable() . '.column_find');
		return array_replace($find,  array_intersect_key($array, $find) );
	}
	
	/**
	 * Use the processed array and find array functions to esseentially findOrCreate
	 */
	 
	function find_or_build($processed_data){
		
		$find_data = $this->find_array($processed_data);
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
