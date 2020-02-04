<form action = '/admin/vitrine/formacao/create' id='form-form' method = 'post' class = 'form-fit'>
  @component('IntranetOne::io.components.wizard',[
    "_id" => "form-wizard",
    "_min_height"=>"435px",
    "_steps"=> [
        ["name" => "Dados Gerais", "view"=> "Vitrine::form-form.form-general-form"],
      ]
  ])
  @endcomponent
</form>