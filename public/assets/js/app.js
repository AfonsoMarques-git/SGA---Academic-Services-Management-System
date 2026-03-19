(function () {
    'use strict';

    var STORAGE_KEY = 'sga-theme';

    function getPreferredTheme() {
        var saved = null;
        try {
            saved = window.localStorage.getItem(STORAGE_KEY);
        } catch (error) {
            saved = null;
        }

        if (saved === 'light' || saved === 'dark') {
            return saved;
        }

        return window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    }

    function updateThemeToggles(theme) {
        var nextTheme = theme === 'dark' ? 'light' : 'dark';

        document.querySelectorAll('.js-theme-toggle').forEach(function (button) {
            var labelLight = button.getAttribute('data-label-light') || 'Theme: Light';
            var labelDark = button.getAttribute('data-label-dark') || 'Theme: Dark';
            var nextWordLight = button.getAttribute('data-next-light') || 'light';
            var nextWordDark = button.getAttribute('data-next-dark') || 'dark';
            var togglePrefix = button.getAttribute('data-toggle-prefix') || 'Switch to theme';

            var label = theme === 'dark' ? labelDark : labelLight;
            var nextWord = nextTheme === 'dark' ? nextWordDark : nextWordLight;
            var ariaLabel = togglePrefix + ' ' + nextWord;

            button.textContent = label;
            button.setAttribute('aria-label', ariaLabel);
            button.setAttribute('aria-pressed', String(theme === 'dark'));
            button.setAttribute('data-next-theme', nextTheme);
        });
    }

    function applyTheme(theme, persist) {
        var safeTheme = theme === 'dark' ? 'dark' : 'light';
        document.documentElement.setAttribute('data-theme', safeTheme);
        updateThemeToggles(safeTheme);

        if (persist) {
            try {
                window.localStorage.setItem(STORAGE_KEY, safeTheme);
            } catch (error) {
                // Ignore localStorage errors and keep session-only theme.
            }
        }
    }

    function initTheme() {
        applyTheme(getPreferredTheme(), false);

        document.querySelectorAll('.js-theme-toggle').forEach(function (button) {
            button.addEventListener('click', function () {
                var current = document.documentElement.getAttribute('data-theme') === 'dark' ? 'dark' : 'light';
                var next = current === 'dark' ? 'light' : 'dark';
                applyTheme(next, true);
            });
        });
    }

    function revealOnLoad() {
        var targets = document.querySelectorAll('.card, .alert, .table-responsive, .student-hero, .login-shell');
        targets.forEach(function (element) {
            element.classList.add('ui-reveal');
        });

        if (!('IntersectionObserver' in window)) {
            targets.forEach(function (element) {
                element.classList.add('is-visible');
            });
            return;
        }

        var observer = new IntersectionObserver(function (entries, obs) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    obs.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.12
        });

        targets.forEach(function (element) {
            observer.observe(element);
        });
    }

    function enhanceKeyboardHints() {
        document.body.classList.add('js-enabled');
    }

    document.addEventListener('DOMContentLoaded', function () {
        initTheme();
        enhanceKeyboardHints();
        revealOnLoad();
    });
})();
