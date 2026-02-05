<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;

class ItemSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $items = [
      [
        'name' => '腕時計',
        'price' => 15000,
        'brand' => 'Rolax',
        'condition' => '良好',
        'description' => 'スタイリッシュなデザインのメンズ腕時計',
        'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Armani+Mens+Clock.jpg'
      ],
      [
        'name' => 'HDD',
        'price' => 5000,
        'brand' => '西芝',
        'condition' => '目立った傷や汚れなし',
        'description' => '高速で信頼性の高いハードディスク',
        'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/HDD+Hard+Disk.jpg'
      ],
      [
        'name' => '玉ねぎ3束',
        'price' => 300,
        'brand' => 'null',
        'condition' => 'やや傷や汚れあり',
        'description' => '新鮮な玉ねぎ3束のセット',
        'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/iLoveIMG+d.jpg'
      ],
      [
        'name' => '革靴',
        'price' => 4000,
        'brand' => 'null',
        'condition' => '状態が悪い',
        'description' => 'クラシックなデザインの革靴',
        'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Leather+Shoes+Product+Photo.jpg'
      ],
      [
        'name' => 'ノートPC',
        'price' => 45000,
        'brand' => 'null',
        'condition' => '良好',
        'description' => '高性能なノートパソコン',
        'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Living+Room+Laptop.jpg'
      ],
      [
        'name' => 'マイク',
        'price' => 8000,
        'brand' => 'null',
        'condition' => '目立った傷や汚れなし',
        'description' => '高音質のレコーディング用マイク',
        'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Music+Mic+4632231.jpg'
      ],
      [
        'name' => 'ショルダーバッグ',
        'price' => 3500,
        'brand' => 'null',
        'condition' => 'やや傷や汚れあり',
        'description' => 'おしゃれなショルダーバッグ',
        'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Purse+fashion+pocket.jpg'
      ],
      [
        'name' => 'タンブラー',
        'price' => 500,
        'brand' => 'null',
        'condition' => '状態が悪い',
        'description' => '使いやすいタンブラー',
        'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Tumbler+souvenir.jpg'
      ],
      [
        'name' => 'コーヒーミル',
        'price' => 4000,
        'brand' => 'starbacks',
        'condition' => '良好',
        'description' => '手動のコーヒーミル',
        'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Waitress+with+Coffee+Grinder.jpg'
      ],
      [
        'name' => 'メイクセット',
        'price' => 2500,
        'brand' => 'null',
        'condition' => '目立った傷や汚れなし',
        'description' => '便利なメイクアップセット',
        'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/%E5%A4%96%E5%87%BA%E3%83%A1%E3%82%A4%E3%82%AF%E3%82%A2%E3%83%83%E3%83%95%E3%82%9A%E3%82%BB%E3%83%83%E3%83%88.jpg'
      ],
    ];

    foreach ($items as $item) {
      Item::updateOrCreate(
        ['name' => $item['name']],
        [
          'price' => $item['price'],
          'brand' => $item['brand'],
          'condition' => $item['condition'],
          'description' => $item['description'],
          'img_url' => $item['img_url'],
          'user_id' => 1,
        ]
      );
    }
  }
}
