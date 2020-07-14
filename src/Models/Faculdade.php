<?php

namespace Dataview\IOVitrine\Models;

use Illuminate\Database\Eloquent\Model;

class Faculdade extends Model
{
    protected $fillable = ['id','faculdade'];

    public function vitrines(){
      return $this->hasMany('Vitrine');
    }    
}
