<?php
include '../config.php';

// Fetch data for the specific id
$id = $_GET['id'];
$sql = "SELECT * FROM resev_ruangan WHERE id = '$id'";
$re = mysqli_query($conn, $sql);
$row = mysqli_fetch_array($re);

$nama_dosen = $row['nama_dosen'];
$matkul = $row['matkul'];
$jam_mulai = $row['jam_mulai'];
$tanggal = $row['tanggal'];
$jam_selesai = $row['jam_selesai'];
$no_ruang = $row['no_ruang'];
$fakultas = $row['fakultas'];

if (isset($_POST['guestdetailedit'])) {
    $EditName = $_POST['Name'];
    $EditMatkul = $_POST['matkul'];
    $EditFakultas = $_POST['fakultas'];
    $EditNoRuang = $_POST['noRuang'];
    $EditJamMulai = $_POST['cin'];
    $EditJamSelesai = $_POST['cout'];
    $EditTanggal = $_POST['date'];

    $sql = "UPDATE resev_ruangan SET
            nama_dosen = '$EditName',
            matkul = '$EditMatkul',
            fakultas = '$EditFakultas',
            no_ruang = '$EditNoRuang',
            jam_mulai = '$EditJamMulai',
            jam_selesai = '$EditJamSelesai',
            tanggal = '$EditTanggal'
            WHERE id = '$id'";

    $result = mysqli_query($conn, $sql);

    if ($result) {
        header("Location: roombook.php");
    } else {
        echo "<script>swal({
            title: 'Something went wrong',
            icon: 'error',
        });</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link rel="stylesheet" href="./css/roombook.css">
    <style>
        #editpanel {
            position: fixed;
            z-index: 1000;
            height: 100%;
            width: 100%;
            display: flex;
            justify-content: center;
            background-color: #00000079;
        }
        #editpanel .guestdetailpanelform {
            height: 620px;
            width: 1170px;
            background-color: #ccdff4;
            border-radius: 10px;
            position: relative;
            top: 20px;
            animation: guestinfoform .3s ease;
        }
    </style>
    <title>Edit Reservation</title>
</head>
<body>
    <div id="editpanel">
        <form method="POST" class="guestdetailpanelform">
            <div class="head">
                <h3>EDIT RESERVATION</h3>
                <a href="./roombook.php"><i class="fa-solid fa-circle-xmark"></i></a>
            </div>
            <div class="middle">
                <div class="guestinfo">
                    <h4>Guest information</h4>
                    <input type="text" name="Name" placeholder="Enter Full name" value="<?php echo $nama_dosen; ?>">
                    <input type="text" name="matkul" placeholder="Enter Matkul" value="<?php echo $matkul; ?>">
                </div>
                <div class="line"></div>
                <div class="reservationinfo">
                    <h4>Reservation information</h4>
                    <input type="text" name="fakultas" placeholder="Enter Fakultas" value="<?php echo $fakultas; ?>">
                    <select name="noRuang" class="selectinput">
                        <option value="" selected>Select Room</option>
                        <?php
$ruangAll = mysqli_query($conn, "SELECT DISTINCT no_ruang FROM resev_ruangan");
while ($roomRow = mysqli_fetch_assoc($ruangAll)) {
    $selected = ($roomRow['no_ruang'] == $no_ruang) ? 'selected' : '';
    echo '<option value="' . $roomRow['no_ruang'] . '" ' . $selected . '>' . $roomRow['no_ruang'] . '</option>';
}
?>
                    </select>
                    <div class="datesection">
                        <span>
                            <label for="cin"> Jam Mulai</label>
                            <input name="cin" type="time" value="<?php echo $jam_mulai; ?>">
                        </span>
                        <span>
                            <label for="cout"> Jam Selesai</label>
                            <input name="cout" type="time" value="<?php echo $jam_selesai; ?>">
                        </span>
                    </div>
                    <div class="date">
                        <label for="date">Date</label>
                        <input type="date" name="date" value="<?php echo $tanggal; ?>">
                    </div>
                </div>
            </div>
            <div class="footer">
                <button class="btn btn-success" name="guestdetailedit">Edit</button>
            </div>
        </form>
    </div>
</body>
</html>
