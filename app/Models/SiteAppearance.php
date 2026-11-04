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
            'accent_color' => '#94745b',
            'background_color' => '#f8f7f3',
            'text_color' => '#24241f',
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

    /**
     * Build CSS overrides from the values an administrator has saved.
     */
    public static function customCss(): string
    {
        $saved = self::query()->pluck('value', 'key');

        $variables = array_filter([
            '--site-bg' => $saved->get('background_color'),
            '--site-text' => $saved->get('text_color'),
            '--site-accent' => $saved->get('accent_color'),
            '--color-tuwaiq-purple' => $saved->get('primary_color'),
            '--site-width' => $saved->has('content_width') ? $saved->get('content_width').'px' : null,
            '--site-radius' => $saved->has('card_radius') ? $saved->get('card_radius').'px' : null,
        ]);

        $css = $variables === []
            ? ''
            : ':root{'.collect($variables)->map(fn (string $value, string $name) => "{$name}:{$value}")->implode(';').'}';

        if ($saved->has('background_color')) {
            $css .= 'body{background:var(--site-bg)!important;color:var(--site-text)!important}';
        }

        if ($saved->has('font_family')) {
            $css .= "body,button,input,textarea,select{font-family:'{$saved->get('font_family')}',sans-serif!important}";
        }

        return $css;
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
