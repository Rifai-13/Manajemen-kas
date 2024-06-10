// Mengonversi data pendapatan harian ke format Chart.js
const dailyLabels = dailyIncomeData.map(entry => entry.date);
const dailyTotals = dailyIncomeData.map(entry => entry.total);

// Mengonversi data pendapatan mingguan ke format Chart.js
const weeklyLabels = weeklyIncomeData.map(entry => entry.week);
const weeklyTotals = weeklyIncomeData.map(entry => entry.total);

// Mengonversi data pendapatan bulanan ke format Chart.js
const monthlyLabels = monthlyIncomeData.map(entry => entry.month);
const monthlyTotals = monthlyIncomeData.map(entry => entry.total);

// Data for Daily Income Chart
const dailyIncomeChartData = {
    labels: dailyLabels,
    datasets: [{
        label: 'Daily Income',
        data: dailyTotals,
        backgroundColor: 'rgba(54, 162, 235, 0.2)',
        borderColor: 'rgba(54, 162, 235, 1)',
        borderWidth: 1
    }]
};

// Data for Weekly Income Chart
const weeklyIncomeChartData = {
    labels: weeklyLabels,
    datasets: [{
        label: 'Weekly Income',
        data: weeklyTotals,
        backgroundColor: 'rgba(75, 192, 192, 0.2)',
        borderColor: 'rgba(75, 192, 192, 1)',
        borderWidth: 1
    }]
};

// Data for Monthly Income Chart
const monthlyIncomeChartData = {
    labels: monthlyLabels,
    datasets: [{
        label: 'Monthly Income',
        data: monthlyTotals,
        backgroundColor: 'rgba(153, 102, 255, 0.2)',
        borderColor: 'rgba(153, 102, 255, 1)',
        borderWidth: 1
    }]
};

// Daily Income Chart
const dailyIncomeChartCtx = document.getElementById('dailyIncomeChart').getContext('2d');
const dailyIncomeChart = new Chart(dailyIncomeChartCtx, {
    type: 'line',
    data: dailyIncomeChartData,
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Weekly Income Chart
const weeklyIncomeChartCtx = document.getElementById('weeklyIncomeChart').getContext('2d');
const weeklyIncomeChart = new Chart(weeklyIncomeChartCtx, {
    type: 'line',
    data: weeklyIncomeChartData,
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Monthly Income Chart
const monthlyIncomeChartCtx = document.getElementById('monthlyIncomeChart').getContext('2d');
const monthlyIncomeChart = new Chart(monthlyIncomeChartCtx, {
    type: 'line',
    data: monthlyIncomeChartData,
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Ambil elemen total pendapatan
const totalIncomeElement = document.getElementById('totalIncome');

// Fungsi untuk mengupdate total pendapatan pada bulan yang dipilih
function updateTotalIncome(month) {
    // Kirim permintaan AJAX ke server untuk mendapatkan total pendapatan pada bulan yang dipilih
    const xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                const totalIncome = xhr.responseText;
                totalIncomeElement.textContent = totalIncome;
            } else {
                console.error('Failed to fetch total income.');
            }
        }
    };
    xhr.open('GET', 'get_total_income.php?month=' + month);
    xhr.send();
}

// Event listener untuk perubahan bulan
monthSelector.addEventListener('change', function() {
    const selectedMonth = this.value;
    updateTotalIncome(selectedMonth);
});

// Panggil fungsi untuk menginisialisasi total pendapatan pada bulan ini
updateTotalIncome(currentMonth);