<!DOCTYPE html>
<html lang="en">
<head>
<script src="js/jquery.js" type="text/javascript"></script>
<script src="js/bootstrap.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="css/bootstrap-theme.css">

<style type="text/css">
	@font-face{
    	font-family:Cus_font;
    	src:url("fonts/MyriadPro Regular.otf");
    }
    body{font-family: Cus_font;}
    #main{height: 500px;
    	background: -webkit-linear-gradient(left,rgba(66,139,202,0),rgba(66,139,202,0.8),rgba(66,139,202,0));
		background: -o-linear-gradient(right,rgba(66,139,202,0),rgba(66,139,202,0.8),rgba(66,139,202,0));
		background: -moz-linear-gradient(right,rgba(66,139,202,0),rgba(66,139,202,0.8),rgba(66,139,202,0));
		background: linear-gradient(to right,rgba(66,139,202,0),rgba(66,139,202,0.6),rgba(66,139,202,0));}
	#pic{position:relative;height: 75px;width: 94px;}
	#title{padding:0;margin: 20px;}
	#hkz{font-size: 40px;position: relative;}
	#header{font-size: 25px;position: relative;top:80px;left:50px;}
	#lg{width: 400px;position: relative;top:80px;left:50px;}
	label{color:#999999;}
	#sub{width: 150px;position: relative;top:10px;}
	#link{position: relative;top:20px;}
	#login_form{width: 300px;text-align: center;}
	.form-group{width: 300px;}
	.form-control{height: 40px;font-size: 20px;}
	span {font-size: 15px;color:red;}
</style>

<script type="text/javascript">
 	$(document).ready(function(){
 	$("#sub").click(function(){
 		$("#err1").text("");
 		$("#err2").text("");
 		nm=$("#usr").val();
 		ps=$("#in_psw").val();
 		valid=true;
    RE=/^\w{5,10}$/gi;
 		//validate input
 		if(!nm){
 			$("#err1").text("Required");
 			valid=false;
 		}else if(!RE.test(nm)){
 			$("#err1").text("Invalid input");
 			valid=false;
 		}

 		if(!ps){
 			$("#err2").text("Required");
 			valid=false;
 		}

 		if(valid){
 			$.ajax({
       			url: "login.php",  
       	 		type: "POST",
        		data:{name:nm,pass:ps},
				dataType: "json",  
        		success: function(data){    
            		if(data.success){
                		window.location.href="main/info.php";
            		}else{
            			$("#alrmodal").find('h4').text("Mismatch of email and password!");
            			$("#alrmodal").modal('show');
            		}
        		}
    		});
 		} 
		
 	}); 
 })
 </script>
</head>
<body>
<div class="container">
<div id="title" class="row">
<div class="col-md-2"><center><img id="pic" src="logo.JPG" /></center></div>
<div id="hkz" class="col-md-10">Healthy Kids Zone Survey Management System</div>
</div>
</div>
<div id="main">
<div class="container">
<div class="row">
<div class="col-md-6" id="header"><b>Welcome to CHC</b>
<p>Community Health Councils (CHC) is a non-profit, community-based health education and policy organization. Established in 1992, our mission is to promote social justice and achieve equity in community and environmental resources for underserved populations. Watch the video About Community Health Councils to learn more about our work.</p>
</div>
<div class="col-md-6">
<div class="jumbotron" id="lg"><form role="form">
<div class="form-group"><label for="usr">Username</label>&nbsp;&nbsp;&nbsp;<span id="err1"></span> 
<input class="form-control" id="usr" name="em" placeholder="Enter Username" type="text" />
<label for="in_psw">Password</label>&nbsp;&nbsp;&nbsp;<span id="err2"></span>
<input class="form-control" id="in_psw" name="ps" placeholder="Enter Password" type="password" /></div>
<center><input id="sub" class="btn btn-lg btn-primary" value="Login" type="button" /><span class="hint"></span></form><br><!--<a href="#title" id="link">Forgot Password?</a></center>-->
</div>
  <div class="modal fade in" id="alrmodal">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-body">
              <h4></h4>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>
</div>
</div>
</div>
</div>
</body></html>