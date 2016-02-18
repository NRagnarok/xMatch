<?php
session_start();
$c = mysqli_connect("localhost", "xmatch", "xmatch", "xmatch");
if(isset($_SESSION['profil'])){
	if(isset($_GET['next'])){
		$u = mysqli_fetch_array(mysqli_query($c, "SELECT vote FROM profils WHERE id = '".$_SESSION['profil']."'"));
		$q = $u['vote']-1;
		mysqli_query($c, "UPDATE profils SET vote = '".$q."' WHERE id = '".$_SESSION['profil']."'");
		$e = mysqli_fetch_assoc(mysqli_query($c, "SELECT COUNT(*) as t FROM ip WHERE ip='".$_SERVER['REMOTE_ADDR']."'"));
		if($e['t'] == 0){
			$t[0] = $_SESSION['profil'];
			mysqli_query($c, "INSERT INTO ip (ip, profils) VALUES ('".$_SERVER['REMOTE_ADDR']."', '".serialize($t)."')");
		}else{
			$m = mysqli_fetch_array(mysqli_query($c, "SELECT profils FROM ip WHERE ip = '".$_SERVER['REMOTE_ADDR']."'"));
			$j = unserialize($m['profils']);
			$h = count($j);
			$j[$h] = $_SESSION['profil'];
			mysqli_query($c, "UPDATE ip SET profils = '".serialize($j)."' WHERE ip='".$_SERVER['REMOTE_ADDR']."'");
		}
	}else if(isset($_GET['like'])){
		$u = mysqli_fetch_array(mysqli_query($c, "SELECT vote FROM profils WHERE id = '".$_SESSION['profil']."'"));
		$q = $u['vote']+1;
		mysqli_query($c, "UPDATE profils SET vote = '".$q."' WHERE id = '".$_SESSION['profil']."'");
		$e = mysqli_fetch_assoc(mysqli_query($c, "SELECT COUNT(*) as t FROM ip WHERE ip='".$_SERVER['REMOTE_ADDR']."'"));
		if($e['t'] == 0){
			$t[0] = $_SESSION['profil'];
			mysqli_query($c, "INSERT INTO ip (ip, profils) VALUES ('".$_SERVER['REMOTE_ADDR']."', '".serialize($t)."')");
		}else{
			$m = mysqli_fetch_array(mysqli_query($c, "SELECT profils FROM ip WHERE ip = '".$_SERVER['REMOTE_ADDR']."'"));
			$j = unserialize($m['profils']);
			$h = count($j);
			$j[$h] = $_SESSION['profil'];
			mysqli_query($c, "UPDATE ip SET profils = '".serialize($j)."' WHERE ip='".$_SERVER['REMOTE_ADDR']."'");
		}
	}
session_unset($_SESSION['profil']);
header('Location:?');
exit();
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>XMatch</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
    body {
        padding-top: 70px;
        /* Required padding for .navbar-fixed-top. Remove if using .navbar-static-top. Change if height of navigation changes. */
    }
    </style>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="?">XMach</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li>
                        <a href="?">Accueil</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

    <!-- Page Content -->
    <div class="container">

        <div class="row">
            <div class="col-lg-12 text-center">
				<div style="width:100%;position:relative;display:inline-block;text-align:center;">
                	<?php
					$f = 0;
					$a = mysqli_fetch_assoc(mysqli_query($c, "SELECT COUNT(*) as t FROM profils"));
					$b = mysqli_fetch_assoc(mysqli_query($c, "SELECT COUNT(*) as t FROM ip WHERE ip='".$_SERVER['REMOTE_ADDR']."'"));
					if($b['t'] == 0){
						$r = rand(1, $a['t']);
						$y = mysqli_fetch_array(mysqli_query($c, "SELECT lien FROM profils WHERE id = '".$r."'"));
					}else{
						$s = 0;
						$i = 0;
						while($s == 0){
							$r = rand(1, $a['t']);
							$x = mysqli_fetch_array(mysqli_query($c, "SELECT profils FROM ip WHERE ip = '".$_SERVER['REMOTE_ADDR']."'"));			
							if(!in_array($r, unserialize($x['profils']))){
								$s = 1;
							}
							if($i == $a['t']){
								$s = 1;
								$f = 1;
							}
							$i++;
						}
						//echo var_dump(unserialize($x['profils']));
						//echo unserialize($x['profils'])[0];
						if($f == 0){$y = mysqli_fetch_array(mysqli_query($c, "SELECT lien FROM profils WHERE id = '".$r."'"));}
					}
					if($f == 0){
					$_SESSION['profil'] = $r;
					$d = scandir("profils/".$y['lien']."/");
					$l = "profils/".$y['lien']."/".$d[2];
					
					?>
                 	<img src="<?php echo ($l);?>" class="img-responsive img-thumbnail" style="width: 100%; height:100%;">
                 	<a href="?next" class="btn btn-danger btn-large" style="position:absolute;bottom:10px;left:10px;"><h1>Next</h1></a>
                 	<a href="?like" class="btn btn-success btn-large" style="position:absolute;bottom:10px;right:10px;"><h2>Like</h2></a>
                    <?php
					}else{
					?>
                    <div class="alert alert-info">
  <i class="icon icon-info-circle icon-lg"></i>
  <strong>Attention !</strong> Il n'y a plus d'image disponibles !.
</div>
                    
                    <?php
					}
					?>
            	</div>            
            </div>
        </div>
        <div class="row">
        	<div class="col-lg-6">
            	<div class="panel panel-success">
   					<div class="panel-heading">
        				<h3 class="panel-title">Top 10</h3>
    				</div>
    				<div class="panel-body">
						
                        <?php
						$p = mysqli_query($c, "SELECT lien, vote FROM profils ORDER BY vote DESC LIMIT 10");
						while($o = mysqli_fetch_array($p)){
							$g = scandir("profils/".$o['lien']."/");
							$k = "profils/".$o['lien']."/".$g[2];
						?>
                        <div class="row list-group-item" style="vertical-align:central; text-align:center;">
                        	<div class="col-lg-6 text-center"><h2><?php echo $o['vote']; ?></h2></div>
                        	<div class="col-lg-6 text-center"><center><img src="<?php echo $k;?>" class="img-responsive img-circle" style="height:60px;"></center></div>
                        </div>
  						<?php
						}
						?>
                        
                        
                        
                        
					</div>
				</div>
			</div>
        	<div class="col-lg-6">
            	<div class="panel panel-danger">
   					<div class="panel-heading">
        				<h3 class="panel-title">Flop 10</h3>
    				</div>
    				<div class="panel-body">
                        <?php
						$p = mysqli_query($c, "SELECT lien, vote FROM profils ORDER BY vote ASC LIMIT 10");
						while($o = mysqli_fetch_array($p)){
							$g = scandir("profils/".$o['lien']."/");
							$k = "profils/".$o['lien']."/".$g[2];
						?>
                        <div class="row list-group-item" style="vertical-align:central; text-align:center;">
                        	<div class="col-lg-6 text-center"><h2><?php echo $o['vote']; ?></h2></div>
                        	<div class="col-lg-6 text-center"><center><img src="<?php echo $k;?>" class="img-responsive img-circle" style="height:60px;"></center></div>
                        </div>
  						<?php
						}
						?>
					</div>
				</div>
			</div>
        </div>

    </div>
    
    <!-- /.container -->

    <!-- jQuery Version 1.11.1 -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

</body>

</html>
