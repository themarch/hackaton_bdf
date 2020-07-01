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
            $table->string('link_paper', '4096')->nullable();
            $table->string('name_paper', '4096')->nullable();
            $table->string('id_auteur', '4096')->nullable();
            $table->string('JEL_name', '4096')->nullable();
            $table->string('JEL_1', '4096')->nullable();
            $table->string('JEL_2', '4096')->nullable();
            $table->string('JEL_3', '4096')->nullable();
            $table->string('JEL_4', '4096')->nullable();
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
