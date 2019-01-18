<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNomeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nomes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('doc')->index();
            $table->string('nome')->index();
            $table->string('cidade')->index();
            $table->string('uf')->index();
            $table->string('cep')->index();
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
        Schema::dropIfExists('nome');
    }
}
