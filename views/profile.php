<?php
	// create a database connection, using the constants from config/db.php (which we loaded in index.php)
	$db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

	// change character set to utf8 and check it
	if (!$db_connection->set_charset("utf8")) {
		$db_connection->errors[] = $db_connection->error;
	}
	$userPid = "";
	$curpassHash = "";
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Life Control</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/sb-admin.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="css/plugins/morris.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">Life Control</a>
            </div>
            <!-- Top Menu Items -->
            <ul class="nav navbar-right top-nav">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i>  <?php echo $_SESSION['user_name']; ?> <b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="profile.php"><i class="fa fa-fw fa-user"></i> Profile</a>
                        </li>
						<?php
								if ($_SESSION['user_level'] >= 2)
								{

									echo"<li class='divider'></li>";
									echo"<li>";
									echo"<a href='admin.php'><i class='fa fa-fw fa-cog'></i> Admin</a>";
									echo"</li>";

									echo"<li class='divider'></li>";
									echo"<li>";
									echo"<a href='register.php'><i class='fa fa-fw fa-cog'></i> Add New User</a>";
									echo"</li>";
								}
						
						?>
						<li class="divider"></li>
                        <li>
                            <a href="index.php?logout"><i class="fa fa-fw fa-power-off"></i> Log Out</a>
                        </li>
                    </ul>
                </li>
            </ul>
            <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav side-nav">
                    <li class="active">
                        <a href="index.php"><i class="fa fa-fw fa-dashboard"></i> Dashboard</a>
                    </li>
                    <li>
                        <a href="players.php"><i class="fa fa-fw fa-child "></i> Players</a>
                    </li>
                    <li>
                        <a href="vehicles.php"><i class="fa fa-fw fa-car"></i> Vehicles</a>
                    </li>
                    <li>
                        <a href="houses.php"><i class="fa fa-fw fa-home"></i> Houses</a>
                    </li>
                   <li>
                        <a href="gangs.php"><i class="fa fa-fw fa-sitemap"></i> Gangs</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </nav>

        <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            Profile <small>Overview</small>
                        </h1>
                        <ol class="breadcrumb">
                            <li class="active">
                                <i class="fa fa-user"></i> Profile
                            </li>
                        </ol>
                    </div>
                </div>
                <!-- /.row -->

                    <div class="col-lg-4">
						<div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-user fa-fw"></i> <?php echo $_SESSION['user_name']; ?> </h3>
                            </div>
                            <div class="panel-body">
										<?php
											if (!$db_connection->connect_errno) 
											{
												
												if (!empty($_POST))
												{
													$email = $_POST['email'];
													
													$update = "UPDATE `users` SET `user_email`= '".$email."' WHERE `user_name` = '".$_SESSION['user_name']."' ";
													$result_of_query = $db_connection->query($update);
													$sql = "SELECT * FROM `users` WHERE `user_name` ='".$_SESSION['user_name']."' ;";													
												}
												else
												{
													$sql = "SELECT * FROM `users` WHERE `user_name` ='".$_SESSION['user_name']."' ;";
												}
												$sql = "SELECT * FROM `users` WHERE `user_name` ='".$_SESSION['user_name']."' ;";
												$result_of_query = $db_connection->query($sql);
												while($row = mysqli_fetch_assoc($result_of_query)) 
												{
										?>
													<form method='post' action='<?php echo htmlentities($_SERVER['PHP_SELF']); ?>' name='profileEdit'>
										<?php
														$userPid = $row["playerid"];
														echo "<center>";
															echo "<h4>Email: <input style='min-width:300px;text-align:center;'id='email' type='text' name='email' value='".$row["user_email"]."'></h4>";
															echo "<h4>Level: ".$row["user_level"]."</h4>";
															echo "<h4>Player ID: ".$row["playerid"]."</h4>";
															echo "<input class='btn btn-sm btn-primary'  type='submit'  name='edit' value='Update Profile'>";
														echo "</center>";
												};	
										?>			
													</form>
										<?php
											} 
											else 
											{
												$this->errors[] = "Database connection problem.";
											}
										?>
							</div>		                       
						</div>
					</div>
					<!-- /.Profile -->
					<div class="col-lg-8">
						<div class="panel panel-default">
							
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-child fa-fw"></i> My Player</h3>
                            </div>
							
                            <div class="panel-body">
								<table class="table table-bordered table-hover table-striped">
									<thead>
										<tr>
											<th>Player Name</th>
											<th>Player ID</th>
											<th>Cash</th>
											<th>Bank</th>
											<th>Cop</th>
											<th>Medic</th>
											<th>Admin</th>
											<th>Edit</th>
										</tr>
									</thead>
									<tbody>
							
										<?php
											if (!$db_connection->connect_errno) 
											{
												$sql = "SELECT * FROM `players` WHERE `playerId` ='".$userPid."'";												
												
												$result_of_query = $db_connection->query($sql);
												while($row = mysqli_fetch_assoc($result_of_query)) 
												{
													$playersID = $row["playerid"];
													echo "<tr>";
														echo "<td>".$row["name"]."</td>";
														echo "<td>".$playersID."</td>";
														echo "<td>".$row["cash"]."</td>";
														echo "<td>".$row["bankacc"]."</td>";
														echo "<td>".$row["coplevel"]."</td>";
														echo "<td>".$row["mediclevel"]."</td>";
														echo "<td>".$row["adminlevel"]."</td>";
														echo "<td><form method='post' action='editPlayer.php' name='PlayerEdit'>";
														echo "<input id='playerId' type='hidden' name='playerId' value='".$playersID."'>";
														echo "<input class='btn btn-sm btn-primary'  type='submit'  name='edit' value='Edit Player'>";
														echo "</form></td>";
													echo "</tr>";
												};
											}
											else 
											{
												$this->errors[] = "Database connection problem.";
											}
										?>
									</tbody>
								</table>
							</div>		                       
						</div>
					</div>		                
            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <!-- Morris Charts JavaScript -->
    <script src="js/plugins/morris/raphael.min.js"></script>
    <script src="js/plugins/morris/morris.min.js"></script>
    <script src="js/plugins/morris/morris-data.js"></script>

</body>

</html>