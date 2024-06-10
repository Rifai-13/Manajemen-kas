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
