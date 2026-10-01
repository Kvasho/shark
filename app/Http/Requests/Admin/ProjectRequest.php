<?php

namespace App\Http\Requests\Admin;

use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Validator;

class ProjectRequest extends FormRequest
{
    // ერთ გაგზავნაში — PHP-ის max_file_uploads (ნაგულისხმევად 20) ზედმეტ ფაილებს ჩუმად აგდებს.
    public const MAX_UPLOAD = 15;
    // გალერეაში სულ.
    public const MAX_MEDIA = 40;
    public const IMAGE_MAX_KB = 8192;    // 8MB
    public const VIDEO_MAX_KB = 102400;  // 100MB

    public const IMAGE_EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp'];
    public const VIDEO_EXTENSIONS = ['mp4', 'webm', 'mov'];

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            // მთავარი სურათი ახალ პროექტს სჭირდება, რედაქტირებისას — არა.
            'cover' => [$this->project() ? 'nullable' : 'required', 'image', 'mimes:' . implode(',', self::IMAGE_EXTENSIONS), 'max:' . self::IMAGE_MAX_KB],
            'media' => ['nullable', 'array', 'max:' . self::MAX_UPLOAD],
            'media.*' => ['file', 'mimes:' . implode(',', [...self::IMAGE_EXTENSIONS, ...self::VIDEO_EXTENSIONS]), 'max:' . self::VIDEO_MAX_KB],
            'remove_media' => ['nullable', 'array'],
            'remove_media.*' => ['integer'],
            'year' => ['nullable', 'integer', 'min:1900', 'max:2100'],
            'area' => ['nullable', 'integer', 'min:1', 'max:100000000'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_active' => ['boolean'],
        ];

        foreach (array_keys(config('admin.locales')) as $locale) {
            $rules["title.$locale"] = ['required', 'string', 'max:200'];
            $rules["category.$locale"] = ['required', 'string', 'max:100'];
            $rules["location.$locale"] = ['nullable', 'string', 'max:150'];
            $rules["excerpt.$locale"] = ['nullable', 'string', 'max:500'];
            $rules["description.$locale"] = ['nullable', 'string', 'max:5000'];
        }

        return $rules;
    }

    /**
     * სურათებს უფრო მცირე ლიმიტი აქვთ, ვიდრე ვიდეოებს; გალერეის საერთო რაოდენობაც შეზღუდულია.
     */
    public function after(): array
    {
        return [
            function (Validator $validator) {
                foreach ($this->file('media', []) as $index => $file) {
                    if (self::isImage($file) && $file->getSize() > self::IMAGE_MAX_KB * 1024) {
                        $validator->errors()->add("media.$index", 'სურათის ზომა არ უნდა აღემატებოდეს 8MB-ს: ' . $file->getClientOriginalName());
                    }
                }

                $project = $this->project();
                $existing = $project ? $project->media()->whereNotIn('id', $this->removeMediaIds())->count() : 0;

                if ($existing + count($this->file('media', [])) > self::MAX_MEDIA) {
                    $validator->errors()->add('media', 'გალერეაში სულ მაქსიმუმ ' . self::MAX_MEDIA . ' ფაილი შეიძლება იყოს.');
                }
            },
        ];
    }

    public static function isImage(UploadedFile $file): bool
    {
        return in_array(strtolower($file->getClientOriginalExtension()), self::IMAGE_EXTENSIONS, true)
            && str_starts_with((string) $file->getMimeType(), 'image/');
    }

    public function attributes(): array
    {
        $attributes = [
            'cover' => 'მთავარი სურათი',
            'media' => 'გალერეა',
            'media.*' => 'ფაილი',
            'year' => 'წელი',
            'area' => 'ფართობი',
            'sort_order' => 'რიგი',
        ];

        foreach (config('admin.locales') as $locale => $language) {
            $attributes["title.$locale"] = "სათაური ($language)";
            $attributes["category.$locale"] = "ტიპი ($language)";
            $attributes["location.$locale"] = "ლოკაცია ($language)";
            $attributes["excerpt.$locale"] = "მოკლე აღწერა ($language)";
            $attributes["description.$locale"] = "აღწერა ($language)";
        }

        return $attributes;
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute სავალდებულოა.',
            'cover.image' => 'მთავარი სურათი უნდა იყოს სურათის ფაილი.',
            'cover.mimes' => 'მთავარი სურათი უნდა იყოს JPG, PNG ან WEBP ფორმატში.',
            'cover.max' => 'მთავარი სურათის ზომა არ უნდა აღემატებოდეს 8MB-ს.',
            'media.max' => 'ერთდროულად მაქსიმუმ ' . self::MAX_UPLOAD . ' ფაილის ატვირთვაა შესაძლებელი — დანარჩენი რედაქტირებიდან დაამატეთ.',
            'media.*.mimes' => 'დაშვებულია მხოლოდ სურათები (JPG, PNG, WEBP) და ვიდეოები (MP4, WEBM, MOV).',
            'media.*.max' => 'ვიდეოს ზომა არ უნდა აღემატებოდეს 100MB-ს.',
            'media.*.uploaded' => 'ფაილის ატვირთვა ვერ მოხერხდა (შესაძლოა ძალიან დიდია).',
            'cover.uploaded' => 'სურათის ატვირთვა ვერ მოხერხდა (შესაძლოა ძალიან დიდია).',
            'max' => ':attribute ძალიან გრძელია.',
            'integer' => ':attribute უნდა იყოს რიცხვი.',
            'year.min' => 'წელი არასწორია.',
            'year.max' => 'წელი არასწორია.',
        ];
    }

    public function project(): ?Project
    {
        return $this->route('project');
    }

    /**
     * @return array<int>
     */
    public function removeMediaIds(): array
    {
        return array_map('intval', (array) $this->input('remove_media', []));
    }

    /**
     * ვალიდირებული მონაცემები ბაზაში შესანახად მზა ფორმით.
     */
    public function projectData(): array
    {
        $data = [
            'year' => $this->validated('year') ?: null,
            'area' => $this->validated('area') ?: null,
            'sort_order' => (int) $this->validated('sort_order', 0),
            'is_active' => $this->boolean('is_active'),
        ];

        foreach (['title', 'category', 'location', 'excerpt', 'description'] as $field) {
            $data[$field] = $this->translations($field);
        }

        // არასავალდებულო ველი, რომელიც არცერთ ენაზე არ შევსებულა, ინახება null-ად.
        foreach (['location', 'excerpt', 'description'] as $field) {
            if (! array_filter($data[$field])) {
                $data[$field] = null;
            }
        }

        return $data;
    }

    private function translations(string $field): array
    {
        $values = [];

        foreach (array_keys(config('admin.locales')) as $locale) {
            $value = $this->validated("$field.$locale");
            $values[$locale] = filled($value) ? trim($value) : null;
        }

        return $values;
    }
}
