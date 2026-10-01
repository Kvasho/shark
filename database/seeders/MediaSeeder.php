<?php

namespace Database\Seeders;

use App\Models\MediaPhoto;
use App\Models\MediaVideo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaSeeder extends Seeder
{
    /**
     * მედიის გვერდის საწყისი ფოტოები და ვიდეოები (ადრე HTML-ში ეწერა).
     * თითოეული ნაწილი ეშვება მხოლოდ მაშინ, როცა შესაბამისი ცხრილი ცარიელია.
     */
    public function run(): void
    {
        if (MediaPhoto::exists()) {
            $this->command?->info('ფოტოები უკვე არსებობს — გამოტოვებულია.');
        } else {
            // გალერეაში ახალი პირველი ჩანს, ამიტომ უკუღმა ვამატებთ — 1.png იქნება პირველი.
            foreach (range(10, 1) as $number) {
                MediaPhoto::create(['path' => $this->copyFromPublic("$number.png", 'media/photos')]);
            }

            $this->command?->info('დაემატა 10 ფოტო.');
        }

        if (MediaVideo::exists()) {
            $this->command?->info('ვიდეოები უკვე არსებობს — გამოტოვებულია.');

            return;
        }

        $titles = [
            ['ka' => 'იდეიდან პირველ ხაზამდე', 'en' => 'From idea to the first line', 'ru' => 'От идеи до первой линии'],
            ['ka' => 'როგორ იქმნება ხარისხი', 'en' => 'How quality is made', 'ru' => 'Как создаётся качество'],
            ['ka' => 'მასალა, შუქი და ფორმა', 'en' => 'Material, light and form', 'ru' => 'Материал, свет и форма'],
            ['ka' => 'მშენებლობა ერთ წუთში', 'en' => 'Construction in one minute', 'ru' => 'Строительство за минуту'],
            ['ka' => 'სივრცე მზად არის', 'en' => 'The space is ready', 'ru' => 'Пространство готово'],
        ];

        foreach ($titles as $index => $title) {
            MediaVideo::create([
                'title' => $title,
                'path' => $this->copyFromPublic(($index + 1) . '.mp4', 'media/videos'),
                'sort_order' => $index + 1,
            ]);
        }

        $this->command?->info('დაემატა ' . count($titles) . ' ვიდეო.');
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
