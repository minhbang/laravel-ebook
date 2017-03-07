<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoryEbookTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_ebook', function (Blueprint $table) {
            $table->integer('category_id')->unsigned();
            $table->integer('ebook_id')->unsigned();
            $table->primary(['category_id', 'ebook_id']);
            $table->foreign('reader_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('ebook_id')->references('id')->on('ebooks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('category_ebook');
    }

}
