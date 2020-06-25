<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user', function (Blueprint $table) {
            $table->increments('uniqid');
            $table->string('prenom_user');
            $table->string('nom_user');
            $table->string('suffix_user')->nullable();
            $table->string('repec_short-id')->nullable();
            $table->string('email_user')->nullable();
            $table->string('homepage_user')->nullable();
            $table->string('adresse_user')->nullable();
            $table->string('telephone_user')->nullable();
            $table->string('id_etablissement_user')->nullable();
            $table->string('id_article_user')->nullable();
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
        Schema::dropIfExists('user');
    }
}