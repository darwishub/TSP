<?php

	
    

	//jika klik tombol 
    if(@$_POST['login']){
        
        
        session_start();
        
		include ('config.php');
		
		$username = $_POST['username'];
		$password = $_POST['password'];
		
		$query = mysql_query("SELECT * FROM tb_sales WHERE username='$username' AND password = '$password'");

		if(!$query)
		{
			die(mysql_error());
		}
		
		$cek = mysql_num_rows($query);
        
        //jika ada data
		if($cek>0){
			
            $row_admin = mysql_fetch_array($query);
            
			$_SESSION['username'] = $row_admin['username'];
			header("location:admin/pencarian.php");
		}
		
		else {
			$error = "Password atau username salah" . "<br />";
		}
	}
?>


    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>NIDA FOOD TSP</title>
        <!-- Bootstrap core CSS-->
        <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <!-- Custom fonts for this template-->
        <link href="assets/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <!-- Custom styles for this template-->
        <link href="assets/css/sb-admin.css" rel="stylesheet">
    </head>

    <body class="bg-dark">
        <div class="container">
            <div class="row">
                <div class="col-4 offset-4">

                    <div class="card card-login mx-auto mt-5">
                        <div class="card-header">Login</div>

                        <div class="card-body">
                            <?php if(@$error != NULL){ echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>$error<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>" ;} ?>
                            
                            <form action="." method="POST">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Username</label>
                                    <input class="form-control" name="username" type="text" placeholder="Masukkan Username">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Password</label>
                                    <input class="form-control" name="password" type="password" placeholder="Masukkan Password">
                                </div>

                                <button class="btn btn-primary btn-block" value="submit" name="login">Login</button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- Bootstrap core JavaScript-->
        <script src="assets/vendor/jquery/jquery.min.js"></script>
        <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    </body>

    </html>