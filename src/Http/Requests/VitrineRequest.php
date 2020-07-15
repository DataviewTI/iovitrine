<?php

namespace Dataview\IOVitrine;

use Dataview\IntranetOne\IORequest;
use Illuminate\Validation\Rule;

class VitrineRequest extends IORequest
{
  public function sanitize()
  {
    $input = parent::sanitize();
    if (isset($input['city_id'])) 
      $input['city_id'] = $input['city_id'];
  
    if (isset($input['cpf']))
      $input['cpf'] = preg_replace('/\D/', '', $input['cpf']);
  
    if (isset($input['zipCode']))
      $input['cep'] = preg_replace('/\D/', '', $input['zipCode']);

    if (isset($input['__dz_copy_params']))
      $input['sizes'] = $input['__dz_copy_params'];
    // $input['otica_id'] = $input['ori'];

    if (isset($input['dt_nascimento_submit']))
      $input['dt_nascimento'] = $input['dt_nascimento_submit'];

    $this->replace($input);
    return $input;
  }

    public function rules()
    {
      $san = $this->sanitize();

      if(!isset($san['isUpdate']))
        $san['isUpdate'] = false;


      $isUpdate = $san['isUpdate'] ? $san['isUpdate'] : null;

      return [
        'cpf' => 'required|unique:vitrines,cpf,'.$isUpdate.',id',
        'email' => 'required|unique:vitrines,email,'.$isUpdate.',id',
      ]; 
    }

    public function messages(){
      return [
        'cpf.unique' => 'CPF/CNPJ já existe para outro usuário',
        'email.unique' => 'Email já existe para outro usuário',
      ];
    }
}
