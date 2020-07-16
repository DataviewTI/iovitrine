<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class CreateFaculdadesTable extends Migration
{
    public function up() {
    if (!Schema::hasTable('faculdades'))
      Schema::create('faculdades', function (Blueprint $table) {
        $table->increments('id');
        $table->string('faculdade');
        $table->timestamps();
      });
    }

    public function down() {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::drop('faculdades');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
