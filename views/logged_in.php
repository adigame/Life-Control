<?php
	// create a database connection, using the constants from config/db.php (which we loaded in index.php)
	$db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

	// change character set to utf8 and check it
	if (!$db_connection->set_charset("utf8")) {
		$db_connection->errors[] = $db_connection->error;
	}

	require __DIR__ . '/SourceQuery/SourceQuery.class.php';
	
	// Edit this ->
	define( 'SQ_TIMEOUT',     1 );
	define( 'SQ_ENGINE',      SourceQuery :: SOURCE );
	// Edit this <-
	
	$Timer = MicroTime( true );
	
	$Query = new SourceQuery( );
	
	$Info    = Array( );
	$Rules   = Array( );
	$Players = Array( );
	
	try
	{
		$Query->Connect( SQ_SERVER_ADDR, SQ_SERVER_PORT, SQ_TIMEOUT, SQ_ENGINE );
		
		$Info    = $Query->GetInfo( );
		$Players = $Query->GetPlayers( );
		$Rules   = $Query->GetRules( );
	}
	catch( Exception $e )
	{
		$Exception = $e;
	}
	
	$Query->Disconnect( );
	
	$Timer = Number_Format( MicroTime( true ) - $Timer, 4, '.', '' );
	
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

	<script src="js/raphael.2.1.0.min.js"></script>
	<script src="js/justgage.1.0.1.min.js"></script>
	<script>
		var g;
	
		window.onload = function(){
			  var g = new JustGage({
				id: "bigfella",
				value:<?php echo htmlspecialchars( $Info[ 'Players' ] ); ?>,
				min: 0,
				max: <?php echo htmlspecialchars( $Info[ 'MaxPlayers' ] ); ?>,
				title: 'Current Players'
			  });
		};
	</script>
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
                            Dashboard <small>Statistics Overview</small>
                        </h1>
                        <ol class="breadcrumb">
                            <li class="active">
                                <i class="fa fa-dashboard"></i> Dashboard
                            </li>
                        </ol>
                    </div>
                </div>
				
				<div class="alert alert-info alert-dismissable">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<i class="fa fa-info-circle"></i> <strong>Welcome</strong> To Life Control <?php echo $_SESSION['user_name']; ?>.
				</div>
				
				<div class="row">
                    <div class="col-lg-3 col-md-6">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <div class="row">
									<center><div id="bigfella" style="width:200px; height:120px"></div></center>
                                </div>
                            </div>
                            <a href="curPlayers.php">
                                <div class="panel-footer">
                                    <span class="pull-left">View All Players</span>
                                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                        </div>
                    </div>
				</div>	
				
				<div class="row">
					<div class="col-lg-4">
						<div class="panel panel-default">
							<div class="panel-heading">
								<h3 class="panel-title"><i class="fa fa-taxi fa-fw"></i>Police Overview</h3>
							</div>
							<div class="panel-body">
								<div class="table-responsive">
									<table class="table table-bordered table-hover table-striped">
										<thead>
											<tr>
												<th>Player Name</th>
												<th>Player ID</th>
												<th>Rank</th>
											</tr>
										</thead>
										<tbody>
											<?php
												if (!$db_connection->connect_errno) 
												{
													$sql = "SELECT `name`,`coplevel`,`playerid` FROM `players` WHERE `coplevel` >= '1' ORDER BY `coplevel` DESC LIMIT 10";
													$result_of_query = $db_connection->query($sql);
													while($row = mysqli_fetch_assoc($result_of_query)) 
													{
														$playersID = $row["playerid"];
														echo "<tr>";
															echo "<td>".$row["name"]."</td>";
															echo "<td>".$playersID."</td>";
															echo "<td>".$row["coplevel"]."</td>";
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
								<div class="text-right">
									<a href="police.php">View All Police <i class="fa fa-arrow-circle-right"></i></a>
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-4">
						<div class="panel panel-default">
							<div class="panel-heading">
								<h3 class="panel-title"><i class="fa fa-money fa-fw"></i>Top Ten Richest Players</h3>
							</div>
							<div class="panel-body">
								<div class="table-responsive">
									<table class="table table-bordered table-hover table-striped">
										<thead>
											<tr>
												<th>Player Name</th>
												<th>Player ID</th>
												<th>Cash</th>
												<th>Bank</th>
											</tr>
										</thead>
										<tbody>
											<?php
												if (!$db_connection->connect_errno) 
												{
													$sql = "SELECT `name`, `cash`, `bankacc`, `playerid` FROM `players` ORDER BY `bankacc` DESC LIMIT 10";
													$result_of_query = $db_connection->query($sql);
													while($row = mysqli_fetch_assoc($result_of_query)) 
													{
														$playersID = $row["playerid"];
														echo "<tr>";
															echo "<td>".$row["name"]."</td>";
															echo "<td>".$playersID."</td>";
															echo "<td>".$row["cash"]."</td>";
															echo "<td>".$row["bankacc"]."</td>";
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
								<div class="text-right">
									<br/>
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-4">
						<div class="panel panel-default">
							<div class="panel-heading">
								<h3 class="panel-title"><i class="fa fa-ambulance fa-fw"></i>Medic Overview</h3>
							</div>
							<div class="panel-body">
								<div class="table-responsive">
									<table class="table table-bordered table-hover table-striped">
										<thead>
											<tr>
												<th>Player Name</th>
												<th>Player ID</th>
												<th>Rank</th>
											</tr>
										</thead>
										<tbody>
											<?php
												if (!$db_connection->connect_errno) 
												{
													$sql = "SELECT `name`,`mediclevel`,`playerid` FROM `players` WHERE `mediclevel` >= '1' ORDER BY `mediclevel` DESC LIMIT 10";
													$result_of_query = $db_connection->query($sql);
													while($row = mysqli_fetch_assoc($result_of_query)) 
													{
														$playersID = $row["playerid"];
														echo "<tr>";
															echo "<td>".$row["name"]."</td>";
															echo "<td>".$playersID."</td>";
															echo "<td>".$row["mediclevel"]."</td>";
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
								<div class="text-right">
									<a href="medic.php">View All Medics <i class="fa fa-arrow-circle-right"></i></a>
								</div>
							</div>
						</div>
					</div>
				</div>
                <!-- /.row -->			
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
