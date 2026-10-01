<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PartnerRequest extends FormRequest
{
    /**
     * Validation-ის შეცდომები ცალკე "partner" ჩანთაში, რომ კომპანიის გვერდზე
     * თანამშრომლის ფორმის შეცდომებს არ აერიოს.
     */
    protected $errorBag = 'partner';

    public function authorize(): bool
    {
        return true;
    }

    /**
     * "www.facebook.com" → "https://www.facebook.com", რომ ბმული სწორად გაიხსნას.
     */
    protected function prepareForValidation(): void
    {
        $url = trim((string) $this->input('url'));

        if ($url !== '' && ! preg_match('#^https?://#i', $url)) {
            $url = 'https://' . ltrim($url, '/');
        }

        $this->merge(['url' => $url !== '' ? $url : null]);
    }

    public function rules(): array
    {
        return [
            // ახალ პარტნიორს ლოგო სჭირდება, რედაქტირებისას — არა.
            'logo' => [$this->route('partner') ? 'nullable' : 'required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'url' => ['nullable', 'url:http,https', 'max:2048'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'is_active' => ['boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'logo' => 'ლოგო',
            'url' => 'ბმული',
            'sort_order' => 'რიგი',
        ];
    }

    public function messages(): array
    {
        return [
            'logo.required' => 'ლოგო სავალდებულოა.',
            'logo.image' => 'ატვირთეთ სურათის ფაილი.',
            'logo.mimes' => 'ლოგო უნდა იყოს PNG, JPG ან WEBP ფორმატში.',
            'logo.max' => 'ლოგოს ზომა არ უნდა აღემატებოდეს 2MB-ს.',
            'logo.uploaded' => 'ლოგოს ატვირთვა ვერ მოხერხდა (შესაძლოა ძალიან დიდია).',
            'url.url' => 'ბმული არასწორია. მაგალითად: www.facebook.com',
            'max' => ':attribute ძალიან გრძელია.',
            'integer' => ':attribute უნდა იყოს რიცხვი.',
        ];
    }

    /**
     * შეცდომისას კომპანიის გვერდზე პირდაპირ პარტნიორების სექციაზე დაბრუნება.
     */
    protected function getRedirectUrl(): string
    {
        $url = parent::getRedirectUrl();

        return $this->route('partner') ? $url : strtok($url, '#') . '#partners';
    }

    public function partnerData(): array
    {
        return [
            'url' => $this->validated('url'),
            'sort_order' => (int) $this->validated('sort_order', 0),
            'is_active' => $this->boolean('is_active'),
        ];
    }
}
