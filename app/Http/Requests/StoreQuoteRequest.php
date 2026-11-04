<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'post_type' => [
                'required',
                'in:text,audio',
            ],

            'text' => [
                'required_if:post_type,text',
                'nullable',
                'string',
                'min:3',
                'max:155',
            ],

            'attribution' => [
                'nullable',
                'string',
                'max:100',
            ],

            'title' => [
                'required_if:post_type,audio',
                'nullable',
                'string',
                'max:100',
            ],

            'audio' => [
                'required_if:post_type,audio',
                'nullable',
                'file',
                'mimes:mp3,m4a,wav,ogg,webm,mp4',
                'max:4096',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'post_type.required' => 'اختاري نوع المنشور.',
            'post_type.in' => 'نوع المنشور غير صحيح.',

            'text.required_if' => 'اكتبي نص الاقتباس.',
            'text.min' => 'يجب ألا يقل الاقتباس عن 3 أحرف.',
            'text.max' => 'يجب ألا يزيد الاقتباس عن 155 حرفًا.',

            'title.required_if' => 'اكتبي عنوان الحلقة الصوتية.',
            'title.max' => 'عنوان الحلقة طويل جدًا.',

            'audio.required_if' => 'أضيفي التسجيل الصوتي.',
            'audio.file' => 'الملف المرفوع غير صالح.',
            'audio.mimes' => 'صيغة الملف غير مدعومة.',
            'audio.max' => 'حجم الملف يجب ألا يتجاوز 10 ميجابايت.',
        ];
    }
}
