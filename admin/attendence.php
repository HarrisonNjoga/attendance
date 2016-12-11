<?php
session_start();
if(!isset($_SESSION['username']) or !isset($_SESSION['password']) or $_SESSION['is_admin'] == false){
    header("location: ../login.php");
}

include 'db.php';
if(isset($_POST['go'])){
  $startDate = $_POST['startDate'];
  $endDate = $_POST['endDate'];
  $date1 = date_create($endDate);
  $date2 = date_create($startDate);
  $d = date_diff($date1,$date2);
  $dateDiff = $d->d;
$rows = "";
// select sum(logout-login) from attendence where data>=? and date <= ? and user_id =?
//select * users where is_admin=0
$query = mysqli_query($con,"SELECT * FROM users WHERE is_admin = 0") or die("Error in getting users");
while($row = mysqli_fetch_assoc($query)){
  //get every user id and username
  $id = $row['user_id'];
  $username = $row['username'];
  
  //loop for each user and get his attendence
  $query2 = mysqli_query($con,"SELECT * from attendence WHERE user_id='$id' AND dayDate >= '$startDate' AND dayDate <= '$endDate' ") or die("Error in query attendence");
  $presence = mysqli_num_rows($query2);
  //$query3 = mysql_query("SELECT (sum(SignOutTime) - sum(signInTime)) as som from attendence WHERE user_id='$id' AND dayDate >= '$startDate' AND dayDate <= '$endDate'") or die("Error in query attendence");
  $query3 = mysqli_query($con,"SELECT signInTime,SignOutTime,TIMESTAMPDIFF(SECOND,signInTime,SignOutTime) as seconds,sum(TIMESTAMPDIFF(SECOND,signInTime,SignOutTime)) as coun from attendence WHERE user_id='$id' AND dayDate >= '$startDate' AND dayDate <= '$endDate'") or die(mysql_error());
  $row2 = mysqli_fetch_assoc($query3);
  $seconds = $row2['seconds'];
  $total_hours_per_day = gmdate("H:i:s",$seconds);
  $coun = $row2['coun'];
  $total_hours_per_duration = gmdate("H:i:s",$coun);
  //$total_hours = date('G:i:s',$total_hours);
  $absence = $dateDiff - $presence;
    //echo $id." - ".$username." - ".$presence." - ".$absence." - ".$total_hours_per_day." - ".$total_hours_per_duration."<br >";
  $rows .= "
        <tr>
                                  <td>$id</td>
                                  <td>$username</td>
                                  <td>$presence</td>
                                  <td>$absence</td>
                                  <td>$total_hours_per_duration</td>
                              </tr>
  ";

   

}//end while loop

//loop for each user and get attendence days

//calculate total hours 

// absence days is (enddate - startdate) - (count days they have logged in)  

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

    <title>Admin Panel</title>

    <!-- Bootstrap core CSS -->
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/bootstrap-reset.css" rel="stylesheet">
    <!--external css-->
    <link href="../assets/assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <!-- Custom styles for this template -->
    <link href="../assets/css/style.css" rel="stylesheet">
    <link href="../assets/css/style-responsive.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="../assets/assets/bootstrap-fileupload/bootstrap-fileupload.css" />
    <link rel="stylesheet" type="text/css" href="../assets/assets/bootstrap-wysihtml5/bootstrap-wysihtml5.css" />
    <link rel="stylesheet" type="text/css" href="../assets/assets/bootstrap-datepicker/css/datepicker.css" />
    <link rel="stylesheet" type="text/css" href="../assets/assets/bootstrap-timepicker/compiled/timepicker.css" />
    <link rel="stylesheet" type="text/css" href="../assets/assets/bootstrap-colorpicker/css/colorpicker.css" />
    <link rel="stylesheet" type="text/css" href="../assets/assets/bootstrap-daterangepicker/daterangepicker-bs3.css" />
    <link rel="stylesheet" type="text/css" href="../assets/assets/bootstrap-datetimepicker/css/datetimepicker.css" />
    <link rel="stylesheet" type="text/css" href="../assets/assets/jquery-multi-select/css/multi-select.css" />

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
    <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->
   
</head>

  <body>

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
            <li><a href="configdates.php">Edit Times</a></li>
            <li class="active"><a href="attendence.php">Attendence</a></li>
            <li><a href="../logout.php">logout</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

    <div class="container">
    <br />
    <br />
    <br />
    <br />
    <?php if(isset($_GET['success'])): ?>
    <div class="alert alert-success" role="alert">
        <?php echo "Saved Successfuly"; ?>   
    </div>
  <?php endif; ?>
<div class="row">
<form class="form-horizontal  tasi-form" action="attendence.php" method="post">
<!--//////////////////////////////////////////////////////////////////////////////////////-->            
<div class="col-md-12">
                  <section class="panel">
                      <header class="panel-heading">
                          Attendence Table
                          <span class="tools pull-right">
                            <a href="javascript:;" class="icon-chevron-down"></a>
                            <a href="javascript:;" class="icon-remove"></a>
                        </span>
                      </header>
                      <div class="panel-body">
                          <form action="#" class="form-horizontal tasi-form">

                                  <div class="col-md-3 col-xs-11">
                                      <input class="form-control form-control-inline input-medium default-date-picker" size="16" type="date" id="startDate" name="startDate" >
                                  </div>                             
                                  <div class="col-md-3">
                                    <label class="form-control btn btn-info">To</label>
                                  </div>
                                  <div class="col-md-3 col-xs-11">
                                          <input class="form-control form-control-inline input-medium default-date-picker" size="16" type="date" id="endDate" name="endDate" >
                                  </div>
                                  <div class="col-md-3 col-xs-11">
                                          <input class="form-control form-control-inline input-medium btn btn-danger" name="go"  type="submit" value="Go" >
                                  </div>

                          </form>
                      </div>
                  </section>
              </div>
<!--///////////////////////////////////////////////////////////////////////////////////////-->                             
 
</form>
</div><!--//end row-->
        
<?php if(isset($_POST['go'])): ?>
<div class="row">
                  <div class="col-lg-12">
                      <section class="panel">
                          <table class="table table-striped table-advance table-hover">
                              <thead>
                              <tr>
                                  <th><i class="icon-list-ol"></i> ID</th>
                                  <th><i class="icon-male"></i> Employee Name</th>
                                  <th><i class="icon-thumbs-up"></i> Presence Days</th>
                                  <th><i class="icon-thumbs-down"></i> Absence Days</th>
                                  <th><i class="icon-time"></i> Total Hours</th>
                                  <th></th>
                              </tr>
                              </thead>
                              <tbody>
                          <?php echo $rows; ?>
                          
                              </tbody>
                          </table>
                      </section>
                  </div>
</div> 
<?php endif; ?>            
     
    </div><!-- /.container -->


    
    <!-- js placed at the en.d of the document so the pages load faster -->
    <script src="../assets/js/jquery.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>
      <script type="text/javascript" src="../assets/assets/fuelux/js/spinner.min.js"></script>
  <script type="text/javascript" src="../assets/assets/bootstrap-fileupload/bootstrap-fileupload.js"></script>
  <script type="text/javascript" src="../assets/assets/bootstrap-wysihtml5/wysihtml5-0.3.0.js"></script>
  <script type="text/javascript" src="../assets/assets/bootstrap-wysihtml5/bootstrap-wysihtml5.js"></script>
  <script type="text/javascript" src="../assets/assets/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
  <script type="text/javascript" src="../assets/assets/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>
  <script type="text/javascript" src="../assets/assets/bootstrap-daterangepicker/moment.min.js"></script>
  <script type="text/javascript" src="../assets/assets/bootstrap-daterangepicker/daterangepicker.js"></script>
  <script type="text/javascript" src="../assets/assets/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>
  <script type="text/javascript" src="../assets/assets/bootstrap-timepicker/js/bootstrap-timepicker.js"></script>
  <script type="text/javascript" src="../assets/assets/jquery-multi-select/js/jquery.multi-select.js"></script>
  <script type="text/javascript" src="../assets/assets/jquery-multi-select/js/jquery.quicksearch.js"></script>
<!--common script for all pages-->
    <script src="../assets/js/common-scripts.js"></script>
    <!--this page  script only-->
    <script src="../assets/js/advanced-form-components.js"></script>
<?php 
$sql = "SELECT * FROM `config`";
$query = mysqli_query($con,$sql);
$row = mysqli_fetch_assoc($query);

$starttime =  $row['startTime'];
$endtime =  $row['endTime'];

 ?> 
 <script type="text/javascript">
 //get times stored in database and set it

  
//timepicker start

var today = new Date();
var dd = today.getDate();
var mm = today.getMonth()+1; //January is 0!
var yyyy = today.getFullYear();

if(dd<10) {
    dd='0'+dd
} 

if(mm<10) {
    mm='0'+mm
} 

today = yyyy+'-'+mm+'-'+dd;
fDay = yyyy+'-'+mm+'-'+'01';


<?php if(isset($_POST['go'])): ?>
$('#startDate').val('<?php echo $_POST['startDate']; ?>');
$('#endDate').val('<?php echo $_POST['endDate']; ?>');
<?php else: ?>
$('#startDate').val(fDay);
$('#endDate').val(today);
<?php endif; ?>
   
  </script>
  </body>
</html>
