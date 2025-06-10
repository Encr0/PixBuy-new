function normalize(str) {
    return (str || '')
        .toLowerCase()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .replace(/ñ/g, 'n')
        .replace(/\s+/g, '-');
}

const searchInput = document.getElementById('searchInput');
const filterButtons = document.querySelectorAll('.filter-btn');
const productCards = document.querySelectorAll('.game-card');

// Filtrado por texto
searchInput.addEventListener('input', function() {
    const query = normalize(searchInput.value);
    productCards.forEach(card => {
        const title = normalize(card.querySelector('.game-title').textContent);
        card.style.display = title.includes(query) ? '' : 'none';
    });
});

// Filtrado por categoría, género o subgénero
filterButtons.forEach(btn => {
    btn.addEventListener('click', function() {
        filterButtons.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        const filter = normalize(btn.dataset.filter);
        productCards.forEach(card => {
            const category = normalize(card.dataset.category);
            const genre = normalize(card.querySelector('.genre-badge')?.textContent || '');
            const subgenre = normalize(card.querySelector('.subgenre-badge')?.textContent || '');
            if (
                filter === 'all' ||
                category === filter ||
                genre === filter ||
                subgenre === filter
            ) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    });
});
