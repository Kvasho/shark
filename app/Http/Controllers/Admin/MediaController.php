<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MediaPhotoRequest;
use App\Models\MediaPhoto;
use App\Models\MediaVideo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class MediaController extends Controller
{
    /**
     * მედიის გვერდი: ფოტო გალერეა და ვიდეოები.
     */
    public function index(): View
    {
        return view('admin.pages.media', [
            'photos' => MediaPhoto::latestFirst()->get(),
            'videos' => MediaVideo::ordered()->get(),
        ]);
    }

    public function storePhotos(MediaPhotoRequest $request): RedirectResponse
    {
        $files = $request->file('photos');

        foreach ($files as $file) {
            MediaPhoto::create(['path' => $file->store('media/photos', 'public')]);
        }

        return redirect()
            ->to(route('admin.media') . '#photos')
            ->with('success', 'დაემატა ' . count($files) . ' ფოტო.');
    }

    /**
     * მონიშნული ფოტოების წაშლა (რამდენიმე ერთად).
     */
    public function destroyPhotos(Request $request): RedirectResponse
    {
        $ids = array_map('intval', (array) $request->input('photos', []));
        $photos = MediaPhoto::whereKey($ids)->get();

        if ($photos->isEmpty()) {
            return redirect()->to(route('admin.media') . '#photos');
        }

        MediaPhoto::whereKey($photos->modelKeys())->delete();
        Storage::disk('public')->delete($photos->pluck('path')->all());

        return redirect()
            ->to(route('admin.media') . '#photos')
            ->with('success', 'წაიშალა ' . $photos->count() . ' ფოტო.');
    }
}
