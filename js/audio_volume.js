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
const observer = new MutationObserver(mutations => {
    mutations.forEach(mutation => {
        mutation.addedNodes.forEach(node => {
            if (node.nodeName === 'AUDIO') {
                node.volume = 0.05;
            }
        });
    });
});

observer.observe(document.body, { childList: true, subtree: true });