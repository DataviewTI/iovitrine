<?php

namespace Dataview\IOVitrine;

use Illuminate\Support\ServiceProvider;

class IOVitrineServiceProvider extends ServiceProvider
{
  public static function pkgAddr($addr){
    return __DIR__.'/'.$addr;
  }

  public function boot(){
    $this->loadViewsFrom(__DIR__.'/views', 'Vitrine');
  }

  public function register(){
  $this->commands([
    Console\Install::class,
    Console\Remove::class
  ]);

  $this->app['router']->group(['namespace' => 'dataview\iovitrine'], function () {
    include __DIR__.'/routes/web.php';
  });
  
    $this->app->make('Dataview\IOVitrine\VitrineController');
    $this->app->make('Dataview\IOVitrine\VitrineRequest');
    $this->app->make('Dataview\IOVitrine\VitrineHistoryRequest');
  }
}
