//
document.addEventListener('DOMContentLoaded', () => {
    const root = document.documentElement;

    // Night and day
    const themeButton = document.getElementById('theme-toggle');

    if (themeButton) {
        let theme = localStorage.getItem('quoteit-theme') || 'light';

        function applyTheme() {
            root.dataset.theme = theme;

            if (theme === 'dark') {
                themeButton.innerHTML = '<svg width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="4"/><path d="M12 2v2m0 16v2M4.93 4.93l1.42 1.42m11.3 11.3 1.42 1.42M2 12h2m16 0h2M4.93 19.07l1.42-1.42m11.3-11.3 1.42-1.42"/></svg>';
                themeButton.setAttribute('aria-label', 'تفعيل الوضع النهاري');
                themeButton.title = 'الوضع النهاري';
            } else {
                themeButton.innerHTML = '<svg width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20.9 13A9 9 0 0 1 11 3.1 9 9 0 1 0 20.9 13Z"/></svg>';
                themeButton.setAttribute('aria-label', 'تفعيل الوضع الليلي');
                themeButton.title = 'الوضع الليلي';
            }

            localStorage.setItem('quoteit-theme', theme);
        }

        applyTheme();

        themeButton.addEventListener('click', () => {
            theme = theme === 'dark' ? 'light' : 'dark';
            applyTheme();
        });
    }

    // Accessibility panel
    const accessButton = document.getElementById('accessibility-toggle');
    const panel = document.getElementById('accessibility-panel');

    if (accessButton && panel) {
        accessButton.addEventListener('click', () => {
            panel.hidden = !panel.hidden;
            accessButton.setAttribute('aria-expanded', String(!panel.hidden));
        });
    }

    // Font size
    let fontSize = Number(localStorage.getItem('quoteit-font-size')) || 100;

    function applyFontSize() {
        root.style.fontSize = fontSize + '%';
        localStorage.setItem('quoteit-font-size', fontSize);
    }

    document.getElementById('font-increase')?.addEventListener('click', () => {
        fontSize = Math.min(150, fontSize + 10);
        applyFontSize();
    });

    document.getElementById('font-decrease')?.addEventListener('click', () => {
        fontSize = Math.max(80, fontSize - 10);
        applyFontSize();
    });

    document.getElementById('font-reset')?.addEventListener('click', () => {
        fontSize = 100;
        applyFontSize();
    });

    applyFontSize();

    // High contrast
    const contrastButton = document.getElementById('contrast-toggle');

    function applyContrast(enabled) {
        root.classList.toggle('high-contrast', enabled);
        contrastButton?.setAttribute('aria-pressed', String(enabled));
        localStorage.setItem('quoteit-high-contrast', enabled);
    }

    applyContrast(localStorage.getItem('quoteit-high-contrast') === 'true');

    contrastButton?.addEventListener('click', () => {
        applyContrast(!root.classList.contains('high-contrast'));
    });

    // Reduce motion
    const motionButton = document.getElementById('motion-toggle');

    function applyMotion(enabled) {
        root.classList.toggle('reduce-motion', enabled);
        motionButton?.setAttribute('aria-pressed', String(enabled));
        localStorage.setItem('quoteit-reduce-motion', enabled);
    }

    applyMotion(localStorage.getItem('quoteit-reduce-motion') === 'true');

    motionButton?.addEventListener('click', () => {
        applyMotion(!root.classList.contains('reduce-motion'));
    });
});
// Quote audio recording
document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('quote-audio-input');
    const recordButton = document.getElementById('record-audio');
    const stopButton = document.getElementById('stop-audio');
    const preview = document.getElementById('audio-preview');
    const status = document.getElementById('audio-status');

    if (!input || !recordButton || !stopButton || !preview || !status) {
        return;
    }

    let recorder;
    let stream;
    let chunks = [];

    stopButton.disabled = true;

    recordButton.addEventListener('click', async () => {
        try {
            if (!navigator.mediaDevices?.getUserMedia || !window.MediaRecorder) {
                status.textContent = 'المتصفح لا يدعم تسجيل الصوت.';
                return;
            }

            stream = await navigator.mediaDevices.getUserMedia({ audio: true });

            const types = [
                'audio/mp4',
                'audio/webm;codecs=opus',
                'audio/webm',
                'audio/ogg;codecs=opus'
            ];

            const mimeType = types.find(type =>
                MediaRecorder.isTypeSupported(type)
            );

            recorder = new MediaRecorder(
                stream,
                mimeType ? { mimeType } : undefined
            );

            chunks = [];

            recorder.ondataavailable = event => {
                if (event.data.size > 0) {
                    chunks.push(event.data);
                }
            };

            recorder.onstop = () => {
                const type = recorder.mimeType || 'audio/webm';
                const extension = type.includes('mp4')
                    ? 'm4a'
                    : type.includes('ogg')
                    ? 'ogg'
                    : 'webm';

                const audioFile = new File(
                    chunks,
                    `recording.${extension}`,
                    { type }
                );

                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(audioFile);
                input.files = dataTransfer.files;

                preview.src = URL.createObjectURL(audioFile);
                preview.load();

                status.textContent = 'تم التسجيل! استمعي للصوت قبل النشر.';
                stream.getTracks().forEach(track => track.stop());
            };

            recorder.start();

            recordButton.disabled = true;
            stopButton.disabled = false;
            status.textContent = 'جاري تسجيل الصوت...';

        } catch (error) {
            status.textContent = 'تعذر التسجيل. تأكدي من السماح باستخدام الميكروفون.';
            console.error(error);
        }
    });

    stopButton.addEventListener('click', () => {
        if (recorder && recorder.state === 'recording') {
            recorder.stop();
            recordButton.disabled = false;
            stopButton.disabled = true;
        }
    });
});
// Accessibility: Read written quotes aloud
document.addEventListener('DOMContentLoaded', () => {

    document.querySelectorAll('.quote-speak').forEach(button => {

        button.addEventListener('click', () => {

            const text = button.dataset.quote;

            if (!text || !('speechSynthesis' in window)) {
                alert('Audio reading is not supported in this browser.');
                return;
            }

            // Stop previous reading
            window.speechSynthesis.cancel();

            const speech = new SpeechSynthesisUtterance(text);

            // Detect Arabic or English
            const isArabic = /[\u0600-\u06FF]/.test(text);

            speech.lang = isArabic ? 'ar-SA' : 'en-US';
            speech.rate = 0.9;
            speech.pitch = 1;

            const voices = window.speechSynthesis.getVoices();

            const voice = voices.find(v =>
                isArabic
                    ? v.lang.toLowerCase().startsWith('ar')
                    : v.lang.toLowerCase().startsWith('en')
            );

            if (voice) {
                speech.voice = voice;
            }

            window.speechSynthesis.speak(speech);
        });
    });

    // Stop reading
    document.querySelectorAll('.quote-stop-reading').forEach(button => {

        button.addEventListener('click', () => {
            window.speechSynthesis.cancel();
        });

    });

});

// Vercel rejects larger requests before they reach the app
const MAX_UPLOAD_BYTES = 4 * 1024 * 1024;

document.addEventListener('submit', (event) => {
    const fileInputs = event.target.querySelectorAll('input[type="file"]');
    const hasLargeFile = [...fileInputs].some((input) =>
        [...input.files].some((file) => file.size > MAX_UPLOAD_BYTES),
    );

    if (hasLargeFile) {
        event.preventDefault();
        alert('حجم الملف أكبر من 4 ميجابايت. اختر ملفًا أصغر ثم حاول مرة أخرى.');
    }
});
