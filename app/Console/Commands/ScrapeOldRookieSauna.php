<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use Illuminate\Support\Str;

class ScrapeOldRookieSauna extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ScrapeOldRookieSauna';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'オールドルーキーサウナ渋谷ハチ公口店の人数をスクレイピングして通知する';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    const STORE = [
        "六本木通り店",
        "渋谷ハチ公口店",
        "新宿駅新南口店"
    ];

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $baseUrl = "https://oldrookiesauna.com/get_current_user.php";

        $client = new Client();
        $response = $client->request('GET', $baseUrl);
        $json = $response->getBody()->getContents();
        $str = json_decode($json)->str;
        $text = $this->generate($str);

        $count = 0;
        $color = $this->color($count);

        $this->send($text, $color);
    }

    private function generate($str)
    {
        $str = collect(explode("<div>", $str))
            ->filter(fn ($_, $key) => in_array($key, [3, 6, 9]))
            ->values()
            ->map(fn ($item) => Str::before(Str::afterLast($item, "お客様"), "名"))
            ->map(
                fn ($count, $index) =>
                Str::contains($count, '営業時間外')
                    ? self::STORE[$index] . "：" . "営業時間外"
                    : self::STORE[$index] . "：" . $count . "人"
            )
            ->push("https://oldrookiesauna.com")
            ->implode(PHP_EOL);

        return $str;
    }

    private function color(?int $count): string
    {
        return "#ab0015";
    }

    public function send(string $text, string $color): void
    {
        $message = [
            "username" => "オールドルーキーサウナ",
            "icon_emoji" => ":old_rookie_sauna:",
            "attachments" => array(array("text" => $text, "color" => $color))
        ];

        \Log::channel('single')->emergency($text);

        $url = "https://hooks.slack.com/services/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";

        $messageJson = json_encode($message);
        $messagePost = "payload=" . urlencode($messageJson);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $messagePost);
        curl_exec($ch);
        curl_close($ch);
    }
}
