<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MediaVideoRequest;
use App\Models\MediaVideo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class MediaVideoController extends Controller
{
    public function store(MediaVideoRequest $request): RedirectResponse
    {
        MediaVideo::create([
            ...$request->videoData(),
            'path' => $request->file('video')->store('media/videos', 'public'),
        ]);

        return redirect()
            ->to(route('admin.media') . '#videos')
            ->with('success', 'ვიდეო წარმატებით დაემატა.');
    }

    public function edit(MediaVideo $video): View
    {
        return view('admin.pages.media-videos.edit', compact('video'));
    }

    public function update(MediaVideoRequest $request, MediaVideo $video): RedirectResponse
    {
        $data = $request->videoData();

        if ($request->hasFile('video')) {
            $oldPath = $video->path;
            $data['path'] = $request->file('video')->store('media/videos', 'public');
        }

        $video->update($data);

        if (isset($oldPath)) {
            Storage::disk('public')->delete($oldPath);
        }

        return redirect()
            ->to(route('admin.media') . '#videos')
            ->with('success', 'ვიდეო განახლდა.');
    }

    public function destroy(MediaVideo $video): RedirectResponse
    {
        $video->delete();

        Storage::disk('public')->delete($video->path);

        return redirect()
            ->to(route('admin.media') . '#videos')
            ->with('success', 'ვიდეო წაიშალა.');
    }
}
