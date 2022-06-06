<?php
session_start();
if (!isset($_SESSION['sessionid'])) {
    echo "<script>alert('Session not available. Please login');</script>";
    echo "<script> window.location.replace('login.php')</script>";
}
include_once("dbconnect.php");
if (isset($_GET['submit'])) {
    $operation = $_GET['submit'];
    if ($operation == 'search') {
        $search = $_GET['search'];
        $sqlsubjects = "SELECT * FROM tbl_subjects WHERE subject_name LIKE '%$search%'";
    }
}else{
    $sqlsubjects = "SELECT * FROM tbl_subjects";
}

$results_per_page = 10;
if (isset($_GET['pageno'])) {
    $pageno = (int)$_GET['pageno'];
    $page_first_result = ($pageno - 1) * $results_per_page;
} else {
    $pageno = 1;
    $page_first_result = 0;
}

$stmt = $conn->prepare($sqlsubjects);
$stmt->execute();
$number_of_result = $stmt->rowCount();
$number_of_page = ceil($number_of_result / $results_per_page);
$sqlsubjects = $sqlsubjects . " LIMIT $page_first_result , $results_per_page";
$stmt = $conn->prepare($sqlsubjects);
$stmt->execute();
$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
$rows = $stmt->fetchAll();
$conn= null;

function truncate($string, $length, $dots = "...") {
    return (strlen($string) > $length) ? substr($string, 0, $length - strlen($dots)) . $dots : $string;
}

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
    <title>Welcome to MY Tutor</title>
</head>

<body>
    <!-- Sidebar -->
    <div class="w3-sidebar w3-bar-block w3-card w3-animate-left w3-large" style="display:none; z-index:5;" id="mySidebar">
        <button onclick="w3_close()" class="w3-bar-item w3-button w3-large">Close &times;</button>
        <a href="main.php" class="w3-bar-item w3-button w3-hover-green"><i class="fa fa-home"></i>&ensp;Dashboard</a>
        <a href="main.php" class="w3-bar-item w3-button w3-hover-green"><i class="fa fa-html5"></i>&ensp;Courses</a>
        <a href="tutors.php" class="w3-bar-item w3-button w3-hover-green"><i class="fa fa-book"></i>&ensp;Tutors</a>
        <a href="#" class="w3-bar-item w3-button w3-hover-green"><i class="fa fa-bell"></i>&ensp;Subscription</a>
        <a href="#" class="w3-bar-item w3-button w3-hover-green"><i class="fa fa-user"></i>&ensp;Profile</a>
    </div>

    <div class="w3-overlay" onclick="w3_close()" style="cursor:pointer" id="myOverlay"></div>

    <header class="w3-green w3-header">
        <button class="w3-button w3-green w3-xlarge" onclick="w3_open()">☰</button>
        <div class="w3-display-container w3-center w3-padding">
            <h1 style="font-size:calc(8px+4vw);">List of Courses</h1>
        </div>
    </header>

    <div class="w3-container w3-padding-32" style="display:flex; justify-content: center">
        <div class="w3-card-4 w3-round" style="width:400px; margin:auto; text-align: left;">
        <div class="w3-green w3-container w3-center"><h2>Subject Search</h2></div>
            <form class="w3-container">
            <div> 
            <p><input class="w3-input w3-block w3-round w3-border" type="search" name="search" placeholder="Enter search term" /></p>
            </div>
            <button class="w3-button w3-green w3-round w3-block" type="submit" name="submit" value="search">search</button>  
            <br>
            </form>
            </div>
        </div>
    </div>



    <div class="w3-margin w3-grid-template">
        <?php
        $i=0;
        foreach ($rows as $subjects) {
            $i++;
            $subid = $subjects['subject_id'];
            $subname = truncate($subjects['subject_name'], 15);
            $subprice = number_format((float)$subjects['subject_price'], 2, '.', ''); 
            $subsessions = $subjects['subject_sessions'];
            $subrating = number_format((float)$subjects['subject_rating'], 2, '.', '');

            echo "<div class='w3-card-4 w3-round' style='margin:8px'>";
            echo "<img class='w3-image' src=../../admin/res/assets/courses/$subid.png" .
            " onerror=this.onerror=null;this.src='../../user/res/images/logomytutor.png'"
            . " style='width:100%;height:250px'><hr>";
            echo "<div class='w3-center'>
            <p class='w3-text-green' style='font-weight: 500; font-size: 20px; margin: 0px;'>$subname</p>
            <p style= 'font-weight: 700; font-size: 30px; margin: 0px;'>RM $subprice</p>
            <p class='w3-text-green' style= 'font-weight: 500; font-size: 20px; margin: 0px;'>$subsessions Sessions</p>
            <p class='w3-text-green' style= 'font-weight: 500; font-size: 20px; margin: 0px;'>Rating: $subrating/5</p>
            <button class='w3-button w3-green w3-round' style= 'margin: 15px;' type='submit' name='submit'>Book Now</button>
            </div>
            </div></a>";
        }
        ?>
    </div>

    <br>
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
            echo '<a href = "main.php?pageno=' . $page . '" style=
            "text-decoration: none">&nbsp&nbsp' . $page . ' </a>';
        }
        echo " ( " . $pageno . " )";
        echo "</center>";
        echo "</div>";
    ?>
    <br>
    <footer class="w3-footer w3-center w3-bottom w3-green">Copyright &copy; 2022 MY Tutor</footer>

</body>

</html>