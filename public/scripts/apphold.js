/* ----------------------------------------------------------------------------
 * Apphold - Online Software Telemetry
 *
 * @package     Apphold
 * @author      A.Tselegidis <alextselegidis@gmail.com>
 * @copyright   Copyright (c) Alex Tselegidis
 * @license     https://opensource.org/licenses/GPL-3.0 - GPLv3
 * @link        https://apphold.org
 * ---------------------------------------------------------------------------- */

// Auto-hide alert

document.querySelectorAll('.toast').forEach(function (toastEl) {
    const toast = new bootstrap.Toast(toastEl, {
        autohide: true,
        delay: 3000,
    });

    toast.show();
});

// Auto-focus create modal input

document.getElementById('create-modal')?.addEventListener('shown.bs.modal', (event) => {
    return event.target.querySelector('input:not([type="hidden"])').focus();
});

// Close other table row dropdowns when one opens

document.addEventListener('show.bs.dropdown', function (event) {
    // Check if this dropdown is inside a table
    const table = event.target.closest('table');

    if (table) {
        // Find all other open dropdowns in this table and close them
        const openDropdowns = table.querySelectorAll('.dropdown-menu.show');

        openDropdowns.forEach(function (dropdown) {
            // Skip if it's the same dropdown being opened
            if (dropdown !== event.target.nextElementSibling) {
                const dropdownInstance = bootstrap.Dropdown.getInstance(dropdown.previousElementSibling);

                if (dropdownInstance) {
                    dropdownInstance.hide();
                }
            }
        });
    }
});
