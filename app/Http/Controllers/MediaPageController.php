<?php

namespace App\Http\Controllers;

use App\Models\MediaPhoto;
use App\Models\MediaVideo;
use Illuminate\View\View;

class MediaPageController extends Controller
{
    public function __invoke(): View
    {
        return view('user.pages.media', [
            'photos' => MediaPhoto::latestFirst()->get(),
            'videos' => MediaVideo::active()->ordered()->get(),
        ]);
    }
}
