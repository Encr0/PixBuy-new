function normalize(str) {
    return (str || '')
        .toLowerCase()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .replace(/ñ/g, 'n')
        .replace(/\s+/g, '-');
}

// Elementos de filtro
const searchInput = document.getElementById('searchInput');
const filterButtons = document.querySelectorAll('.filter-btn');
const consoleSelect = document.getElementById('consoleSelect');
const productCards = document.querySelectorAll('.game-card');

// Filtro combinado principal
function filterGames() {
    const query = normalize(searchInput.value);
    const activeBtn = document.querySelector('.filter-btn.active');
    const filter = activeBtn ? normalize(activeBtn.dataset.filter) : 'all';
    const selectedConsole = consoleSelect.value;

    productCards.forEach(card => {
        // Filtrado por texto (nombre)
        const title = normalize(card.querySelector('.game-title').textContent);

        // Filtrado por categoría/género/subgénero
        const category = normalize(card.dataset.category);
        const genre = normalize(card.querySelector('.genre-badge')?.textContent || '');
        const subgenre = normalize(card.querySelector('.subgenre-badge')?.textContent || '');
        const matchesCategory = (
            filter === 'all' ||
            category === filter ||
            genre === filter ||
            subgenre === filter
        );

        // Filtrado por consola (si no es "all")
        const consoles = card.getAttribute('data-consoles').split(',').map(c => c.trim());
        const matchesConsole = (selectedConsole === 'all') || consoles.includes(selectedConsole);

        // Mostrar solo si cumple todos los criterios
        if (title.includes(query) && matchesCategory && matchesConsole) {
            card.style.display = '';
        } else {
            card.style.display = 'none';
        }
    });
}

// Eventos de búsqueda y filtros
searchInput.addEventListener('input', filterGames);

filterButtons.forEach(btn => {
    btn.addEventListener('click', function() {
        filterButtons.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        filterGames();
    });
});

consoleSelect.addEventListener('change', filterGames);

// Inicializa mostrando todos los juegos
filterGames();
