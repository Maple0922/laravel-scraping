<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Media;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function __construct(Media $media, Article $article)
    {
        $this->media = $media;
        $this->article = $article;
    }

    public function index()
    {
        return $this->media->all();
    }

    public function articles(int $mediaId)
    {
        return $this->media->find($mediaId)->articles;
    }
}
