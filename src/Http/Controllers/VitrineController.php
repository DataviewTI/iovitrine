<?php
namespace Dataview\Vitrine;

use DataTables;
use Dataview\IntranetOne\IOController;
use Dataview\IOVitrine\Vitrine;
use Dataview\IntranetOne\Group;
use Dataview\IOVitrine\VitrineRequest;
use Illuminate\Http\Response;

class VitrineController extends IOController
{

  public function __construct(){
    $this->service = 'vitrine';
  }

  public function index(){
    return view('Vitrine::index');
  }

  function list() {
    $query = Vitrine::select('id','cpf_cnpj','otica_id','nome','rg','sexo','estado_civil','dt_nascimento','telefone1','telefone2','celular1','celular2','email','zipCode','address','address2','city_id','observacao','group_id', 'created_at')
    ->with(['otica', 'groups' => function($query){
        $query->select('status')->orderBy('vitrine_group.id','desc')->limit(2);
		}])
    ->orderBy('created_at', 'desc')->get();

    return Datatables::of(collect($query))->make(true);
  }

  function historyList($id) {
    $ent = Vitrine::where('id',$id)->first();
    
    $query = filled($ent) ? $ent
      ->groups()
      ->select('vitrine_group.id','otica_id','date','value','payment','product','details','alias','status')
      ->leftJoin('oticas','oticas.id','vitrine_group.otica_id')
      ->orderBy('vitrine_group.id','desc')
      ->get() : [];

    return Datatables::of(collect($query))->make(true);
  }


  public function create(VitrineRequest $request){
    $check = $this->__create($request);
    
    if (!$check['status']) {
        return response()->json(['errors' => $check['errors']], $check['code']);
    }
    
    $obj = new Vitrine($request->all());

    if($request->sizes!= null){
      $obj->setAppend("sizes",$request->sizes);
      $obj->setAppend("has_images",$request->has_images);
      $obj->save();
    }

    $obj->save();

    return response()->json(['success' => true, 'data' => ["id"=>$obj->id]]);
  }


  public function historyCreate(VitrineHistoryRequest $request){
    $check = $this->__create($request);
    
    if (!$check['status']) {
        return response()->json(['errors' => $check['errors']], $check['code']);
    } 
    
    $r = (object) $request->all();

    $ent =Vitrine::where('id',$r->vitrineId)->first();

    if(filled($ent)){
      $ent->groups()->save(new Group([
          'group' => "Histórico teste",
          'sizes' => ''
        ]),[
            "otica_id"=>$r->hist_otica_id,
            "date"=>$r->dt_compra_submit,
            "value"=>$r->value,
            "payment"=>$r->payment,
            "product"=>$r->product,
            "details"=>$r->details,
            "status"=>$r->status
          ]);
    }

    // $obj->save();

    return response()->json(['success' => true, 'data' => null]);
  }


  public function view($id)
  {
    $check = $this->__view();
    if (!$check['status']) {
        return response()->json(['errors' => $check['errors']], $check['code']);
    }

    $query = Vitrine::select('vitrines.*', 'cities.city', 'cities.region')
      ->with([
          'group'=>function($query){
          $query->select('groups.id','sizes')
          ->with('files');
        },
      ])
        ->join('cities', 'vitrines.city_id', '=', 'cities.id')
        ->where('vitrines.id', $id)->get();

    return response()->json(['success' => true, 'data' => $query]);
  }

  public function update($id, VitrineRequest $request){
    $check = $this->__update($request);
    if (!$check['status']) {
        return response()->json(['errors' => $check['errors']], $check['code']);
    }

    $_new = (object) $request->all();

    $_old = Vitrine::find($id);

    $upd = ['otica_id','nome','rg','sexo','local_trabalho','estado_civil','dt_nascimento','telefone1','telefone2','celular1','celular2','email','zipCode','address','address2','city_id','observacao','refs_comerciais','refs_pessoais'];

    foreach($upd as $u)
      $_old->{$u} = optional($_new)->{$u};
      
    if($_old->group != null){
      $_old->group->sizes = $_new->sizes;
      $_old->group->manageImages(json_decode($_new->__dz_images),json_decode($_new->sizes));
      $_old->group->save();
    }
    else
      if(count(json_decode($_new->__dz_images))>0){
        $_old->group()->associate(Group::create([
          'group' => "Album da Vitrine".$id,
          'sizes' => $_new->sizes
          ])
        );
        $_old->group->manageImages(json_decode($_new->__dz_images),json_decode($_new->sizes));
      }    

    $_old->save();
    return response()->json(['success' => $_old->save()]);
  }

  public function delete($id){
    $check = $this->__delete();
    if (!$check['status']) {
        return response()->json(['errors' => $check['errors']], $check['code']);
    }

    $obj = Vitrine::find($id);
    $obj = $obj->delete();
    return json_encode(['sts' => $obj]);
  }


  public function deleteHist($eid,$gid){
    $check = $this->__delete();
    if (!$check['status']) {
        return response()->json(['errors' => $check['errors']], $check['code']);
    }

    $obj = Vitrine::find($eid);
    $x = $obj->groups()->detach($gid);
    return json_encode(['sts' => $x]);
  }

  
  public function checkId($id){
    return Vitrine::select('razaosocial')->where('id', '=', $id)->get();
  }

  public function getCep($cep){
  }

  public function cidadesMigration(){
    $json = File::get("js/data/cidades.json");
    $data = json_decode($json, true);
    $r = "";
    foreach ($data as $obj)
      $r .= $obj['id'];
    return $r;
  }

  public function getVitrines($query){
    return json_encode(Vitrine::select('razaosocial as n', 'cpf_cnpj as k')->where('razaosocial', 'like', "%$query")->get());
  }
}
