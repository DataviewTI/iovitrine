<?php
namespace Dataview\IOVitrine\Console;
use Dataview\IntranetOne\Console\IOServiceInstallCmd;
use Dataview\IOVitrine\IOVitrineServiceProvider;
use Dataview\IOVitrine\VitrineSeeder;

class Install extends IOServiceInstallCmd
{
  public function __construct(){
    parent::__construct([
      "service"=>"vitrine",
      "provider"=> IOVitrineServiceProvider::class,
      "seeder"=>VitrineSeeder::class,
    ]);
  }

  public function handle(){
    parent::handle();
  }
}
