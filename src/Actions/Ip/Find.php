<?php

namespace Corbinjurgens\Quaip\Actions\Ip;

use Corbinjurgens\Quaip\Actions\Interfaces;

use Illuminate\Database\Eloquent\Model;

use Corbinjurgens\Quaip\Models\Ip;

class Find implements Interfaces\Find
{
	/**
	 * Load model from key
	 *
	 * @param string|int $key
	 *
	 * @return Model
	 */
	public static function action($key) : Model
	{
		
		return Ip::find($key);
	}
}
