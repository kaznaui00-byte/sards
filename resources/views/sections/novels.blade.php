@extends('Layout.app')

@section('content')

<style>
    .novels-page {
        direction: rtl;
        color: #29252c;
        background: #faf9f6;
        min-height: 100vh;
        padding: 45px 20px;
    }

    .novels-container {
        max-width: 1150px;
        margin: auto;
    }

    .novels-heading {
        display: flex;
        align-items: center;
        gap: 18px;
        margin-bottom: 18px;
    }

    .novels-icon {
        width: 65px;
        height: 65px;
        border-radius: 18px;
        background: #eee8f5;
        color: #72518b;
        display: grid;
        place-items: center;
        flex-shrink: 0;
    }

    .novels-page h1 {
        font-size: 36px;
        font-weight: 800;
        margin: 0 0 8px;
    }

    .novels-description {
        color: #77717b;
        font-size: 16px;
        line-height: 2;
        max-width: 650px;
    }

    .novels-categories {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin: 35px 0;
        padding-bottom: 22px;
        border-bottom: 1px solid #e9e5eb;
    }

    .novels-category {
        padding: 10px 20px;
        border: 1px solid #e5dfe9;
        border-radius: 30px;
        color: #655b6b;
        font-size: 14px;
    }

    .novels-category.active {
        background: #29252c;
        color: white;
        border-color: #29252c;
    }

    .novels-section-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 22px;
        gap: 10px;
    }

    .novels-section-heading h2 {
        font-size: 23px;
        font-weight: 800;
    }

    .novels-section-heading span {
        color: #99919d;
        font-size: 13px;
    }

    .novels-empty {
        background: white;
        border: 1px solid #eee9ef;
        border-radius: 20px;
        padding: 65px 20px;
        text-align: center;
    }

    .novels-empty-icon {
        width: 75px;
        height: 75px;
        background: #f3eff6;
        color: #806a91;
        border-radius: 22px;
        display: grid;
        place-items: center;
        margin: 0 auto 20px;
    }

    .novels-empty h3 {
        font-size: 20px;
        font-weight: 800;
        margin-bottom: 10px;
    }

    .novels-empty p {
        color: #89828d;
        font-size: 14px;
        line-height: 2;
    }

    @media (max-width: 600px) {
        .novels-page {
            padding: 30px 15px;
        }

        .novels-page h1 {
            font-size: 28px;
        }
    }
</style>

<div class="novels-page">
    <div class="novels-container">

        <div class="novels-heading">
            <div class="novels-icon">
                <svg width="34" height="34" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="1.6">
                    <path d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v17H6.5A2.5 2.5 0 0 0 4 22V5.5Z"/>
                    <path d="M4 6v16m4-14h8m-8 4h8" stroke-linecap="round"/>
                </svg>
            </div>

            <div>
                <h1>الروايات</h1>
                <div style="color:#806a91; font-size:13px; font-weight:600;">
                    QuoteIt · عالم الأدب
                </div>
            </div>
        </div>

        <p class="novels-description">
            لكل رواية عالم، ولكل كاتب حكاية.
            اكتشف الأعمال الأدبية، وتعرّف على عوالم جديدة
            يشاركها المبدعون معك.
        </p>

        <div class="novels-categories">
            <div class="novels-category active">جميع الروايات</div>
            <div class="novels-category">روايات قصيرة</div>
            <div class="novels-category">روايات طويلة</div>
            <div class="novels-category">روايات مكتملة</div>
            <div class="novels-category">روايات جديدة</div>
        </div>

        <div class="novels-section-heading">
            <h2>أحدث الروايات</h2>
            <span>اكتشف أحدث الأعمال الأدبية</span>
        </div>

        <div class="novels-empty">
            <div class="novels-empty-icon">
                <svg width="38" height="38" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="1.5">
                    <path d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v17H6.5A2.5 2.5 0 0 0 4 22V5.5Z"/>
                    <path d="M4 6v16m4-14h8" stroke-linecap="round"/>
                </svg>
            </div>

            <h3>مساحة تنتظر رواياتكم</h3>

            <p>
                لا توجد روايات منشورة حتى الآن.
                ستكون هنا مساحة لاكتشاف الروايات وقراءة أعمال المبدعين.
            </p>
        </div>

    </div>
</div>

@endsection