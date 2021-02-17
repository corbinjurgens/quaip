<?php

namespace Corbinjurgens\Quaip;

use Illuminate\Support\Facades\Facade as BaseFacade;

use Corbinjurgens\Quaip\ServiceProvider as S;

class Facade extends BaseFacade {
   protected static function getFacadeAccessor() { return S::$name; }
}