<?php
namespace Dataview\IOVitrine;

use DataTables;
use Dataview\IntranetOne\IOController;
use Dataview\IOVitrine\Vitrine;
use Dataview\IntranetOne\Group;
use Dataview\IntranetOne\Category;
use Dataview\IOVitrine\VitrineRequest;
use Illuminate\Http\Response;
use Validator;

class VitrineController extends IOController
{

  public function __construct(){
    $this->service = 'vitrine';
  }

  public function index(){
    return view('Vitrine::index');
  }

  function list() {
    $query = Vitrine::select('id','cpf','nome','sexo','dt_nascimento','telefone1','telefone2','celular1','celular2','email','zipCode','address','address2','city_id','resumo','group_id', 'created_at')
    ->with(['formacao' => function($query) {
        $query->select('vitrine_category.id as vid','area','curso','instituicao','inicio','fim','c.category','c.id','c.order')
        ->whereNotNull('fim')
        ->leftJoin('categories as c', 'vitrine_category.category_id', '=', 'c.id')
        ->orderBy('c.order','desc')
        ->orderBy('vitrine_category.id','desc');
        // ->limit(2);
		}])
    ->orderBy('created_at', 'desc')->get();

    return Datatables::of(collect($query))->make(true);
  }

  function formacaoList($id) {
    $ent = Vitrine::where('id',$id)->first();
    
    $query = filled($ent) ? $ent
      ->formacao()
      ->select('vitrine_category.id as vid','area','curso','instituicao','inicio','fim','c.category','c.id','c.order')
      ->leftJoin('categories as c', 'vitrine_category.category_id', '=', 'c.id')
      ->orderBy('c.order','desc')
      ->orderBy('vitrine_category.id','desc')
      ->get() : [];

    return Datatables::of(collect($query))->make(true);
  }


  public function createFrontEnd(VitrineRequest $request){
   
    $validator = Validator::make($request->all(),$request->rules(),$request->messages());
    if($validator->fails())
      $check = [
        "status"=>false,
        "errors"=>[
          $validator->errors()->all()
        ],
        "code"=>422
      ];
    else
      $check = null;

   if(filled($check))
      return response()->json(['errors' => $check['errors']], $check['code']);

    $data = (object) $request->all();

    $obj = new Vitrine([
      "cpf"=>$data->cpf,
      "nome"=>$data->name,
      "celular1"=>$data->phone,
      "email"=>$data->email,
      "resumo"=>$data->description
    ]);


    $obj->setAppend("sizes",'{"original":true,"sizes":{"thumb":{"w":180,"h":180}}}');
    $obj->setAppend("hasImages",true);
    $obj->setAppend("frontendUser",true);
    $obj->save();

    return response()->json(['success' => true, 'data' => ["id"=>$obj->id]]);
  }



  public function create(VitrineRequest $request){
    $check = $this->__create($request);
    
    if (!$check['status']) {
        return response()->json(['errors' => $check['errors']], $check['code']);
    }
    
    $obj = new Vitrine($request->all());

    if($request->sizes!= null){
      $obj->setAppend("sizes",$request->sizes);
      $obj->setAppend("hasImages",$request->has_images);
      $obj->save();
    }

    $obj->save();

    return response()->json(['success' => true, 'data' => ["id"=>$obj->id]]);
  }


  public function formacaoCreate(VitrineFormacaoRequest $request){
    // $check = $this->__create($request);
    
    // if (!$check['status']) {
    //     return response()->json(['errors' => $check['errors']], $check['code']);
    // } 
    
    $validator = Validator::make($request->all(),$request->rules(),$request->messages());
    if($validator->fails())
      $check = [
        "status"=>false,
        "errors"=>[
          $validator->errors()->all()
        ],
        "code"=>422
      ];
    else
      $check = null;

   if(filled($check))
      return response()->json(['errors' => $check['errors']], $check['code']);
      
          $r = (object) $request->all();

    $ent =Vitrine::where('id',$r->vitrineId)->first();

    if(filled($ent)){
      $ent->formacao()->attach(Category::where('id',$r->form_tipo)->first()->id,[
            "curso"=>$r->form_curso,
            "area"=>$r->form_area,
            "instituicao"=>$r->form_instituicao,
            "inicio"=>$r->form_inicio,
            "fim"=>optional($r)->form_fim,
          ]);
    }

    // $obj->save();

    return response()->json(['success' => true, 'data' => null]);
  }


  public function listFrontEnd() {

    $query = Vitrine::select('*','group_id')
    ->with([
        'city',
        'formacao',
        'group' => function($query){
        $query->select('groups.id','sizes')
          ->with('files');
        },
    ])
    ->whereHas('formacao')
    ->get();
 
    return response()->json(['success' => true, 'data' => $query]);
  }
  
  public function view($id)
  {
    $check = $this->__view();
    if (!$check['status']) {
        return response()->json(['errors' => $check['errors']], $check['code']);
    }

    $query = Vitrine::
      with([
          'city',
          'group'=>function($query){
          $query->select('groups.id','sizes')
          ->with('files');
        },
      ]);

      $query = filter_var($id, FILTER_VALIDATE_EMAIL) ? $query->whereEmail($id) : $query->whereId($id);

    return response()->json(['success' => true, 'data' => $query->get()]);
  }


  public function formacaoView($id)
  {
    $check = $this->__view();
    if (!$check['status']) {
        return response()->json(['errors' => $check['errors']], $check['code']);
    }

    $query = \DB::table('vitrine_category')
      ->select('vitrine_category.id as vid','area','curso','instituicao','inicio','fim','c.category','c.id','c.order')
      ->leftJoin('categories as c', 'vitrine_category.category_id', '=', 'c.id')
      ->orderBy('c.order','desc')
      ->orderBy('vitrine_category.id','desc')
      ->where('vitrine_category.id',$id)
      ->get();

    return response()->json(['success' => true, 'data' => $query]);
  }


  public function update($id, VitrineRequest $request){
    $check = $this->__update($request);
    if (!$check['status']) {
        return response()->json(['errors' => $check['errors']], $check['code']);
    }

    $_new = (object) $request->all();

    $_old = Vitrine::find($id);
    // $_old = Vitrine::filter_var($id, FILTER_VALIDATE_EMAIL) ? Vitrine::whereEmail($id)->first() : Vitrine::whereId($id)->first();

    $upd = ['nome','sexo','dt_nascimento','telefone1','telefone2','celular1','celular2','email','zipCode','address','address2','city_id','resumo'];

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


  public function formacaoUpdate($id, VitrineFormacaoRequest $request){
    $check = $this->__update($request);
    if (!$check['status']) {
        return response()->json(['errors' => $check['errors']], $check['code']);
    }

    $_new = (object) $request->all();

    $res = \DB::table('vitrine_category')->where('id',$id)->update([
      "curso"=>$_new->form_curso,
      "area"=>$_new->form_area,
      "instituicao"=>$_new->form_instituicao,
      "inicio"=>$_new->form_inicio,
      "fim"=>optional($_new)->form_fim,
    ]);


    return response()->json(['success' => $res]);
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


  public function formacaoDelete($id){
    $check = $this->__delete();
    if (!$check['status']) {
        return response()->json(['errors' => $check['errors']], $check['code']);
    }

    $ret = \DB::table('vitrine_category')->where('id',$id)->delete();
    return json_encode(['sts' => $ret]);
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
    return json_encode(Vitrine::select('razaosocial as n', 'cpf as k')->where('razaosocial', 'like', "%$query")->get());
  }
}
