<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEbooksTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ebooks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 255);
            $table->string('slug', 255);
            $table->string('filename', 255);
            $table->string('filemime', 60);
            $table->integer('filesize')->unsigned();
            $table->text('summary')->nullable();
            $table->string('featured_image', 255)->nullable();
            $table->integer('pyear')->unsigned()->nullable(); //new
            $table->integer('pages')->unsigned()->nullable();
            $table->integer('category_id')->unsigned();
            $table->integer('language_id')->unsigned();
            $table->integer('security_id')->unsigned();
            $table->integer('writer_id')->unsigned();
            $table->integer('publisher_id')->unsigned();
            $table->integer('pplace_id')->unsigned()->nullable();//new
            $table->integer('series_id')->unsigned()->nullable();
            $table->integer('user_id')->unsigned();
            $table->smallInteger('status')->default(1);
            $table->integer('hit')->unsigned()->default(0);
            $table->boolean('featured')->default(0);
            $table->nullableTimestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ebooks');
    }

}
