<?php
include 'config.php';
session_start();

// page redirect
$usermail = "";
$usermail = $_SESSION['usermail'];
if (!$usermail) {
    header("location: index.php");
    exit();
}

if (isset($_POST['guestdetailsubmit'])) {
    $nama_dosen = $_POST['Name'];
    $matkul = $_POST['matkul'];
    $jam_mulai = $_POST['cin'];
    $jam_selesai = $_POST['cout'];
    $tanggal = $_POST['date'];
    $id_ruang = $_POST['noRuang'];
    $fakultas = $_POST['fakultas'];

    if ($nama_dosen == "" || $matkul == "" || $jam_mulai == "" || $jam_selesai == "" || $tanggal == "" || $id_ruang == "" || $fakultas == "") {
        echo "<script>swal({
                        title: 'Fill the proper details',
                        icon: 'error',
                    });
                    </script>";
    } else {
        $sql = "INSERT INTO resev_ruangan (`nama_dosen`, `matkul`, `jam_mulai`, `tanggal`, `jam_selesai`, `id_ruang`, `fakultas`) VALUES ('$nama_dosen', '$matkul', '$jam_mulai', '$tanggal', '$jam_selesai', '$id_ruang', '$fakultas')";
        $result = mysqli_query($conn, $sql);

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
    }
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
    </style>
</head>

<body>
  <nav>
    <div class="logo">
      <img class="bluebirdlogo" src="./image/bluebirdlogo.png" alt="logo">
      <p>BLUEBIRD</p>
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
                    
                    <?php
$ruangAll = mysqli_query($conn, "SELECT noRuang FROM ruangan");
?>


                </div>

                <div class="line"></div>

                <div class="reservationinfo">
                    <h4>Reservation information</h4>
                    <input type="text" name="fakultas" id="fakultas">
                    <select name="noRuang" class="selectinput">
                <option value="" selected>Select your room</option>
                <?php
// Loop through each row and create an option element
while ($row = mysqli_fetch_assoc($ruangAll)):
    echo '<option value="' . $row['noRuang'] . '">' . $row['noRuang'] . '</option>';
endwhile;
?>
            </select>

                    <div class="datesection">
                        <span>
                            <label for="cin"> Jam Mulai</label>
                            <input name="cin" type="number" step="0.01">
                        </span>
                        <span>
                            <label for="cin"> Jam Selesai</label>
                            <input name="cout" type ="number" step="0.01">
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
    var bookbox = document.getElementById("guestdetailpanel");

    openbookbox = (fakultas) => {
      document.getElementById("fakultas").value = fakultas;
      bookbox.style.display = "flex";
    }

    closebox = () =>{
      bookbox.style.display = "none";
    }
</script>
</html>
