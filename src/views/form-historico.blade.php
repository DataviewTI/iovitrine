<form action = '/admin/vitrine/history/create' id='hist-form' method = 'post' class = 'form-fit'>
  @component('IntranetOne::io.components.wizard',[
    "_id" => "hist-wizard",
    "_min_height"=>"435px",
    "_steps"=> [
        ["name" => "Dados Gerais", "view"=> "Vitrine::form-hist.form-general-hist"],
      ]
  ])
  @endcomponent
</form>