<?php
session_start();
include '../config.php';

$filter_date = isset($_POST['filter_date']) ? $_POST['filter_date'] : '';
$filter_time = isset($_POST['filter_time']) ? $_POST['filter_time'] : '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WALIFAROMA - Admin</title>
    <!-- fontowesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- boot -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="css/room.css">
    <style>
        .faculty h1 {
            margin-top: 0.7rem;
            color: #fff;
            font-size: 20px;
        }
        input {
            height: 40px;
            border-radius: 4px;
        }
        input::placeholder {
            text-align: center;
        }
        .filter {
            background: linear-gradient(rgba(251,253,254,0.4),rgba(250,252,253,0.4));
            position: absolute;
            display: flex;
            align-items: center;
            justify-content: center;
            top: 8rem;
            left: 2rem;
            padding: 0.5rem 1rem;
            z-index: 99;
        }
        .filter form {
margin: auto;
        }
        .filter input {
            border-radius: 10px;
            padding: 4px;
            background-color: aquamarine;
            border: none;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="addroomsection">
        <form action="" method="POST">
            <div class="faculty">
                <h1>FST</h1>
            </div>
            <label for="noroom">No Room :</label>
            <input name="noroom" type="number" step="0.01" placeholder="Enter No Room">
            <button type="submit" class="btn btn-success" name="addroom">Add Room</button>
        </form>

        <?php
if (isset($_POST['addroom'])) {
    $noroom = $_POST['noroom'];

    $sql = "INSERT INTO ruangan(noRuang) VALUES ('$noroom')";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        echo "<script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Add Room Success',
                            text: 'Room has been added successfully',
                        }).then(function() {
                            window.location = 'room.php';
                        });
                      </script>";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}
?>
    </div>
    <div class="filter">
    <form method="POST" class="mb-4">
            <label for="filter_date">Filter Date:</label>
            <input type="date" id="filter_date" name="filter_date" value="<?php echo $filter_date; ?>">
            <label for="filter_time">Filter Time:</label>
            <input type="time" id="filter_time" name="filter_time" value="<?php echo $filter_time; ?>">
            <button type="submit" class="btn btn-primary">Filter</button>
        </form>
    </div>

    <div class="room">


        <?php
$sql = "SELECT * FROM ruangan ORDER BY noRuang ASC";
$re = mysqli_query($conn, $sql);

$reservedRooms = [];
if (!empty($filter_date) && !empty($filter_time)) {
    $sql2 = "SELECT no_ruang FROM resev_ruangan WHERE tanggal = '$filter_date' AND jam_mulai <= '$filter_time' AND jam_selesai >= '$filter_time'";
    $res = mysqli_query($conn, $sql2);
    while ($row = mysqli_fetch_array($res)) {
        $reservedRooms[] = $row['no_ruang'];
    }
}

while ($row = mysqli_fetch_array($re)) {
    $noRuang = $row['noRuang'];
    $backgroundColor = in_array($noRuang, $reservedRooms) ? 'background-color: red;' : 'background-color: green;';

    echo "<div class='roombox' style='{$backgroundColor}'>
                        <div class='text-center no-boder'>
                            <h3>{$noRuang}</h3>
                            <a href='roomdelete.php?id={$row['id']}'><button class='btn btn-danger'>Delete</button></a>
                        </div>
                    </div>";
}
?>
    </div>

</body>

</html>
