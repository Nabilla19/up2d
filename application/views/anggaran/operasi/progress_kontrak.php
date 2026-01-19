<main class="main-content position-relative border-radius-lg ">
    <?php $this->load->view('layout/navbar'); ?>

    <!-- Content -->
    <div class="container-fluid py-4">
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger text-white"><?= htmlentities($error) ?></div>
        <?php endif; ?>

        <div class="card mb-4 shadow border-0 rounded-4">
            <div class="card-header py-2 d-flex justify-content-between align-items-center bg-gradient-primary text-white rounded-top-4">
                <h6 class="mb-0 text-white"><i class="fas fa-file-contract me-2"></i>Data Input Kontrak</h6>
                <div class="d-flex align-items-center">
                    <a href="<?= base_url('anggaran/operasi/add_progress_kontrak'); ?>" class="btn btn-sm btn-light text-primary me-2 d-flex align-items-center no-anim">
                        <i class="fas fa-plus me-1"></i> Tambah
                    </a>
                    <a href="#" class="btn btn-sm btn-light text-secondary d-flex align-items-center no-anim" onclick="downloadCSVProgressKontrak()">
                        <i class="fas fa-file-csv me-1"></i> Download CSV
                    </a>
                </div>
            </div>

            <div class="card-body px-0 pt-0 pb-2 bg-white">

                <!-- FILTERS -->
                <div class="px-3 mt-3 mb-3 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <label class="mb-0 me-2 text-sm">Tampilkan:</label>
                        <select id="perPageSelectProgressKontrak" class="form-select form-select-sm" style="width: 80px;" onchange="changePerPageProgressKontrak(this.value)">
                            <option value="5">5</option>
                            <option value="10" selected>10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="200">200</option>
                        </select>
                        <span class="ms-3 text-sm">dari <?= count($rows) ?> data</span>
                    </div>

                    <input type="text" id="searchInputProgressKontrak" onkeyup="searchTableProgressKontrak()"
                        class="form-control form-control-sm rounded-3"
                        style="max-width: 300px;"
                        placeholder="Cari data Progress Kontrak...">
                </div>

                <!-- TABLE -->
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0" id="progressKontrakTable">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    No
                                </th>

                                <?php foreach ($fields as $f): ?>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        <?= htmlentities($f) ?>
                                    </th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>

                        <tbody id="tableBodyProgressKontrak">
                            <?php if (empty($rows)): ?>
                                <tr>
                                    <td colspan="<?= 1 + count($fields) ?>" class="text-center text-secondary py-4">
                                        Belum ada data
                                    </td>
                                </tr>
                            <?php else: ?>

                                <?php $no = 1; ?>
                                <?php foreach ($rows as $r): ?>
                                    <tr>
                                        <!-- Kolom No pasti tampil -->
                                        <td class="text-sm"><?= $no++; ?></td>

                                        <?php foreach ($fields as $f): ?>
                                            <td class="text-sm">
                                                <?= htmlentities((string)$r[$f]) ?>
                                            </td>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endforeach; ?>

                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- PAGINATION -->
                <div class="card-footer d-flex justify-content-end">
                    <nav>
                        <ul class="pagination pagination-sm mb-0" id="paginationProgressKontrak"></ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
    .asset-pagination .page-link {
        border-radius: 50%;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .asset-pagination .page-link.last {
        border-radius: 20px;
        width: auto;
        padding: 0 16px;
    }
</style>

<script>
    let currentPage = 1;
    let perPage = 10;

    // Ambil semua row berdasarkan filter
    function getFilteredRows() {
        const filter = (document.getElementById('searchInputProgressKontrak').value || '').toUpperCase();
        const rows = Array.from(document.querySelectorAll('#progressKontrakTable tbody tr'));

        if (!filter) return rows;
        return rows.filter(r => r.textContent.toUpperCase().includes(filter));
    }

    // Render tabel + nomor urut
    function renderTableProgressKontrak() {
        const rows = getFilteredRows();
        const total = rows.length;

        const start = (currentPage - 1) * perPage;
        const end = start + perPage;

        let nomor = start + 1;

        rows.forEach((row, i) => {
            if (i >= start && i < end) {
                row.style.display = '';
                row.querySelector('.no-col').innerText = nomor++; // NOMOR OTOMATIS
            } else {
                row.style.display = 'none';
            }
        });

        renderPaginationProgressKontrak(total);
    }

    // Render pagination
    function renderPaginationProgressKontrak(total) {
        const pagination = document.getElementById('paginationProgressKontrak');
        pagination.innerHTML = '';

        const totalPages = Math.ceil(total / perPage);
        if (totalPages <= 1) return;

        pagination.appendChild(createPageItem('«', currentPage > 1, () => setPage(currentPage - 1)));

        for (let i = 1; i <= totalPages; i++) {
            pagination.appendChild(createPageItem(i, true, () => setPage(i), i === currentPage));
        }

        pagination.appendChild(createPageItem('»', currentPage < totalPages, () => setPage(currentPage + 1)));
        pagination.appendChild(createPageItem('Last', currentPage < totalPages, () => setPage(totalPages), false, true));
    }

    // Helper pagination
    function createPageItem(text, enabled, onClick, active = false, last = false) {
        const li = document.createElement('li');
        li.className = 'page-item' + (enabled ? '' : ' disabled') + (active ? ' active' : '');

        const a = document.createElement('a');
        a.className = 'page-link' + (last ? ' last' : '');
        a.href = '#';
        a.innerText = text;

        if (enabled) a.onclick = e => {
            e.preventDefault();
            onClick();
        };
        li.appendChild(a);
        return li;
    }

    function setPage(p) {
        currentPage = p;
        renderTableProgressKontrak();
    }

    function changePerPageProgressKontrak(v) {
        perPage = parseInt(v);
        currentPage = 1;
        renderTableProgressKontrak();
    }

    function searchTableProgressKontrak() {
        currentPage = 1;
        renderTableProgressKontrak();
    }

    // Download CSV
    function downloadCSVProgressKontrak() {
        let csv = '';
        const headers = Array.from(document.querySelectorAll('#progressKontrakTable thead th')).map(h => `"${h.innerText}"`);
        csv += headers.join(',') + "\n";

        const rows = getFilteredRows();
        rows.forEach((row, index) => {
            const cols = [`"${index + 1}"`]; // Auto number in CSV
            row.querySelectorAll('td:not(.no-col)').forEach(td => cols.push(`"${td.innerText}"`));
            csv += cols.join(',') + "\n";
        });

        const blob = new Blob([csv], {
            type: 'text/csv'
        });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = "progress_kontrak.csv";
        link.click();
    }

    document.addEventListener('DOMContentLoaded', renderTableProgressKontrak);
</script>