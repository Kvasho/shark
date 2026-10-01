<?php

namespace App\Http\Requests\Admin;

use App\Models\ServiceStep;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class ServiceStepRequest extends FormRequest
{
    public const MAX_IMAGES = 10;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            // ახალ ნაბიჯს მინიმუმ ერთი სურათი სჭირდება; რედაქტირებისას იხ. after().
            'images' => [$this->step() ? 'nullable' : 'required', 'array', 'max:' . self::MAX_IMAGES],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'remove_images' => ['nullable', 'array'],
            'remove_images.*' => ['integer'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_active' => ['boolean'],
        ];

        foreach (array_keys(config('admin.locales')) as $locale) {
            $rules["title.$locale"] = ['required', 'string', 'max:200'];
            $rules["description.$locale"] = ['nullable', 'string', 'max:3000'];
        }

        return $rules;
    }

    /**
     * რედაქტირებისას: წაშლის შემდეგ ნაბიჯს მაინც უნდა დარჩეს ერთი სურათი მაინც და არაუმეტეს ლიმიტისა.
     */
    public function after(): array
    {
        return [
            function (Validator $validator) {
                $step = $this->step();

                if (! $step || $validator->errors()->has('images')) {
                    return;
                }

                $remaining = $step->images()->whereNotIn('id', $this->removeImageIds())->count();
                $total = $remaining + count($this->file('images', []));

                if ($total < 1) {
                    $validator->errors()->add('images', 'ნაბიჯს მინიმუმ ერთი სურათი უნდა ჰქონდეს.');
                } elseif ($total > self::MAX_IMAGES) {
                    $validator->errors()->add('images', 'ერთ ნაბიჯს მაქსიმუმ ' . self::MAX_IMAGES . ' სურათი შეიძლება ჰქონდეს.');
                }
            },
        ];
    }

    public function attributes(): array
    {
        $attributes = [
            'images' => 'სურათები',
            'images.*' => 'სურათი',
            'sort_order' => 'რიგი',
        ];

        foreach (config('admin.locales') as $locale => $language) {
            $attributes["title.$locale"] = "სათაური ($language)";
            $attributes["description.$locale"] = "აღწერა ($language)";
        }

        return $attributes;
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute სავალდებულოა.',
            'images.required' => 'ატვირთეთ მინიმუმ ერთი სურათი.',
            'images.max' => 'ერთდროულად მაქსიმუმ ' . self::MAX_IMAGES . ' სურათის ატვირთვაა შესაძლებელი.',
            'images.*.image' => 'ყველა ფაილი უნდა იყოს სურათი.',
            'images.*.mimes' => 'სურათები უნდა იყოს JPG, PNG ან WEBP ფორმატში.',
            'images.*.max' => 'თითო სურათის ზომა არ უნდა აღემატებოდეს 4MB-ს.',
            'images.*.uploaded' => 'სურათის ატვირთვა ვერ მოხერხდა (შესაძლოა ძალიან დიდია).',
            'max' => ':attribute ძალიან გრძელია.',
            'integer' => ':attribute უნდა იყოს რიცხვი.',
        ];
    }

    public function step(): ?ServiceStep
    {
        return $this->route('step');
    }

    /**
     * @return array<int>
     */
    public function removeImageIds(): array
    {
        return array_map('intval', (array) $this->input('remove_images', []));
    }

    /**
     * ვალიდირებული მონაცემები ბაზაში შესანახად მზა ფორმით.
     */
    public function stepData(): array
    {
        $data = [
            'title' => $this->translations('title'),
            'description' => $this->translations('description'),
            'sort_order' => (int) $this->validated('sort_order', 0),
            'is_active' => $this->boolean('is_active'),
        ];

        if (! array_filter($data['description'])) {
            $data['description'] = null;
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
