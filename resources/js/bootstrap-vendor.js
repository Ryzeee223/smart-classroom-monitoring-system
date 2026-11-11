import * as bootstrap from 'bootstrap';

window.bootstrap = bootstrap;

function showSubmitLoading(form, submitter = null) {
    if (form.dataset.submitting === 'true') return;

    form.dataset.submitting = 'true';
    form.setAttribute('aria-busy', 'true');

    const button = submitter || form.querySelector(
        'button[type="submit"], button:not([type]), input[type="submit"]',
    );

    if (!button) return;

    if (button.name && !button.disabled) {
        const submittedValue = document.createElement('input');
        submittedValue.type = 'hidden';
        submittedValue.name = button.name;
        submittedValue.value = button.value;
        form.append(submittedValue);
    }

    button.disabled = true;
    button.classList.add('form-submit-loading');
    button.setAttribute('aria-busy', 'true');

    if (button instanceof HTMLButtonElement) {
        const spinner = document.createElement('span');
        spinner.className = 'spinner-border spinner-border-sm me-2';
        spinner.setAttribute('aria-hidden', 'true');
        button.prepend(spinner);

        const loadingStatus = document.createElement('span');
        loadingStatus.className = 'visually-hidden';
        loadingStatus.textContent = 'Loading';
        button.append(loadingStatus);
    } else {
        button.value = `${button.value}...`;
    }
}

window.showSubmitLoading = showSubmitLoading;

document.addEventListener('submit', (event) => {
    const form = event.target;
    if (!(form instanceof HTMLFormElement)) return;

    if (form.dataset.submitting === 'true') {
        event.preventDefault();
        return;
    }

    if (event.defaultPrevented || form.method.toLowerCase() === 'get') return;

    showSubmitLoading(form, event.submitter);
});
