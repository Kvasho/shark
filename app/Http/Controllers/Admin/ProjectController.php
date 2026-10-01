<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProjectRequest;
use App\Models\Project;
use App\Models\ProjectMedia;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProjectController extends Controller
{
    /**
     * პროექტების გვერდი: სია და დამატების ფორმა.
     */
    public function index(): View
    {
        return view('admin.pages.projects', [
            'projects' => Project::withCount([
                'media as images_count' => fn ($query) => $query->where('type', ProjectMedia::IMAGE),
                'media as videos_count' => fn ($query) => $query->where('type', ProjectMedia::VIDEO),
            ])->ordered()->get(),
        ]);
    }

    public function store(ProjectRequest $request): RedirectResponse
    {
        $data = $request->projectData();

        DB::transaction(function () use ($request, $data) {
            $project = Project::create([
                ...$data,
                'slug' => Project::uniqueSlug($data['title']),
                'cover' => $request->file('cover')->store('projects', 'public'),
            ]);

            $this->storeMedia($project, $request->file('media', []));
        });

        return redirect()
            ->route('admin.projects')
            ->with('success', 'პროექტი წარმატებით დაემატა.');
    }

    public function edit(Project $project): View
    {
        return view('admin.pages.projects.edit', [
            'project' => $project->load('media'),
        ]);
    }

    public function update(ProjectRequest $request, Project $project): RedirectResponse
    {
        $data = $request->projectData();
        $removed = $project->media()->whereIn('id', $request->removeMediaIds())->get();
        $oldCover = null;

        if ($request->hasFile('cover')) {
            $oldCover = $project->cover;
            $data['cover'] = $request->file('cover')->store('projects', 'public');
        }

        DB::transaction(function () use ($request, $project, $data, $removed) {
            $project->update($data);
            $project->media()->whereKey($removed->modelKeys())->delete();
            $this->storeMedia($project, $request->file('media', []));
        });

        // ფაილები იშლება მხოლოდ ბაზის წარმატებით განახლების შემდეგ.
        Storage::disk('public')->delete(array_filter([$oldCover, ...$removed->pluck('path')]));

        return redirect()
            ->route('admin.projects')
            ->with('success', 'პროექტი განახლდა.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        $paths = [$project->cover, ...$project->media()->pluck('path')];

        $project->delete();

        Storage::disk('public')->delete($paths);

        return redirect()
            ->route('admin.projects')
            ->with('success', 'პროექტი წაიშალა.');
    }

    /**
     * @param  array<UploadedFile>  $files
     */
    private function storeMedia(Project $project, array $files): void
    {
        $order = (int) $project->media()->max('sort_order');

        foreach ($files as $file) {
            $project->media()->create([
                'type' => ProjectRequest::isImage($file) ? ProjectMedia::IMAGE : ProjectMedia::VIDEO,
                'path' => $file->store('projects/gallery', 'public'),
                'sort_order' => ++$order,
            ]);
        }
    }
}
