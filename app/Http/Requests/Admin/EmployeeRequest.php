<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            // ახალი თანამშრომლისთვის სურათი სავალდებულოა, რედაქტირებისას — არა.
            'photo' => [$this->route('employee') ? 'nullable' : 'required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_active' => ['boolean'],
        ];

        foreach (array_keys(config('admin.locales')) as $locale) {
            $rules["first_name.$locale"] = ['required', 'string', 'max:100'];
            $rules["last_name.$locale"] = ['required', 'string', 'max:100'];
            $rules["position.$locale"] = ['nullable', 'string', 'max:150'];
            $rules["bio.$locale"] = ['nullable', 'string', 'max:3000'];
        }

        return $rules;
    }

    public function attributes(): array
    {
        $attributes = [
            'photo' => 'სურათი',
            'sort_order' => 'რიგითობა',
        ];

        foreach (config('admin.locales') as $locale => $language) {
            $attributes["first_name.$locale"] = "სახელი ($language)";
            $attributes["last_name.$locale"] = "გვარი ($language)";
            $attributes["position.$locale"] = "თანამდებობა ($language)";
            $attributes["bio.$locale"] = "ტექსტი ($language)";
        }

        return $attributes;
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute სავალდებულოა.',
            'max' => ':attribute ძალიან გრძელია.',
            'photo.image' => 'ატვირთეთ სურათის ფაილი.',
            'photo.mimes' => 'სურათი უნდა იყოს JPG, PNG ან WEBP ფორმატში.',
            'photo.max' => 'სურათის ზომა არ უნდა აღემატებოდეს 4MB-ს.',
            'photo.uploaded' => 'სურათის ატვირთვა ვერ მოხერხდა (შესაძლოა ძალიან დიდია).',
            'integer' => ':attribute უნდა იყოს რიცხვი.',
        ];
    }

    /**
     * ვალიდირებული მონაცემები ბაზაში შესანახად მზა ფორმით.
     */
    public function employeeData(): array
    {
        $data = [
            'first_name' => $this->translations('first_name'),
            'last_name' => $this->translations('last_name'),
            'position' => $this->translations('position'),
            'bio' => $this->translations('bio'),
            'sort_order' => (int) $this->validated('sort_order', 0),
            'is_active' => $this->boolean('is_active'),
        ];

        // არასავალდებულო ველი, რომელიც არცერთ ენაზე არ შევსებულა, ინახება null-ად.
        foreach (['position', 'bio'] as $field) {
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
