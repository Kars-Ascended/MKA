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

// Initialize both volume control, settings panel, and theme control
document.addEventListener('DOMContentLoaded', function() {
    initVolumeControl();
    initSettingsPanel();
    initThemeControl();
});