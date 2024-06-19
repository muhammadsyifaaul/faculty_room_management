<?php
include './config.php';
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Redirect if not logged in
if (!isset($_SESSION['usermail'])) {
    header("location: index.php");
    exit();
}
$reservation_status = "";

if (isset($_POST['guestdetailsubmit'])) {
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
        $sql = "INSERT INTO resev_ruangan (nama_dosen, matkul, jam_mulai, tanggal, jam_selesai, no_ruang, fakultas) VALUES ('$nama_dosen', '$matkul', '$jam_mulai', '$tanggal', '$jam_selesai', '$id_ruang', '$fakultas')";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            $reservation_status = "success";
        } else {
            $reservation_status = "error";
        }
    }
}

// Fetch all rooms
$ruangAll = mysqli_query($conn, "SELECT noRuang FROM ruangan");

// Fetch booked rooms
$bookedRoomsQuery = mysqli_query($conn, "SELECT no_ruang FROM resev_ruangan");
$bookedRooms = [];
while ($row = mysqli_fetch_assoc($bookedRoomsQuery)) {
    $bookedRooms[] = $row['no_ruang'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/home.css">
    <title>Hotel blue bird</title>
    <!-- boot -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <!-- fontowesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <!-- sweet alert -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link rel="stylesheet" href="./admin/css/roombook.css">
    <style>
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
      <li><a href="#secondsection">Rooms</a></li>
      <li><a href="#thirdsection">Facilites</a></li>
      <li><a href="#contactus">contact us</a></li>
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
          <h1 class="welcometag">Welcome to heaven on earth</h1>
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
                    <input type="text" name="Name" placeholder="Enter Full name">
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
          <div class="hotelphoto h1"></div>
          <div class="roomdata">
            <h2>SAINTEK</h2>
            <button class="btn btn-primary bookbtn" onclick="openbookbox('SAINTEK')">Reservation</button>
          </div>
        </div>
        <div class="roombox">
          <div class="hotelphoto h2"></div>
          <div class="roomdata">
            <h2>FISIP</h2>
            <button class="btn btn-primary bookbtn" onclick="openbookbox('FISIP')">Reservation</button>
          </div>
        </div>
        <div class="roombox">
          <div class="hotelphoto h3"></div>
          <div class="roomdata">
            <h2>FITK</h2>
            <button class="btn btn-primary bookbtn" onclick="openbookbox('FITK')">Reservation</button>
          </div>
        </div>
        <div class="roombox">
          <div class="hotelphoto h4"></div>
          <div class="roomdata">
            <h2>FUHUM</h2>
            <button class="btn btn-primary bookbtn" onclick="openbookbox('FUHUM')">Reservation</button>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section id="contactus">
    <div class="social">
      <i class="fa-brands fa-instagram"></i>
      <i class="fa-brands fa-facebook"></i>
      <i class="fa-solid fa-envelope"></i>
    </div>
    <div class="createdby">
      <h5>Copyright &copy;UIN WALISONGO 2024</h5>
    </div>
  </section>
</body>

<script>
    document.addEventListener('DOMContentLoaded', function() {
          // Flag PHP untuk status reservasi
          var reservationStatus = "<?php echo $reservation_status; ?>";

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
      });

      var bookbox = document.getElementById("guestdetailpanel");

      function openbookbox(fakultas) {
          document.getElementById("fakultas").value = fakultas;
          bookbox.style.display = "flex";
      }

      function closebox() {
          bookbox.style.display = "none";
      }
</script>
</html>
