<?php
namespace Dataview\IOVitrine\Console;

use Dataview\IntranetOne\Console\IOServiceRemoveCmd;
use Dataview\IntranetOne\IntranetOne;
use Dataview\IOVitrine\IOVitrineServiceProvider;


class Remove extends IOServiceRemoveCmd
{
  public function __construct(){
    parent::__construct([
      "service"=>"vitrine",
      "tables" =>['vitrine_group','vitrines','cities','oticas'],
    ]);
  }

  public function handle(){
    parent::handle();
  }
}
