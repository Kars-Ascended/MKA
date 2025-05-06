
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
    const mp3_url = `https://cosmetics-key-nationally-blade.trycloudflare.com/${discog}/${album}/${song}.mp3`;

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