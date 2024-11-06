document.getElementById('searchForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const search = document.getElementById('search').value;
    const role = document.getElementById('role').value;

    fetch(`/admin/users/search?search=${search}&role=${role}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(response => response.json())
        .then(data => {
            const resultsContainer = document.getElementById('results');
            resultsContainer.innerHTML = '';

            resultsContainer.innerHTML = data.html;

            const resultsTitle = document.getElementById('resultsTitle');
            resultsTitle.textContent = `${data.count} Résultats`;

            convertTimes();
        })
        .catch(error => console.error('Error fetching data:', error));
});
