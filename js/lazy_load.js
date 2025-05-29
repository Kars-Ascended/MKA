// Create intersection observer
document.addEventListener('DOMContentLoaded', function() {
    const options = {
        root: null,
        rootMargin: '50px',
        threshold: 0.1
    };

    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const playerDiv = entry.target;
                loadAudioPlayer(playerDiv);
                observer.unobserve(playerDiv); // Stop observing once loaded
            }
        });
    }, options);

    // Start observing all audio player divs
    document.querySelectorAll('.audio-player').forEach(playerDiv => {
        observer.observe(playerDiv);
    });

    // Function to load individual audio player
    function loadAudioPlayer(playerDiv) {
        const album = encodeURIComponent(playerDiv.dataset.album);
        const song = encodeURIComponent(playerDiv.dataset.song);
        const discog = encodeURIComponent(playerDiv.dataset.discog);
        const mp3_url = `https://gauge-flying-photos-rebecca.trycloudflare.com/discogs/${discog}/${album}/${song}.mp3`;

        const audio = document.createElement('audio');
        audio.controls = true;
        audio.innerHTML = `<source src="${mp3_url}" type="audio/mpeg">`;
        audio.volume = 0.05;

        // Remove placeholder and add audio element
        const placeholder = playerDiv.querySelector('.audio-placeholder');
        if (placeholder) {
            placeholder.remove();
        }
        playerDiv.appendChild(audio);
    }

    // Handle column visibility toggle
    document.querySelector('.toggle-column[data-column="3"]').addEventListener('change', function() {
        const audioPlayers = document.querySelectorAll('.audio-player');
        audioPlayers.forEach(player => {
            player.style.display = this.checked ? 'block' : 'none';
        });
    });
});

// Function to set all audio elements' volume to 5%
function setAllAudioVolume() {
    const audioElements = document.getElementsByTagName('audio');
    const volumeLevel = 0.05;
    Array.from(audioElements).forEach(audio => {
        audio.volume = volumeLevel;
    });
}