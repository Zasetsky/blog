<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'subject.required' => 'Поле "Тема" обязательно для заполнения.',
            'subject.string' => 'Поле "Тема" должно быть строкой.',
            'subject.max' => 'Поле "Тема" не может превышать 255 символов.',
            'body.required' => 'Поле "Текст" обязательно для заполнения.',
            'body.string' => 'Поле "Текст" должно быть строкой.',
        ];
    }
}
