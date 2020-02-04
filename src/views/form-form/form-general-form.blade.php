@php
  use Dataview\IntranetOne\IntranetOne;
  use Dataview\IntranetOne\Category;

  $serv = \DB::table('services')->where('service','Vitrine')->value('id');

  $grauId = Category::where('service_id',$serv)->where('category','grau')->whereNull('category_id')->value('id');
  $areaId = Category::where('service_id',$serv)->where('category','area')->whereNull('category_id')->value('id');
  

  $grau = Category::select('id','category_id','category')->where('category_id',$grauId)->get();
  $area = Category::select('id','category_id','category')->where('category_id',$areaId)->get();
@endphp

<div class = 'row'>
  <div class="col-xs-12 col-sm-8">
      @include('Vitrine::form-form.form-list')
  </div>
  <div class="col-xs-12 col-sm-4">
    <div class = 'row mb-2' id="user_name_container">
      <div class="col-sm-12 col-xs-12 px-3">
        <h6 class="my-auto py-2 mx-0 mb-2" style="border-bottom:1px #ccc solid"><span class="ico ico-user text-primary"></span><span class="ml-2 mt-1 text-primary" id="user_name">&nbsp;</span></h6>
      </div>
    </div>
    <div class = 'row'>
      <div class="col-sm-6 col-xs-12">
        <div class="form-group selectContainer">
          <label for='form_tipo'>Tipo</label>
          <select name="form_tipo" id="form_tipo" class="form-control input-lg mt-1">
            @foreach($grau as $o)
              <option value="{{$o->id}}">{{$o->category}}</option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="col-sm-6 col-xs-12">
        <div class="form-group selectContainer">
          <label for='form_area'>Área</label>
          <select name="form_area" id="form_area" class="form-control input-lg mt-1">
            @foreach($area as $a)
              <option value="{{$a->id}}">{{$a->category}}</option>
            @endforeach
          </select>
        </div>
      </div>
    </div>
    
    <div class = 'row'>
      <div class="col-xs-12 col-sm-12">
        <div class="form-group">
          <label for='form_curso'>Nome do curso</label>
          <input type="text" id='form_curso' name='form_curso' class = 'form-control input-lg' />
        </div>
      </div>
    </div>

    <div class = 'row'>
      <div class="col-xs-12 col-sm-12">
        <div class="form-group">
          <label for='form_instituicao'>Instituição</label>
          <input type="text" id='form_instituicao' name='form_instituicao' class = 'form-control input-lg' />
        </div>
      </div>
    </div>
    
    <div class = 'row'>
      <div class="col-sm-6 col-xs-12">
        <div class="form-group">
          <label for='form_inicio'>Data de Início</label>
          <input type="text" id='form_inicio' name='form_inicio' class = 'form-control input-lg'/>
        </div>
      </div>
      <div class="col-sm-6 col-xs-12">
        <div class="form-group">
          <label for='form_fim'>Data de Conclusão</label>
          <input type="text" id='form_fim' name='form_fim' class = 'form-control input-lg' />
        </div>
      </div>
    </div>

    <input type="hidden" id='maskvalue' />

{{-- </div> --}}