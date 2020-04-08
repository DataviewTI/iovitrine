@php
  use Dataview\IntranetOne\IntranetOne;
  use Dataview\IntranetOne\Category;

  $serv = \DB::table('services')->where('service','Vitrine')->value('id');

  $grauId = Category::where('service_id',$serv)->where('category','grau')->whereNull('category_id')->value('id');
  $areaId = Category::where('service_id',$serv)->where('category','area')->whereNull('category_id')->value('id');

  $grau = Category::select('id','category_id','category')->where('category_id',$grauId)->get();
  $area = Category::select('id','category_id','category')->where('category_id',$areaId)->get();
@endphp

	<div class = 'row dt-filters-container mb-2'>
		<div class="col-sm-4 col-xs-12">
			<div class="form-group">
        <label for = 'subtitulo' class = 'bmd-label-static'><i class = 'ico ico-filter'></i> Nome, CPF ou email</label>
        <input type = 'text' class = 'form-control form-control-lg' name ='ft_search' id = 'ft_search' />
			</div>
		</div>
    <div class="col-md-6 col-sm-12">
      <div class = 'row'>
        <div class="col-md-4 col-sm-12">
          <div class="form-group">
            <label for = 'ft_formacao' class = 'bmd-label-static'><i class = 'ico ico-filter'></i> Formação</label>
            <select id = 'ft_formacao' class = 'form-control form-control-lg'>
              <option value = ''>Todas</option>
                @foreach($grau as $o)
                  <option value="{{$o->id}}">{{$o->category}}</option>
                @endforeach
            </select>
          </div>
        </div>   
        <div class="col-md-4 col-sm-12">
          <div class="form-group">
            <label for = 'ft_instituicao' class = 'bmd-label-static'><i class = 'ico ico-filter'></i> Instituição</label>  
            <input type = 'text' name = 'ft_instituicao' id = 'ft_instituicao' class = 'form-control form-control-lg'>
          </div>
        </div>
        <div class="col-md-4 col-sm-12">
          <div class="form-group">
            <label for = 'ft_curso' class = 'bmd-label-static'><i class = 'ico ico-filter'></i> Curso</label>  
            <input type = 'text' name = 'ft_curso' id = 'ft_curso' class = 'form-control form-control-lg'>
          </div>
        </div>
      </div>
    </div>

		{{-- <div class="col-md-3 col-sm-12">
			<div class = 'row'>
				<div class="col-md-6 col-sm-12">
          <div class="form-group">
            <label for = 'ft_dtini' class = 'bmd-label-static'><i class = 'ico ico-filter'></i> Data Inicial</label>  
            <input type = 'text' name = 'ft_dtini' id = 'ft_dtini' class = 'form-control form-control-lg'>
          </div>
        </div>
        <div class="col-md-6 col-sm-12">
          <div class="form-group">
          <label for = 'ft_dtfim' class = 'bmd-label-static'><i class = 'ico ico-filter'></i> Data Final</label>  
          <input type = 'text' name = 'ft_dtfim' id = 'ft_dtfim' class = 'form-control form-control-lg'>
          </div>
        </div>
			</div>
		</div>     --}}

  </div>
	@component('IntranetOne::io.components.datatable',[
	"_id" => "default-table",
	"_columns"=> [
			["title" => "#"],
			["title" => "Formação"],
			["title" => "Instituição"],
			["title" => "Curso"],
			["title" => "data"],
			["title" => "Nome"],
			["title" => "cpf"],
			["title" => "Email"],
			["title" => "Celular"],
			["title" => "Ações"]
		]
	])
@endcomponent