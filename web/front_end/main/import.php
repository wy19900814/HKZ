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
    .btn{width: 150px;position: relative;top:60px;}
    .form-control{
       height:300px;
     }
</style>

<script>
  $(document).ready(function(){
      //get SMList & DBList
       <?php include'survey_import.php'; 
            $arr_sm=get_SMList(); $arr_db=get_DBList();
            if(isset($_POST['add'])){
            $cur_data=$_POST['cur_data'];
            survey_import($cur_data);
           /* $dbl=$_POST['db'];
            if($dbl!="none"){
            for($j=0;$j<count($cur_data);$j++){
            	$flag=true;
            		for($i=0;$i<count($dbl);$i++){
            			if($cur_data[$j]==$dbl[$i]){$flag=false;}
            		}
              		if($flag){survey_import($cur_data[$j]);}
            }
            for($i=0;$i<count($dbl);$i++){
            	$flag=true;
            	for($j=0;$j<count($cur_data);$j++){
            		if($dbl[$i]==$cur_data[$j]){$flag=false;}
            		}
            	if($flag){survey_delete($dbl[$i]);}
            	}
            }else{
            	for($j=0;$j<count($cur_data);$j++){survey_import($cur_data[$j]);}
            }*/
           }
            if(isset($_POST['del'])){
              $cur_data=$_POST['cur_data'];
              survey_delete($cur_data);
            }
      ?>
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
        /*  for(var i=0;i<dblist.DBSurvey.length;i++){
          	if(i==dblist.DBSurvey.length-1){
          		db_data=db_data+dblist.DBSurvey[i].s_id;
          	}
          	else{
          		db_data=db_data+dblist.DBSurvey[i].s_id+",";
          	}
          }
          db_data=db_data.split(",");
          if(db_data==""){db_data="none"};
          //alert(db_data);
          //alert(isurveys);*/
           $.ajax({
            url:"import.php",
            type:"POST",
            data:{cur_data:isurveys,add:true},
            //dataType:"json",
            async:false,
            error: function(){  
              alert('Error loading JSON document');  
            },  
            success:function(data){}
          });
           window.location.href="import.php";
      });

      $("#remov").click(function(){
          var db=$("#dbl option:selected").val();
          $.ajax({
            url:"import.php",
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
      })
  })
</script></head>
<body>
<div class="container"><nav class="navbar navbar-inverse nav-collapse" role="navigation"> <nav class="navbar-inner nav-collapse" style="height: auto;">
<div class="navbar-header"><a class="navbar-brand" href="info.php">Healthy Kids Zone</a></div>
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
<div class="jumbotron">
<h2>Introduction</h2>
<p>In this part, you can import the surveys created in Survey Monkey to our database. Firstly, you need to select the surveys in the left(created using Survey Monkey) list and add them to the right list which would be saved in database. Once you submit the current selections, it can store the survey lists in the right list to our database.</p>
</div>

<div class="row">
  <div class="col-md-5">
  <span class="info">Survey Monkey List</span>
  <select class="form-control" size="10" id="sml" ></select></div>
  <div class="col-md-2"><center>
    <button type="button" class="btn btn-primary btn-lg" id="mov">Move</button><br><br>
    <button type="button" class="btn btn-default btn-lg" id="remov">Remove</button></center>
  </div>
  <div class="col-md-5">
  <span class="info">Database List</span>
  <select class="form-control" size="10" id="dbl"></select></div>
  </div>
<br><br>
<div class="row">
<table class="table table-hover" id="dbtable">
<tbody>
<tr>
<th>Survey Id</th>
<th>Survey Name</th>
<th>Created Date</th>
<th>Modified Date</th>
</tr>
</tbody>
</table>
</div>
</div>
</body>
</html>