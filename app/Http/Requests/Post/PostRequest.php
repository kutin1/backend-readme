<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Базовые правила
        $rules = [
            'content_type_id' => ['required', 'exists:content_types,id'],

            'title' => ['nullable', 'string', 'max:255'],
            'tags' => ['nullable', 'string'],

            'content' => ['nullable', 'string'],
            'quote_author' => ['nullable', 'string', 'max:255'],

            'image_file' => ['nullable', 'image', 'mimes:png,jpeg,gif', 'max:2048'],
            'image_url' => ['nullable', 'url', 'image_url_valid'],
            // 'image_url' => [
            //     'nullable',
            //     'url',
            //     'regex:/\.(jpg|jpeg|png|gif|webp)$/i'
            // ],

            'video' => ['nullable', 'url'],
            'link' => ['nullable', 'url'],
        ];

        // Валидация по типам
        switch ((int) $this->input('content_type_id')) {
            // Текст
            case 1:
                $rules['content'] = ['required', 'string'];
                break;

            // Цитата
            case 2:
                $rules['content'] = ['required', 'string'];
                $rules['quote_author'] = ['required', 'string', 'max:255'];
                break;

            // Картинка
            case 3:
                $rules['image_file'][] = 'required_without:image_url';
                $rules['image_url'][] = 'required_without:image_file';
                break;

            // Видео
            case 4:
                $rules['video'] = ['required', 'url', 'youtube_url'];
                break;

            // Ссылка
            case 5:
                $rules['link'] = ['required', 'url'];
                break;
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'title.string' => 'Заголовок должен быть строкой.',
            'image_file.required_without' => 'Загрузите файл или укажите ссылку.',
            'image_url.required_without' => 'Укажите ссылку или загрузите файл.',
            'image_file.image' => 'Файл должен быть изображением.',
            'image_file.mimes' => 'Загрузите изображение в формате: png, jpeg или gif.',
            'image_url.url' => 'Введите корректный URL.',
            'image_url.image_url_valid' => 'Не удалось загрузить изображение по указанной ссылке.',
            // 'image_url.regex' => 'Ссылка должна вести на изображение (jpg, png, gif, webp).',
            'video.required' => 'Укажите ссылку на видео.',
            'video.url' => 'Введите корректный URL.',
            'video.youtube_url' => 'Видео должно быть с YouTube.',
            'link.required' => 'Укажите ссылку.',
            'link.url' => 'Введите корректный URL.',
            'tags.string' => 'Теги должны быть строкой.',
            'content.required' => match ((int) $this->input('content_type_id')) {
                1 => 'Введите текст поста.',
                2 => 'Введите текст цитаты.',
                default => 'Поле обязательно.',
            },
            'quote_author.required' => 'Укажите автора цитаты.',
        ];
    }
}
