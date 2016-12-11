<?php
session_start();
if(!isset($_SESSION['username']) or !isset($_SESSION['password']) or $_SESSION['is_admin'] == true){
    header("location: login.php");
}
 include 'admin/db.php';
  //get user id
 $username = $_SESSION['username'];
 $query = mysqli_query($con,"SELECT * FROM users WHERE username='$username'") or die('error in get user id query');
 $row = mysqli_fetch_assoc($query);
 $user_id = $row['user_id'];
 
 //check if user has logged in this day 
 $dateOfDay = date('Y-m-d');
 $query = mysqli_query($con,"SELECT * FROM attendence WHERE user_id='$user_id' AND dayDate='$dateOfDay' AND signInTime>0") or die('error in check user loggedin query');
 $num = mysqli_num_rows($query);
 if ($num > 0) {
    $isLoggedIn = true;
 }else{
    $isLoggedIn = false; 
 }

 //check if user has logged Out this day 
$query = mysqli_query($con,"SELECT * FROM attendence WHERE user_id='$user_id' AND dayDate='$dateOfDay' AND SignOutTime>0") or die('error in check user loggedin query');
 $num = mysqli_num_rows($query);
 if ($num > 0) {
    $isLoggedOut = true;
 }else{
    $isLoggedOut = false;
 }
 
 //check if time now is not less than login time or greater than logout time
   //get login time and logout time from config table
 $query = mysqli_query($con,"SELECT * FROM config ") or die('Error in getting time config');
 $row = mysqli_fetch_assoc($query);
 $inTime = $row['startTime'];
 $outTime = $row['endTime'];
 date_default_timezone_set('Africa/Cairo');
 $current_time = date('G:i:s',time());
 //echo $inTime." - ".$outTime." - ".$current_time;
 
 if($current_time < $inTime or $current_time > $outTime){
    $login = false;
 }else{
    $login = true;
 }



 //make user login
//i got user_id before
 if(isset($_POST['signin'])){
    $dateOfDay = date('Y-m-d');
     $query = mysqli_query($con,"SELECT * FROM attendence WHERE user_id='$user_id' AND dayDate='$dateOfDay' AND signInTime>0") or die('error in check user loggedin query');
     $num = mysqli_num_rows($query);
     if ($num > 0) {
        $msg = "You are already logged In";
     }else{
        //insert login
        $query = mysqli_query($con,"INSERT INTO attendence (user_id,dayDate,signInTime) VALUES ('$user_id','$dateOfDay','$current_time')") or die("Error in loggin process"); 
        if($query){
          $msg = "You have logged in Successfuly";
          header("refresh:1");
        }
     }
 }
 // make user logout
  if(isset($_POST['signout'])){
        //update
        $query = mysqli_query($con,"UPDATE attendence SET SignOutTime='$current_time' WHERE user_id='$user_id'") or die("Error in loggout process"); 
        if($query){
          $msg = "You have logged out Successfuly";
          header("refresh:1");
        }
 }

  ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="keyword" content="">
    <link rel="shortcut icon" href="../assets/img/favicon.png">

    <title>User Panel</title>

    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/bootstrap-reset.css" rel="stylesheet">
    <!--external css-->
    <link href="assets/assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <!-- Custom styles for this template -->
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/style-responsive.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="assets/assets/bootstrap-fileupload/bootstrap-fileupload.css" />
    <link rel="stylesheet" type="text/css" href="assets/assets/bootstrap-wysihtml5/bootstrap-wysihtml5.css" />
    <link rel="stylesheet" type="text/css" href="assets/assets/bootstrap-datepicker/css/datepicker.css" />
    <link rel="stylesheet" type="text/css" href="assets/assets/bootstrap-timepicker/compiled/timepicker.css" />
    <link rel="stylesheet" type="text/css" href="assets/assets/bootstrap-colorpicker/css/colorpicker.css" />
    <link rel="stylesheet" type="text/css" href="assets/assets/bootstrap-daterangepicker/daterangepicker-bs3.css" />
    <link rel="stylesheet" type="text/css" href="assets/assets/bootstrap-datetimepicker/css/datetimepicker.css" />
    <link rel="stylesheet" type="text/css" href="assets/assets/jquery-multi-select/css/multi-select.css" />

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
    <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->
</head>

  <body onload="startTime()">

    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="index.php">PunchIt</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li><a href="index.php">Home</a></li>
            <li><a >Welcome , <?php echo $_SESSION['username']; ?></a></li>
            <li><a href="logout.php">logout</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

    <div class="container">
    <br />
    <br />
    <br />
    <br />
    <?php if(isset($msg)): ?>
    <div class="alert alert-success"><?php echo $msg; ?></div>
  <?php endif; ?>
	<div class="row">
                  <div class="col-md-12">
                      <div class="bio-graph-heading">
                              <h1>Welcome <?php echo $_SESSION['username']; ?> to attendence panel</h1>
                              <div><h1><?php echo date('l , d F Y'); ?></h1></div>
                              <div id="time" ></div>
                      </div>
                  </div>
                  <br />
                  <?php if($login == true): ?>
                  <div class="col-md-12">
                      <div class="col-md-6">
                      <form method="post" action="index.php" >
                        <input type="submit" name="signin" <?php if($isLoggedIn == true) echo "disabled"; ?> value="Sign In" class="btn btn-danger  btn-block"  />
                      </div>
                      <div class="col-md-6">
                        <input type="submit" <?php if(!$isLoggedIn == true) echo "disabled"; if($isLoggedOut == true) echo "disabled"; ?> name="signout" value="Sign Out" class="btn btn-danger  btn-block" />
                      </div>
                      </form>
                  </div>
                <?php endif; ?>
              </div>
     
    </div><!-- /.container -->


    
    <!-- js placed at the en.d of the document so the pages load faster -->
    <script src="assets/js/jquery.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
      <script type="text/javascript" src="assets/assets/fuelux/js/spinner.min.js"></script>
  <script type="text/javascript" src="assets/assets/bootstrap-fileupload/bootstrap-fileupload.js"></script>
  <script type="text/javascript" src="assets/assets/bootstrap-wysihtml5/wysihtml5-0.3.0.js"></script>
  <script type="text/javascript" src="assets/assets/bootstrap-wysihtml5/bootstrap-wysihtml5.js"></script>
  <script type="text/javascript" src="assets/assets/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
  <script type="text/javascript" src="assets/assets/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>
  <script type="text/javascript" src="assets/assets/bootstrap-daterangepicker/moment.min.js"></script>
  <script type="text/javascript" src="assets/assets/bootstrap-daterangepicker/daterangepicker.js"></script>
  <script type="text/javascript" src="assets/assets/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>
  <script type="text/javascript" src="assets/assets/bootstrap-timepicker/js/bootstrap-timepicker.js"></script>
  <script type="text/javascript" src="assets/assets/jquery-multi-select/js/jquery.multi-select.js"></script>
  <script type="text/javascript" src="assets/assets/jquery-multi-select/js/jquery.quicksearch.js"></script>
<!--common script for all pages-->
    <script src="assets/js/common-scripts.js"></script>
    <!--this page  script only-->
    <script src="assets/js/advanced-form-components.js"></script>
    <script type="text/javascript">
        function startTime() {
            var today = new Date();
            var h = today.getHours();
            var m = today.getMinutes();
            var s = today.getSeconds();
            m = checkTime(m);
            s = checkTime(s);
            document.getElementById('time').innerHTML =
            h + ":" + m + ":" + s;
            var t = setTimeout(startTime, 500);
        }
        function checkTime(i) {
            if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
            return i;
        }

  </script>
  </body>
</html>
