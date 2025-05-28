let currentVolume = 1;  // Default volume

// Volume control functionality
function initVolumeControl() {
    const volumeSlider = document.getElementById('volumeSlider');
    
    // Load saved volume from cookie or default to 1
    currentVolume = parseFloat(getCookie('audioVolume')) || 1;
    volumeSlider.value = currentVolume;
    
    // Apply initial volume to all audio elements
    updateAllAudioVolumes(currentVolume);
    
    // Add event listener for volume changes
    volumeSlider.addEventListener('input', function(e) {
        currentVolume = parseFloat(e.target.value);
        updateAllAudioVolumes(currentVolume);
        setCookie('audioVolume', currentVolume, 365); // Store for 1 year
    });
}

// Update volume for all audio elements
function updateAllAudioVolumes(volume) {
    const audioElements = document.getElementsByTagName('audio');
    for(let audio of audioElements) {
        audio.volume = volume;
    }
}

// Cookie handling functions
function setCookie(name, value, days) {
    const expires = new Date();
    expires.setTime(expires.getTime() + (days * 24 * 60 * 60 * 1000));
    document.cookie = name + '=' + value + ';expires=' + expires.toUTCString() + ';path=/';
}

function getCookie(name) {
    const nameEQ = name + '=';
    const ca = document.cookie.split(';');
    for(let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) === ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}

// Settings panel functionality
function initSettingsPanel() {
    const settingsButtons = document.querySelectorAll('[id="settings-button"]');
    const settingsPanel = document.querySelector('.settings');
    let isFirstClick = true;

    // Add click listener to all settings buttons
    settingsButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            if (isFirstClick) {
                settingsPanel.classList.add('active');
                isFirstClick = false;
            } else {
                settingsPanel.classList.toggle('active');
                settingsPanel.classList.toggle('inactive');
            }
        });
    });

    // Close settings when clicking outside
    document.addEventListener('click', function(e) {
        const clickedOnButton = Array.from(settingsButtons).some(button => button.contains(e.target));
        if (!settingsPanel.contains(e.target) && !clickedOnButton) {
            if (!isFirstClick) {
                settingsPanel.classList.remove('active');
                settingsPanel.classList.add('inactive');
            }
        }
    });
}

// Theme functionality
const themes = {
    default: {
        main: '#202030',
        secondary: '#39304A',
        accent: '#783cb1',
        accentSecondary: '#b338ec',
        white: '#F2F4F3'
    },
    dark: {
        main: '#161620',
        secondary: '#251D30',
        accent: '#5C2E88',
        accentSecondary: '#8A2BB3',
        white: '#E0E2E1'
    },
    light: {
        main: '#F0F0F5',
        secondary: '#E6E0F0',
        accent: '#783cb1',
        accentSecondary: '#b338ec',
        white: '#202030'
    },
    blue: {
        main: '#1A1A2E',
        secondary: '#16213E',
        accent: '#0F52BA',
        accentSecondary: '#0066CC',
        white: '#F0F6FF'
    },
    mono: {
        main: '#1A1A1A',
        secondary: '#2A2A2A',
        accent: '#404040',
        accentSecondary: '#505050',
        white: '#FFFFFF'
    }
};

function initThemeControl() {
    const themeSelect = document.getElementById('themeSelect');
    
    // Load saved theme from cookie or default
    const savedTheme = getCookie('siteTheme') || 'default';
    themeSelect.value = savedTheme;
    applyTheme(savedTheme);
    
    themeSelect.addEventListener('change', function(e) {
        const selectedTheme = e.target.value;
        applyTheme(selectedTheme);
        setCookie('siteTheme', selectedTheme, 365); // Store for 1 year
    });
}

function applyTheme(themeName) {
    const root = document.documentElement;
    const theme = themes[themeName];
    
    root.style.setProperty('--main', theme.main);
    root.style.setProperty('--secondary', theme.secondary);
    root.style.setProperty('--accent', theme.accent);
    root.style.setProperty('--accent-secondary', theme.accentSecondary);
    root.style.setProperty('--white', theme.white);
}

// Table options checkboxes (alternating rows, dark mode, borders)
function initTableOptionsControl() {
    const altRows = document.getElementById('tableAltRows');
    const tableTheme = document.getElementById('tableThemeSelect');
    const borderStyle = document.getElementById('tableBorderStyle');

    // Load saved preferences (dark as default)
    altRows.checked = localStorage.getItem('tableAltRows') !== 'false';
    tableTheme.value = localStorage.getItem('tableTheme') || 'dark';
    borderStyle.value = localStorage.getItem('tableBorderStyle') || 'all';

    function applyTableOptions() {
        document.querySelectorAll('table').forEach(table => {
            // Alternating row color
            table.classList.toggle('table-alt-rows', altRows.checked);

            // Remove all theme classes first
            table.classList.remove('table-theme-dark', 'table-theme-light', 'table-theme-kurple');
            table.classList.add('table-theme-' + tableTheme.value);

            // Remove all border classes first
            table.classList.remove(
                'table-border-none',
                'table-border-row',
                'table-border-column',
                'table-border-all'
            );
            // Add selected border class
            table.classList.add('table-border-' + borderStyle.value);

            // Always add a surrounding border
            table.classList.add('table-has-outer-border');
        });
    }

    altRows.addEventListener('change', function() {
        localStorage.setItem('tableAltRows', altRows.checked);
        applyTableOptions();
    });
    tableTheme.addEventListener('change', function() {
        localStorage.setItem('tableTheme', tableTheme.value);
        applyTableOptions();
    });
    borderStyle.addEventListener('change', function() {
        localStorage.setItem('tableBorderStyle', borderStyle.value);
        applyTableOptions();
    });

    // Apply on load
    applyTableOptions();
}

// Initialize controls on DOMContentLoaded
document.addEventListener('DOMContentLoaded', function() {
    initVolumeControl();
    initSettingsPanel();
    initThemeControl();
    initTableOptionsControl();

    // Cursor select (if present)
    const cursorSelect = document.getElementById('cursorSelect');
    if (cursorSelect) {
        const savedCursor = localStorage.getItem('cursor');
        if (savedCursor) {
            document.body.style.cursor = savedCursor;
            cursorSelect.value = savedCursor;
        }
        cursorSelect.addEventListener('change', function() {
            const selectedCursor = this.value;
            if (selectedCursor === 'heart' || selectedCursor === 'star') {
                document.body.style.cursor = `url('/assets/cursors/${selectedCursor}.cur'), auto`;
            } else {
                document.body.style.cursor = selectedCursor;
            }
            localStorage.setItem('cursor', selectedCursor);
        });
    }

    // Mobile nav menu (support multiple navs)
    document.querySelectorAll('.nav').forEach(nav => {
        const menuBtn = nav.querySelector('#mobile-menu-btn');
        const parentElements = nav.querySelectorAll('.parent');
        let expanded = false;

        if (menuBtn && nav) {
            menuBtn.addEventListener('click', function() {
                if (!expanded) {
                    nav.style.height = '70vh';
                    parentElements.forEach(el => el.style.display = 'block');
                    expanded = true;
                } else {
                    nav.style.height = '';
                    parentElements.forEach(el => el.style.display = '');
                    expanded = false;
                }
            });
        }
    });
});