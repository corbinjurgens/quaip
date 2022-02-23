<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreateUaIpTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('ips', function (Blueprint $table) {
			$table->id();
			$table->string('country_code', 2)->nullable()->index();
			$table->point('coordinates')->nullable();
		});
		DB::statement('ALTER TABLE `ips` ADD `ip_binary` VARBINARY(16)');
		
		Schema::table('ips', function (Blueprint $table) {
			$table->index(['ip_binary']);
		});
		
		
		
		Schema::create('ua_browsers', function (Blueprint $table) {
			$table->id();
			$table->string('name')->nullable();
			$table->string('alias')->nullable();
			
			$table->string('using')->nullable();
				$table->string('using_version')->nullable();
					$table->string('using_version_alias')->nullable();
					$table->string('using_version_nickname')->nullable();
			
			$table->string('family')->nullable();
				$table->string('family_version')->nullable();
					$table->string('family_version_alias')->nullable();
					$table->string('family_version_nickname')->nullable();
			
			$table->string('version')->nullable();
				$table->string('version_alias')->nullable();
				$table->string('version_nickname')->nullable();
				
			$table->string('type')->nullable();
			
			
			
			$indexes = config('quaip.ua_browsers.column_find');
			if ($indexes && is_array($indexes)){
				$index_array = [];
				foreach( array_keys($indexes) as $index ){
					$index_array[] = \DB::raw('`'.$index . '`(15)');
				}
				$table->index($index_array, 'ua_browsers_full_index');
			}
			
			
		});
		Schema::create('ua_devices', function (Blueprint $table) {
			$table->id();
			$table->string('type')->nullable()->index();
			$table->string('subtype')->nullable();
			
			$table->string('manufacturer')->nullable();
			$table->string('model')->nullable();
			$table->string('series')->nullable();
			$table->string('carrier')->nullable();
			
			
			
			$indexes = config('quaip.ua_devices.column_find');
			if ($indexes && is_array($indexes)){
				$index_array = [];
				foreach( array_keys($indexes) as $index ){
					$index_array[] = \DB::raw('`'.$index . '`(15)');
				}
				$table->index($index_array, 'ua_devices_full_index');
			}
		});
		Schema::create('ua_os', function (Blueprint $table) {
			$table->id();
			$table->string('name')->nullable();

			$table->string('family')->nullable();
				$table->string('family_version')->nullable();
					$table->string('family_version_alias')->nullable();
					$table->string('family_version_nickname')->nullable();
			
			$table->string('alias')->nullable();
			$table->string('edition')->nullable();
			
			$table->string('version')->nullable();
				$table->string('version_alias')->nullable();
				$table->string('version_nickname')->nullable();
			
			
			
			$indexes = config('quaip.ua_os.column_find');
			if ($indexes && is_array($indexes)){
				$index_array = [];
				foreach( array_keys($indexes) as $index ){
					$index_array[] = \DB::raw('`'.$index . '`(15)');
				}
				$table->index($index_array, 'ua_os_full_index');
			}
		});
		
		Schema::create('uas', function (Blueprint $table) {
			$table->id();
			$table->unsignedBigInteger('ua_browser_id')
				->nullable();
			$table->unsignedBigInteger('ua_device_id')
				->nullable();
			$table->unsignedBigInteger('ua_os_id')
				->nullable();
			
			$table->foreign('ua_browser_id')
				->references('id')
				->on('ua_browsers')
				->onDelete('set null')
				;
			$table->foreign('ua_device_id')
				->references('id')
				->on('ua_devices')
				->onDelete('set null')
				;
			$table->foreign('ua_os_id')
				->references('id')
				->on('ua_os')
				->onDelete('set null')
				;
				
			$table->index(['ua_browser_id', 'ua_device_id', 'ua_os_id']);
			
			$table->text('ua')->nullable();
		});
		 
		 
		 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::dropIfExists('ips');
		Schema::dropIfExists('uas');
		
		Schema::dropIfExists('ua_browsers');
		Schema::dropIfExists('ua_devices');
		Schema::dropIfExists('ua_os');
		
		
		
    }
}
