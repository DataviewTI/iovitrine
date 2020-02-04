<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePivotVitrineCategoryTable extends Migration
{
    public function up() {
        Schema::create('vitrine_category', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('vitrine_id')->unsigned();
            $table->integer('category_id')->unsigned();
            $table->integer('area')->unsigned();
            $table->string('curso');
            $table->string('instituicao');
            $table->char('inicio',4);
            $table->char('fim',4)->nullable();
            $table->timestamps();
            $table->foreign('vitrine_id')->references('id')->on('vitrines')->onDelete('cascade')->onUpdate('cascade');
            // $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('area')->references('id')->on('categories')->onDelete('cascade')->onUpdate('cascade');
        });
    }
    public function down(){
      Schema::dropIfExists('vitrine_category');
    }
}
