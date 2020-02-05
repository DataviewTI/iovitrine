<?php
namespace Dataview\IOVitrine;

use Dataview\IntranetOne\Service;
use Dataview\IntranetOne\Category;
use Dataview\IOVitrine\Models\City;
use Dataview\IOVitrine\Models\Otica;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Dataview\IOVitrine\IOVitrineServiceProvider;
use Sentinel;

class VitrineSeeder extends Seeder
{
  public function run()
  {
    //cria o serviço se ele não existe
    if (!Service::where('service', 'Vitrine')->exists()) {
        $service = Service::create([
            'service' => "Vitrine",
            'alias' => 'vitrine',
            'trans' => 'Professor',
            'ico' => 'ico-teacher',
            'description' => 'Cadastro do Professor',
            'order' => Service::max('order') + 1,
        ]);
    }
      //seta privilegios padrão para o role admin
      $adminRole = Sentinel::findRoleBySlug('admin');
      $adminRole->addPermission('vitrine.view');
      $adminRole->addPermission('vitrine.create');
      $adminRole->addPermission('vitrine.update');
      $adminRole->addPermission('vitrine.delete');
      $adminRole->save();
      

      \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
      \DB::table('cities')->truncate();
      // \DB::table('oticas')->truncate();
      \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

      // $oticas = [
      //   ["name"=>"Ótica Baroni", "alias"=> "Baroni", "main" => false],
      //   ["name"=>"Ótica Ceres", "alias"=> "Ceres", "main" => false],
      //   ["name"=>"Ótica Globo", "alias"=>"Globo", "main" => false],
      //   ["name"=>"Ótica Gurupi", "alias"=>"Gurupi", "main" => false],
      //   ["name"=>"Rio Ótica", "alias"=>"Rio Ótica","main" => true],
      //   ["name"=>"Ótica Vênus", "alias"=>"Vênus", "main" => false],
      //   ["name"=>"Ótica Visão", "alias"=>"Visão", "main" => false],
      // ];

      // foreach($oticas as $o){
      //    Otica::create([
      //       'name' => $o["name"],
      //       'alias' => $o["alias"],
      //       'main' => $o["main"],
      //     ]);
      // }

      // $json = File::get(IOVitrineServiceProvider::pkgAddr('/assets/src/cities.json'));
      // $data = json_decode($json, true);
      // foreach ($data as $obj) {
      //     City::create([
      //       'id' => $obj['i'],
      //       'city' => $obj['c'],
      //       'region' => $obj['u'],
      //     ]);
      // }

      //seed das categories

      $cats = [
        ["name"=>"area", "subc"=> [
          "Ciências Humanas",
          "Ciências Exatas",
          "Ciências Biológicas",
        ]],
        ["name"=>"grau", "subc"=> [
          "Graduação",
          "Especialização",
          "Mestrado",
          "Doutorado",
        ]]
      ];

      $i=0;
      foreach($cats as $c){
         $cat = Category::create([
            'category' => $c['name'],
            'service_id' => $service->id,
            'category_slug' => str_slug($c['name']),
            'order'=>$i++,
            'erasable'=>false
          ]);
          $j=0;
          foreach($c['subc'] as $sc){
            Category::create([
                'category' => $sc,
                'category_id' => $cat->id,
                'category_slug' => str_slug($sc),
                'order'=>$j++,
                'erasable'=>false
              ]);
          }
      }


  }
}
