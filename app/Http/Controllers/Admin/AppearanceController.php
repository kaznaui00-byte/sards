<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteAppearance;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AppearanceController extends Controller
{
    // فتح لوحة تخصيص المظهر
    public function edit()
    {
        abort_unless(auth()->user()?->is_admin, 403);

        $appearance = SiteAppearance::current();

        return view('admin.appearance.edit', ['page' => 'Admin', 'appearance' => $appearance]);
    }

    // حفظ التعديلات
    public function update(Request $request)
    {
        abort_unless(auth()->user()?->is_admin, 403);

        $validated = $request->validate([
            'primary_color' => [
                'required',
                'regex:/^#[0-9A-Fa-f]{6}$/',
            ],

            'accent_color' => [
                'required',
                'regex:/^#[0-9A-Fa-f]{6}$/',
            ],

            'background_color' => [
                'required',
                'regex:/^#[0-9A-Fa-f]{6}$/',
            ],

            'text_color' => [
                'required',
                'regex:/^#[0-9A-Fa-f]{6}$/',
            ],

            'font_family' => [
                'required',
                Rule::in([
                    'Tahoma',
                    'Arial',
                    'Georgia',
                    'Instrument Sans',
                ]),
            ],

            'content_width' => [
                'required',
                Rule::in(['960', '1100', '1200', '1320']),
            ],

            'card_radius' => [
                'required',
                Rule::in(['0', '8', '14', '20', '28']),
            ],
        ], [
            'primary_color.regex' => 'اختاري لونًا صحيحًا.',
            'accent_color.regex' => 'اختاري لونًا صحيحًا.',
            'background_color.regex' => 'اختاري لونًا صحيحًا.',
            'text_color.regex' => 'اختاري لونًا صحيحًا.',
        ]);

        foreach ($validated as $key => $value) {
            SiteAppearance::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return redirect()
            ->route('admin.appearance.edit')
            ->with('success', 'تم حفظ إعدادات المظهر بنجاح.');
    }

    /**
     * Restore the original site appearance.
     */
    public function reset(): RedirectResponse
    {
        SiteAppearance::query()->delete();

        return redirect()
            ->route('admin.appearance.edit')
            ->with('success', 'تمت استعادة المظهر الأصلي.');
    }
}
