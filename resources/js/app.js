(function() {
    'use strict';

    function initMobileMenu() {
        const menuToggle = document.getElementById('togglemenu');
        const body = document.getElementById('body');

        if (!menuToggle || !body) return;

        function isMobile() {
            return window.innerWidth < 1025;
        }

        function openSidebar() {
            body.classList.add('sidebar-open');
            body.classList.remove('enlarge-menu-all');
        }

        function closeSidebar() {
            body.classList.remove('sidebar-open');
            body.classList.add('enlarge-menu-all');
        }

        menuToggle.addEventListener('click', function(e) {
            if (isMobile()) {
                e.preventDefault();
                e.stopPropagation();
                if (body.classList.contains('sidebar-open')) {
                    closeSidebar();
                } else {
                    openSidebar();
                }
            }
        });

        document.addEventListener('click', function(e) {
            if (isMobile() && body.classList.contains('sidebar-open')) {
                const sidebar = document.querySelector('.leftbar-tab-menu');
                const toggleBtn = document.getElementById('togglemenu');
                if (sidebar && !sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
                    closeSidebar();
                }
            }
        });

        window.addEventListener('resize', function() {
            if (!isMobile()) {
                closeSidebar();
                body.classList.remove('enlarge-menu-all');
            }
        });
    }

    function initFormValidation() {
        const forms = document.querySelectorAll('form[data-validate]');

        forms.forEach(form => {
            form.addEventListener('submit', (e) => {
                let isValid = true;
                const inputs = form.querySelectorAll('.form-control[required], .form-input[required]');

                inputs.forEach(input => {
                    if (!input.value.trim()) {
                        isValid = false;
                        input.classList.add('is-invalid');
                    } else {
                        input.classList.remove('is-invalid');
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                }
            });
        });
    }

    function initPasswordToggle() {
        const toggleButtons = document.querySelectorAll('.password-toggle');

        toggleButtons.forEach(button => {
            button.addEventListener('click', () => {
                const input = button.parentElement.querySelector('input');
                const icon = button.querySelector('i');

                if (input.type === 'password') {
                    input.type = 'text';
                    if (icon) {
                        icon.className = 'ti ti-eye-off';
                    }
                } else {
                    input.type = 'password';
                    if (icon) {
                        icon.className = 'ti ti-eye';
                    }
                }
            });
        });
    }

    function initSweetAlert() {
        window.showAlert = function(type, title, text, callback) {
            Swal.fire({
                icon: type,
                title: title,
                text: text,
                confirmButtonColor: '#556ee5'
            }).then((result) => {
                if (callback && typeof callback === 'function') {
                    callback(result);
                }
            });
        };

        window.confirmDelete = function(callback) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed && typeof callback === 'function') {
                    callback();
                }
            });
        };
    }

    function initTooltips() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }

    function init() {
        initMobileMenu();
        initFormValidation();
        initPasswordToggle();
        initSweetAlert();
        initTooltips();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
