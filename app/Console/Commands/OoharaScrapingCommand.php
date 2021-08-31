<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Log;
use Goutte\Client;

class OoharaScrapingCommand extends Command
{
    protected $signature = 'scrape:oohara {url}';

    protected $description = 'URLからその講義の動画一覧をダウンロードします';

    const BASE_URL = "https://goukakuweb3.o-hara.ac.jp";

    const LOGIN_OPTIONS = [
        "Code" => 6000673,
        "Login_Password" => 'qqqqqqq1',
        "Type_System_Site" => 1,
        "Is_MemoryToLogin" => false
    ];

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $accessPageUrl = $this->argument('url');
        $loginPageUrl = self::BASE_URL;
        $client = new Client;
        $loginPage = $client->request('GET', $loginPageUrl);

        // $form = $loginPage->filter('form')->form();
        $form = $loginPage->selectButton('Login')->form();

        // foreach (self::LOGIN_OPTIONS as $property => $value) {
        //     $form[$property] = $value;
        // }
        // $form['Code'] = 6000673;
        // $form['Login_Password'] = 'qqqqqqqq1';
        // $loginButton = $loginPage->selectButton('Login')->form();

        $client->submit($form, self::LOGIN_OPTIONS);
        $clawler = $client->request('GET', $accessPageUrl);
        // $client->click($loginButton);
        // $client->request('POST', $loginPageUrl, self::LOGIN_OPTIONS);

        $response = $clawler->outerHtml();
        // $response = $clawler->filter('p')->first()->text();
        // $text = $response->filter('.dv_1')->first()->text();
        // $text = $response->filter('header > h1 > div')->first()->text();

        Log::info(print_r($response, true));
        // Log::info(print_r($a, true));
        return;
    }
}
