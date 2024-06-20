<?php
include './config.php';
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['usermail'])) {
    header("location: index.php");
    exit();
}

$username = isset($_SESSION['username']) ? $_SESSION['username'] : "";

$reservation_status = "";

if (isset($_POST['guestdetailsubmit'])) {
    $id = isset($_POST['reservation_id']) ? mysqli_real_escape_string($conn, $_POST['reservation_id']) : null;
    $nama_dosen = mysqli_real_escape_string($conn, $_POST['Name']);
    $matkul = mysqli_real_escape_string($conn, $_POST['matkul']);
    $jam_mulai = mysqli_real_escape_string($conn, $_POST['cin']);
    $jam_selesai = mysqli_real_escape_string($conn, $_POST['cout']);
    $tanggal = mysqli_real_escape_string($conn, $_POST['date']);
    $id_ruang = mysqli_real_escape_string($conn, $_POST['noRuang']);
    $fakultas = mysqli_real_escape_string($conn, $_POST['fakultas']);

    if (empty($nama_dosen) || empty($matkul) || empty($jam_mulai) || empty($jam_selesai) || empty($tanggal) || empty($id_ruang) || empty($fakultas)) {
        $reservation_status = "empty_fields";
    } else {
        if ($id) {
            $sql = "UPDATE resev_ruangan SET nama_dosen='$nama_dosen', matkul='$matkul', jam_mulai='$jam_mulai', tanggal='$tanggal', jam_selesai='$jam_selesai', no_ruang='$id_ruang', fakultas='$fakultas' WHERE id='$id'";
        } else {
            $sql = "INSERT INTO resev_ruangan (nama_dosen, matkul, jam_mulai, tanggal, jam_selesai, no_ruang, fakultas) VALUES ('$nama_dosen', '$matkul', '$jam_mulai', '$tanggal', '$jam_selesai', '$id_ruang', '$fakultas')";
        }

        $result = mysqli_query($conn, $sql);

        if ($result) {
            $reservation_status = "success";
        } else {
            $reservation_status = "error";
        }
    }
}

$ruangAll = mysqli_query($conn, "SELECT noRuang FROM ruangan");
$bookedRoomsQuery = mysqli_query($conn, "SELECT no_ruang FROM resev_ruangan");
$bookedRooms = [];
while ($row = mysqli_fetch_assoc($bookedRoomsQuery)) {
    $bookedRooms[] = $row['no_ruang'];
}

$userReservationsQuery = "SELECT * FROM resev_ruangan WHERE nama_dosen = '$username'";
$userReservationsResult = mysqli_query($conn, $userReservationsQuery);

$delete_status = isset($_SESSION['delete_status']) ? $_SESSION['delete_status'] : null;
unset($_SESSION['delete_status']);
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/home.css">
    <title>WALIFAROMA</title>
    <!-- boot -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <!-- fontowesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <!-- sweet alert -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link rel="stylesheet" href="./admin/css/roombook.css">
    <style>
      .h1{
    background-image: url(./image/saintek.jpg);
}
.h2{
    background-image: url(./image/uin.jpg);
}
.h3{
    background-image: url(./image/fitk.jpeg);
}
.h4{
    background-image: url(./image/fuhum.jpg);
}
      #guestdetailpanel{
        display: none;
      }
      #guestdetailpanel .middle{
        height: 450px;
      }
      .booked-room {
          background-color: red;
          color: white;
      }
      /* Table styling */
.roombooktable {
    margin-top: 20px;

}
#reservationTable h1 {
  text-align: center;
}
.table {
    width: 100%;
    margin-bottom: 1rem;
    color: #212529;
}

.table th,
.table td {
    padding: 0.75rem;
    vertical-align: top;
    border-top: 1px solid #dee2e6;
}

.table thead th {
    vertical-align: bottom;
    border-bottom: 2px solid #dee2e6;
}

.table tbody + tbody {
    border-top: 2px solid #dee2e6;
}

.table .table {
    background-color: #fff;
}

.table-bordered {
    border: 1px solid #dee2e6;
}

.table-bordered th,
.table-bordered td {
    border: 1px solid #dee2e6;
}

.table-bordered thead th,
.table-bordered thead td {
    border-bottom-width: 2px;
}

/* Ensure the table section is responsive */
.table-responsive {
    display: block;
    width: 100%;
    overflow-x: auto;

}
#footer {
  color :#fff;
  background-color: #212529;
  width: 100%;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem 2rem;
  position: absolute;
  top: 160rem;
}
.socialMedia {
  display: flex;
  gap: 1rem;
  cursor: pointer;
}

@media (max-width: 30rem) {
    .roombooktable {
        margin-top: 40px;
    }

    .table-responsive {
        overflow-x: auto;
    }
    .contactus {
      position: absolute;
      bottom: 0;
    }
}

/* Other existing CSS styles */

    </style>
</head>

<body>
  <nav>
    <div class="logo">
      <img class="logouin" src="./image/logouin-removebg-preview.png" alt="logo">
      <p>WALIFAROMA</p>
    </div>
    <ul>
      <li><a href="#firstsection">Home</a></li>
      <li><a href="#secondsection">Faculty</a></li>
      <li><a href="#reservationTable">Schedule</a></li>
      <li><a href="#footer">Contact us</a></li>
      <a href="./logout.php"><button class="btn btn-danger">Logout</button></a>
    </ul>
  </nav>

  <section id="firstsection" class="carousel slide carousel_section" data-bs-ride="carousel">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img class="carousel-image" src="./image/walisongo.jpg">
        </div>
        <div class="carousel-item">
            <img class="carousel-image" src="./image/saintek.jpg">
        </div>
        <div class="carousel-item">
            <img class="carousel-image" src="./image/uin.jpg">
        </div>
        <div class="carousel-item">
            <img class="carousel-image" src="./image/2222.jpg">
        </div>

        <div class="welcomeline">
          <h1 class="welcometag">Manage Your Room Schedule</h1>
        </div>

      <!-- bookbox -->
      <div id="guestdetailpanel">
          <form action="" method="POST" class="guestdetailpanelform">
              <div class="head">
                  <h3>RESERVATION</h3>
                  <i class="fa-solid fa-circle-xmark" onclick="closebox()"></i>
              </div>
              <div class="middle">
                  <div class="guestinfo">
                      <h4>Guest information</h4>
                      <input type="text" name="Name" placeholder="Enter Full name" value="<?php echo htmlspecialchars($username); ?>">
                      <input type="text" name="matkul" placeholder="Enter Matkul">
                  </div>

                  <div class="line"></div>

                  <div class="reservationinfo">
                      <h4>Reservation information</h4>
                      <input type="text" name="fakultas" id="fakultas">
                      <select name="noRuang" class="selectinput">
                          <option value="" selected>Select Room</option>
                          <?php
while ($row = mysqli_fetch_assoc($ruangAll)) {
    $isBooked = in_array($row['noRuang'], $bookedRooms);
    $class = $isBooked ? 'booked-room' : '';
    echo '<option value="' . $row['noRuang'] . '" class="' . $class . '">' . $row['noRuang'] . '</option>';
}
?>
                      </select>

                      <div class="datesection">
                          <span>
                              <label for="cin"> Jam Mulai</label>
                              <input name="cin" type="time">
                          </span>
                          <span>
                              <label for="cin"> Jam Selesai</label>
                              <input name="cout" type="time">
                          </span>
                      </div>
                      <div class="date">
                          <label for="date">Date</label>
                          <input type="date" value="" name="date">
                      </div>
                  </div>
              </div>
              <div class="footer">
                  <button class="btn btn-success" name="guestdetailsubmit">Submit</button>
              </div>
          </form>
      </div>
    </div>
  </section>

  <section id="secondsection">
    <img src="./image/homeanimatebg.svg">
    <div class="ourroom">
      <h1 class="head">≼ FACULTY ≽</h1>
      <div class="roomselect">
        <div class="roombox">
          <div class="roomphoto h1"></div>
          <div class="roomdata">
            <h2>FST</h2>
            <button class="btn btn-primary bookbtn" onclick="openbookbox('FST')">Reservation</button>
          </div>
        </div>
        <div class="roombox">
          <div class="roomphoto h2"></div>
          <div class="roomdata">
            <h2>FISIP</h2>
            <button class="btn btn-primary bookbtn" onclick="openbookbox('FISIP')">Reservation</button>
          </div>
        </div>
        <div class="roombox">
          <div class="roomphoto h3"></div>
          <div class="roomdata">
            <h2>FITK</h2>
            <button class="btn btn-primary bookbtn" onclick="openbookbox('FITK')">Reservation</button>
          </div>
        </div>
        <div class="roombox">
          <div class="roomphoto h4"></div>
          <div class="roomdata">
            <h2>FUHUM</h2>
            <button class="btn btn-primary bookbtn" onclick="openbookbox('FUHUM')">Reservation</button>
          </div>
        </div>
      </div>
    </div>
  </section>
  <section id="reservationTable">
    <h1>≼ Your Schedule ≽</h1>
    <div class="table-responsive">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nama Dosen</th>
            <th>Matkul</th>
            <th>Jam Mulai</th>
            <th>Tanggal</th>
            <th>Jam Selesai</th>
            <th>No. Ruang</th>
            <th>Fakultas</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = mysqli_fetch_array($userReservationsResult)) {?>
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
                      <button class="btn btn-primary" onclick="editReservation(
                          '<?php echo $row['id']; ?>',
                          '<?php echo $row['nama_dosen']; ?>',
                          '<?php echo $row['matkul']; ?>',
                          '<?php echo $row['jam_mulai']; ?>',
                          '<?php echo $row['jam_selesai']; ?>',
                          '<?php echo $row['tanggal']; ?>',
                          '<?php echo $row['no_ruang']; ?>',
                          '<?php echo $row['fakultas']; ?>'
                      )">Edit</button>
                      <a href="deleteroom.php?id=<?php echo $row['id'] ?>"><button class='btn btn-danger'>Delete</button></a>
                  </td>
              </tr>
          <?php }?>
        </tbody>
      </table>
    </div>
  </section>

  <section id="footer">
    <div class="socialMedia">
      <i class="fa-brands fa-instagram"></i>
      <i class="fa-brands fa-facebook"></i>
      <i class="fa-solid fa-envelope"></i>
    </div>
    <div class="copyright">
      <h5>Copyright &copy;UIN WALISONGO 2024</h5>
    </div>
  </section>
</body>

<script>
        document.addEventListener('DOMContentLoaded', function() {
            var reservationStatus = "<?php echo $reservation_status; ?>";
            var deleteStatus = "<?php echo $delete_status; ?>";

            if (reservationStatus === "success") {
                swal({
                    title: "Reservation successful",
                    icon: "success"
                });
            } else if (reservationStatus === "error") {
                swal({
                    title: "Something went wrong",
                    icon: "error"
                });
            } else if (reservationStatus === "empty_fields") {
                swal({
                    title: "Fill the proper details",
                    icon: "error"
                });
            }

            if (deleteStatus === "success") {
                swal({
                    title: "Data deleted successfully",
                    icon: "success"
                });
            } else if (deleteStatus === "error") {
                swal({
                    title: "Failed to delete data",
                    icon: "error"
                });
            }
        });

        var bookbox = document.getElementById("guestdetailpanel");

        function openbookbox(fakultas) {
            document.getElementById("fakultas").value = fakultas;
            bookbox.style.display = "flex";
        }

        function closebox() {
            bookbox.style.display = "none";
        }

        function editReservation(id, nama_dosen, matkul, jam_mulai, jam_selesai, tanggal, no_ruang, fakultas) {
            document.querySelector("input[name='Name']").value = nama_dosen;
            document.querySelector("input[name='matkul']").value = matkul;
            document.querySelector("input[name='cin']").value = jam_mulai;
            document.querySelector("input[name='cout']").value = jam_selesai;
            document.querySelector("input[name='date']").value = tanggal;
            document.querySelector("select[name='noRuang']").value = no_ruang;
            document.querySelector("input[name='fakultas']").value = fakultas;

            let hiddenIdField = document.querySelector("input[name='reservation_id']");
            if (!hiddenIdField) {
                hiddenIdField = document.createElement("input");
                hiddenIdField.type = "hidden";
                hiddenIdField.name = "reservation_id";
                document.querySelector(".guestdetailpanelform").appendChild(hiddenIdField);
            }
            hiddenIdField.value = id;

            document.getElementById("guestdetailpanel").style.display = "flex";
        }
    </script>
</html>
