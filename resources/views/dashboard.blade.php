@extends('layout.index')
@section('title', 'dashboard')

@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet/dist/leaflet.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/chart.js/dist/chart.min.css">
<style>
    #map { height: 400px; width: 100%; }
</style>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/jquery"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/leaflet"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const wakafData = @json($wakafs);

    const map = L.map('map').setView([-6.9147, 107.6098], 8);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(map);

    wakafData.forEach(item => {
        if (item.latitude && item.longitude) {
            L.marker([item.latitude, item.longitude])
                .bindPopup(`<b>${item.nama_aset}</b><br>${item.lokasi}<br>Status: ${item.status}`)
                .addTo(map);
        }
    });

    const labels = wakafData.map(w => w.lokasi);
    const counts = {};
    labels.forEach(l => counts[l] = (counts[l] || 0) + 1);

    new Chart(document.getElementById('wakafChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: Object.keys(counts),
            datasets: [{
                label: 'Jumlah Wakaf',
                data: Object.values(counts),
                backgroundColor: 'rgba(54, 162, 235, 0.7)'
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    const zakatData = @json($zakatSedekahs);

    const map2 = L.map('map-zakat').setView([-6.9147, 107.6098], 8);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(map2);

    zakatData.forEach(item => {
        if (item.latitude && item.longitude) {
        L.marker([item.latitude, item.longitude])
            .bindPopup(`<b>${item.nama_pemberi}</b><br>${item.jenis} - Rp${item.nominal}<br>${item.wilayah}`)
            .addTo(map2);
        }
    });

    const wilayah = {};
    zakatData.forEach(item => {
        wilayah[item.wilayah] = (wilayah[item.wilayah] || 0) + parseInt(item.nominal);
    });

    new Chart(document.getElementById('chartZakat').getContext('2d'), {
        type: 'bar',
        data: {
            labels: Object.keys(wilayah),
            datasets: [{
                label: 'Total Dana (Rp)',
                data: Object.values(wilayah),
                backgroundColor: 'rgba(255, 159, 64, 0.7)'
            }]
            },
            options: {
            responsive: true,
            scales: {
                y: {
                beginAtZero: true
                }
            }
        }
    });
});
</script>
@endsection


@section('content')
<h1 class="mt-1">Dashboard</h1>
<!-- Default box -->
<div class="card mt-3">
    <div class="card-header">
        <h3 class="card-title">Wakaf</h3>

        <div class="card-tools">
        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
            <i class="fas fa-minus"></i>
        </button>
        <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
            <i class="fas fa-times"></i>
        </button>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                <div class="card-header">Peta Persebaran Wakaf</div>
                <div class="card-body">
                    <div id="map"></div>
                </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                <div class="card-header">Statistik Wakaf per Wilayah</div>
                <div class="card-body">
                    <canvas id="wakafChart" height="200"></canvas>
                </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer">
        Footer
    </div>
</div>

<div class="card mt-3">
    <div class="card-header">
        <h3 class="card-title">Zakat & Sedekah</h3>

        <div class="card-tools">
        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
            <i class="fas fa-minus"></i>
        </button>
        <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
            <i class="fas fa-times"></i>
        </button>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                <div class="card-header">Peta Persebaran Wakaf</div>
                <div class="card-body">
                    <div id="map-zakat"></div>
                </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                <div class="card-header">Statistik Wakaf per Wilayah</div>
                <div class="card-body">
                    <canvas id="chartZakat" height="200"></canvas>
                </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer">
        Footer
    </div>
</div>
<!-- /.card -->
@endsection