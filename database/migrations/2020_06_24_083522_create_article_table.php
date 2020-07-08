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
            $table->longText('link_paper')->nullable();
            $table->longText('name_paper')->nullable();
            $table->longText('id_auteur')->nullable();
            $table->longText('JEL_name')->nullable();
            $table->longText('JEL_1')->nullable();
            $table->longText('JEL_2')->nullable();
            $table->longText('JEL_3')->nullable();
            $table->longText('JEL_4')->nullable();
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
