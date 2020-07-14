@php
  use Dataview\IntranetOne\IntranetOne;
  // use Dataview\IOVitrine\Models\Otica;
  // $ecivil = IntranetOne::getEnumValues('vitrines');
  // $oticas = Otica::select('id','alias','main','name')->orderBy('main')->orderBy('alias')->get();
  // $situacao = IntranetOne::getEnumValues('vitrine','status');
@endphp

<div class = 'row'>
  <div class="col-xs-12 col-sm-2">
    <div class = 'row d-flex'>
      <div class="col-sm-12 d-flex p-0">
          <div id = 'custom-dropzone' class = 'entity-dz w-100 d-flex justify-content-center dz-drop-files-here flex-wrap dropzone'>
          </div>
          @include('IntranetOne::io.components.dropzone.dropzone-infos-modal-default',[])
        <input type = 'hidden' id = 'hasImages' name='hasImages' value='0' />
      </div>
    </div>
  </div>
  <div class="col-xs-12 col-sm-10 px-0 pl-3">
    <div class = 'row'>
      <div class="col-sm-8 col-xs-12">
        <div class = 'row'>
          <div class="col-sm-3 col-xs-12">
            <div class="form-group">
              <label for='cpf_cnpj'>CPF</label>
              <input type="text" id='cpf' name='cpf' class = 'form-control input-lg' />
            </div>
          </div>
          <div class="col-sm-9 col-xs-12">
            <div class="form-group">
              <label for='nome_fantasia'>Nome Completo</label>
              <input type="text" id='nome' name='nome' class = 'form-control input-lg' />
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-4 col-xs-12">
        <div class = 'row my-0'>
          <div class="col-xs-12 col-sm-6">
            <div class="form-group selectContainer">
              <label for='sexo'>Sexo</label>
              <select name="sexo" id="sexo" class="form-control input-lg mt-1">
                <option value="F">Feminino</option>
                <option value="M">Masculino</option>
                <option value="I">Não Informado</option>
              </select>
            </div>
          </div>
          <div class="col-sm-6 col-xs-12">
            <div class="form-group">
              <label for='dt_nascimento'>Dt Nascimento</label>
              <input type="text" id='dt_nascimento' name='dt_nascimento' class = 'form-control input-lg' />
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class = 'row px-0 mx-0'>
      <div class="col-xs-12 w-100">
          @component('IntranetOne::io.components.nav-tabs',
          [
            "_id" => "aditional-data",
            "_active"=>0,
            '_controls'=>false,
            "_tabs"=> [
              [
                "tab"=>"Telefones e Endereço",
                "icon"=>"ico ico-business-card",
                "view"=>"Vitrine::form-parts.address",
                "params"=>[
                ],
              ],
              [
                "tab"=>"Resumo",
                "icon"=>"ico ico-talking",
                "view"=>"Vitrine::form-parts.resumo",
                "params"=>[
                ],
              ]            ]
          ])
          @endcomponent
      </div>
    </div>
  </div>

</div>

