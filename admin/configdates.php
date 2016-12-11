<?php 
session_start();
if(!isset($_SESSION['username']) or !isset($_SESSION['password']) or $_SESSION['is_admin'] == false){
    header("location: ../login.php");
}
include 'db.php';
?>
<?php 
if (isset($_POST['save'])) {
  $start = $_POST['starttime'];
  $end = $_POST['endtime'];
  $query = mysqli_query($con,"UPDATE config SET startTime='$start', endTime='$end'") or die("error in query update");
  if($query){
      header("location: configdates.php?success=ok");
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
            <li class="active"><a href="configdates.php">Edit Times</a></li>
            <li><a href="attendence.php">Attendence</a></li>
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
                  <div class="col-md-12">
                      <section class="panel">
                          <header class="panel-heading">
                              Choose Start time and End Time
                              
                          </header>
                          <div class="panel-body">
                              <form class="form-horizontal  tasi-form" action="configdates.php" method="post">
                                  <div class="form-group">
                                      <label class="control-label col-md-3">Start Time</label>
                                      <div class="col-md-4">
                                          <div class="input-group bootstrap-timepicker">
                                              <input type="text" id="starttime" name="starttime" value="00:00 AM" required class="form-control timepicker-24">
                                                <span class="input-group-btn">
                                                <button class="btn btn-default" type="button"><i class="icon-time"></i></button>
                                                </span>
                                          </div>
                                      </div>
                                  </div>
                                  <div class="form-group">
                                      <label class="control-label col-md-3">End Time</label>
                                      <div class="col-md-4">
                                          <div class="input-group bootstrap-timepicker">
                                              <input type="text" id="endtime" name="endtime" value="00:00 AM" required class="form-control timepicker-24">
                                                <span class="input-group-btn">
                                                <button class="btn btn-default" type="button"><i class="icon-time"></i></button>
                                                </span>
                                          </div>
                                      </div>
                                  </div>
                                  
                                  <input type="submit" class="btn btn-primary" name="save" value="save" />
 
                              </form>
                          </div>
                      </section>
                  </div>
              </div>
     
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
$('#starttime').timepicker({
    autoclose: true,
    minuteStep: 1,
    showSeconds: true,
    showMeridian: false,
    defaultTime: '<?php echo $starttime; ?>'
});

$('#endtime').timepicker({
    autoclose: true,
    minuteStep: 1,
    showSeconds: true,
    showMeridian: false,
    defaultTime: '<?php echo $endtime; ?>'
});


      
  </script>
  </body>
</html>
