<?php ob_start(); ?>
<!DOCTYPE HTML>
<html>
<meta http-equiv="content-type" content="text/html;charset=UTF-8"/>
<head>
<script src="../js/jquery.js" type="text/javascript"></script>
<script src="../js/bootstrap.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="../css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="../css/bootstrap-theme.css">

<style type="text/css">
	@font-face{
    	font-family:Cus_font;
    	src:url("../fonts/MyriadPro Regular.otf");
    }
    body{font-family:Cus_font;}
    .info{font-size: 20px;}
    .imp{width:120px;position: relative;top:60px;}
    .form-control{
       height:300px;
     }
</style>

<script>
  $(document).ready(function(){
      //get SMList & DBList
       <?php include'survey_import.php'; 
       		$res='';
       		$temp=get_defined_vars();
            $arr_sm=get_SMList(); $arr_db=get_DBList();?>
        var smlist=<?php echo $arr_sm; ?>;
        var dblist=<?php echo $arr_db; ?>;

      //display the SMList & DBList in Duallistbox
      for(var i=0; i<smlist.SMSurvey.length; i++){
          var flag=0;
          for(var j=0;j<dblist.DBSurvey.length; j++) {
            if(smlist.SMSurvey[i].s_id==dblist.DBSurvey[j].s_id) {flag=1;}
          };
          if(!flag) {
            $("#sml").append('<option value="'+smlist.SMSurvey[i].s_id+'">'+smlist.SMSurvey[i].s_name+'</option>');
          }
      }

      for(var j=0; j<dblist.DBSurvey.length;j++){
        $("#dbl").append('<option value="'+dblist.DBSurvey[j].s_id+'">'+dblist.DBSurvey[j].s_name+'</option>');
        $("#dbtable").append('<tr><td>'+dblist.DBSurvey[j].s_id+'</td><td>'+dblist.DBSurvey[j].s_name+'</td><td>'+dblist.DBSurvey[j].date_created+'</td><td>'+dblist.DBSurvey[j].date_modified+'</td></tr>');
      }

      $("#mov").click(function(){
          var isurveys=$('#sml option:selected').val();
          if(typeof isurveys!="undefined"){
          	$.ajax({
            url:"survey_import.php",
            type:"POST",
            data:{cur_data:isurveys,add:true},
            //dataType:"json",
            async:false, 
            success:function(msg){
            	if(msg!=""){
            		$("#alrmodal").find("h4").text(msg);
            		$("#alrmodal").modal('show');
            	}else{
            		window.location.href="import.php";
            	}
            }
          	});
          };       
      });

      $("#remov").click(function(){
          var db=$("#dbl option:selected").val();
          if(typeof db!="undefined"){
          	$.ajax({
            url:"survey_import.php",
            type:"POST",
            data:{cur_data:db,del:true},
            //dataType:"json",
            async:false,
            error: function(){  
              alert('Error loading JSON document');  
            },  
            success:function(data){}
          	});
           window.location.href="import.php";
        };
      }); 
  }) 
</script></head>
<body>
<div class="container">
	<nav class="navbar navbar-inverse nav-collapse" role="navigation"> 
	<nav class="navbar-inner nav-collapse" style="height: auto;">
	<div class="navbar-header">
	<a class="navbar-brand" href="info.php">Healthy Kids Zone</a></div>
	<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
	<ul class="nav navbar-nav">
	<li><a href="import.php">Import</a></li>
	<li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown">Configuration <b class="caret"></b></a>
	<ul class="dropdown-menu">
	<li><a href="school.php">School</a></li>
	<li><a href="path.php">Path</a></li>
	<li><a href="association.php">Association</a></li>
	</ul>
	</li>
	<li><a href="deployment.php">Deployment</a></li>
	<li><a href="export.php">Export</a></li>
	<li><a href="#">Log out</a></li>
	</ul>
	</div>
	</nav> </nav>

<div class="row">
  <div class="col-md-3">
  	<div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Survey Import</h3>
    </div>
    <div class="panel-body">
      <p>In this part, you can view the survey list in Survey Monkey and Database. By click on move and remove button, you can import a survey from Survey Monkey to Database or delete the survey from database to Survey Monkey.</p>
    </div>
    </div>
  </div>
  <div class="col-md-9">
    <ul class="nav nav-tabs" id="survey_tabs">
        <li class="active"><a a href="#import" data-toggle="tab">Survey Import</a></li></ul>
    <div class="tab-content">
        <div class="tab-pane fade in active" id="manage"><br>
          <div class="row">
            <div class="col-md-5">
              <span class="info">Survey Monkey List</span>
              <select class="form-control" size="10" id="sml" ></select>
            </div>
            <div class="col-md-2"><center>
              <button type="button" class="btn btn-primary btn-lg imp" id="mov">Move</button><br><br>
              <button type="button" class="btn btn-default btn-lg imp" id="remov">Remove</button></center>
            </div>
            <div class="col-md-5">
              <span class="info">Database List</span>
              <select class="form-control" size="10" id="dbl"></select>
            </div>
          </div>
          <br><br><div class="row">
            <table class="table table-hover" id="dbtable"><tbody>
              <tr><th>Survey Id</th><th>Survey Name</th><th>Created Date</th><th>Modified Date</th></tr></tbody></table>
          </div>
        </div>
    </div>
  </div></div>
  <div class="modal fade" id="alrmodal">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-body">
              <h4></h4>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" id="hint">Close</button>
              </div>
            </div>
          </div>
    </div>
 </div>
</body>
</html>