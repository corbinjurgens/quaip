# Setup
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

## Usage
The middleware is automatically added to web group

You can access Quaip::ip() or Quaip::ua() in any web controller to access a cached model

Use Corbinjurgens\Quaip\IpTrait and / or Corbinjurgens\Quaip\UaTrait trait to connect a table to ua() and ip() relationship