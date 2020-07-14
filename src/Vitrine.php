<?php
namespace Dataview\IOVitrine;

use Dataview\IntranetOne\IOModel;
use Dataview\IntranetOne\Group;
use Dataview\IntranetOne\Service;
use Dataview\IntranetOne\Category;
use Dataview\IntranetOne\City;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vitrine extends IOModel
{
    use SoftDeletes;
    protected $primaryKey = 'id';
    protected $table = "vitrines";
    // protected $hidden = array('pivot');


    protected $fillable = [
        'cpf',
        // 'otica_id',
        'nome',
        'sexo',
        'dt_nascimento',
        'telefone1',
        'telefone2',
        'celular1',
        'celular2',
        'email',
        'zipCode',
        'address',
        'address2',
        'city_id',
        'group_id',
        'observacao',
      ];

    protected $dates = ['deleted_at'];

    public function city(){
      return $this->belongsTo('Dataview\IntranetOne\City');
    }

    public function group(){
      return $this->belongsTo('Dataview\IntranetOne\Group');
    }
    
    // public function otica(){
    //   return $this->belongsTo('Dataview\IOVitrine\Models\Otica');
    // }

    // public function groups(){
    //   return $this->belongsToMany('Dataview\IntranetOne\Group')->withPivot('otica_id','date','value','payment','product','details','status');
    // }

    public function formacao(){
      return $this->belongsToMany('Dataview\IntranetOne\Category','vitrine_category')->withPivot('area','curso','instituicao','inicio','fim');
    }


    // public function groups(){
    //   return $this->belongsToMany('Dataview\IntranetOne\Group')->withPivot('otica_id','date','value','payment','product','details','status');
    // }


  public static function boot(){ 
    parent::boot(); 

    static::created(function (Vitrine $obj) {
      if($obj->getAppend("hasImages")){
        $group = new Group([
          'group' => "Vitrine Avatar".$obj->id,
          'sizes' => $obj->getAppend("sizes"),
          'service_id' => Service::where('alias','vitrine')->value('id')
        ]);
        $group->save();
        $obj->group()->associate($group)->save();
      }
    });
    
  }
}
