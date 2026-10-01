<?php

namespace App\Http\Requests\Admin;

use App\Models\MediaVideo;
use Illuminate\Foundation\Http\FormRequest;

class MediaVideoRequest extends FormRequest
{
    protected $errorBag = 'video';

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            // ახალ ვიდეოს ფაილი სჭირდება, რედაქტირებისას — არა.
            'video' => [$this->video() ? 'nullable' : 'required', 'file', 'mimes:mp4,webm,mov', 'max:102400'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_active' => ['boolean'],
        ];

        foreach (array_keys(config('admin.locales')) as $locale) {
            $rules["title.$locale"] = ['required', 'string', 'max:200'];
        }

        return $rules;
    }

    public function attributes(): array
    {
        $attributes = ['video' => 'ვიდეო', 'sort_order' => 'რიგი'];

        foreach (config('admin.locales') as $locale => $language) {
            $attributes["title.$locale"] = "სათაური ($language)";
        }

        return $attributes;
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute სავალდებულოა.',
            'video.required' => 'აირჩიეთ ვიდეო ფაილი.',
            'video.mimes' => 'ვიდეო უნდა იყოს MP4, WEBM ან MOV ფორმატში.',
            'video.max' => 'ვიდეოს ზომა არ უნდა აღემატებოდეს 100MB-ს.',
            'video.uploaded' => 'ვიდეოს ატვირთვა ვერ მოხერხდა (შესაძლოა ძალიან დიდია).',
            'max' => ':attribute ძალიან გრძელია.',
            'integer' => ':attribute უნდა იყოს რიცხვი.',
        ];
    }

    public function video(): ?MediaVideo
    {
        return $this->route('video');
    }

    /**
     * ახალი ვიდეოს დამატების შეცდომისას — ვიდეოების სექციაზე.
     */
    protected function getRedirectUrl(): string
    {
        $url = parent::getRedirectUrl();

        return $this->video() ? $url : strtok($url, '#') . '#videos';
    }

    public function videoData(): array
    {
        $title = [];

        foreach (array_keys(config('admin.locales')) as $locale) {
            $title[$locale] = trim($this->validated("title.$locale"));
        }

        return [
            'title' => $title,
            'sort_order' => (int) $this->validated('sort_order', 0),
            'is_active' => $this->boolean('is_active'),
        ];
    }
}
