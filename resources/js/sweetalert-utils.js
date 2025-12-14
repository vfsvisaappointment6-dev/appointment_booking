/**
 * SweetAlert2 Utility Functions
 * Easy-to-use alert functions with consistent styling
 */

// Default configuration
const alertConfig = {
    confirmButtonColor: '#FF7F39',
    cancelButtonColor: '#6B7280',
    background: '#FFFFFF',
    color: '#0A0A0A',
};

/**
 * Success Alert
 * @param {string} title - Alert title
 * @param {string} message - Alert message
 * @param {number} timer - Auto-close timer in ms (0 = no timer)
 */
function showSuccess(title, message = '', timer = 0) {
    const config = {
        ...alertConfig,
        icon: 'success',
        title: title,
        text: message,
        iconColor: '#10B981',
    };

    if (timer > 0) {
        config.timer = timer;
        config.timerProgressBar = true;
    }

    return Swal.fire(config);
}

/**
 * Error Alert
 * @param {string} title - Alert title
 * @param {string} message - Alert message
 * @param {number} timer - Auto-close timer in ms (0 = no timer)
 */
function showError(title, message = '', timer = 0) {
    const config = {
        ...alertConfig,
        icon: 'error',
        title: title,
        text: message,
        iconColor: '#EF4444',
    };

    if (timer > 0) {
        config.timer = timer;
        config.timerProgressBar = true;
    }

    return Swal.fire(config);
}

/**
 * Warning Alert
 * @param {string} title - Alert title
 * @param {string} message - Alert message
 * @param {number} timer - Auto-close timer in ms (0 = no timer)
 */
function showWarning(title, message = '', timer = 0) {
    const config = {
        ...alertConfig,
        icon: 'warning',
        title: title,
        text: message,
        iconColor: '#F59E0B',
    };

    if (timer > 0) {
        config.timer = timer;
        config.timerProgressBar = true;
    }

    return Swal.fire(config);
}

/**
 * Info Alert
 * @param {string} title - Alert title
 * @param {string} message - Alert message
 * @param {number} timer - Auto-close timer in ms (0 = no timer)
 */
function showInfo(title, message = '', timer = 0) {
    const config = {
        ...alertConfig,
        icon: 'info',
        title: title,
        text: message,
        iconColor: '#3B82F6',
    };

    if (timer > 0) {
        config.timer = timer;
        config.timerProgressBar = true;
    }

    return Swal.fire(config);
}

/**
 * Confirmation Alert
 * @param {string} title - Alert title
 * @param {string} message - Alert message
 * @param {function} onConfirm - Callback when confirmed
 * @param {string} confirmText - Confirm button text
 * @param {string} cancelText - Cancel button text
 */
function showConfirm(title, message = '', onConfirm = () => {}, confirmText = 'Confirm', cancelText = 'Cancel') {
    return Swal.fire({
        ...alertConfig,
        icon: 'warning',
        title: title,
        text: message,
        showCancelButton: true,
        confirmButtonText: confirmText,
        cancelButtonText: cancelText,
        iconColor: '#F59E0B',
    }).then((result) => {
        if (result.isConfirmed) {
            onConfirm();
        }
    });
}

/**
 * Delete Confirmation Alert
 * @param {string} itemName - Name of item to delete
 * @param {function} onConfirm - Callback when confirmed
 */
function showDeleteConfirm(itemName, onConfirm = () => {}) {
    return Swal.fire({
        ...alertConfig,
        icon: 'warning',
        title: 'Delete ' + itemName,
        text: 'This action cannot be undone. Are you sure?',
        showCancelButton: true,
        confirmButtonText: 'Delete',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#EF4444',
        iconColor: '#EF4444',
    }).then((result) => {
        if (result.isConfirmed) {
            onConfirm();
        }
    });
}

/**
 * Logout Confirmation
 * @param {HTMLElement} form - Form element to submit
 */
function confirmLogout(form) {
    return Swal.fire({
        ...alertConfig,
        icon: 'warning',
        title: 'Logout',
        text: 'Are you sure you want to logout?',
        showCancelButton: true,
        confirmButtonText: 'Yes, logout',
        cancelButtonText: 'Cancel',
        iconColor: '#FF7F39',
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
}

/**
 * Toast notification (small notification at corner)
 * @param {string} icon - Icon type: success, error, warning, info
 * @param {string} title - Toast title
 * @param {number} duration - Duration in ms
 */
function showToast(icon, title, duration = 3000) {
    return Swal.fire({
        toast: true,
        position: 'top-end',
        icon: icon,
        title: title,
        showConfirmButton: false,
        timer: duration,
        timerProgressBar: true,
        background: '#FFFFFF',
        color: '#0A0A0A',
    });
}

/**
 * Success Toast
 * @param {string} message - Toast message
 * @param {number} duration - Duration in ms
 */
function showSuccessToast(message, duration = 3000) {
    return showToast('success', message, duration);
}

/**
 * Error Toast
 * @param {string} message - Toast message
 * @param {number} duration - Duration in ms
 */
function showErrorToast(message, duration = 3000) {
    return showToast('error', message, duration);
}

/**
 * Warning Toast
 * @param {string} message - Toast message
 * @param {number} duration - Duration in ms
 */
function showWarningToast(message, duration = 3000) {
    return showToast('warning', message, duration);
}

/**
 * Loading Alert (doesn't allow dismissing)
 * @param {string} title - Alert title
 * @param {string} message - Alert message
 */
function showLoading(title = 'Loading', message = 'Please wait...') {
    return Swal.fire({
        title: title,
        text: message,
        icon: 'info',
        allowOutsideClick: false,
        allowEscapeKey: false,
        confirmButtonColor: '#FF7F39',
        background: '#FFFFFF',
        color: '#0A0A0A',
        iconColor: '#3B82F6',
        didOpen: (modalElement) => {
            Swal.showLoading();
        },
    });
}

/**
 * Close loading alert
 */
function closeLoading() {
    return Swal.close();
}

export {
    showSuccess,
    showError,
    showWarning,
    showInfo,
    showConfirm,
    showDeleteConfirm,
    confirmLogout,
    showToast,
    showSuccessToast,
    showErrorToast,
    showWarningToast,
    showLoading,
    closeLoading,
};
