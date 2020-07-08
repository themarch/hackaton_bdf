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
            $table->longText('link_etablissement')->nullable();
            $table->longText('nom_etablissement')->nullable();
            $table->longText('pays_ville_etablissement')->nullable();
            $table->longText('site_etablissement')->nullable();
            $table->longText('email_etablissement')->nullable();
            $table->longText('phone_etablissement')->nullable();
            $table->longText('fax_etablissement')->nullable();
            $table->longText('adresse_etablissement')->nullable();
            $table->longText('function_etablissement')->nullable();
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
