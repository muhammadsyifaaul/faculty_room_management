<?php
session_start();
include '../config.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- boot -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <!-- fontowesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- sweet alert -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link rel="stylesheet" href="./css/roombook.css">
    <title>WALIFAROMA - Admin</title>
</head>

<body>
    <!-- guestdetailpanel -->

    <div id="guestdetailpanel">
        <form action="" method="POST" class="guestdetailpanelform">
            <div class="head">
                <h3>RESERVATION</h3>
                <i class="fa-solid fa-circle-xmark" onclick="adduserclose()"></i>
            </div>
            <div class="middle">
            <div class="guestinfo">
                    <h4>Guest information</h4>
                    <input type="text" name="Name" placeholder="Enter Full name">
                    <input type="text" name="matkul" placeholder="Enter Matkul">
                </div>

                <div class="line"></div>

                <div class="reservationinfo">
                    <h4>Reservation information</h4>
                    <input type="text" name="fakultas" placeholder="Enter Fakultas">
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
                <button class="btn btn-success" name="guestdetailsubmit">Submit</button>
            </div>
        </form>

        <?php
// <!-- room availablity start-->

$rsql = "select * from room";
$rre = mysqli_query($conn, $rsql);
$r = 0;
$sc = 0;
$gh = 0;
$sr = 0;
$dr = 0;

while ($rrow = mysqli_fetch_array($rre)) {
    $r = $r + 1;
    $s = $rrow['type'];
    if ($s == "Superior Room") {
        $sc = $sc + 1;
    }
    if ($s == "Guest House") {
        $gh = $gh + 1;
    }
    if ($s == "Single Room") {
        $sr = $sr + 1;
    }
    if ($s == "Deluxe Room") {
        $dr = $dr + 1;
    }
}

$csql = "select * from payment";
$cre = mysqli_query($conn, $csql);
$cr = 0;
$csc = 0;
$cgh = 0;
$csr = 0;
$cdr = 0;
while ($crow = mysqli_fetch_array($cre)) {
    $cr = $cr + 1;
    $cs = $crow['RoomType'];

    if ($cs == "Superior Room") {
        $csc = $csc + 1;
    }

    if ($cs == "Guest House") {
        $cgh = $cgh + 1;
    }
    if ($cs == "Single Room") {
        $csr = $csr + 1;
    }
    if ($cs == "Deluxe Room") {
        $cdr = $cdr + 1;
    }
}
// room availablity
// Superior Room =>
$f1 = $sc - $csc;
if ($f1 <= 0) {
    $f1 = "NO";
}
// Guest House =>
$f2 = $gh - $cgh;
if ($f2 <= 0) {
    $f2 = "NO";
}
// Single Room =>
$f3 = $sr - $csr;
if ($f3 <= 0) {
    $f3 = "NO";
}
// Deluxe Room =>
$f4 = $dr - $cdr;
if ($f4 <= 0) {
    $f4 = "NO";
}
//total available room =>
$f5 = $r - $cr;
if ($f5 <= 0) {
    $f5 = "NO";
}
?>
        <!-- room availablity end-->

        <!-- ==== room book php ====-->
        <?php
if (isset($_POST['guestdetailsubmit'])) {
    $Name = $_POST['Name'];
    $Email = $_POST['Email'];
    $Country = $_POST['Country'];
    $Phone = $_POST['Phone'];
    $RoomType = $_POST['RoomType'];
    $Bed = $_POST['Bed'];
    $NoofRoom = $_POST['NoofRoom'];
    $Meal = $_POST['Meal'];
    $cin = $_POST['cin'];
    $cout = $_POST['cout'];

    if ($Name == "" || $Email == "" || $Country == "") {
        echo "<script>swal({
                        title: 'Fill the proper details',
                        icon: 'error',
                    });
                    </script>";
    } else {
        $sta = "NotConfirm";
        $sql = "INSERT INTO roombook(Name,Email,Country,Phone,RoomType,Bed,NoofRoom,Meal,cin,cout,stat,nodays) VALUES ('$Name','$Email','$Country','$Phone','$RoomType','$Bed','$NoofRoom','$Meal','$cin','$cout','$sta',datediff('$cout','$cin'))";
        $result = mysqli_query($conn, $sql);

        // if($f1=="NO")
        // {
        //     echo "<script>swal({
        //         title: 'Superior Room is not available',
        //         icon: 'error',
        //     });
        //     </script>";
        // }
        // else if($f2=="NO")
        // {
        //     echo "<script>swal({
        //         title: 'Guest House is not available',
        //         icon: 'error',
        //     });
        //     </script>";
        // }
        // else if($f3 == "NO")
        // {
        //     echo "<script>swal({
        //         title: 'Si Room is not available',
        //         icon: 'error',
        //     });
        //     </script>";
        // }
        // else if($f4 == "NO")
        // {
        //     echo "<script>swal({
        //         title: 'Deluxe Room is not available',
        //         icon: 'error',
        //     });
        //     </script>";
        // }
        // else if($result = mysqli_query($conn, $sql))
        // {
        if ($result) {
            echo "<script>swal({
                                title: 'Reservation successful',
                                icon: 'success',
                            });
                        </script>";
        } else {
            echo "<script>swal({
                                    title: 'Something went wrong',
                                    icon: 'error',
                                });
                        </script>";
        }
        // }
    }
}
?>
    </div>


    <!-- ================================================= -->
    <div class="searchsection">
        <input type="text" name="search_bar" id="search_bar" placeholder="search..." onkeyup="searchFun()">
        <button class="adduser" id="adduser" onclick="adduseropen()"><i class="fa-solid fa-bookmark"></i> Add</button>
        <form action="./exportdata.php" method="post">
            <button class="exportexcel" id="exportexcel" name="exportexcel" type="submit"><i class="fa-solid fa-file-arrow-down"></i></button>
        </form>
    </div>

    <div class="roombooktable table-responsive">
            <?php
$sql = "SELECT * FROM resev_ruangan";
$result = mysqli_query($conn, $sql);
?>
            <table class="table table-bordered" id="table-data">
                <thead>
                    <tr>
                        <th scope="col">Id</th>
                        <th scope="col">Nama Dosen</th>
                        <th scope="col">Mata Kuliah</th>
                        <th scope="col">Jam Mulai</th>
                        <th scope="col">Tanggal</th>
                        <th scope="col">Jam Selesai</th>
                        <th scope="col">No Ruang</th>
                        <th scope="col">Fakultas</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_array($result)) {?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['nama_dosen']; ?></td>
                            <td><?php echo $row['matkul']; ?></td>
                            <td><?php echo $row['jam_mulai']; ?></td>
                            <td><?php echo $row['tanggal']; ?></td>
                            <td><?php echo $row['jam_selesai']; ?></td>
                            <td><?php echo $row['no_ruang']; ?></td>
                            <td><?php echo $row['fakultas']; ?></td>
                            <td class="action">
    <?php

    echo "<a href='roomconfirm.php?id=" . $row['id'] . "'><button class='btn btn-success'>Confirm</button></a>";

    ?>
    <a href="roombookedit.php?id=<?php echo $row['id'] ?>"><button class="btn btn-primary">Edit</button></a>
    <a href="roombookdelete.php?id=<?php echo $row['id'] ?>"><button class='btn btn-danger'>Delete</button></a>
</td>

                        </tr>
                    <?php }?>
                </tbody>
            </table>
        </div>
</body>
<script src="./javascript/roombook.js"></script>



</html>
