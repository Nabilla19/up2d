<main class="main-content position-relative border-radius-lg ">
  <?php $this->load->view('layout/navbar'); ?>

  <!-- Content -->
  <div class="container-fluid py-4">
    <?php if (!empty($error)): ?>
      <div class="alert alert-danger text-white">
        <?= htmlentities($error) ?>
      </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('success')): ?>
      <div class="alert alert-success text-white">
        <?= $this->session->flashdata('success'); ?>
      </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('error')): ?>
      <div class="alert alert-danger text-white">
        <?= $this->session->flashdata('error'); ?>
      </div>
    <?php endif; ?>

    <div class="card mb-4 shadow border-0 rounded-4">
      <!-- HEADER -->
      <div class="card-header py-2 d-flex justify-content-between align-items-center bg-gradient-primary text-white rounded-top-4">
        <h6 class="mb-0 d-flex align-items-center text-white"><i class="fas fa-tachometer-alt me-2"></i>Data Monitoring Investasi</h6>
        <div class="d-flex align-items-center" style="padding-top: 16px;">
          <a href="<?= base_url('anggaran/investasi/add_monitoring'); ?>" class="btn btn-sm btn-light text-primary me-2 d-flex align-items-center no-anim">
            <i class="fas fa-plus me-1"></i> Tambah
          </a>
          <a href="#" class="btn btn-sm btn-light text-secondary ms-2 d-flex align-items-center no-anim" onclick="downloadCSVMonitoring(); return false;">
            <i class="fas fa-file-csv me-1"></i> Download CSV
          </a>
        </div>
      </div>

      <div class="card-body px-0 pt-0 pb-2 bg-white">
        <div class="px-3 mt-3 mb-3 d-flex justify-content-between align-items-center">
          <div class="d-flex align-items-center">
            <label class="mb-0 me-2 text-sm">Tampilkan:</label>
            <select id="perPageSelectMonitoring" class="form-select form-select-sm" style="width: 80px; padding-right: 2rem;" onchange="changePerPageMonitoring(this.value)">
              <option value="5">5</option>
              <option value="10" selected>10</option>
              <option value="25">25</option>
              <option value="50">50</option>
              <option value="100">100</option>
              <option value="200">200</option>
            </select>
            <span class="ms-3 text-sm">dari <?= count($rows) ?? 0; ?> data</span>
          </div>
          <input type="text" id="searchInputMonitoring" onkeyup="searchTableMonitoring()" class="form-control form-control-sm rounded-3" style="max-width: 300px;" placeholder="Cari data...">
        </div>

        <div class="table-responsive p-0">
          <table class="table align-items-center mb-0" id="monitoringTable">
            <thead class="bg-light">
              <tr>
                <?php if (!empty($fields)): ?>
                  <?php foreach ($fields as $f): ?>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"><?= htmlentities($f) ?></th>
                  <?php endforeach; ?>
                <?php else: ?>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Belum ada kolom</th>
                <?php endif; ?>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($rows)): ?>
                <tr><td colspan="<?= max(1, count($fields)) ?>" class="text-center text-secondary py-4">Belum ada data</td></tr>
              <?php else: ?>
                <?php foreach ($rows as $r): ?>
                  <tr>
                    <?php foreach ($fields as $f): ?>
                      <td class="text-sm"><?= isset($r[$f]) ? htmlentities((string)$r[$f]) : '' ?></td>
                    <?php endforeach; ?>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
        <div class="card-footer d-flex justify-content-end">
          <nav>
            <ul class="pagination pagination-sm mb-0" id="paginationMonitoring"></ul>
          </nav>
        </div>
      </div>
    </div>
  </div>
</main>

<script>
let currentPageMonitoring = 1;
let perPageMonitoring = 10;

function getFilteredRowsMonitoring() {
  const input = document.getElementById('searchInputMonitoring');
  const filter = (input.value || '').toUpperCase();
  const table = document.getElementById('monitoringTable');
  const allRows = Array.from(table.getElementsByTagName('tbody')[0].getElementsByTagName('tr'));
  if (!filter) return allRows;
  return allRows.filter(row => (row.textContent || row.innerText || '').toUpperCase().indexOf(filter) > -1);
}

function renderTableMonitoring() {
  const table = document.getElementById('monitoringTable');
  const rows = getFilteredRowsMonitoring();
  const total = rows.length;
  const start = (currentPageMonitoring - 1) * perPageMonitoring;
  const end = start + perPageMonitoring;
  rows.forEach((row, i) => row.style.display = (i >= start && i < end) ? '' : 'none');
  renderPaginationMonitoring(total);
}

function renderPaginationMonitoring(total) {
  const pagination = document.getElementById('paginationMonitoring');
  pagination.innerHTML = '';
  const totalPages = Math.ceil(total / perPageMonitoring);
  if (totalPages <= 1) return;
  pagination.appendChild(createPageItemMonitoring('«', currentPageMonitoring > 1, () => setPageMonitoring(currentPageMonitoring - 1)));
  for (let i = 1; i <= totalPages; i++) pagination.appendChild(createPageItemMonitoring(i, true, () => setPageMonitoring(i), i === currentPageMonitoring));
  pagination.appendChild(createPageItemMonitoring('»', currentPageMonitoring < totalPages, () => setPageMonitoring(currentPageMonitoring + 1)));
}

function createPageItemMonitoring(text, enabled, onClick, active = false) {
  const li = document.createElement('li');
  li.className = 'page-item' + (enabled ? '' : ' disabled') + (active ? ' active' : '');
  const a = document.createElement('a');
  a.className = 'page-link';
  a.href = '#';
  a.innerText = text;
  if (enabled) a.onclick = function(e){e.preventDefault(); onClick();};
  li.appendChild(a);
  return li;
}

function setPageMonitoring(page) { currentPageMonitoring = page; renderTableMonitoring(); }
function changePerPageMonitoring(val) { perPageMonitoring = parseInt(val); currentPageMonitoring = 1; renderTableMonitoring(); }
function searchTableMonitoring(){ currentPageMonitoring = 1; renderTableMonitoring(); }

function downloadCSVMonitoring() {
  const table = document.getElementById('monitoringTable');
  let csv = '';
  const rows = getFilteredRowsMonitoring();
  const header = table.querySelectorAll('thead th');
  let headerRow = [];
  for (let h of header) headerRow.push('"' + h.innerText.replace(/"/g, '""') + '"');
  csv += headerRow.join(',') + '\n';
  for (let row of rows) {
    let cols = row.querySelectorAll('td');
    let rowData = [];
    for (let col of cols) rowData.push('"' + col.innerText.replace(/"/g, '""') + '"');
    csv += rowData.join(',') + '\n';
  }
  const blob = new Blob([csv], { type: 'text/csv' });
  const link = document.createElement('a');
  link.href = window.URL.createObjectURL(blob);
  link.download = 'monitoring_inv.csv';
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
}

document.addEventListener('DOMContentLoaded', function() {
  document.getElementById('perPageSelectMonitoring').value = perPageMonitoring;
  renderTableMonitoring();
});
</script>
<main class="main-content position-relative border-radius-lg ">
  <div class="container-fluid py-4">
    <h3>Monitoring (placeholder)</h3>
    <p>Halaman monitoring akan diimplementasikan di sini.</p>
  </div>
</main>
