document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('project_judge_search');
    const selectElement = document.getElementById('project_judge_select');
    const noResultsMsg = document.getElementById('project_no_results');

    if (searchInput && selectElement) {
        searchInput.addEventListener('input', function (e) {
            const searchTerm = e.target.value.toLowerCase().trim();
            const options = selectElement.querySelectorAll('option');
            let hasVisibleOptions = false;

            options.forEach(option => {
                const text = option.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    option.style.display = '';
                    hasVisibleOptions = true;
                } else {
                    option.style.display = 'none';
                }
            });

            // Mostrar/ocultar mensaje de "no hay resultados"
            if (noResultsMsg) {
                noResultsMsg.classList.toggle('hidden', hasVisibleOptions || searchTerm === '');
            }

            // Si no hay resultados visibles, limpiar selección
            if (!hasVisibleOptions && searchTerm !== '') {
                selectElement.selectedIndex = -1;
            }
        });
    }
});
