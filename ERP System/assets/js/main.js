// Main JavaScript file for the ERP System

/**
 * Shows a toast notification.
 * @param {string} message The message to display.
 * @param {string} type 'success' or 'error'.
 */
function showToast(message, type = 'success') {
    const container = document.getElementById('toast-container');
    if (!container) return;

    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.textContent = message;

    container.appendChild(toast);

    // Trigger the animation
    setTimeout(() => {
        toast.classList.add('show');
    }, 100);

    // Remove the toast after a few seconds
    setTimeout(() => {
        toast.classList.remove('show');
        toast.addEventListener('transitionend', () => {
            toast.remove();
        });
    }, 3000);
}

document.addEventListener('DOMContentLoaded', function() {
    // This is where we can check for a message from the session
    const toastMessage = document.body.getAttribute('data-toast-message');
    const toastType = document.body.getAttribute('data-toast-type');

    if (toastMessage) {
        showToast(toastMessage, toastType);
    }
});
