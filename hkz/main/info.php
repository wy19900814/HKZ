<!DOCTYPE html>
<html lang="en">
<head>
<script src="../js/jquery.js" type="text/javascript"></script>
<script src="../js/bootstrap.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="../css/bootstrap-theme.css">
<link rel="stylesheet" type="text/css" href="../css/bootstrap.css">
<style type="text/css">
	@font-face{
    	font-family:Cus_font;
    	src:url("../fonts/MyriadPro Regular.otf");
    }
    body{font-family:Cus_font;}
    #board{height: 233px;}
    #board h2,p{position: relative;bottom: 20px;}
    #carousel-example{height:300px;}
    .slides{height: 400px;}
</style>
</head>
<body>
<div class="container">
	<nav class="navbar navbar-inverse nav-collapse" role="navigation">
    <nav class="navbar-inner nav-collapse" style="height:auto;">
    <div class="navbar-header">
      <a class="navbar-brand" href="info.php">Healthy Kids Zone</a>
    </div>
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
      <li><a href="import.php">Import</a></li>
        <li class="dropdown">
         <a href="" class="dropdown-toggle" data-toggle="dropdown">Configuration <b class="caret"></b></a>
         <ul class="dropdown-menu">
          <li><a href="school.php">School</a></li>
          <li><a href="path.php" target="ifr_b">Path</a></li>
          <li><a href="survey.php" target="ifr_b">Survey</a></li>
         </ul>
        </li>
        <li><a href="deployment.php">Deployment</a></li>
        <li><a href="export.php">Export</a></li>
        <li><a href="../index.php">Log out</a></li>
        </ul></div>
      </nav>
  </nav>
  <div class="row">
    <div class="col-md-2">
      <img style="width:172px;height:233px "src="HKZlogoFINAL.PNG">
    </div>
    <div class="col-md-10">
      <div class="jumbotron" id="board">
      <h2>Healthy Kids Zone Survey Management System</h2>
      <p>Healthy Kids Zone Survey Management System is designed for administrators to import their created surveys from Survey Monkey to the database, associate survey with paths and schools, and export survey results to analysis.</p>
    </div>
    </div>
  </div>
<!--  <div id="carousel-example" class="carousel slide" data-ride="carousel">
  <ol class="carousel-indicators">
    <li data-target="#carousel-example" data-slide-to="0" class="active"></li>
    <li data-target="#carousel-example" data-slide-to="1"></li>
  </ol>

  <div class="carousel-inner">
    <div class="item active">
      <img src="csl1.jpg" alt="Website Tutorial">
    </div>
    <div class="item">
      <img src="csl2.jpg" alt="Phone Application Tutorial">
    </div>
  </div>

  <a class="left carousel-control" href="#carousel-example" data-slide="prev">
    <img class="slides" src="left.png">
  </a>
  <a class="right carousel-control" href="#carousel-example" data-slide="next">
    <img class="slides" src="right.png">
  </a>
</div> -->

  </div>
	
</body></html>