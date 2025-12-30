/**
 * KPTV Stream Manager - Main JavaScript
 * Mobile-First UIKit3 Dashboard
 */

(function () {
    'use strict';

    // ============================================
    // KPTV Namespace
    // ============================================
    const KPTV = {
        init: function () {
            this.initScrollToTop();
            this.initMobileMenu();
            this.initTableSort();
            this.initSearch();
            this.initPerPage();
            this.initTooltips();
            this.initConfirmActions();
            console.log('KPTV Stream Manager initialized');
        },

        // ============================================
        // Scroll to Top Button
        // ============================================
        initScrollToTop: function () {
            const scrollBtn = document.getElementById('kptv-scroll-top');
            if (!scrollBtn) return;

            const showScrollBtn = function () {
                if (window.scrollY > 300) {
                    scrollBtn.classList.add('visible');
                } else {
                    scrollBtn.classList.remove('visible');
                }
            };

            window.addEventListener('scroll', showScrollBtn, { passive: true });

            scrollBtn.addEventListener('click', function (e) {
                e.preventDefault();
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        },

        // ============================================
        // Mobile Menu Handling
        // ============================================
        initMobileMenu: function () {
            // Close offcanvas when clicking a link (for SPAs)
            const offcanvasLinks = document.querySelectorAll('.kptv-offcanvas-nav a:not(.uk-parent > a)');
            offcanvasLinks.forEach(function (link) {
                link.addEventListener('click', function () {
                    const offcanvas = document.getElementById('kptv-mobile-nav');
                    if (offcanvas && typeof UIkit !== 'undefined') {
                        UIkit.offcanvas(offcanvas).hide();
                    }
                });
            });

            // Handle parent items in mobile nav
            const parentItems = document.querySelectorAll('.kptv-offcanvas-nav .uk-parent > a');
            parentItems.forEach(function (item) {
                item.addEventListener('click', function (e) {
                    // Let UIkit handle the accordion behavior
                });
            });
        },

        // ============================================
        // Table Sorting
        // ============================================
        initTableSort: function () {
            const sortableHeaders = document.querySelectorAll('.kptv-table th[data-sort]');

            sortableHeaders.forEach(function (header) {
                header.style.cursor = 'pointer';
                header.addEventListener('click', function () {
                    const table = this.closest('table');
                    const tbody = table.querySelector('tbody');
                    const rows = Array.from(tbody.querySelectorAll('tr'));
                    const column = this.dataset.sort;
                    const columnIndex = Array.from(this.parentElement.children).indexOf(this);
                    const isAsc = this.classList.contains('asc');

                    // Reset all headers
                    sortableHeaders.forEach(function (h) {
                        h.classList.remove('asc', 'desc');
                    });

                    // Sort rows
                    rows.sort(function (a, b) {
                        const aVal = a.children[columnIndex].textContent.trim();
                        const bVal = b.children[columnIndex].textContent.trim();

                        // Check if numeric
                        const aNum = parseFloat(aVal);
                        const bNum = parseFloat(bVal);

                        if (!isNaN(aNum) && !isNaN(bNum)) {
                            return isAsc ? bNum - aNum : aNum - bNum;
                        }

                        return isAsc
                            ? bVal.localeCompare(aVal)
                            : aVal.localeCompare(bVal);
                    });

                    // Update class
                    this.classList.add(isAsc ? 'desc' : 'asc');

                    // Re-append rows
                    rows.forEach(function (row) {
                        tbody.appendChild(row);
                    });
                });
            });
        },

        // ============================================
        // Live Search
        // ============================================
        initSearch: function () {
            const searchInputs = document.querySelectorAll('.kptv-search input');

            searchInputs.forEach(function (input) {
                let debounceTimer;

                input.addEventListener('input', function () {
                    clearTimeout(debounceTimer);
                    const searchValue = this.value.toLowerCase();
                    const targetTable = document.querySelector(this.dataset.target || '.kptv-table');

                    debounceTimer = setTimeout(function () {
                        if (!targetTable) return;

                        const rows = targetTable.querySelectorAll('tbody tr');

                        rows.forEach(function (row) {
                            const text = row.textContent.toLowerCase();
                            row.style.display = text.includes(searchValue) ? '' : 'none';
                        });

                        // Update visible count if exists
                        KPTV.updateVisibleCount(targetTable);
                    }, 300);
                });
            });
        },

        // ============================================
        // Per Page Selector
        // ============================================
        initPerPage: function () {
            const perPageBtns = document.querySelectorAll('.kptv-per-page-btn');

            perPageBtns.forEach(function (btn) {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();

                    // Update active state
                    perPageBtns.forEach(function (b) {
                        b.classList.remove('active');
                    });
                    this.classList.add('active');

                    const perPage = this.dataset.perpage;
                    const targetTable = document.querySelector(this.dataset.target || '.kptv-table');

                    if (!targetTable) return;

                    const rows = targetTable.querySelectorAll('tbody tr');
                    const showAll = perPage === 'all';
                    const limit = parseInt(perPage, 10);

                    rows.forEach(function (row, index) {
                        if (showAll || index < limit) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });

                    KPTV.updateVisibleCount(targetTable);
                });
            });
        },

        // ============================================
        // Update Visible Count Display
        // ============================================
        updateVisibleCount: function (table) {
            const countDisplay = document.querySelector('.kptv-record-count');
            if (!countDisplay || !table) return;

            const totalRows = table.querySelectorAll('tbody tr').length;
            const visibleRows = table.querySelectorAll('tbody tr:not([style*="display: none"])').length;

            countDisplay.textContent = 'Showing ' + visibleRows + ' of ' + totalRows + ' records';
        },

        // ============================================
        // Initialize Tooltips
        // ============================================
        initTooltips: function () {
            // UIkit handles tooltips automatically via uk-tooltip attribute
            // This is just for any custom tooltip logic
        },

        // ============================================
        // Confirm Actions (Delete, etc.)
        // ============================================
        initConfirmActions: function () {
            const confirmBtns = document.querySelectorAll('[data-confirm]');

            confirmBtns.forEach(function (btn) {
                btn.addEventListener('click', function (e) {
                    const message = this.dataset.confirm || 'Are you sure?';
                    if (!confirm(message)) {
                        e.preventDefault();
                        e.stopPropagation();
                    }
                });
            });
        },

        // ============================================
        // Utility: Format Number
        // ============================================
        formatNumber: function (num) {
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        },

        // ============================================
        // Utility: Debounce Function
        // ============================================
        debounce: function (func, wait) {
            let timeout;
            return function executedFunction() {
                const context = this;
                const args = arguments;
                const later = function () {
                    timeout = null;
                    func.apply(context, args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }
    };

    // ============================================
    // Initialize on DOM Ready
    // ============================================
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            KPTV.init();
        });
    } else {
        KPTV.init();
    }

    // Expose KPTV globally for external access
    window.KPTV = KPTV;

})();