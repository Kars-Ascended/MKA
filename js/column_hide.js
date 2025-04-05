document.addEventListener('DOMContentLoaded', function() {
    const toggles = document.querySelectorAll('.toggle-column');
    
    // Load saved states and apply them
    toggles.forEach(toggle => {
        const columnIndex = toggle.getAttribute('data-column');
        const isHidden = localStorage.getItem(`column_${columnIndex}`) === 'hidden';
        toggle.checked = !isHidden;
        
        // Apply initial state
        const table = document.querySelector('table');
        const cells = table.querySelectorAll(`tr > *:nth-child(${parseInt(columnIndex) + 1})`);
        cells.forEach(cell => {
            cell.classList.toggle('hidden-column', isHidden);
        });

        // Add change listener
        toggle.addEventListener('change', function() {
            const columnIndex = this.getAttribute('data-column');
            const table = document.querySelector('table');
            const cells = table.querySelectorAll(`tr > *:nth-child(${parseInt(columnIndex) + 1})`);
            
            cells.forEach(cell => {
                cell.classList.toggle('hidden-column', !this.checked);
            });

            // Save state
            localStorage.setItem(`column_${columnIndex}`, this.checked ? 'visible' : 'hidden');
        });
    });
});