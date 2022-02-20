<?php

namespace Corbinjurgens\Quaip\Actions\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface FindOrCreate
{
	
	public static function action(array $data) : Model;
}
