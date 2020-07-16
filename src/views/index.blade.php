@extends('IntranetOne::io.layout.dashboard')

{{-- page level styles --}}
@section('header_styles')
  <link rel="stylesheet" type="text/css" href="{{ asset('io/services/io-vitrine.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('css/pickadate-full.min.css') }}">
@stop

@section('main-content')
    @component('IntranetOne::io.components.nav-tabs',
    [
      "_id" => "default-tablist",
      "_active"=>0,
      "_tabs"=> [
        [
          "tab"=>"Listar",
          "icon"=>"ico ico-list",
          "view"=>"Vitrine::table-list",
          "display"=>false,
        ],
        [
          "tab"=>"Cadastrar",
          "icon"=>"ico ico-new",
          "view"=>"Vitrine::form"
        ],
        [
          "tab"=>"Formação Acadêmica",
          "icon"=>"ico ico-history",
          "view"=>"Vitrine::form-formacao"
        ],      
      ]
    ])
    @endcomponent
@stop
@section('footer_scripts')
<script src="{{ asset('js/pickadate-full.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/optimized_cities.js') }}" charset="ISO-8859-1" type="text/javascript"></script>

<script src="{{ asset('io/services/io-vitrine.min.js') }}"></script>
<script src="{{ asset('io/services/io-vitrine-mix.min.js') }}"></script>
@stop
