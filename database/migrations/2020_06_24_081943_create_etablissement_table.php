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
            $table->string('link_etablissement', '4096')->nullable();
            $table->string('nom_etablissement', '4096')->nullable();
            $table->string('pays_ville_etablissement', '4096')->nullable();
            $table->string('site_etablissement', '4096')->nullable();
            $table->string('email_etablissement', '4096')->nullable();
            $table->string('phone_etablissement', '4096')->nullable();
            $table->string('fax_etablissement', '4096')->nullable();
            $table->string('adresse_etablissement', '4096')->nullable();
            $table->string('function_etablissement', '4096')->nullable();
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
