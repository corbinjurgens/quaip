<?php
return [

	// Things to load on Middleware
	// Will search for each action in the actions array or use part of the class name, eg Corbinjurgens\Quaip\Actions\{Loader}\...
	// Will also make these items be accessible from the Facade, eg, Quaip::ip();
	'loader' => [
		'Ua',
		'Ip'
	],

	// Loader actions are Convert, Fetch, Find, FindOrCreate, Lookup
	// Map the default classes to a custom class here, or provide location of classes that are outside of Ua and Ip
	// point to it by providing string loader\action eg "Ip\\Fetch"
	// Anything not found in this list, will look to \Corbinjurgens\Quaip\Actions\ for example, \Corbinjurgens\Quaip\Actions\Ip\Fetch::class
	// Point to a location for all actions of a loader by adding just the loader name, eg 'Ip' => "Your\\Custom\\Ip" and it will look to Your\Custom\Ip\Fetch::class and other action names
	'actions' => [
		//"Ip\\Fetch" => \Your\Custom\Ip\Fetcher::class
	],

	//--------
	// Everything below is used by default actions
	//--------

	/**
	 * Within each table, yuou can set
	 * 'column_find' to set columns used for where and their defaults, also useful for correct ordering to ensure compound index work
	 * This is only used for the default FindOrCreate Actions. 
	 */
	'ips' => [
		/**
		 * The columns used for find, and default value (so as to ensure you get the correct result with null if key is not provided)
		 */
		'column_find' => [
			'ip' => null
		]
	],
	'ua_browsers' => [
		/**
		 * The columns used for find, and default value (so as to ensure you get the correct result with null if key is not provided)
		 */
		'column_find' => [
			'name' => null,
			'alias' => null,
			
			'using' => null,
			'using_version' => null,
			'using_version_alias' => null,
			'using_version_nickname' => null,
			
			'family' => null,
			'family_version' => null,
			'family_version_alias' => null,
			'family_version_nickname' => null,
			
			'version' => null,
			'version_alias' => null,
			'version_nickname' => null,
			
			'type' => null,
		]
	],
	'ua_devices' => [
		/**
		 * The columns used for find, and default value (so as to ensure you get the correct result with null if key is not provided)
		 */
		'column_find' => [
			'type' => null,
			'subtype' => null,
			'manufacturer' => null,
			'model' => null,
			'series' => null,
			'carrier' => null,
		]
	],
	'ua_os' => [
		/**
		 * The columns used for find, and default value (so as to ensure you get the correct result with null if key is not provided)
		 */
		'column_find' => [
			'name' => null,
			
			'family' => null,
			'family_version' => null,
			'family_version_alias' => null,
			'family_version_nickname' => null,
			
			'alias' => null,
			'edition' => null,
			
			'version' => null,
			'version_alias' => null,
			'version_nickname' => null,
		]
	],
	
	'uas' => [
		'column_find' => [
			'ua_browser_id' => null,
			'ua_device_id' => null,
			'ua_os_id' => null,
			'ua' => null, // searching by ua is not recommended but having all the other relationships also searched should make it quicker. And good if need to refactor later
		],
	],
	
];
