
    // Create intersection observer
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const player = entry.target;
                if (!player.querySelector('audio')) {
                    loadAudioPlayer(player);
                }
                // Unobserve after loading
                observer.unobserve(player);
            }
        });
    }, {
        rootMargin: '50px 0px' // Start loading when within 50px of viewport
    });

    // Function to load audio player
    function loadAudioPlayer(playerDiv) {
    const album = encodeURIComponent(playerDiv.dataset.album);
    const song = encodeURIComponent(playerDiv.dataset.song);
    const discog = encodeURIComponent(playerDiv.dataset.discog);
    const mp3_url = `https://emacs-expressions-be-customers.trycloudflare.com/${discog}/${album}/${song}.mp3`;

    const audio = document.createElement('audio');
    audio.controls = true;
    audio.innerHTML = `<source src="${mp3_url}" type="audio/mpeg">`;
    
    playerDiv.querySelector('.audio-placeholder').remove();
    playerDiv.appendChild(audio);
}

    // Observe all audio player divs
    document.querySelectorAll('.audio-player').forEach(player => {
        observer.observe(player);
    });

    // Handle column visibility toggle
    document.querySelector('.toggle-column[data-column="3"]').addEventListener('change', function() {
        const audioPlayers = document.querySelectorAll('.audio-player');
        audioPlayers.forEach(player => {
            player.style.display = this.checked ? 'block' : 'none';
        });
    });

// Function to set all audio elements' volume to 5%
function setAllAudioVolume() {
    // Get all audio elements on the page
    const audioElements = document.getElementsByTagName('audio');
    
    // Convert volume percentage to decimal (5% = 0.05)
    const volumeLevel = 0.05;
    
    // Set volume for each audio element
    Array.from(audioElements).forEach(audio => {
        audio.volume = volumeLevel;
    });
}

// Call the function when the page loads
document.addEventListener('DOMContentLoaded', setAllAudioVolume);

// Also set volume for any new audio elements that might be added dynamically
const volumeObserver = new MutationObserver(mutations => {
    mutations.forEach(mutation => {
        mutation.addedNodes.forEach(node => {
            if (node.nodeName === 'AUDIO') {
                node.volume = 0.05;
            }
        });
    });
});

volumeObserver.observe(document.body, { childList: true, subtree: true });