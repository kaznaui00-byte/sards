<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class QuoteSeeder extends Seeder
{
    /**
     * Publish the verified classical Arabic verses under a library account.
     */
    public function run(): void
    {
        $library = User::factory()->create([
            'name' => 'مكتبة سرد',
            'email' => 'library@sard.test',
        ]);

        foreach ($this->verses() as $verse) {
            $library->quotes()->create([...$verse, 'post_type' => 'text']);
        }
    }

    /**
     * @return array<int, array{text: string, attribution: string}>
     */
    private function verses(): array
    {
        return [
            ['text' => 'ما كلُّ ما يتمنّى المرءُ يدركُهُ — تجري الرياحُ بما لا تشتهي السفنُ', 'attribution' => 'المتنبي'],
            ['text' => 'على قدرِ أهلِ العزمِ تأتي العزائمُ — وتأتي على قدرِ الكرامِ المكارمُ', 'attribution' => 'المتنبي'],
            ['text' => 'إذا غامرتَ في شرفٍ مرومِ — فلا تقنعْ بما دونَ النجومِ', 'attribution' => 'المتنبي'],
            ['text' => 'ذو العقلِ يشقى في النعيمِ بعقلِهِ — وأخو الجهالةِ في الشقاوةِ ينعمُ', 'attribution' => 'المتنبي'],
            ['text' => 'أعزُّ مكانٍ في الدُّنى سرجُ سابحٍ — وخيرُ جليسٍ في الزمانِ كتابُ', 'attribution' => 'المتنبي'],
            ['text' => 'دعِ الأيامَ تفعلُ ما تشاءُ — وطبْ نفسًا إذا حكمَ القضاءُ', 'attribution' => 'منسوب إلى الإمام الشافعي'],
            ['text' => 'ولا حزنٌ يدومُ ولا سرورٌ — ولا بؤسٌ عليكَ ولا رخاءُ', 'attribution' => 'منسوب إلى الإمام الشافعي'],
            ['text' => 'ستبدي لكَ الأيامُ ما كنتَ جاهلًا — ويأتيكَ بالأخبارِ من لم تزوّدِ', 'attribution' => 'طرفة بن العبد'],
            ['text' => 'لسانُ الفتى نصفٌ ونصفٌ فؤادُهُ — فلم يبقَ إلا صورةُ اللحمِ والدمِ', 'attribution' => 'زهير بن أبي سلمى'],
            ['text' => 'إذا الشعبُ يومًا أرادَ الحياةَ — فلا بدَّ أن يستجيبَ القدرْ', 'attribution' => 'أبو القاسم الشابي'],
            ['text' => 'ومن لا يحبُّ صعودَ الجبالِ — يعشْ أبدَ الدهرِ بينَ الحفرْ', 'attribution' => 'أبو القاسم الشابي'],
            ['text' => 'وما نيلُ المطالبِ بالتمنّي — ولكن تؤخذُ الدنيا غلابا', 'attribution' => 'أحمد شوقي'],
            ['text' => 'الأمُّ مدرسةٌ إذا أعددتَها — أعددتَ شعبًا طيّبَ الأعراقِ', 'attribution' => 'حافظ إبراهيم'],
            ['text' => 'أعلّلُ النفسَ بالآمالِ أرقبُها — ما أضيقَ العيشَ لولا فسحةُ الأملِ', 'attribution' => 'الطغرائي'],
        ];
    }
}
