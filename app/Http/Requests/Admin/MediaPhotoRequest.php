<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class MediaPhotoRequest extends FormRequest
{
    // ერთ გაგზავნაში — PHP-ის max_file_uploads (ნაგულისხმევად 20) ზედმეტ ფაილებს ჩუმად აგდებს.
    public const MAX_UPLOAD = 15;

    protected $errorBag = 'photos';

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'photos' => ['required', 'array', 'max:' . self::MAX_UPLOAD],
            'photos.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:8192'],
        ];
    }

    public function messages(): array
    {
        return [
            'photos.required' => 'აირჩიეთ მინიმუმ ერთი ფოტო.',
            'photos.max' => 'ერთდროულად მაქსიმუმ ' . self::MAX_UPLOAD . ' ფოტოს ატვირთვაა შესაძლებელი.',
            'photos.*.image' => 'ყველა ფაილი უნდა იყოს სურათი.',
            'photos.*.mimes' => 'ფოტოები უნდა იყოს JPG, PNG ან WEBP ფორმატში.',
            'photos.*.max' => 'თითო ფოტოს ზომა არ უნდა აღემატებოდეს 8MB-ს.',
            'photos.*.uploaded' => 'ფოტოს ატვირთვა ვერ მოხერხდა (შესაძლოა ძალიან დიდია).',
        ];
    }

    /**
     * შეცდომისას პირდაპირ ფოტოების სექციაზე დაბრუნება.
     */
    protected function getRedirectUrl(): string
    {
        return strtok(parent::getRedirectUrl(), '#') . '#photos';
    }
}
