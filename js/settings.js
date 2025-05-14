let currentVolume = 1;  // Default volume

// Add volume slider HTML to your interface (add this to your HTML file)
// <input type="range" id="volumeSlider" min="0" max="1" step="0.1" value="1">

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
    const settingsButton = document.getElementById('settings-button');
    const settingsPanel = document.querySelector('.settings');

    // Prevent the default link behavior and toggle settings
    settingsButton.addEventListener('click', function(e) {
        e.preventDefault();
        settingsPanel.classList.toggle('active');
    });

    // Close settings when clicking outside
    document.addEventListener('click', function(e) {
        if (!settingsPanel.contains(e.target) && !settingsButton.contains(e.target)) {
            settingsPanel.classList.remove('active');
        }
    });
}

// Initialize both volume control and settings panel
document.addEventListener('DOMContentLoaded', function() {
    initVolumeControl();
    initSettingsPanel();
});