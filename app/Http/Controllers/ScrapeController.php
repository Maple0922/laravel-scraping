<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Media;
use App\Models\Article;
use Illuminate\Support\Facades\Artisan;

class ScrapeController extends Controller
{
    public function __construct(Media $media, Article $article)
    {
        $this->media = $media;
        $this->article = $article;
    }

    public function run()
    {
        Artisan::call('scrape:hoken');
        return;
    }
}
