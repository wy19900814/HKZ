<?php ob_start(); ?>
<!DOCTYPE HTML>
<html>
<meta http-equiv="content-type" content="text/html;charset=UTF-8"/>
<head>
<script src="../js/jquery.js" type="text/javascript"></script>
<script src="../js/bootstrap.js" type="text/javascript"></script>
<script src="jQuery/jquery.bootstrap-duallistbox.js" type="text/javascript"></script>
<script src="jQuery/prettify.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="../css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="../css/bootstrap-theme.css">
<link rel="stylesheet" type="text/css" href="css/bootstrap-duallistbox.css">
<link rel="stylesheet" type="text/css" href="css/prettify.css">

<style type="text/css">
	@font-face{
    	font-family:Cus_font;
    	src:url("../fonts/MyriadPro Regular.otf");
    }
    body{font-family:Cus_font;}
</style>

<script>
  $(document).ready(function(){
      //get SMList & DBList
      <?php include'survey_import.php'; 
            $arr_sm=get_SMList(); $arr_db=get_DBList();
            if(isset($_POST['update'])){
            $back="before";
            $cur_data=$_POST['cur_data'];
            $dbl=$_POST['db'];
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
            }
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
            $("#slist").append('<option value="'+smlist.SMSurvey[i].s_id+'">'+smlist.SMSurvey[i].s_name+'</option>');
          }
      }

      for(var j=0; j<dblist.DBSurvey.length;j++){
        $("#slist").append('<option value="'+dblist.DBSurvey[j].s_id+'" selected>'+dblist.DBSurvey[j].s_name+'</option>');
        $("#dbtable").append('<tr><td>'+dblist.DBSurvey[j].s_id+'</td><td>'+dblist.DBSurvey[j].s_name+'</td><td>'+dblist.DBSurvey[j].date_created+'</td><td>'+dblist.DBSurvey[j].date_modified+'</td></tr>');
      }

      //Initial the Duallistbox 
      $("#slist").bootstrapDualListbox({
      nonselectedlistlabel:"Survey Lists from Survey Monkey",
      selectedlistlabel:'Survey Lists from Database',
      preserveselectiononmove:'moved',
      moveonselect:false
      });


      $("#list_btn").click(function(){
          var isurveys=$('#slist').val();
          var db_data="";
          for(var i=0;i<dblist.DBSurvey.length;i++){
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
          //alert(isurveys);
           $.ajax({
            url:"import.php",
            type:"POST",
            data:{cur_data:isurveys,db:db_data,update:true},
            //dataType:"json",
            async:false,
            error: function(){  
              alert('Error loading JSON document');  
            },  
            success:function(data){}
          });
           window.location.reload();
      });
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
<li><a href="path.php" target="ifr_b">Path</a></li>
<li><a href="survey.php" target="ifr_b">Survey</a></li>
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
<form id="slistform" action=""><select multiple="multiple" size="10" name="duallistbox[]" id="slist"></select><br />
<button type="button" class="btn btn-default btn-block" id="list_btn">Update Survey</button></form><br><br><br>
<div class="row">
<table class="table table-hover" id="dbtable">
<tbody>
<tr>
<td>Survey Id</td>
<td>Survey Name</td>
<td>Created Date</td>
<td>Modified Date</td>
</tr>
</tbody>
</table>
</div>
</div>
</body>
</html>