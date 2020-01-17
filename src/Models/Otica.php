<?php

namespace Dataview\IOVitrine\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Otica extends Model
{
  protected $fillable = ['id','name','alias','main'];

  
  public function vitrines(){
		return $this->hasMany('Dataview\IOVitrine\Vitrine');
	}}
