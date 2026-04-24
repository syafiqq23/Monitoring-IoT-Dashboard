<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script type="text/javascript" src="{{ asset('jquery/jquery.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<style>
@keyframes blink-soft {
  0%, 100% { opacity: 1; }
  50% { opacity: 0.7; }
}
@keyframes pulse-danger {
  0%   { box-shadow: 0 0 0 0 rgba(220,53,69,0.6); }
  70%  { box-shadow: 0 0 10px 10px rgba(220,53,69,0); }
  100% { box-shadow: 0 0 0 0 rgba(220,53,69,0); }
}
@keyframes pulse-warning {
  0%   { box-shadow: 0 0 0 0 rgba(255,193,7,0.6); }
  70%  { box-shadow: 0 0 10px 10px rgba(255,193,7,0); }
  100% { box-shadow: 0 0 0 0 rgba(255,193,7,0); }
}
.alert-danger-animate  { animation: pulse-danger  1.5s infinite, blink-soft 1.5s infinite; }
.alert-warning-animate { animation: pulse-warning 2s   infinite, blink-soft 2s   infinite; }
</style>

<script>
    // ── Batas Normal Ruang Server ──
    const TEMP_MIN_NORMAL = 18, TEMP_MAX_NORMAL = 27;
    const TEMP_MIN_WARN   = 15, TEMP_MAX_WARN   = 30;
    const HUM_MIN_NORMAL  = 45, HUM_MAX_NORMAL  = 60;
    const HUM_MIN_WARN    = 40, HUM_MAX_WARN    = 70;

    // ── Setup Chart (kosong dulu, diisi setelah fetch) ──
    const chartSuhu = new Chart(document.getElementById('chartSuhu').getContext('2d'), {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Temperature (°C)',
                data: [],
                borderColor: '#FFC107',
                backgroundColor: 'rgba(255,193,7,0.1)',
                tension: 0.4,
                fill: true,
            }]
        },
        options: { scales: { y: { min: 10, max: 40 } }, animation: false }
    });

    const chartHum = new Chart(document.getElementById('chartHumidity').getContext('2d'), {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Humidity (%)',
                data: [],
                borderColor: '#17a2b8',
                backgroundColor: 'rgba(23,162,184,0.1)',
                tension: 0.4,
                fill: true,
            }]
        },
        options: { scales: { y: { min: 20, max: 100 } }, animation: false }
    });

    // ── Fetch chart dari database ──
    // Dipanggil saat halaman dibuka + tiap 30 menit
    function muatChart() {
        $.get("{{ url('chart-hari-ini') }}", function(res) {
            chartSuhu.data.labels            = res.labels;
            chartSuhu.data.datasets[0].data  = res.temp;
            chartSuhu.update();

            chartHum.data.labels             = res.labels;
            chartHum.data.datasets[0].data   = res.hum;
            chartHum.update();
        });
    }

    // Load saat halaman pertama dibuka
    muatChart();

    // Refresh chart tiap 30 menit
    setInterval(muatChart, 3600000);

    // ── Alert ──
    function cekAlert(suhu, hum) {
        let html = '';

        if (suhu > TEMP_MAX_WARN) {
            html += `<div class="alert alert-danger alert-dismissible fade show alert-danger-animate" role="alert">
                        🔴 <strong>BAHAYA!</strong> Suhu ${suhu}°C di atas batas aman!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>`;
        } else if (suhu < TEMP_MIN_WARN) {
            html += `<div class="alert alert-danger alert-dismissible fade show alert-danger-animate" role="alert">
                        🔴 <strong>BAHAYA!</strong> Suhu ${suhu}°C di bawah batas aman!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>`;
        } else if (suhu > TEMP_MAX_NORMAL) {
            html += `<div class="alert alert-warning alert-dismissible fade show alert-warning-animate" role="alert">
                        ⚠️ <strong>PERINGATAN!</strong> Suhu ${suhu}°C mendekati batas atas!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>`;
        } else if (suhu < TEMP_MIN_NORMAL) {
            html += `<div class="alert alert-warning alert-dismissible fade show alert-warning-animate" role="alert">
                        ⚠️ <strong>PERINGATAN!</strong> Suhu ${suhu}°C mendekati batas bawah!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>`;
        }

        if (hum > HUM_MAX_WARN) {
            html += `<div class="alert alert-danger alert-dismissible fade show alert-danger-animate" role="alert">
                        🔴 <strong>BAHAYA!</strong> Humidity ${hum}% di atas batas aman!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>`;
        } else if (hum < HUM_MIN_WARN) {
            html += `<div class="alert alert-danger alert-dismissible fade show alert-danger-animate" role="alert">
                        🔴 <strong>BAHAYA!</strong> Humidity ${hum}% di bawah batas aman!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>`;
        } else if (hum > HUM_MAX_NORMAL) {
            html += `<div class="alert alert-warning alert-dismissible fade show alert-warning-animate" role="alert">
                        ⚠️ <strong>PERINGATAN!</strong> Humidity ${hum}% mendekati batas atas!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>`;
        } else if (hum < HUM_MIN_NORMAL) {
            html += `<div class="alert alert-warning alert-dismissible fade show alert-warning-animate" role="alert">
                        ⚠️ <strong>PERINGATAN!</strong> Humidity ${hum}% mendekati batas bawah!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>`;
        }

        $('#alert-section').hide().html(html).fadeIn(300);
    }

    // ── INTERVAL 1 — Card: fetch tiap 1 detik ──
    setInterval(function() {
        $.get("{{ url('bacasuhu') }}", function(suhu) {
            suhu = parseFloat(suhu);
            $('#suhu').text(suhu);

            $.get("{{ url('bacahumidity') }}", function(hum) {
                hum = parseFloat(hum);
                $('#humidity').text(hum);
                cekAlert(suhu, hum);
            });
        });
    }, 1000);

    // ── INTERVAL 2 — Tabel: refresh tiap 30 detik ──
    setInterval(function() {
        $.get("{{ url('tabel-riwayat') }}", function(data) {
            $('#tbody-riwayat').html(data);
        });
    }, 2000);

    // ── Statistik ──
    function loadStatistik() {
        const start = $('#stat-start').val();
        const end   = $('#stat-end').val();

        $.get("{{ url('statistik') }}", { start: start, end: end }, function(data) {
            $('#avg-temp').text(data.avg_temp + ' °C');
            $('#min-temp').text(data.min_temp + ' °C');
            $('#max-temp').text(data.max_temp + ' °C');
            $('#avg-hum').text(data.avg_hum  + ' %');
            $('#min-hum').text(data.min_hum  + ' %');
            $('#max-hum').text(data.max_hum  + ' %');
        });
    }

    loadStatistik();
</script>
