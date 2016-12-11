<?php 
session_start();
//check if logged in
if(isset($_SESSION['username']) && isset($_SESSION['password']) &&  $_SESSION['is_admin'] == true){
    header('Location: admin/index.php');
    exit;

}elseif(isset($_SESSION['username']) && isset($_SESSION['password']) &&  $_SESSION['is_admin'] == false){
    header('Location: index.php');
    exit;
}
//end check for login
include 'config.php';
if(isset($_POST['login'])){
   $username = $_POST['username'];
   $password = md5($_POST['password']);
   // prepare and bind
    $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        //there there are a user registered with successful credentials
        $user = $result->fetch_object();
        $username = $user->username;
        $password = $user->password;
        $is_admin = $user->is_admin;
        if($is_admin == 0){ // check if user or admin
            //this is a user
            $_SESSION['username'] = $username;
            $_SESSION['password'] = $password;
            $_SESSION['is_admin'] = false;
            header("Location: index.php");
        }elseif ($is_admin == 1) {
            //this is an admin
            $_SESSION['username'] = $username;
            $_SESSION['password'] = $password;
            $_SESSION['is_admin'] = true;
            header("Location: admin/index.php");
        }
    }else{
        $error = 'Username or Password is incorrect';
    }
    $conn->close();
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
    <link rel="shortcut icon" href="assets/img/favicon.png">

    <title>Login To PunchIt</title>

    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/bootstrap-reset.css" rel="stylesheet">
    <!--external css-->
    <link href="assets/assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <!-- Custom styles for this template -->
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/style-responsive.css" rel="stylesheet" />

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
    <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->
</head>

  <body class="login-body">

    <div class="container">
      <?php if (isset($error)): ?>
            <div class="alert alert-danger" role="alert"><?php echo $error; ?></div>
      <?php endif; ?>  
      <form class="form-signin" method="post" action="login.php">
        <h2 class="form-signin-heading">Login in Now</h2>
        <div class="login-wrap">
            <input type="text" name="username" class="form-control" placeholder="Enter Your Username" required autofocus>
            <input type="password" name="password" class="form-control" placeholder="Enter Your Password" required>
            <button class="btn btn-lg btn-login btn-block" name="login" type="submit">Login in</button>
            </div>
        </div>
      </form>

    </div>



    <!-- js placed at the end of the document so the pages load faster -->
    <script src="assets/js/jquery.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>


  </body>
</html>
