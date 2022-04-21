export function initThemeTogglers() {
    const pageContainer = $('#page-container');
    const colorSchemeToggler = $('#color-scheme-toggler');
    const colorThemeToggler = $('.theme-item');

    colorSchemeToggler.on('click', function () {
        const thisObj = $(this);
        const hadDarkMode = thisObj.hasClass('active');

        if (hadDarkMode) {
            pageContainer.removeClass('page-header-dark dark-mode');
        } else {
            pageContainer.addClass('page-header-dark dark-mode');
        }

        const cookie = `dark_mode=${
            hadDarkMode ? 0 : 1
        }; SameSite=Strict; Path=/`;
        window.document.cookie = cookie;
        colorSchemeToggler.toggleClass('active');
    });

    colorThemeToggler.on('click', function (e) {
        e.preventDefault();

        const thisObj = $(this);
        const theme = thisObj.data('theme');

        colorThemeToggler.removeClass('active');
        thisObj.addClass('active');

        const htmlObj = document.querySelector('html');
        htmlObj.classList.remove(...htmlObj.classList);
        htmlObj.classList.add(`theme-${theme}`);

        const cookie = `color_theme=${theme}; SameSite=Strict; Path=/`;
        window.document.cookie = cookie;
    });
}
