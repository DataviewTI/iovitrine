<?php

Route::group(['prefix' => 'vitrine'], function () {
  Route::get('list-frontend', 'VitrineController@listFrontEnd');
  Route::post('create-frontend', 'VitrineController@createFrontEnd');
});	


Route::group(['prefix' => 'admin', 'middleware' => ['web','admin'], 'as' => 'admin.'],function(){
    Route::group(['prefix' => 'vitrine'], function () {
    Route::get('/','VitrineController@index');
    Route::get('list', 'VitrineController@list');
    Route::get('list-frontend', 'VitrineController@listFrontEnd');
    Route::get('view/{id}', 'VitrineController@view');
    Route::post('create', 'VitrineController@create');
    Route::post('update/{id}', 'VitrineController@update');
    Route::get('delete/{id}', 'VitrineController@delete');			

    Route::post('formacao/update/{id}', 'VitrineController@formacaoUpdate');
    Route::get('formacao/view/{id}', 'VitrineController@formacaoView');
    Route::post('formacao/create', 'VitrineController@formacaoCreate');
    Route::get('formacao/list/{id?}', 'VitrineController@formacaoList');
    Route::get('formacao/delete/{id}', 'VitrineController@formacaoDelete');
  });
});

Route::group(['prefix' => 'admin', 'middleware' => ['web','admin'], 'as' => 'admin.'],function(){
    Route::group(['prefix' => 'vitrine'], function () {
    Route::get('/','VitrineController@index');
    Route::get('list', 'VitrineController@list');
    Route::get('list-frontend', 'VitrineController@listFrontEnd');
    Route::get('view/{id}', 'VitrineController@view');
    Route::post('create', 'VitrineController@create');
    Route::post('update/{id}', 'VitrineController@update');
    Route::get('delete/{id}', 'VitrineController@delete');			

    Route::post('formacao/update/{id}', 'VitrineController@formacaoUpdate');
    Route::get('formacao/view/{id}', 'VitrineController@formacaoView');
    Route::post('formacao/create', 'VitrineController@formacaoCreate');
    Route::get('formacao/list/{id?}', 'VitrineController@formacaoList');
    Route::get('formacao/delete/{id}', 'VitrineController@formacaoDelete');
  });
});
