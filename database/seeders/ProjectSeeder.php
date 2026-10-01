<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\ProjectMedia;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProjectSeeder extends Seeder
{
    /**
     * საწყისი 4 პროექტი (ადრე ProjectController-ში ეწერა) — სამივე ენაზე.
     * ეშვება მხოლოდ მაშინ, როცა ცხრილი ცარიელია — ადმინში შეცვლილს არ გადააწერს.
     */
    public function run(): void
    {
        if (Project::exists()) {
            $this->command?->info('პროექტები უკვე არსებობს — გამოტოვებულია.');

            return;
        }

        $projects = require __DIR__ . '/data/projects.php';

        foreach ($projects as $data) {
            $project = Project::create([
                ...collect($data)->except(['cover', 'gallery'])->all(),
                'cover' => $this->copyFromPublic($data['cover'], 'projects'),
            ]);

            foreach ($data['gallery'] as $index => $file) {
                $project->media()->create([
                    'type' => ProjectMedia::IMAGE,
                    'path' => $this->copyFromPublic($file, 'projects/gallery'),
                    'sort_order' => $index + 1,
                ]);
            }
        }

        $this->command?->info('დაემატა ' . count($projects) . ' პროექტი.');
    }

    /**
     * public/ საქაღალდის ფაილის ასლი storage-ში, რომ ადმინიდან წაშლისას ორიგინალი არ დაზიანდეს.
     */
    private function copyFromPublic(string $file, string $directory): string
    {
        $path = $directory . '/' . Str::random(40) . '.' . pathinfo($file, PATHINFO_EXTENSION);
        Storage::disk('public')->put($path, file_get_contents(public_path($file)));

        return $path;
    }
}
