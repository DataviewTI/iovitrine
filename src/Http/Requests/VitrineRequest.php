<?php

namespace Dataview\IOVitrine;

use Dataview\IntranetOne\IORequest;
use Illuminate\Validation\Rule;

class VitrineRequest extends IORequest
{
  public function sanitize()
  {
    $input = parent::sanitize();
    $input['city_id'] = $input['city_id'];
  
    if (isset($input['cpf_cnpj']))
      $input['cpf_cnpj'] = preg_replace('/\D/', '', $input['cpf_cnpj']);
  
    $input['cep'] = preg_replace('/\D/', '', $input['zipCode']);

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

      $isUpdate = $san['isUpdate'] ? $san['isUpdate'] : null;

      return [
        'cpf_cnpj' => 'required|unique:vitrines,cpf_cnpj,'.$isUpdate.',id',
        'cod_cliente' => 'required|unique:vitrines,cod_cliente,'.$isUpdate.',id',
      ]; 
    }

    public function messages(){
      return [
        'cpf_cnpj.unique' => 'CPF/CNPJ já existe para outro usuário',
        'cod_cliente.unique' => 'Código já existe para outro usuário',
      ];
    }
}
