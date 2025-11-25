function toggleMobileMenu() {
    const navMenu = document.getElementById('navMenu');
    navMenu.classList.toggle('active');
}

document.addEventListener('click', function(event) {
    const navMenu = document.getElementById('navMenu');
    const navToggle = document.querySelector('.nav-toggle');
    
    if (navMenu && navToggle && !navMenu.contains(event.target) && !navToggle.contains(event.target)) {
        navMenu.classList.remove('active');
    }
});


const css = (varName) =>
    getComputedStyle(document.documentElement).getPropertyValue(varName);

function renderKesehatanPemudaChart(data) {
    const ctx = document
        .getElementById(data.canvas)
        .getContext("2d");

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: data.tahun,
            datasets: [
                {
                    type: 'line',
                    label: data.angka_kesakitan.label,
                    data: data.angka_kesakitan.data,
                    borderColor: css("--primary-blue"),
                    backgroundColor: css("--primary-blue"),
                    pointRadius: 5,
                    borderWidth: 3,
                    tension: 0.3,
                    yAxisID: 'y2'
                },
                {
                    type: 'bar',
                    label: data.keluhan_kesehatan.label,
                    data: data.keluhan_kesehatan.data,
                    backgroundColor: css("--primary-green") + "55",
                    borderColor: css("--primary-green"),
                    borderWidth: 1,
                    yAxisID: 'y1'
                }
            ]
        },

        options: {
            responsive: true,
            maintainAspectRatio: false,

            scales: {
                y1: {
                    type: 'linear',
                    position: 'left',
                    ticks: { color: css("--text-dark") }
                },
                y2: {
                    type: 'linear',
                    position: 'right',
                    ticks: { color: css("--text-dark") },
                    grid: { drawOnChartArea: false }
                }
            },

            plugins: {
                legend: {
                    labels: {
                        color: css("--text-dark")
                    }
                }
            }
        }
    });
}

fetch("assets/data/statistik.json")
    .then(res => res.json())
    .then(json => {
        const data = json.kesehatan_pemuda;
        renderKesehatanPemudaChart(data);
    })
    .catch(err => console.error("Gagal memuat JSON:", err));
