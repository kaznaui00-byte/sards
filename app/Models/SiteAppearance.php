<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteAppearance extends Model
{
    protected $table = 'site_appearances';

    protected $fillable = [
        'key',
        'value',
    ];

    // الإعدادات الافتراضية للموقع
    public static function defaults(): array
    {
        return [
            'primary_color' => '#29272b',
            'accent_color' => '#a58c70',
            'background_color' => '#ffffff',
            'text_color' => '#29272b',
            'font_family' => 'Tahoma',
            'content_width' => '1200',
            'card_radius' => '14',
        ];
    }

    // جلب الإعدادات المحفوظة
    public static function current(): array
    {
        $saved = self::query()
            ->pluck('value', 'key')
            ->all();

        return array_merge(
            self::defaults(),
            $saved
        );
    }

    // حفظ إعداد واحد
    public static function saveSetting(string $key, string $value): void
    {
        self::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }
}