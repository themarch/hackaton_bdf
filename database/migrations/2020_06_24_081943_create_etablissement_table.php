<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEtablissementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('etablissement', function (Blueprint $table) {
            $table->increments('uniqid');
            $table->string('nom_etablissement');
            $table->string('pays-ville_etablissement')->nullable();
            $table->string('site_etablissement')->nullable();
            $table->string('email_etablissement')->nullable();
            $table->string('phone_etablissement')->nullable();
            $table->string('fax_etablissement')->nullable();
            $table->string('adresse_etablissement')->nullable();
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
        Schema::dropIfExists('affiliation');
    }
}
