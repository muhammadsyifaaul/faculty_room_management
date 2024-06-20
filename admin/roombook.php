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
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- SweetAlert -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link rel="stylesheet" href="./css/roombook.css">
    <title>WALIFAROMA - Admin</title>
    <style>
        #guestdetailpanel {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1000;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }

        #overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }
        .datesection {
            margin-left: -8rem;
        }
        .date {
            margin-left: -19rem;
        }
    </style>
</head>

<body>
    <div id="overlay" onclick="closeForm()"></div>

    <div id="guestdetailpanel">
        <form action="" method="POST" class="guestdetailpanelform">
            <div class="head">
                <h3>RESERVATION</h3>
                <i class="fa-solid fa-circle-xmark" onclick="closeForm()"></i>
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
                            <input name="cin" type="time">
                        </span>
                        <span>
                            <label for="cout"> Jam Selesai</label>
                            <input name="cout" type="time">
                        </span>
                    </div>
                    <div class="date">
                        <label for="date">Date</label>
                        <input type="date" name="date">
                    </div>
                </div>
            </div>
            <div class="footer">
                <button class="btn btn-success" name="guestdetailsubmit">Submit</button>
            </div>
        </form>
    </div>

    <div class="searchsection">
        <input type="text" name="search_bar" id="search_bar" placeholder="search..." onkeyup="searchFun()">
        <button class="adduser" id="adduser" onclick="openForm()"><i class="fa-solid fa-bookmark"></i> Add</button>
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
                            <a href="javascript:void(0);" onclick="editReservation(<?php echo $row['id']; ?>)" class="btn btn-primary">Edit</a>
                            <a href="javascript:void(0);" onclick="deleteReservation(<?php echo $row['id']; ?>)" class="btn btn-danger">Delete</a>
                        </td>
                    </tr>
                <?php }?>
            </tbody>
        </table>
    </div>

    <script>
        <?php if (isset($_SESSION['status'])) {?>
            swal({
                title: "<?php echo $_SESSION['status'] === 'success' ? 'Success' : 'Error'; ?>",
                text: "<?php echo $_SESSION['message']; ?>",
                icon: "<?php echo $_SESSION['status'] === 'success' ? 'success' : 'error'; ?>",
            });
            <?php unset($_SESSION['status']);
    unset($_SESSION['message']);?>
        <?php }?>

        function openForm() {
            document.getElementById('guestdetailpanel').style.display = 'block';
            document.getElementById('overlay').style.display = 'block';
        }

        function closeForm() {
            document.getElementById('guestdetailpanel').style.display = 'none';
            document.getElementById('overlay').style.display = 'none';
        }

        function editReservation(id) {
            window.location.href = `roombookedit.php?id=${id}`;
        }

        function deleteReservation(id) {
            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this reservation!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    window.location.href = `roombookdelete.php?id=${id}`;
                } else {
                    swal("Your reservation is safe!");
                }
            });
        }
    </script>
</body>

</html>
