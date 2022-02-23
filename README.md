## Introduction

Load ip and user-agent models on each request for easy access. Keeps table key in session, and compares last value to see if needs to refresh. Models are lazy loaded, and you can get just the key without loading model. Also offers a simple framework to add custom models or customize the existing ones

## Manual Installation

Copy the following to main composer.(in the case that the package is added to packages/corbinjurgens/qform)
```
 "autoload": {
	"psr-4": {
		"Corbinjurgens\\Quaip\\": "packages/corbinjurgens/quaip/src"
	},
},
```
and run 
```
composer dump-autoload
```


Add the following to config/app.php providers
```
Corbinjurgens\Quaip\ServiceProvider::class,
```
Add alias to config/app.php alias
```
"Quaip" => Corbinjurgens\Quaip\Facade::class,
```

## Requires

If using the default Ip and User-Agent loaders, this package requires the following.

- Ip: stevebauman/location:>=4.0.1 (requires configuration with geoip2/geoip2 or other) and grimzy/laravel-mysql-spatial ^4.0, ^3.0, ^2.0, or ^1.0 depending on your Laravel and Mysql version
- User-Agent: whichbrowser/parser:>=2.0.18

They are configurable, and you may also add entirely different loaders that you create yourself.

## Setup

If you wish to use the default Ip address (Ip) and User-Agent (Ua) loaders, be sure to install and configure the necessary packages above, and publish the database migration via `php artisan vendor:publish --tag="quaip-migrations"`, customize migrations if you want, and run the migrations

Change the config by publishing config file with `php artisan vendor:publish --tag="quaip-config`

## Usage

Use the middleware "quaip" to load your configured loaders. Then your configured loaders can be accessed by Quaip::{loader name}() or Quaip::get({loader name}) such as Quaip::Ip();

By default all loaders in config quaip.loaders are loaded. To customize which loaders are used you can pass parameter to the middleware, eg: `->middleware('quaip:Ip,Info')` to load Ip and a custom loader called "Info"

To get just the key and avoid fetching the full model from the database, you can use  Quaip::getKey({loader name}), which you can use for model relationships.

You can access Quaip::ip() or Quaip::ua() in any web controller to access a cached model

Use Corbinjurgens\Quaip\IpTrait and / or Corbinjurgens\Quaip\UaTrait trait to connect a table to ua() and ip() relationship

## Custom loader

Add a custom loader by adding its name to config quaip.loaders, and adding each of the actions to config.actions (or if all actions are in one place, just the action name to namespace)

Actions should implement their respective interfaces at Corbinjurgens\Quaip\Actions\Interfaces

Actions are:
- Fetch : get the target value, eg get the ip address
- Lookup : with the target value get info, eg get info about ip address such as coordinates
- Convert : with the data from lookup, format it in a way that can be easily added to database
- FindOrCreate : with the prepared data, search for a model, or create it
- Find : with the model id, find the model

For example if creating a loader called "Info" and all Actions share the same namespace and class names as above (eg Test\Info\Fetch), you can do the following inside the config quaip:

```
'loader' => [
	...
	'Info'
],
'actions' => [
	'Info' => "Test\\Info"
]
```

## Modify existing loader

You can create your own class for one or more of the actions of Ip and Ua loaders, and overwrite the default by adding it to config quaip.actions. For example

```
namespace Test
class Fetch implements \Corbinjurgens\Quaip\Actions\Interfaces\Fetch{
	public static function action()
	{
		return $_SERVER['REMOTE_ADDR'];
	}
}

```

Then in config quaip

```
'actions' => [
	"Ip\\Fetch" => Test\Fetch::class
]
```