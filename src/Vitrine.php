<?php
namespace Dataview\IOVitrine;

use Dataview\IntranetOne\IOModel;
use Dataview\IntranetOne\Group;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vitrine extends IOModel
{
    use SoftDeletes;
    protected $primaryKey = 'id';
    protected $table = "vitrines";
    // protected $hidden = array('pivot');


    protected $fillable = [
        'cpf_cnpj',
        'cod_cliente',
        'otica_id',
        'nome',
        'rg',
        'sexo',
        'estado_civil',
        'profissao',
        'local_trabalho',
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
        'refs_pessoais',
        'refs_comerciais'
      ];

    protected $dates = ['deleted_at'];

    public function city(){
      return $this->belongsTo('City');
    }

    public function group(){
      return $this->belongsTo('Dataview\IntranetOne\Group');
    }
    
    public function otica(){
      return $this->belongsTo('Dataview\IOVitrine\Models\Otica');
    }

    public function groups(){
      return $this->belongsToMany('Dataview\IntranetOne\Group')->withPivot('otica_id','date','value','payment','product','details','status');
    }


  public static function boot(){ 
    parent::boot(); 

    static::created(function (Vitrine $obj) {
      if($obj->getAppend("has_images")){
        $group = new Group([
          'group' => "Vitrine Avatar".$obj->id,
          'sizes' => $obj->getAppend("sizes")
        ]);
        $group->save();
        $obj->group()->associate($group)->save();
      }
    });
    
  }
}
