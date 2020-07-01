<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article', function (Blueprint $table) {
            $table->increments('uniqid');
            $table->string('link_paper', '2048')->nullable();
            $table->string('name_paper', '2048')->nullable();
            $table->string('id_auteur', '2048')->nullable();
            $table->string('JEL_name', '2048')->nullable();
            $table->string('JEL_1', '2048')->nullable();
            $table->string('JEL_2', '2048')->nullable();
            $table->string('JEL_3', '2048')->nullable();
            $table->string('JEL_4', '2048')->nullable();
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
        Schema::dropIfExists('article');
    }
}
