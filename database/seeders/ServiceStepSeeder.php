<?php

namespace Database\Seeders;

use App\Models\ServiceStep;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ServiceStepSeeder extends Seeder
{
    /**
     * სერვისების გვერდის საწყისი 5 ნაბიჯი (ადრე HTML-ში ეწერა).
     * ეშვება მხოლოდ მაშინ, როცა ცხრილი ცარიელია — ადმინში შეცვლილს არ გადააწერს.
     */
    public function run(): void
    {
        if (ServiceStep::exists()) {
            $this->command?->info('ნაბიჯები უკვე არსებობს — გამოტოვებულია.');

            return;
        }

        $steps = [
            [
                'image' => '3.png',
                'title' => ['ka' => 'კონსულტაცია და საჭიროებების კვლევა', 'en' => 'Consultation and needs assessment', 'ru' => 'Консультация и анализ потребностей'],
                'description' => ['ka' => 'ვიგებთ მიზანს, ფუნქციურ მოთხოვნებს, სავარაუდო ბიუჯეტსა და სასურველ ვადებს.', 'en' => 'We define the goal, functional needs, estimated budget and desired timeline.', 'ru' => 'Определяем цель, функциональные требования, бюджет и сроки.'],
            ],
            [
                'image' => '4.png',
                'title' => ['ka' => 'კონცეფცია და დაგეგმარება', 'en' => 'Concept and planning', 'ru' => 'Концепция и планирование'],
                'description' => ['ka' => 'ვამზადებთ არქიტექტურულ კონცეფციას, სივრცით გადაწყვეტასა და პირველად ვიზუალიზაციას.', 'en' => 'We prepare the architectural concept, spatial solution and initial visualization.', 'ru' => 'Готовим архитектурную концепцию, пространственное решение и первичную визуализацию.'],
            ],
            [
                'image' => '5.png',
                'title' => ['ka' => 'ბიუჯეტი, გრაფიკი და დოკუმენტაცია', 'en' => 'Budget, schedule and documentation', 'ru' => 'Бюджет, график и документация'],
                'description' => ['ka' => 'ვადგენთ დეტალურ ხარჯთაღრიცხვას, სამუშაო გრაფიკსა და ტექნიკურ დოკუმენტაციას.', 'en' => 'We prepare a detailed estimate, work schedule and technical documentation.', 'ru' => 'Составляем подробную смету, график работ и техническую документацию.'],
            ],
            [
                'image' => '6.png',
                'title' => ['ka' => 'მშენებლობა და ყოველდღიური კონტროლი', 'en' => 'Construction and daily supervision', 'ru' => 'Строительство и ежедневный контроль'],
                'description' => ['ka' => 'კვალიფიციური გუნდი ასრულებს სამუშაოს, ხოლო მენეჯერი აკონტროლებს ხარისხსა და ვადებს.', 'en' => 'A qualified team performs the work while the manager controls quality and deadlines.', 'ru' => 'Квалифицированная команда выполняет работы, а менеджер контролирует качество и сроки.'],
            ],
            [
                'image' => '7.png',
                'title' => ['ka' => 'შემოწმება და პროექტის ჩაბარება', 'en' => 'Inspection and handover', 'ru' => 'Проверка и сдача проекта'],
                'description' => ['ka' => 'ვატარებთ ხარისხის საბოლოო აუდიტს, ვაბარებთ დოკუმენტაციას და დასრულებულ სივრცეს.', 'en' => 'We conduct a final quality audit and hand over the documentation and completed space.', 'ru' => 'Проводим итоговый аудит качества и передаём документацию и готовое пространство.'],
            ],
        ];

        foreach ($steps as $index => $data) {
            $step = ServiceStep::create([
                'title' => $data['title'],
                'description' => $data['description'],
                'sort_order' => $index + 1,
            ]);

            $path = 'service-steps/' . Str::random(40) . '.' . pathinfo($data['image'], PATHINFO_EXTENSION);
            Storage::disk('public')->put($path, file_get_contents(public_path($data['image'])));

            $step->images()->create(['path' => $path, 'sort_order' => 1]);
        }

        $this->command?->info('დაემატა ' . count($steps) . ' ნაბიჯი.');
    }
}
