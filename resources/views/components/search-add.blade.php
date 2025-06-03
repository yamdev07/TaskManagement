<div class="row mb-3 align-items-center">
    <div class="col-md-6">
        <input type="text" id="{{ $searchId ?? 'searchInput' }}" class="form-control" placeholder="{{ $searchPlaceholder ?? 'Rechercher...' }}">
    </div>
    <div class="col-md-6 text-end">
        <a href="{{ $addUrl ?? '#' }}" class="btn btn-success">{{ $addText ?? '+ Ajouter' }}</a>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('{{ $searchId ?? 'searchInput' }}');
        const rows = document.querySelectorAll('{{ $tableRowsSelector ?? "tbody tr" }}');

        if (!searchInput) return;

        searchInput.addEventListener('input', function () {
            const value = this.value.toLowerCase();

            rows.forEach(row => {
                const nom = row.dataset.nom ? row.dataset.nom.toLowerCase() : '';
                const lieu = row.dataset.lieu ? row.dataset.lieu.toLowerCase() : '';

                if (nom.includes(value) || lieu.includes(value)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
</script>
