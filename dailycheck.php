<?php
session_start();
include 'koneksi.php';

// 1. Cek Login (Keamanan)
if (!isset($_SESSION['login_user'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$message = "";
$msg_type = ""; 

// 2. Proses Form Submit (Menyimpan Data ke Database)
if (isset($_POST['save_checkin'])) {
    $water = (int) $_POST['water'];
    $sleep = (float) $_POST['sleep']; 
    $exercise = $_POST['exercise'] === 'yes' ? 1 : 0;
    $mood = (int) $_POST['mood'];
    $today = date('Y-m-d');

    // Cek apakah user sudah check-in hari ini?
    $check_query = "SELECT id FROM health_checkins WHERE user_id = '$user_id' AND checkin_date = '$today'";
    $check_result = mysqli_query($koneksi, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        // UPDATE (Timpa data lama)
        $query = "UPDATE health_checkins SET 
                  water = '$water', 
                  sleep = '$sleep', 
                  exercise = '$exercise', 
                  mood = '$mood' 
                  WHERE user_id = '$user_id' AND checkin_date = '$today'";
        $msg_text = "Data hari ini berhasil diperbarui!";
    } else {
        // INSERT (Data baru)
        $query = "INSERT INTO health_checkins (user_id, water, sleep, exercise, mood, checkin_date) 
                  VALUES ('$user_id', '$water', '$sleep', '$exercise', '$mood', '$today')";
        $msg_text = "Check-in berhasil disimpan!";
    }

    if (mysqli_query($koneksi, $query)) {
        $message = $msg_text;
        $msg_type = "green";
    } else {
        $message = "Terjadi kesalahan: " . mysqli_error($koneksi);
        $msg_type = "red";
    }
}

// 3. Ambil Data untuk Grafik (7 Hari Terakhir dari Database)
$data_labels = [];
$data_water = [];
$data_sleep = [];
$data_mood = [];

$query_chart = "SELECT * FROM health_checkins WHERE user_id = '$user_id' ORDER BY checkin_date ASC LIMIT 7";
$result_chart = mysqli_query($koneksi, $query_chart);

while ($row = mysqli_fetch_assoc($result_chart)) {
    // Format tanggal: 26/11
    $data_labels[] = date('d/m', strtotime($row['checkin_date']));
    $data_water[] = $row['water'];
    $data_sleep[] = $row['sleep'];
    $data_mood[] = $row['mood'];
}

// Kirim data ke JS
$json_labels = json_encode($data_labels);
$json_water = json_encode($data_water);
$json_sleep = json_encode($data_sleep);
$json_mood = json_encode($data_mood);

?>

<!DOCTYPE html>
<html lang="id">
<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Health Check-In</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="assets/style.css">
    
</head>

<body class="healthcheck-body fade-in">

    <header class="healthcheck-header">
        <a href="index.php" class="back-btn">Kembali</a>
        <h1 style="margin: 0; font-size: 22px;">Daily Health Check-In</h1>
    </header>

    <section class="checkin-form">
        <h2>Catatan Kesehatan Hari Ini</h2>

        <label>Jumlah air yang diminum (gelas)</label>
        <input type="number" id="water" min="0" required>

        <label>Durasi tidur (jam)</label>
        <input type="number" id="sleep" min="0" step="0.5" required>

        <label>Sudah berolahraga?</label>
        <select id="exercise" required>
            <option value="yes">Ya</option>
            <option value="no">Tidak</option>
        </select>

        <label>Tingkat stres/mood hari ini (1–5)</label>
        <input type="range" id="mood" min="1" max="5" value="3">

        <button onclick="saveCheckIn()">Simpan Check-In</button>

        <p id="status"></p>
    </section>

    <section class="chart-section">
        <h2>Grafik Perkembangan Mingguan</h2>
        <canvas id="healthChart"></canvas>
    </section>

    <script>
        let healthData = JSON.parse(localStorage.getItem("healthData")) || [];

        function saveCheckIn() {
            const water = document.getElementById("water").value;
            const sleep = document.getElementById("sleep").value;
            const exercise = document.getElementById("exercise").value;
            const mood = document.getElementById("mood").value;

            if (!water || !sleep) {
                document.getElementById("status").innerText = "Isi semua data terlebih dahulu!";
                document.getElementById("status").style.color = "#D32F2F";
                return;
            }

            const today = new Date().toLocaleDateString("id-ID");

            const entry = {
                date: today,
                water: Number(water),
                sleep: Number(sleep),
                exercise: exercise === "yes" ? 1 : 0,
                mood: Number(mood)
            };

            // -----------------------------
            // FITUR BARU: HANYA 1 CHECK-IN / HARI
            // -----------------------------
            const existingIndex = healthData.findIndex(item => item.date === today);

            if (existingIndex !== -1) {
                // Update data hari ini
                healthData[existingIndex] = entry;
            } else {
                // Tambahkan data baru
                healthData.push(entry);
            }

            localStorage.setItem("healthData", JSON.stringify(healthData));
            document.getElementById("status").innerText = "Data berhasil disimpan!";
            document.getElementById("status").style.color = "green";
            updateChart();
        }

        let chart;

        function updateChart() {
            const ctx = document.getElementById("healthChart");

            const recentData = healthData.slice(-7); 
            
            const labels = recentData.map(item => item.date);
            const waterData = recentData.map(item => item.water);
            const sleepData = recentData.map(item => item.sleep);
            const moodData = recentData.map(item => item.mood);

            if (chart) chart.destroy();

            chart = new Chart(ctx, {
                type: "line",
                data: {
                    labels: labels,
                    datasets: [
                        { label: "Air (gelas)", data: waterData, borderWidth: 2, borderColor: '#4e73df' },
                        { label: "Tidur (jam)", data: sleepData, borderWidth: 2, borderColor: '#2ECC71' },
                        { label: "Mood (1-5)", data: moodData, borderWidth: 2, borderColor: '#FFC107' }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        updateChart();
    </script>

</body>
</html>
