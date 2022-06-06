<?php
session_start();
if (!isset($_SESSION['sessionid'])) {
    echo "<script>alert('Session not available. Please login');</script>";
    echo "<script> window.location.replace('login.php')</script>";
}

include_once("dbconnect.php");
$sqltutors="SELECT * FROM tbl_tutors";
$stmt = $conn->prepare($sqltutors);
$stmt->execute();
$rows = $stmt->fetchAll();

$results_per_page = 10;
if (isset($_GET['pageno'])) {
    $pageno = (int)$_GET['pageno'];
    $page_first_result = ($pageno - 1) * $results_per_page;
} else {
    $pageno = 1;
    $page_first_result = 0;
}

$stmt = $conn->prepare($sqltutors);
$stmt->execute();
$number_of_result = $stmt->rowCount();
$number_of_page = ceil($number_of_result / $results_per_page);
$sqltutors = $sqltutors . " LIMIT $page_first_result , $results_per_page";
$stmt = $conn->prepare($sqltutors);
$stmt->execute();
$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
$rows = $stmt->fetchAll();

$conn= null;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="../js/menu.js" defer></script>
    <link rel="stylesheet" href="../css/style.css">
    <title>Meet the Tutors</title>
</head>

<body>

<!-- Sidebar -->

    <div class="w3-sidebar w3-bar-block w3-card w3-animate-left w3-large" style="display:none; z-index:5;" id="mySidebar">
        <button class="w3-bar-item w3-button w3-large w3-hover-green " onclick="w3_close()">Close &times;</button>
        <a href="main.php" class="w3-bar-item w3-button w3-hover-green"> <i class="fa fa-home"></i>&ensp;Dashboard</a>
        <a href="main.php" class="w3-bar-item w3-button w3-hover-green"><i class="fa fa-html5"></i>&ensp;Courses</a>
        <a href="tutors.php" class="w3-bar-item w3-button w3-hover-green"><i class="fa fa-book"></i>&ensp;Tutors</a>
        <a href="#" class="w3-bar-item w3-button w3-hover-green"><i class="fa fa-bell"></i>&ensp;Subscription</a>
        <a href="#" class="w3-bar-item w3-button w3-hover-green"> <i class="fa fa-user"></i>&ensp;Profile</a>
    </div>
    
    <div class="w3-overlay" onclick="w3_close()" style="cursor:pointer" id="myOverlay"></div>

    <header class="w3-green w3-header">
        <button class="w3-button w3-green w3-xlarge" onclick="w3_open()">☰</button>
        <div class="w3-display-container w3-center w3-padding">
            <h1 style="font-size:calc(8px+4vw);">List of Tutors</h1>
            <p style="font-size:calc(8px+1vw);">Contact Information of Tutors in MY Tutor</p>
        </div>
    </header>

    <div class="w3-margin w3-grid-template">
        <?php
        $i=0;
        foreach ($rows as $tutors) {
            $i++;
            $tutorid = $tutors['tutor_id'];
            $tutorname = $tutors['tutor_name'];
            $tutoremail = $tutors['tutor_email'];
            $tutorphone = $tutors['tutor_phone'];
            echo "<div class='w3-card-4 w3-round' style='margin:8px'>";
        
            echo "<img class='w3-image' src=../../admin/res/assets/tutors/$tutorid.jpg" .
            " onerror=this.onerror=null;this.src='../../user/res/images/logomytutor.png'"
            . " style='width:100%;height:250px'><hr>";
            echo "<div class='w3-container'><p>Name:<br><b>$tutorname</b><br>Email:<br><b>$tutoremail</b><br>Phone:<br><b>$tutorphone</b><br></p></div>
            </div></a>";

        }
        ?>
    </div>
    
    <?php
        $num = 1;
        if ($pageno == 1) {
            $num = 1;
        } else if ($pageno == 2) {
            $num = ($num) + 10;
        } else {
            $num = $pageno * 10 - 9;
        }
        echo "<div class='w3-container w3-row'>";
        echo "<center>";
        for ($page = 1; $page <= $number_of_page; $page++) {
            echo '<a href = "tutors.php?pageno=' . $page . '" style=
            "text-decoration: none">&nbsp&nbsp' . $page . ' </a>';
        }
        echo " ( " . $pageno . " )";
        echo "</center>";
        echo "</div>";
    ?>
    <br><br>
    <footer class="w3-footer w3-center w3-bottom w3-green">Copyright &copy; 2022 MY Tutor</footer>

</body>

</html>