import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

const lazyImages = document.querySelectorAll('img[data-full]');

if ('IntersectionObserver' in window) {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) return;
            const img = entry.target;
            const fullSrc = img.dataset.full;
            if (!fullSrc) return;
            const fullImg = new Image();
            fullImg.src = fullSrc;
            fullImg.onload = () => {
                img.src = fullSrc;
                img.classList.remove('blur-sm');
            };
            observer.unobserve(img);
        });
    }, { rootMargin: '200px 0px' });

    lazyImages.forEach((img) => observer.observe(img));
} else {
    lazyImages.forEach((img) => {
        const fullSrc = img.dataset.full;
        if (!fullSrc) return;
        img.src = fullSrc;
        img.classList.remove('blur-sm');
    });
}
