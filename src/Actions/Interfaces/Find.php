<?php

namespace Corbinjurgens\Quaip\Actions\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface Find
{
	
	public static function action($key) : ?Model;
}
