<?php
session_start();
include '../config.php';

// Check if date is selected
$dateFilter = isset($_POST['date']) ? $_POST['date'] : '';

// roombook
if ($dateFilter) {
    $roombooksql = "SELECT * FROM resev_ruangan WHERE DATE(tanggal) = '$dateFilter'";
} else {
    $roombooksql = "SELECT * FROM resev_ruangan";
}
$roombookre = mysqli_query($conn, $roombooksql);
$roombookrow = mysqli_num_rows($roombookre);

// staff
$staffsql = "SELECT * FROM staff";
$staffre = mysqli_query($conn, $staffsql);
$staffrow = mysqli_num_rows($staffre);

// room
$roomsql = "SELECT * FROM ruangan";
$roomre = mysqli_query($conn, $roomsql);
$roomrow = mysqli_num_rows($roomre);

// roombook roomtype
if ($dateFilter) {
    $chartroom1 = "SELECT * FROM resev_ruangan WHERE no_ruang >= 2 AND no_ruang <= 2.9 AND DATE(tanggal) = '$dateFilter'";
    $chartroom2 = "SELECT * FROM resev_ruangan WHERE no_ruang >= 3 AND no_ruang <= 3.9 AND DATE(tanggal) = '$dateFilter'";
    $chartroom3 = "SELECT * FROM resev_ruangan WHERE no_ruang >= 4 AND no_ruang <= 4.9 AND DATE(tanggal) = '$dateFilter'";
} else {
    $chartroom1 = "SELECT * FROM resev_ruangan WHERE no_ruang >= 2 AND no_ruang <= 2.9";
    $chartroom2 = "SELECT * FROM resev_ruangan WHERE no_ruang >= 3 AND no_ruang <= 3.9";
    $chartroom3 = "SELECT * FROM resev_ruangan WHERE no_ruang >= 4 AND no_ruang <= 4.9";
}

$chartroom1re = mysqli_query($conn, $chartroom1);
$chartroom1row = mysqli_num_rows($chartroom1re);

$chartroom2re = mysqli_query($conn, $chartroom2);
$chartroom2row = mysqli_num_rows($chartroom2re);

$chartroom3re = mysqli_query($conn, $chartroom3);
$chartroom3row = mysqli_num_rows($chartroom3re);

// moriss profit
if ($dateFilter) {
    $query = "SELECT * FROM payment WHERE DATE(cout) = '$dateFilter'";
} else {
    $query = "SELECT * FROM payment";
}
$result = mysqli_query($conn, $query);
$chart_data = '';
$tot = 0;
while ($row = mysqli_fetch_array($result)) {
    $chart_data .= "{ date:'" . $row["cout"] . "', profit:" . $row["finaltotal"] * 10 / 100 . "}, ";
    $tot = $tot + $row["finaltotal"] * 10 / 100;
}
$chart_data = substr($chart_data, 0, -2);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/dashboard.css">
    <!-- chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- morish bar -->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>

    <title>BlueBird - Admin </title>
</head>
<body>
    <div class="databox">

        <div class="box roombookbox">
            <h2>Total Booked Room</h2>
            <h1><?php echo $roombookrow ?> / <?php echo $roomrow ?></h1>
        </div>
        <div class="box guestbox">
            <h2>Total Staff</h2>
            <h1><?php echo $staffrow ?></h1>
        </div>
        <div class="box profitbox">
            <h2>Profit</h2>
            <h1><?php echo $tot ?> <span>&#8377</span></h1>
        </div>
    </div>

    <div class="chartbox">

        <div class="bookroomchart">
        <form method="post" action="">
            <label for="date">Select Date: </label>
            <input type="date" id="date" name="date">
            <input type="submit" value="Filter">
        </form>
            <canvas id="bookroomchart"></canvas>
            <h3 style="text-align: center;margin:10px 0;">Booked Room</h3>
        </div>
        <div class="profitchart">
            <div id="profitchart"></div>
            <h3 style="text-align: center;margin:10px 0;">Profit</h3>
        </div>
    </div>
</body>

<script>
    const labels = [
        'Lantai 2',
        'Lantai 3',
        'Lantai 4',
    ];

    const data = {
        labels: labels,
        datasets: [{
            backgroundColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(153, 102, 255, 1)',
            ],
            borderColor: 'black',
            data: [<?php echo $chartroom1row ?>,<?php echo $chartroom2row ?>,<?php echo $chartroom3row ?>],
        }]
    };

    const doughnutchart = {
        type: 'doughnut',
        data: data,
        options: {}
    };

    const myChart = new Chart(
        document.getElementById('bookroomchart'),
        doughnutchart
    );
</script>

<script>
    Morris.Bar({
        element: 'profitchart',
        data: [<?php echo $chart_data; ?>],
        xkey: 'date',
        ykeys: ['profit'],
        labels: ['Profit'],
        hideHover: 'auto',
        stacked: true,
        barColors: [
            'rgba(153, 102, 255, 1)',
        ]
    });
</script>

</html>
