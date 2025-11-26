<?php
session_start();
include 'koneksi.php';

//  Cek Login
if (!isset($_SESSION['login_user'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$message = "";
$msg_type = ""; 

//  Simpan Data ke Database
if (isset($_POST['save_checkin'])) {
    $water = (int) $_POST['water'];
    $sleep = (float) $_POST['sleep']; 
    $exercise = $_POST['exercise'] === 'yes' ? 1 : 0;
    $mood = (int) $_POST['mood'];
    $today = date('Y-m-d');

    // Cek apakah hari ini sudah isi
    $check_query = "SELECT id FROM health_checkins WHERE user_id = '$user_id' AND checkin_date = '$today'";
    $check_result = mysqli_query($koneksi, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        // Jika sudah, Lakukan UPDATE
        $query = "UPDATE health_checkins SET 
                  water = '$water', 
                  sleep = '$sleep', 
                  exercise = '$exercise', 
                  mood = '$mood' 
                  WHERE user_id = '$user_id' AND checkin_date = '$today'";
        $msg_text = "Data hari ini berhasil diperbarui!";
    } else {
        // kalo belum, Lakukan INSERT
        $query = "INSERT INTO health_checkins (user_id, water, sleep, exercise, mood, checkin_date) 
                  VALUES ('$user_id', '$water', '$sleep', '$exercise', '$mood', '$today')";
        $msg_text = "Check-in berhasil disimpan!";
    }

    if (mysqli_query($koneksi, $query)) {
        $message = $msg_text;
        $msg_type = "green";
    } else {
        $message = "Terjadi kesalahan: " . mysqli_error($koneksi);
        $msg_type = "#D32F2F";
    }
}

//Ambil Data Untuk Grafik (7 Hari Terakhir)
$data_labels = [];
$data_water = [];
$data_sleep = [];
$data_mood = [];

$query_chart = "SELECT * FROM health_checkins WHERE user_id = '$user_id' ORDER BY checkin_date ASC LIMIT 7";
$result_chart = mysqli_query($koneksi, $query_chart);

while ($row = mysqli_fetch_assoc($result_chart)) {
    // Format tanggal jadi tgl/bln 
    $data_labels[] = date('d/m', strtotime($row['checkin_date']));
    $data_water[] = $row['water'];
    $data_sleep[] = $row['sleep'];
    $data_mood[] = $row['mood'];
}

// Ubah data PHP ke format JSON biar bisa dibaca Javascript
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

        <ul class="nav-menu" id="navMenu" style="text-align: right; margin-left: auto;">
                
                <li><a href="faskes.php">Faskes</a></li>
                <li><a href="education.php">Education</a></li>
        </ul>
    </header>

    <section class="checkin-form">
        <h2>Catatan Kesehatan Hari Ini</h2>

        <?php if ($message): ?>
            <p style="background: <?php echo $msg_type; ?>; color: white; padding: 10px; border-radius: 5px; text-align: center;">
                <?php echo $message; ?>
            </p>
        <?php endif; ?>

        <form method="POST" action="">
            <label>Jumlah air yang diminum (gelas)</label>
            <input type="number" name="water" id="water" min="0" required placeholder="Contoh: 8">

            <label>Durasi tidur (jam)</label>
            <input type="number" name="sleep" id="sleep" min="0" step="0.5" required placeholder="Contoh: 7.5">

            <label>Sudah berolahraga?</label>
            <select name="exercise" id="exercise" required>
                <option value="yes">Ya</option>
                <option value="no">Tidak</option>
            </select>

            <label>Tingkat stres/mood hari ini (1–5)</label>
            <div style="display: flex; justify-content: space-between; font-size: 12px; color: #666; margin-bottom: 5px;">
                <span>Buruk 😫</span>
                <span>Bahagia 😊</span>
            </div>
            <input type="range" name="mood" id="mood" min="1" max="5" value="3">

            <button type="submit" name="save_checkin">Simpan Check-In</button>
        </form>

    </section>

    <section class="chart-section">
        <h2>Grafik Perkembangan Harian</h2>
        <div style="height: 300px;">
            <canvas id="healthChart"></canvas>
        </div>
    </section>

    <script>
        // Mengambil data JSON dari PHP ke Variabel Javascript
        
        const labels = <?php echo $json_labels; ?>;
        const waterData = <?php echo $json_water; ?>;
        const sleepData = <?php echo $json_sleep; ?>;
        const moodData = <?php echo $json_mood; ?>;

        const ctx = document.getElementById("healthChart").getContext('2d');

        new Chart(ctx, {
            type: "line",
            data: {
                labels: labels,
                datasets: [
                    { 
                        label: "Air (gelas)", 
                        data: waterData, 
                        borderWidth: 2, 
                        borderColor: '#3F8CFF', 
                        backgroundColor: '#3F8CFF',
                        tension: 0.3
                    },
                    { 
                        label: "Tidur (jam)", 
                        data: sleepData, 
                        borderWidth: 2, 
                        borderColor: '#2ECC71',
                        backgroundColor: '#2ECC71',
                        tension: 0.3
                    },
                    { 
                        label: "Mood (1-5)", 
                        data: moodData, 
                        borderWidth: 2, 
                        borderColor: '#FFC107',
                        backgroundColor: '#FFC107',
                        tension: 0.3
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>

</body>
</html>