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
            $table->string('link_paper')->nullable();
            $table->string('name_paper')->nullable();
            $table->string('id_auteur')->nullable();
            $table->string('JEL_name', '1000')->nullable();
            $table->string('JEL_1')->nullable();
            $table->string('JEL_2')->nullable();
            $table->string('JEL_3')->nullable();
            $table->string('JEL_4')->nullable();
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
