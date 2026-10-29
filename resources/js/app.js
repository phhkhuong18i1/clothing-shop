import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

document.addEventListener('change', (event) => {
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

document.addEventListener('click', (event) => {
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
