<?php
namespace Dataview\IOVitrine;

use Dataview\IntranetOne\Service;
use Dataview\IntranetOne\Category;
use Dataview\IOVitrine\Models\City;
use Dataview\IOVitrine\Models\Faculdade;
// use Dataview\IOVitrine\Models\Otica;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Dataview\IOVitrine\IOVitrineServiceProvider;
use Sentinel;
use Faker;
use Illuminate\Support\Str;

class VitrineSeeder extends Seeder
{
  public function run()
  {
    $faker = Faker\Factory::create('pt_BR');
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
    else
      $service = Service::where('service', 'Vitrine')->first();

      $pravatar=[
        "male"=>[3,4,6,7,8,11,12,13,14,15,17,18,33,50,51,52,53,54,55,56,57,58,59,60,61,63,64,65,66,67,68,69,70],
        "female"=> [1,2,5,9,10,16,19,20,21,22,23,24,25,26,27,28,29,30,31,32,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,62]
      ];
      
            //seta privilegios padrão para o role admin
      $adminRole = Sentinel::findRoleBySlug('admin');
      $adminRole->addPermission('vitrine.view');
      $adminRole->addPermission('vitrine.create');
      $adminRole->addPermission('vitrine.update');
      $adminRole->addPermission('vitrine.delete');
      $adminRole->save();
      

      $graduacao = ["Administração","Ciências Contábeis","Direito","Ciência da Computação","Pedagogia","Letras","Arquitetura","Física","Educação Física","Quimica","Medicina","Enfermagem","Odontologia","Farmácia","Sistemas de Informação","Publicidade","Jornalismo","Designer","Moda","Artes","Bioquimica","Arqueologia","Agronomia","Artes Visuais","Biblioteconomia","Biologia","Economia","Engenharia Cívil","Engenharia Ambiental","Engenharia Química","Engenharia Elétrica","Engenharia de Pesca","Engenharia de Alimentos","Estatística","Matemática","Fisioterapia","Filosofia","Psicologia","Relações Internacionais","Turismo", "Teatro","Zootecnia","Sistemas de Informação","Serviço Social"];

      $pos = ["Auditoria","Gerência Contábil","Design Gráfico","Redes de Computadores","Engenharia Social","Engenharia de Software","Direito Digital","Direito na Internet","Assessoria de Comunicação","Disfagia","Arquitetura de Interiores","Direito Civil","Direito Penal","Direito Internacional","Computação Forense","Planejamento Tributário","Processo Eletitoral","Design de Jogos","Estatística Esportiva","Mecânica de Flúidos","Inteligência Artificial","Sustentabilidade","Políticas Públicas","Paisagismo","Perícias Médicas","Sexualidade Humana","Manutenção Industrial","Ciência de Dados","Excelência Operacional","Marketing","Gestão Financeira","Psicologia Analítica","Saúde Coletiva","Psicologia Analítica","Matemática","Física","Química","Língua Portuguesa","Física Quantica","Gestão Comercial","Big Data","Integridade Corporativa","Psicomotricidade","Neurociências","Gestão de Conflitos","Novas Mídias","Epdemiologia","Fisioteraia Respiratória","Eficiência Energética"];


      // \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
      // \DB::table('cities')->truncate();
      // // \DB::table('oticas')->truncate();
      // \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

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
            'category_slug' => Str::slug($c['name']),
            'order'=>$i++,
            'erasable'=>false
          ]);
          $j=0;
          foreach($c['subc'] as $sc){
            Category::create([
                'category' => $sc,
                'service_id' => $service->id,
                'category_id' => $cat->id,
                'category_slug' => Str::slug($sc),
                'order'=>$j++,
                'erasable'=>false
              ]);
          }
      }


      //Criar seeds de teste

      //Criando providers de teste
      for($i=0;$i<55;$i++){
        
        $midName = $faker->boolean(30) ? $faker->lastName.' ' : '';
        $gender = $faker->boolean(60) ? 'female' : 'male';
      
        $obj = new Vitrine([
          "nome"=> $name = "{$faker->firstName($gender)} {$midName}{$faker->lastName}",
          "cpf" => $faker->unique()->cpf(false),
          "sexo" => $gender === 'female' ? 'F' : 'M',
          "dt_nascimento" => $faker->dateTimeBetween($startDate = '-70 years', $endDate = '-18 years', $timezone = null),
          "celular1"=> $faker->unique()->cellphoneNumber,
          "celular2"=> $faker->boolean(50) ? $faker->unique()->cellphoneNumber : null,
          "email"=> $faker->unique()->email,
          "zipCode"=>"77410-010",
          "address" => "{$faker->streetAddress}",
          "address2"=>"{$faker->streetName}",
          "city_id"=> City::inRandomOrder()->take(1)->value('id'),
          "resumo" =>  $faker->text
        ]);

        $img_sizes = [
          "original"=>false,
          "sizes"=>[
              "thumb"=>["w"=>180,"h"=>180],
            ]
        ];

        $obj->setAppend("sizes",json_encode($img_sizes));
        $obj->setAppend("hasImages",true);
        $obj->setAppend("service_id",$service->id);

        $obj->save();


         //add some
        if(filled($obj)){
          //add images
          $tmp_name = tempnam(sys_get_temp_dir(),'dz');
          $stream = file_get_contents("https://i.pravatar.cc/300?img={$faker->unique()->randomElement($pravatar[$gender])}");
          $file = file_put_contents($tmp_name,$stream);

          $imgs = json_encode([(object)[
              "name"=>$faker->bothify('imgseed_#?#?#?#?.jpg'),
              "tmp"=>$tmp_name,
              "data"=>[
                "caption"=>null,
                "details"=>null,
              ],
              "mimetype"=>"image/jpeg",
              "id"=>null,
              "order"=>1
            ]]);

          $obj->group->manageImages(json_decode($imgs),$img_sizes);
          $obj->save();            




          //graduação

          $area_id = Category::where('category','area')
            ->whereNull('category_id')
            ->where('service_id',$service->id)
            ->take(1)
            ->value('id');
          

          $graduacao_id = Category::where('category','Graduação')
            ->where('service_id',$service->id)
            ->take(1)
            ->value('id');

          $ini = rand(1950,2018);

          $fim = $ini+($faker->boolean(80) ? 4 : 5);

          $obj->formacao()->attach($graduacao_id,[
            "area"=>Category::whereCategoryId($area_id)->inRandomOrder()->take(1)->value('id'),
            "curso"=>$faker->randomElement($graduacao),
            "instituicao"=>Faculdade::inRandomOrder()->take(1)->value('faculdade'),
            "inicio"=>$ini,
            "fim"=>$fim,
          ]);

          //2% de chance de 1 segunda graduação
          if($faker->boolean(2)) {

            $ini = $fim+($faker->boolean(50) ? 2 : 3);

            $fim = $ini+($faker->boolean(80) ? 4 : 5);

            $obj->formacao()->attach($graduacao_id,[
              "area"=>Category::whereCategoryId($area_id)->inRandomOrder()->take(1)->value('id'),
              "curso"=>$faker->randomElement($graduacao),
              "instituicao"=>Faculdade::inRandomOrder()->take(1)->value('faculdade'),
              "inicio"=>$ini,
              "fim"=>$fim
            ]);
          }


          //80% de chance de especialização
          if($faker->boolean(80)) {

            $especializacao_id = Category::where('category','Especialização')
              ->where('service_id',$service->id)
              ->take(1)
              ->value('id');

              $ini = $fim+($faker->boolean(50) ? 0 : 1);
              $fim = $ini+($faker->boolean(80) ? 1 : 2);

              $obj->formacao()->attach($especializacao_id,[
                "area"=>Category::whereCategoryId($area_id)->inRandomOrder()->take(1)->value('id'),
                "curso"=>$faker->randomElement($pos),
                "instituicao"=>Faculdade::inRandomOrder()->take(1)->value('faculdade'),
                "inicio"=>$ini,
                "fim"=>$fim
              ]);

              //10% de chance de uma seguna especialização
              if($faker->boolean(10)) {
                $ini = $fim+($faker->boolean(50) ? 0 : 1);
                $fim = $ini+($faker->boolean(80) ? 1 : 2);

                $obj->formacao()->attach($especializacao_id,[
                  "area"=>Category::whereCategoryId($area_id)->inRandomOrder()->take(1)->value('id'),
                  "curso"=>$faker->randomElement($pos),
                  "instituicao"=>Faculdade::inRandomOrder()->take(1)->value('faculdade'),
                  "inicio"=>$ini,
                  "fim"=>$fim
                ]);
              }


          //10% de chance de mestrado
          if($faker->boolean(10)) {

            $mestrado_id = Category::where('category','Mestrado')
              ->where('service_id',$service->id)
              ->take(1)
              ->value('id');

              $ini = $fim+($faker->boolean(50) ? 0 : 1);
              $fim = $ini+($faker->boolean(80) ? 3 : 4);

              $obj->formacao()->attach($mestrado_id,[
                "area"=>Category::whereCategoryId($area_id)->inRandomOrder()->take(1)->value('id'),
                "curso"=>$faker->randomElement($pos),
                "instituicao"=>Faculdade::inRandomOrder()->take(1)->value('faculdade'),
                "inicio"=>$ini,
                "fim"=>$fim
              ]);


                //3% de chance de doutorado
                if($faker->boolean(10)) {

                  $doutorado_id = Category::where('category','Doutorado')
                    ->where('service_id',$service->id)
                    ->take(1)
                    ->value('id');

                    $ini = $fim+($faker->boolean(50) ? 2 : 3);
                    $fim = $ini+($faker->boolean(80) ? 2 : 3);

                    $obj->formacao()->attach($doutorado_id,[
                      "area"=>Category::whereCategoryId($area_id)->inRandomOrder()->take(1)->value('id'),
                      "curso"=>$faker->randomElement($pos),
                      "instituicao"=>Faculdade::inRandomOrder()->take(1)->value('faculdade'),
                      "inicio"=>$ini,
                      "fim"=>$fim
                    ]);

                }
          
            }
          }




          $obj->save();
          


          // $main = $faker->randomElement($cats);
          // $prov->categories()->attach($main);

          // $subcats = Category::where('service_id', $service->id)
          //     ->where('category_id',$main)
          //     ->pluck('id');

          // for($y=0;$y<3;$y++){
          //   $prov->categories()->attach($faker->randomElement($subcats));
          // }
        }       
    }
  }
}
