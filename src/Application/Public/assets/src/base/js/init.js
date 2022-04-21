import { initThemeTogglers } from './colorTheme';

export default function init() {
    initThemeTogglers();

    // eslint-disable-next-line global-require
    require('oneui/app');
}
