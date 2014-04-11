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
          <li><a href="path.php">Path</a></li>
          <li><a href="association.php">Association</a></li>
         </ul>
        </li>
        <li><a href="deployment.php">Deployment</a></li>
        <li><a href="export.php">Export</a></li>
        <li><a href="#">Log out</a></li>
        </ul></div>
      </nav>
  </nav>
	<div class="jumbotron">
  		<h2>Healthy Kids Zone Survey Management System</h2>
  		<p>Healthy Kids Zone Survey Management System is designed for administrators to import their created surveys from Survey Monkey to the database, associate survey with paths and schools, and export survey results to analysis.</p>
  		<p><a class="btn btn-primary btn-lg" role="button">Learn more</a></p>
		</div>
</div>
</body></html>