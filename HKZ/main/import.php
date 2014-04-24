<?php ob_start(); ?>
<!DOCTYPE HTML>
<html>
<meta http-equiv="content-type" content="text/html;charset=UTF-8"/>
<head>
<script src="../js/jquery.js" type="text/javascript"></script>
<script src="../js/bootstrap.js" type="text/javascript"></script>
<script src="../js/ajaxfileupload.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="../css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="../css/bootstrap-theme.css">

<style type="text/css">
	@font-face{
    	font-family:Cus_font;
    	src:url("../fonts/MyriadPro Regular.otf");
    }
    body{font-family:Cus_font;}
    .info{font-size: 20px;}
    .imp{width:120px;position: relative;top:100px;}
    #sml,#dbl{height:300px;}
    #showing{width: 32px;height: 32px;}
</style>

<script>
  <?php include'survey_import.php'; 
        $directory_self = str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']);
        $uploadpath = 'http://' . $_SERVER['HTTP_HOST'] . $directory_self . 'question_img/';
        $res='';
        $temp=get_defined_vars();
        $arr_sm=get_SMList(); $arr_db=get_DBList();?>

  var smlist=<?php echo $arr_sm; ?>;
  var dblist=<?php echo $arr_db; ?>;
  var content='',qlist=[];
  
  function init_SM(){
        init='';
        for(var i=0; i<smlist.SMSurvey.length; i++){
          var flag=0;
          for(var j=0;j<dblist.DBSurvey.length; j++) {
            if(smlist.SMSurvey[i].s_id==dblist.DBSurvey[j].s_id) {flag=1;}
          };
          if(!flag) {
            init+='<option value="'+smlist.SMSurvey[i].s_id+'">'+smlist.SMSurvey[i].s_name+'</option>';
          }
      }
      $("#sml").html(init);
  }

  function init_DB(){
    init='';init_tb='';
    for(var j=0; j<dblist.DBSurvey.length;j++){
        init+='<option value="'+dblist.DBSurvey[j].s_id+'">'+dblist.DBSurvey[j].s_name+'</option>';
        init_tb+='<tr><td>'+dblist.DBSurvey[j].s_id+'</td><td>'+dblist.DBSurvey[j].s_name+'</td><td>'+dblist.DBSurvey[j].date_created+'</td><td>'+dblist.DBSurvey[j].date_modified+'</td></tr>';
      }
    $("#dbl").html(init);$("#dbtable").html(init_tb);
    $("#img_sl").html(init);
  }

  function init_qlist(cont){
    for(i=0;i<3;i++){
      qlist[i]='<option value=-1></option>';
    }
    
    for(j=0;j<cont.Questions.Blocks.length;j++){
      qlist[0]+='<option value='+cont.Questions.Blocks[j].q_id+'>'+(j+1)+'</option>';
      //alert(cont.Questions.Blocks[j].q_id);
    }
    for(j=0;j<cont.Questions.Tallies.length;j++){
      qlist[1]+='<option value='+cont.Questions.Tallies[j].q_id+'>'+(j+1)+'</option>';
      //alert(cont.Questions.Tallies[j].q_id);
    }
    for(j=0;j<cont.Questions.Others.length;j++){
      qlist[2]+='<option value='+cont.Questions.Others[j].q_id+'>'+(j+1)+'</option>';
      //alert(cont.Questions.Others[j].q_id);
    }
  }

  function getFilename(pt){
    var pos=pt.lastIndexOf("\\");
    return pt.substring(pos+1);
  }

  $(document).ready(function(){
      //get SMList & DBList
        init_SM();init_DB();$("#pnl").hide();$("#loading").hide();

      $("#survey_tabs a").click(function(e){
        $(this).tab('show');
      });

      $("#survey_tabs a[href='#manage']").on('shown.bs.tab',function(e){
        init_SM();init_DB();
      });

      $("#survey_tabs a[href='#upl']").on('shown.bs.tab',function(e){
        $("#img").val('');
        init_DB();
        $("#img_sl").trigger('change');$("#s_type").trigger("change");
        $("#pnl").hide();$("#loading").hide();
      });
      $("#survey_tabs a[href='#manage']").tab('show');


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
          }else{
            $("#alrmodal").find("h4").text("Please select one Survey Monkey survey.");
            $("#alrmodal").modal('show');
          }  
      });

      $("#remov").click(function(){
          var db=$("#dbl option:selected").val();
          if(typeof db!="undefined"){
            $("#alrmodal_1").find("h4").text("Do you really want to remove the "+$("#dbl option:selected").text()+" survey?");
            $("#alrmodal_1").modal('show');
            $(document).on("click","#sub_del",function(){
              $.ajax({
                url:"survey_import.php",
                type:"POST",
                data:{cur_data:db,del:true},
                //dataType:"json",
                async:false,
                error: function(){  
                  $("#alrmodal").find("h4").text("Error removing the survey.");
                  $("#alrmodal").modal('show');
                }
              });
              window.location.href="import.php";
            })
          }else{
            $("#alrmodal").find("h4").text("Please select one database survey.");
            $("#alrmodal").modal('show');
          }
      }); 

      $("#img_sl").change(function(){
        var surid=$("#img_sl option:selected").val();
        $.ajax({
            url:"request_questions.php",
            type:"GET",
            data:{s_id:surid},
            //dataType:"json",
            async:false,
            error: function(){  
              alert('Error loading JSON document');  
            },  
            success:function(data){
              content=eval(data);
            }
          });
        init_qlist(content);
        if($("#qsl option:selected").text()!=''){
          qid=$("#qsl option:selected").text();
          if(qtype==0){
            $("#q_cont").html(content.Questions.Blocks[parseInt(qid)-1].q_heading);
          }else if(qtype==1){
            $("#q_cont").html(content.Questions.Tallies[parseInt(qid)-1].q_heading);
          }else{
            $("#q_cont").html(content.Questions.Others[parseInt(qid)-1].q_heading);
          }
        }
      });

      $("#s_type").change(function(){
        qtype=$("#s_type option:selected").val();
        $("#qsl").html(qlist[qtype]);
        $("#pnl").hide();
      });

      $("#qsl").change(function(){
        qid=$("#qsl option:selected").text();
        qtype=$("#s_type option:selected").val();
        if(qid!=''){
          if(qtype==0){
            $("#q_cont").html(content.Questions.Blocks[parseInt(qid)-1].q_heading);
          }else if(qtype==1){
            $("#q_cont").html(content.Questions.Tallies[parseInt(qid)-1].q_heading);
          }else{
            $("#q_cont").html(content.Questions.Others[parseInt(qid)-1].q_heading);
          }
          $("#pnl").show();
        }else{
          $("#pnl").hide();
        }
      });

      $("#upl_asso").click(function(){
          if($("#qsl option:selected").text()==''){
              $("#alrmodal").find("h4").text("Please select one question to associate image.");
              $("#alrmodal").modal('show');
          }else{
            var filepath='<?php echo $uploadpath ?>'+getFilename($("#img").val());
            $("#loading").show();
            $.ajaxFileUpload ({
            url:'doajaxfileupload.php', 
            secureuri:false,
            fileElementId:'img',
            dataType: 'text', 
            success: function (data) {
              if(data=='success'){
                $.ajax({
                  url:"associate_image.php",
                  type:"GET",
                  data:{q_id:$("#qsl option:selected").val(),url:filepath},
                  async:false,
                  error: function(){  
                    $("#alrmodal").find("h4").text("Error in associating image, please try again later.");
                    $("#alrmodal").modal('show');
                  },  
                  success:function(){
                    $("#alrmodal").find("h4").text("Image has successfully associated with question!");
                    $("#alrmodal").modal('show');
                  }
                });
              }else{
                    $("#alrmodal").find("h4").text(data);
                    $("#alrmodal").modal('show');
              };
              $("#loading").hide(); 
            },
            error: function (data, status, e)
            {
                $("#alrmodal").find("h4").text(e);
                $("#alrmodal").modal('show');
                $("#loading").hide();
            }
          })
          $("#img").val('');
        }
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
	<li><a href="../index.php">Log out</a></li>
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
        <li class="active"><a href="#manage" data-toggle="tab">Survey Import</a></li>
        <li><a href="#upl" data-toggle="tab">Images Upload</a></li></ul>
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
            <table class="table table-hover">
              <tr><th>Survey Id</th><th>Survey Name</th><th>Created Date</th><th>Modified Date</th></tr><tbody id="dbtable"></tbody></table>
          </div>
        </div>
        <div class="tab-pane fade in" id="upl"><br>
         <div class="row">
            <div class="col-md-5">
              <span class="info">Survey List</span>
              <select class="form-control" id="img_sl"></select>
            </div>

            <div class="col-md-3">
              <span class="info">Question Type</span>
              <select class="form-control" id="s_type">
                <option value="0">Block</option>
                <option value="1">Tally</option>
                <option value="2">Other</option>
              </select></div>
            <div class="col-md-2">
              <span class="info">Question No.</span>
              <select class="form-control" id="qsl"></select>
            </div>
            
            </div><br>
            <form id="upload" class="form-horizotal" action="" enctype="multipart/form-data" method="post" role="form">
                  <div class="form-group">
                  <div class="row">
                    <span class="info col-md-3" for="file">Search for image:</span>
                    <div class="col-md-7">
                      <input id="img" type="file" name="img">
                    </div>
                  </div></div>
                  <div class="form-group">
                  <div class="row">
                    <span class="info col-md-3" for="submit">Associate Image:</span>
                    <div class="col-md-2">
                      <input class="btn btn-primary" id="upl_asso" type="button" value="Upload">
                      </div>
                    <div id="loading"><img id="showing" src="loading.gif"></div>
                  </div></div>     
              </form><br>
              <div class="row">
                <div class="col-md-10">
                  <div class="panel panel-primary" id="pnl">
                <div class="panel-heading">
                <h3 class="panel-title">Question Content</h3>
                </div>
                <div class="panel-body">
                  <p id="q_cont"></p>
                </div>
                </div>
              </div>
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
    <div class="modal fade in" id="alrmodal_1">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title"></h4>
              </div>
              <div class="modal-body">
              <p>Warning: this means once you remove this survey, you will also delete all the associated survey results.</p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="sub_del">Remove</button>
              </div>
            </div>
          </div>
    </div>
 </div>
</body>
</html>