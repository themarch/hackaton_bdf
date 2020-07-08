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
            $table->longText('link_user')->nullable();
            $table->longText('prenom_user')->nullable();
            $table->longText('surnom_user')->nullable();
            $table->longText('nom_user')->nullable();
            $table->longText('all_name')->nullable();
            $table->longText('all_name_invers')->nullable();
            $table->longText('suffix_user')->nullable();
            $table->longText('repec_shortid')->nullable();
            $table->longText('email_user')->nullable();
            $table->longText('homepage_user')->nullable();
            $table->longText('adresse_user')->nullable();
            $table->string('telephone_user')->nullable();
            $table->string('twitter_user')->nullable();
            $table->longText('degree_user')->nullable();
            $table->string('id_etablissement_user1', '10')->nullable();
            $table->string('id_etablissement_user2', '10')->nullable();
            $table->string('id_etablissement_user3', '10')->nullable();
            $table->string('id_etablissement_user4', '10')->nullable();
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
