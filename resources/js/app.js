import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

document.querySelectorAll('[data-toast]').forEach((toast) => {
    const dismiss = () => {
        toast.classList.add('translate-y-2', 'opacity-0');
        window.setTimeout(() => toast.remove(), 220);
    };

    const timer = window.setTimeout(dismiss, 5000);
    toast.querySelector('[data-toast-close]')?.addEventListener('click', () => {
        window.clearTimeout(timer);
        dismiss();
    });
});

const showToast = (message, type = 'success') => {
    const toast = document.createElement('div');
    const isError = type === 'error';
    toast.className = 'fixed right-4 top-20 z-[100] mx-0 flex w-[calc(100%-2rem)] max-w-md items-start gap-3 rounded-md border p-4 shadow-lg shadow-ink/10 sm:right-6 sm:top-6 ' + (isError ? 'border-red-200 bg-red-50' : 'border-green-200 bg-green-50');
    toast.setAttribute('role', isError ? 'alert' : 'status');
    toast.setAttribute('aria-live', isError ? 'assertive' : 'polite');
    toast.innerHTML = '<span class="mt-0.5 shrink-0 ' + (isError ? 'text-red-700' : 'text-green-700') + '" aria-hidden="true"><svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="9"/><path d="' + (isError ? 'M12 8v5m0 3h.01' : 'm8 12 2.5 2.5L16 9') + '"/></svg></span><p class="min-w-0 flex-1 text-sm leading-6 ' + (isError ? 'text-red-900' : 'text-green-900') + '"></p><button type="button" class="grid h-8 w-8 shrink-0 place-items-center rounded-sm text-stone-500 transition-colors hover:bg-stone-100 hover:text-ink focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ink" aria-label="Đóng thông báo"><svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path d="m5 5 10 10M15 5 5 15"/></svg></button>';
    toast.querySelector('p').textContent = message;
    document.body.append(toast);

    const dismiss = () => {
        toast.classList.add('translate-y-2', 'opacity-0');
        window.setTimeout(() => toast.remove(), 220);
    };
    const timer = window.setTimeout(dismiss, 5000);
    toast.querySelector('button').addEventListener('click', () => {
        window.clearTimeout(timer);
        dismiss();
    });
};

const copyShareUrl = async (url) => {
    try {
        if (navigator.clipboard && window.isSecureContext) {
            await navigator.clipboard.writeText(url);
        } else {
            const input = document.createElement('textarea');
            input.value = url;
            input.setAttribute('readonly', '');
            input.style.position = 'fixed';
            input.style.opacity = '0';
            document.body.append(input);
            input.select();
            const copied = document.execCommand('copy');
            input.remove();
            if (!copied) throw new Error('Clipboard copy failed');
        }
        showToast('Đã sao chép liên kết sản phẩm.');
    } catch {
        showToast('Không thể sao chép liên kết trên thiết bị này.', 'error');
    }
};

document.addEventListener('change', (event) => {
    if (event.target.matches('[data-review-rating]')) {
        const selectedRating = Number(event.target.value);
        event.target.closest('[data-rating-group]')?.querySelectorAll('[data-rating-star]').forEach((star) => {
            const isSelected = Number(star.dataset.ratingStar) <= selectedRating;
            star.classList.toggle('text-clay', isSelected);
            star.classList.toggle('text-stone-300', !isSelected);
        });
        return;
    }

    if (event.target.matches('[data-color-input]')) {
        const value = event.target.closest('[data-color-variant-row]')?.querySelector('[data-color-value]');
        if (value) value.textContent = event.target.value;
        return;
    }

    if (event.target.matches('[data-product-color-radio]')) {
        const colorOption = event.target;
        const visual = colorOption.closest('[data-product-visual]');
        const mainImage = visual?.querySelector('[data-product-main]');
        if (mainImage && colorOption.dataset.src) {
            mainImage.src = colorOption.dataset.src;
            mainImage.alt = colorOption.dataset.alt;
            mainImage.hidden = false;
            mainImage.classList.remove('hidden');
        }
        visual?.querySelectorAll('[data-gallery-thumb]').forEach((button) => button.setAttribute('aria-pressed', 'false'));
        const selectedColor = colorOption.closest('[data-color-selector]')?.querySelector('[data-selected-color]');
        if (selectedColor) selectedColor.textContent = colorOption.dataset.color;
        return;
    }

    if (event.target.matches('[data-color-variant-image]')) {
        const preview = event.target.closest('[data-color-variant-row]')?.querySelector('[data-color-variant-preview]');
        const file = event.target.files?.[0];
        if (!preview || !file || !file.type.startsWith('image/')) return;
        preview.src = URL.createObjectURL(file);
        preview.classList.remove('hidden');
        return;
    }

    if (event.target.matches('[data-image-upload]')) {
        const list = document.querySelector('[data-image-preview]');
        if (!list) return;
        list.replaceChildren();
        [...event.target.files].forEach((file, index) => {
            if (!file.type.startsWith('image/')) return;
            const card = document.createElement('label');
            card.className = 'border border-stone-200 p-2';
            const image = document.createElement('img');
            image.alt = `Xem trước ${file.name}`;
            image.className = 'h-24 w-20 object-cover';
            image.src = URL.createObjectURL(file);
            const primary = document.createElement('input');
            primary.type = 'radio';
            primary.name = 'primary_choice';
            primary.value = `new:${index}`;
            primary.checked = index === 0 && list.dataset.hasExisting !== '1';
            const text = document.createElement('span');
            text.className = 'mt-2 flex items-center gap-2 text-xs';
            text.append(primary, document.createTextNode(' Ảnh đại diện'));
            card.append(image, text);
            list.append(card);
        });
    }
});

document.addEventListener('click', async (event) => {
    const nativeShareButton = event.target.closest('[data-share-native]');
    if (nativeShareButton) {
        const title = nativeShareButton.dataset.shareTitle;
        const url = nativeShareButton.dataset.shareUrl;
        if (navigator.share) {
            try {
                await navigator.share({ title, text: title, url });
            } catch (error) {
                if (error.name !== 'AbortError') await copyShareUrl(url);
            }
        } else {
            await copyShareUrl(url);
        }
        return;
    }

    const copyShareButton = event.target.closest('[data-copy-share]');
    if (copyShareButton) {
        await copyShareUrl(copyShareButton.dataset.shareUrl);
        return;
    }

    const addColor = event.target.closest('[data-add-color-variant]');
    if (addColor) {
        const section = addColor.closest('[data-color-variants]');
        const template = document.querySelector('#color-variant-template');
        const list = section?.querySelector('[data-color-variant-list]');
        if (!template || !list) return;
        const key = `new-${Date.now().toString(36)}-${list.children.length}`;
        list.append(template.content.cloneNode(true));
        const row = list.lastElementChild;
        row.querySelectorAll('[name]').forEach((input) => { input.name = input.name.replace('__KEY__', key); });
        row.querySelector('input[name$="[name]"]')?.focus();
        return;
    }

    const removeColor = event.target.closest('[data-remove-color-variant]');
    if (removeColor) {
        removeColor.closest('[data-color-variant-row]')?.remove();
        return;
    }

    const colorOption = event.target.closest('[data-product-color]');
    if (colorOption) {
        const visual = colorOption.closest('[data-product-visual]');
        const mainImage = visual?.querySelector('[data-product-main]');
        if (mainImage && colorOption.dataset.src) {
            mainImage.src = colorOption.dataset.src;
            mainImage.alt = colorOption.dataset.alt;
            mainImage.hidden = false;
            mainImage.classList.remove('hidden');
            const placeholder = visual.querySelector('[data-product-placeholder]');
            if (placeholder) placeholder.classList.add('hidden');
        }
        const selector = colorOption.closest('[data-color-selector]');
        selector?.querySelectorAll('[data-product-color]').forEach((button) => {
            button.setAttribute('aria-pressed', String(button === colorOption));
        });
        visual?.querySelectorAll('[data-gallery-thumb]').forEach((button) => button.setAttribute('aria-pressed', 'false'));
        const selectedColor = selector?.querySelector('[data-selected-color]');
        if (selectedColor) selectedColor.textContent = colorOption.dataset.color;
        return;
    }

    const thumbnail = event.target.closest('[data-gallery-thumb]');
    if (!thumbnail) return;
    const gallery = thumbnail.closest('[data-gallery]');
    const mainImage = gallery?.querySelector('[data-product-main]');
    if (!mainImage) return;
    mainImage.src = thumbnail.dataset.src;
    mainImage.alt = thumbnail.dataset.alt;
    gallery.querySelectorAll('[data-gallery-thumb]').forEach((button) => button.setAttribute('aria-pressed', String(button === thumbnail)));
    const selector = gallery.closest('[data-product-visual]')?.querySelector('[data-color-selector]');
    selector?.querySelectorAll('[data-product-color]').forEach((button) => button.setAttribute('aria-pressed', 'false'));
    const selectedColor = selector?.querySelector('[data-selected-color]');
    if (selectedColor) selectedColor.textContent = 'Chọn màu';
});

document.addEventListener('error', (event) => {
    if (event.target instanceof HTMLImageElement && event.target.matches('[data-fallback-image]')) {
        event.target.hidden = true;
    }
}, true);
