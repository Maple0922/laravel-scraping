<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Log;
use Goutte\Client;

class ScrapeDisneyRestaurantCommand extends Command
{
    protected $signature = 'scrape:disney-restaurant';

    protected $description = 'ディズニーレストランの空き状況を確認してLINEに通知します';

    const BASE_URL = "https://reserve.tokyodisneyresort.jp/restaurant/calendar/?useDate=20210909&searchUseDate=20210909&adultNum=2&searchAdultNum=2&childNum=0&searchChildNum=0&stretcherCount=0&searchStretcherCount=0&wheelchairCount=0&searchWheelchairCount=0&nameCd=RBVL0&searchNameCd=&contentsCd=03&childAgeInform=&searchChildAgeInform=&mealDivList=3&searchMealDivList=3&restaurantType=2&searchRestaurantTypeList=2&searchKeyword=&reservationStatus=0";

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $url = self::BASE_URL;
        // $client = new Client;
        // $client->setServerParameters(['HTTP_USER_AGENT' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/92.0.4515.159 Safari/537.36']);
        // $client->setMaxRedirects(0);
        // $clawler = $client->request('GET', $url);

        // $status = $clawler->filter('.timeList.cf');
        // Log::info(print_r($status, true));

        // $status->each(function ($element) {
        //     echo $element->text() . "\n";
        // });

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, true);   // ヘッダーも出力する

        $response = curl_exec($curl);

        // header & body 取得
        $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE); // ヘッダサイズ取得
        $header = substr($response, 0, $header_size); // headerだけ切り出し
        $body = substr($response, $header_size); // bodyだけ切り出し

        // json形式で返ってくるので、配列に変換
        $result = json_decode($body, true);
        Log::info($result);

        // ヘッダから必要な要素を取得
        preg_match('/Total-Count: ([0-9]*)/', $header, $matches); // 取得記事要素数
        $total_count = $matches[1];

        curl_close($curl);

        return;
    }
}
