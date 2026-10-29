import './bootstrap';

const flash = document.querySelector('.flash');

if (flash) {
    window.setTimeout(() => flash.classList.add('flash-dismissed'), 6000);
}

for (const form of document.querySelectorAll('[data-quantity-form]')) {
    const input = form.querySelector('input[type="number"]');

    for (const button of form.querySelectorAll('[data-step]')) {
        button.addEventListener('click', () => {
            const step = Number(button.dataset.step);
            const current = Number(input.value) || Number(input.min) || 1;
            const next = Math.min(Number(input.max), Math.max(Number(input.min), current + step));
            input.value = String(next);

            if (form.classList.contains('quantity-form')) {
                form.requestSubmit();
            }
        });
    }
}
