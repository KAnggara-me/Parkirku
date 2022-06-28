<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('plates', function (Blueprint $table) {
			$table->id();
			$table->string('uid');
			$table->string("image1")->nullable();
			$table->string("image2")->nullable();
			$table->integer("status")->default(0);
			$table->string("plate_number")->nullable();
			$table->dateTimeTz("login")->useCurrent();;
			$table->dateTimeTz("logout")->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('plates');
	}
};
