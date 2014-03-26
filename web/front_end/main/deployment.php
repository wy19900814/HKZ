<!DOCTYPE HTML>
<html>
  <script src="../js/jquery.js" type="text/javascript"></script>
  <script src="jQuery/jquery-ui.custom.js" type="text/javascript"></script>
  <script src="jQuery/jquery.cookie.js" type="text/javascript"></script>
  <script src="jQuery/jquery.dynatree.js" type="text/javascript"></script>
  <script src="../js/bootstrap.js" type="text/javascript"></script>
  <script type="text/javascript"
      src="http://maps.googleapis.com/maps/api/js?key=AIzaSyCk5e1145jcXNvV_siNFQ-nahoqyFigDzU&sensor=true">
  </script>
  <link rel="stylesheet" type="text/css" href="../css/bootstrap.css">
  <link href="src/skin/ui.dynatree.css" rel="stylesheet" type="text/css">
  <link rel="stylesheet" type="text/css" href="../css/bootstrap-theme.css">

  <style type="text/css">
  	@font-face{
    	font-family:Cus_font;
    	src:url("../fonts/MyriadPro Regular.otf");
    }
    body{font-family:Cus_font;}
    #mov,#remov{width: 100px;position: relative;top:130px;}
  </style>
  <script type="text/javascript">
  <?php include('school_path_survey.php');
	   include('survey_import.php');?>
  	var SPSList;
    var DBList;
    $.ajax({
            url:"get_data.php",
            cache: false,
            dataType:"json",
            data:{list:"getList"},
            type:"POST",
            async:false,
            success:function(data){
              SPSList=data;
            }
      });

    $.ajax({
            url:"get_data.php",
            cache: false,
            dataType:"json",
            data:{list_db:"getList"},
            type:"POST",
            async:false,
            success:function(data){
              DBList=data;
            }
      });

   function init_school(ls){
      var init='<option value=-1></option>';
      for(var i=0;i<SPSList.Schools.length;i++){
        chk_sch=0;
        if(SPSList.Schools[i].Paths.length>0){
          for(var j=0;j<SPSList.Schools[i].Paths.length;j++){
            if(SPSList.Schools[i].Paths[j].Surveys.length>0){
              count=0;
              for(var k=0;k<SPSList.Schools[i].Paths[j].Surveys.length;k++){
                for(var m=0;m<DBList.DBSurvey.length;m++){
                  if(SPSList.Schools[i].Paths[j].Surveys[k].s_id==DBList.DBSurvey[m].s_id && DBList.DBSurvey[m].deployed==1){count++;}
                }
              }
              if(count<SPSList.Schools[i].Paths[j].Surveys.length){chk_sch=1;}
            }
          }
        }
        if(chk_sch){
           init+='<option value='+SPSList.Schools[i].sch_id+'>'+SPSList.Schools[i].sch_name+'</option>';
        }
      };
      $(ls).html(init);
    };

    function init_path(ls){
          //show school list
          var init='<option value=-1></option>';
          $(ls).html(init);
    };

    function init_surveys(ls,status){
      var init="";
      for(var j=0; j<DBList.DBSurvey.length;j++){
          if(DBList.DBSurvey[j].deployed==status){
            init+='<option value='+DBList.DBSurvey[j].s_id+'>'+DBList.DBSurvey[j].s_name+'</option>';
          }
        }
      //$("#all_dep").attr("checked",false);
      $(ls).html(init);
    };

    function find_sch(ls){
        ls+=' option:selected';
        schoolid=$(ls).val();
        if(schoolid!=-1){
          for(j=0;j<SPSList.Schools.length;j++){
              if(SPSList.Schools[j].sch_id==schoolid){break;}
            };
          }
        else{j=-1;}
        return (j);
    };

     function find_path(sel_sch,ls){
        ls+=" option:selected";
        sel_id=$(ls).val();
        for(var i=0;i<SPSList.Schools[sel_sch].Paths.length;i++){
          if(SPSList.Schools[sel_sch].Paths[i].p_id==sel_id){break;}
        }
        return (i);
    };

    $(function(){
      $('#tree').dynatree({
          initAjax: {
          url: "test.json"
          }
      });
      init_school("#sch"); init_path("#pth");
      init_surveys("#undep",0);init_surveys("#dep",1);

    $("#sch").change(function(){
        init='<option value=-1></option>';
        j=find_sch("#sch");
        //alert($("#sch option:selected").val());
        if($("#sch option:selected").val()!=-1){
            for(var i=0;i<SPSList.Schools[j].Paths.length;i++){
              chk_pth=0;
              if(SPSList.Schools[j].Paths[i].Surveys.length>0){
                count=0;
                for(var k=0;k<SPSList.Schools[j].Paths[i].Surveys.length;k++){
                  for(var m=0;m<DBList.DBSurvey.length;m++){
                    if(SPSList.Schools[j].Paths[i].Surveys[k].s_id==DBList.DBSurvey[m].s_id && DBList.DBSurvey[m].deployed==1){count++;}
                  }
                }
                if(count<SPSList.Schools[j].Paths[i].Surveys.length){chk_pth=1;}
              }
              if(chk_pth){
                init+='<option value='+SPSList.Schools[j].Paths[i].p_id+'>'+SPSList.Schools[j].Paths[i].p_name+'</option>';
              }
            }
        }else{init_surveys("#undep",0);}
        $('#pth').html(init);
      });

    $("#pth").change(function(){
        init_surveys("#dep",1);
        j=find_sch("#sch");i=find_path(j,"#pth");
        init_undep='';count=[];
        if($("#pth option:selected").val()!=-1){
            for(var k=0;k<SPSList.Schools[j].Paths[i].Surveys.length;k++){
              chk=0;
              for(var m=0;m<DBList.DBSurvey.length;m++){
                if(SPSList.Schools[j].Paths[i].Surveys[k].s_id==DBList.DBSurvey[m].s_id && DBList.DBSurvey[m].deployed==0){chk=1;break;}
              }
              if(chk){
                init_undep+='<option value='+SPSList.Schools[j].Paths[i].Surveys[k].s_id+'>'+SPSList.Schools[j].Paths[i].Surveys[k].s_name+'</option>';
              }
            }
          $("#undep").html(init_undep);
        }else{init_surveys("#undep",0);}  
    });

    $("#mov").click(function(){
      if($("#sch option:selected").val()==-1 || $("#pth option:selected").val()==-1){
          $("#alrmodal_1").find('h4').text("Please select at least one school and one path");
          $("#alrmodal_1").modal('show');
        }else{
          if($("#undep option:selected").val()==undefined){
            $("#alrmodal_1").find('h4').text("Please select at least one survey");
            $("#alrmodal_1").modal('show');
          }else{
            $("#alrmodal").find("h4").text("Do you really want to deploy the following survey ?");
            $("#alrmodal").find("p").text("School: "+$("#sch option:selected").text()+", Path: "+$("#pth option:selected").text()+", Survey: "+$("#undep option:selected").text());
            $("#alrmodal").modal('show');
            $(document).on("click","#sub_dep",function(){
                  $.ajax({
                    url:"deployment.php",
                    data:{d_survey:$("#undep option:selected").val()},
                    type:"POST",
                    async:false
                  });
                <?php if(isset($_POST['d_survey'])){
                  survey_deploy($_POST['d_survey']);} ?>
                window.location.href="deployment.php";
            });
          }
        }
    });

    $("#remov").click(function(){
      if($("#dep option:selected").val()==undefined){
            $("#alrmodal_1").find('h4').text("Please select at least one survey");
            $("#alrmodal_1").modal('show');
          }else{
            $("#alrmodal_2").find("h4").text("Do you really want to undeploy the following survey ?");
            $("#alrmodal_2").find("p").text($("#dep option:selected").text());
            $("#alrmodal_2").modal('show');
            $(document).on("click","#sub_undep",function(){
                $.ajax({
                    url:"deployment.php",
                    data:{ud_survey:$("#dep option:selected").val()},
                    type:"POST",
                    async:false
                  });
                  <?php if(isset($_POST['ud_survey'])){
                    survey_retract($_POST['ud_survey']);} ?>
                  window.location.href="deployment.php";
              });
          }
        
    });
  });
  </script>
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
          <li><a href="">Log out</a></li>
        </ul></div>
        </nav>
      </nav>
    <div class="row">
    <div class="col-md-3">
      <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title">Survey Configuration</h3>
      </div>
      <div class="panel-body">
        <p>In survey configuration part, administrators can add a school, a path and associate them with surveys to be deployed. Also, administrators can modify or delete a school and a path.</p>
        <p><a class="btn btn-primary" role="button">Learn more</a></p>
      </div>
      </div>
    <div id="tree"> </div>
    </div>
    <div class="col-md-9">
      <ul class="nav nav-tabs" id="survey_tabs">
        <li class="active"><a a href="#manage" data-toggle="tab">Manage Deployment</a></li></ul>
      <div class="tab-content">
        <div class="tab-pane fade in active" id="manage"><br>
          <div class="row">
            <div class="col-md-5">
              <span class="info">Undeployed Surveys</span><br>
              <label for="sch">School Name</label>
              <div><select class="form-control schools" id="sch"></select></div>
              <label for="pth">Path Name</label>
              <div><select class="form-control paths" id="pth"></select><br>
              <select class="form-control" size="10" id="undep"></select></div>
            </div>
            <div class="col-md-2"><center>
            <button type="button" class="btn btn-primary btn-lg" id="mov">Create</button><br><br>
            <button type="button" class="btn btn-default btn-lg" id="remov">Delete</button></center></div>
            <div class="col-md-5">
            <span class="info">Deployed Surveys</span><br><br>
            <select class="form-control" size="17" id="dep" ></select></div>
          </div>
        </div>
      </div>
    </div>
    </div>
    <div class="modal fade in" id="alrmodal">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title"></h4>
              </div>
              <div class="modal-body">
              <p></p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="sub_dep">Deploy</button>
              </div>
            </div>
          </div>
        </div>
    <div class="modal fade in" id="alrmodal_1">
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
    <div class="modal fade in" id="alrmodal_2">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title"></h4>
              </div>
              <div class="modal-body">
              <p></p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="sub_undep">Undeploy</button>
              </div>
            </div>
          </div>
        </div>
    </div>
  </body>
</html>
  