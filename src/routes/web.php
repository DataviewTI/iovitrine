<?php
Route::group(['prefix' => 'admin', 'middleware' => ['web','admin'], 'as' => 'admin.'],function(){
    Route::group(['prefix' => 'vitrine'], function () {
    Route::get('/','VitrineController@index');
    // Route::get('teste', 'VitrineController@teste');
    Route::get('list', 'VitrineController@list');
    Route::get('history/list/{id?}', 'VitrineController@historyList');
    Route::get('view/{id}', 'VitrineController@view');
    Route::post('create', 'VitrineController@create');
    Route::post('history/create', 'VitrineController@historyCreate');
    Route::post('update/{id}', 'VitrineController@update');
    Route::post('history/update/{id}', 'VitrineController@update');
    Route::get('history/delete/{eid}/{gid}', 'VitrineController@deleteHist');
    Route::get('delete/{id}', 'VitrineController@delete');			
  });
});
