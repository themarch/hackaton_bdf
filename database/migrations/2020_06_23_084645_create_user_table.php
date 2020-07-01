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
            $table->string('link_user', '2048')->nullable();
            $table->string('prenom_user', '2048')->nullable();
            $table->string('surnom_user', '2048')->nullable();
            $table->string('nom_user', '2048')->nullable();
            $table->string('all_name', '2048')->nullable();
            $table->string('all_name_invers', '2048')->nullable();
            $table->string('suffix_user', '500')->nullable();
            $table->string('repec_shortid', '500')->nullable();
            $table->string('email_user', '500')->nullable();
            $table->string('homepage_user', '500')->nullable();
            $table->string('adresse_user', '500')->nullable();
            $table->string('telephone_user', '500')->nullable();
            $table->string('twitter_user', '500')->nullable();
            $table->string('degree_user', '500')->nullable();
            $table->string('id_etablissement_user1', '500')->nullable();
            $table->string('id_etablissement_user2', '500')->nullable();
            $table->string('id_etablissement_user3', '500')->nullable();
            $table->string('id_etablissement_user4', '500')->nullable();
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
