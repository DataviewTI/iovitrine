<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateVitrineTable extends Migration
{
    public function up()
    {
      Schema::create('vitrines', function (Blueprint $table) {
        $table->increments('id');
        $table->char('cpf', 14)->unique();
        // $table->integer('otica_id')->unsigned();
        $table->integer('group_id')->unsigned()->nullable();
        $table->string('nome');
        $table->enum('sexo', ['M', 'F', 'I'])->default('F');
        $table->date('dt_nascimento')->nullable();
        $table->char('telefone1', 15)->nullable();
        $table->char('telefone2', 15)->nullable();
        $table->char('celular1', 15)->nullable();
        $table->char('celular2', 15)->nullable();
        $table->string('email')->nullable();
        $table->char('zipCode', 9)->nullable();
        $table->string('address')->nullable();
        $table->string('address2')->nullable();
        $table->char('city_id',7);
        $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade')->onUpdate('cascade');
        // $table->foreign('otica_id')->references('id')->on('oticas')->onDelete('cascade')->onUpdate('cascade');
        $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade')->onUpdate('cascade');
        $table->text('resumo')->nullable();
        $table->timestamps();
        $table->softDeletes();
      });
    }
    
    public function down(){
      Schema::dropIfExists('vitrines');
    }
}
