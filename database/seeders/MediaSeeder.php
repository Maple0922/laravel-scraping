<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MediaSeeder extends Seeder
{
    public function run()
    {
        $medias = [
            ['name' => 'MONEY GROWTH', 'url' => "https://www.maneo.jp/media/is-hoken-soudan-shop-osusume/"],
            ['name' => '保険のぜんぶ', 'url' => "https://hoken-all.co.jp/hoken/recommend/"],
            ['name' => "mybest", 'url' => "https://my-best.com/6972"],
            ['name' => "モノコレ", 'url' => "https://www.nishinippon.co.jp/monokore/hokensoudan-osusume/"],
            ['name' => "税理士が教えるお金の知識", 'url' => "https://chester-souzoku.com/money/3373/"],
            ['name' => "MONEYDOCTOR", 'url' => "https://fp-moneydoctor.com/media/hokensoudan-minaoshi/"],
            ['name' => "保険相談比較ランキング", 'url' => "https://www.three-com.jp/lp/rank-g/"],
            ['name' => "センターライン", 'url' => "https://www.lam-mpi.org/ranking"],
            ['name' => "生活金庫", 'url' => "https://www.chobirich.com/money/insurance-consultation/"],
            ['name' => "ほけんスタート", 'url' => "https://www.b-minded.com/hoken/hokensoudan-osusume/"]
        ];

        $mediaRecords = collect($medias)
            ->map(function ($media) {
                $media['created_at'] = Carbon::now();
                $media['updated_at'] = Carbon::now();
                return $media;
            });

        DB::table('medias')->insert($mediaRecords->toArray());
    }
}
