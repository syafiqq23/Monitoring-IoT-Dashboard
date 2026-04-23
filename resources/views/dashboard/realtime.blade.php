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

    // ── Load data dari localStorage ──
    const maxPoints = 20;
    const hariIni = new Date().toISOString().slice(0, 10);

    function loadFromStorage(storageKey) {
        try {
            const saved = JSON.parse(localStorage.getItem(storageKey));
            if (!saved || saved.date !== hariIni) return { labels: [], data: [] };
            return { labels: saved.labels || [], data: saved.data || [] };
        } catch { return { labels: [], data: [] }; }
    }

    const suhuStore  = loadFromStorage('chart_suhu_v2');
    const humStore   = loadFromStorage('chart_hum_v2');

    const labelsSuhu = suhuStore.labels;
    const dataSuhu   = suhuStore.data;
    const labelsHum  = humStore.labels;
    const dataHum    = humStore.data;

    // ── Setup Chart ──
    const ctxSuhu = document.getElementById('chartSuhu').getContext('2d');
    const chartSuhu = new Chart(ctxSuhu, {
        type: 'line',
        data: {
            labels: labelsSuhu,
            datasets: [{
                label: 'Temperature (°C)',
                data: dataSuhu,
                borderColor: '#FFC107',
                backgroundColor: 'rgba(255,193,7,0.1)',
                tension: 0.4,
                fill: true,
            }]
        },
        options: { scales: { y: { min: 10, max: 40 } }, animation: false }
    });

    const ctxHum = document.getElementById('chartHumidity').getContext('2d');
    const chartHum = new Chart(ctxHum, {
        type: 'line',
        data: {
            labels: labelsHum,
            datasets: [{
                label: 'Humidity (%)',
                data: dataHum,
                borderColor: '#17a2b8',
                backgroundColor: 'rgba(23,162,184,0.1)',
                tension: 0.4,
                fill: true,
            }]
        },
        options: { scales: { y: { min: 20, max: 100 } }, animation: false }
    });

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

    // ── Simpan localStorage ──
    function simpanKeStorage() {
        localStorage.setItem('chart_suhu_v2', JSON.stringify({
            date: hariIni, labels: labelsSuhu, data: dataSuhu
        }));
        localStorage.setItem('chart_hum_v2', JSON.stringify({
            date: hariIni, labels: labelsHum, data: dataHum
        }));
    }

    // ════════════════════════════════════════════════════
    // INTERVAL 1 — Card: fetch tiap 1 detik
    // Hanya update angka di card + cekAlert
    // ════════════════════════════════════════════════════
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

    // ════════════════════════════════════════════════════
    // INTERVAL 2 — Chart + Tabel: fetch tiap 30 menit
    // Update grafik dan tabel riwayat
    // ════════════════════════════════════════════════════
    setInterval(function() {
        const now = new Date().toLocaleTimeString();

        $.get("{{ url('bacasuhu') }}", function(suhu) {
            suhu = parseFloat(suhu);
            if (labelsSuhu.length >= maxPoints) { labelsSuhu.shift(); dataSuhu.shift(); }
            labelsSuhu.push(now);
            dataSuhu.push(suhu);
            chartSuhu.update();
            simpanKeStorage();
        });

        $.get("{{ url('bacahumidity') }}", function(hum) {
            hum = parseFloat(hum);
            if (labelsHum.length >= maxPoints) { labelsHum.shift(); dataHum.shift(); }
            labelsHum.push(now);
            dataHum.push(hum);
            chartHum.update();
            simpanKeStorage();
        });

        $.get("{{ url('tabel-riwayat') }}", function(data) {
            $('#tbody-riwayat').html(data);
        });

    }, 1800000); // ← 30 menit

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
