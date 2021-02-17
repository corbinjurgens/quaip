<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class ModifyUaIpTables extends Migration
{
    /**
     * Run the migrations.
	 * Change to nullable
     *
     * @return void
     */
    public function up()
    {
		
		Schema::table('uas', function (Blueprint $table) {
			$table->dropForeign(['ua_browser_id']);
			$table->dropForeign(['ua_device_id']);
			$table->dropForeign(['ua_os_id']);
			
			$table->unsignedBigInteger('ua_browser_id')
				->nullable()
				->change();
			$table->unsignedBigInteger('ua_device_id')
				->nullable()
				->change();
			$table->unsignedBigInteger('ua_os_id')
				->nullable()
				->change();
			
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
				
				
				
		});
		 
		 
		 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::table('uas', function (Blueprint $table) {
			$table->dropForeign(['ua_browser_id']);
			$table->dropForeign(['ua_device_id']);
			$table->dropForeign(['ua_os_id']);
			
			$table->unsignedBigInteger('ua_browser_id')
				->change();
			$table->unsignedBigInteger('ua_device_id')
				->change();
			$table->unsignedBigInteger('ua_os_id')
				->change();
			
			$table->foreign('ua_browser_id')
				->references('id')
				->on('ua_browsers')
				->onDelete('cascade')
				;
			$table->foreign('ua_device_id')
				->references('id')
				->on('ua_devices')
				->onDelete('cascade')
				;
			$table->foreign('ua_os_id')
				->references('id')
				->on('ua_os')
				->onDelete('cascade')
				;
				
		});
		
		
		
		
    }
}
