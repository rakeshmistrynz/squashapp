<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNoticesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('notices', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('headline');
			$table->text('body');
			$table->integer('author_id');
			$table->softDeletes();
			$table->timestamps();
			$table->string('file_name');
			$table->string('image_name')->default('default.jpg');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('notices', function(Blueprint $table)
		{
			Schema::drop('notices');
		});
	}

}
