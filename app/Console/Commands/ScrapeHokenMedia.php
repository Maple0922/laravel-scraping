<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Media;
use App\Models\Article;
use Carbon\Carbon;
use Goutte\Client;

class ScrapeHokenMedia extends Command
{
    protected $signature = 'scrape:hoken';

    protected $description = '保険相談 おすすめで出てくる10記事の内容を持ってきてDBに保存する';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): void
    {
        Media::get()
            ->each(function ($media) {
                Article::create([
                    'media_id' => $media->id,
                    'text' => $this->filterText($media->id),
                    'html' => $this->filterHtml($media->id)
                ]);
            });
    }

    public function filterText(int $mediaId): string
    {
        $media = Media::find($mediaId);

        switch ($media->name) {
            case "MONEY GROWTH":
                return $this->joinTextInSelectors($media->url, [
                    'body main article header',
                    'body main article section'
                ]);

            case "保険のぜんぶ":
                return $this->joinTextInSelectors($media->url, ['body main article']);

            case "mybest":
                return $this->joinTextInSelectors($media->url, ['.l-column__main']);

            case "モノコレ":
                return $this->joinTextInSelectors($media->url, [
                    'body main article header',
                    'body main article section'
                ]);

            case "税理士が教えるお金の知識":
                return $this->joinTextInSelectors($media->url, ['body main']);

            case "MONEYDOCTOR":
                return $this->joinTextInSelectors($media->url, ['article']);

            case "保険相談比較ランキング":
                return $this->joinTextInSelectors($media->url, [
                    '#main',
                    '#main2',
                    '#main3',
                    '#section10',
                    '#tsuika01'
                ]);

            case "センターライン":
                return $this->joinTextInSelectors($media->url, ['#content']);

            case "生活金庫":
                return $this->joinTextInSelectors($media->url, [
                    'article header',
                    'article section'
                ]);

            case "ほけんスタート":
                return $this->joinTextInSelectors($media->url, [
                    'article header',
                    'article section'
                ]);
        }
    }

    private function filterHtml(int $mediaId): string
    {
        $media = Media::find($mediaId);
        $client = new Client();
        $crawler = $client->request('GET', $media->url);

        return $crawler->filter('body')->html();
    }

    private function joinTextInSelectors(string $url, array $selectors): string
    {
        $client = new Client();
        $crawler = $client->request('GET', $url);

        return collect($selectors)
            ->map(function ($selector) use ($crawler) {
                return $crawler->filter($selector)->text();
            })
            ->implode(PHP_EOL);
    }
}
