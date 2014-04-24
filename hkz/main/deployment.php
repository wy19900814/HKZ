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
    #mov,#remov{width: 100px;position: relative;top:100px;}
    .info{font-size: 20px;}
  </style>
  <script type="text/javascript">
  <?php include('school_path_survey.php');
	   include('survey_import.php');?>
  	var SPSList;
    var DBList;

    function get_deployment(){
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
    }
    

    function init_surveys(){
      var init_undep='',init_dep='';read=[];
      for(var m=0;m<DBList.DBSurvey.length;m++){
        if(DBList.DBSurvey[m].deployed==0){
          init_undep+='<option value='+DBList.DBSurvey[m].s_id+'>'+DBList.DBSurvey[m].s_name+'</option>';
        }else{
          init_dep+='<option value='+DBList.DBSurvey[m].s_id+'>'+DBList.DBSurvey[m].s_name+'</option>';
        }
      }
    
      $("#undep").html(init_undep);$("#dep").html(init_dep);
    };

    $(function(){
    /*  $('#tree').dynatree({
          initAjax: {
          url: "test.json"
          }
      }); */
      get_deployment();
      init_surveys();
 
    $("#mov").click(function(){
        if($("#undep option:selected").val()==undefined){
            $("#alrmodal_1").find('h4').text("Please select at least one survey.");
            $("#alrmodal_1").modal('show');
          }else{
                  $.ajax({
                    url:"deployment.php",
                    data:{d_survey:$("#undep option:selected").val()},
                    type:"POST",
                    async:false
                  });
                <?php if(isset($_POST['d_survey'])){
                  survey_deploy($_POST['d_survey']);} ?>
                get_deployment();init_surveys();
          }
    });

    $("#remov").click(function(){
      if($("#dep option:selected").val()==undefined){
            $("#alrmodal_1").find('h4').text("Please select at least one survey.");
            $("#alrmodal_1").modal('show');
          }else{
            $("#alrmodal_2").find("p").text("Do you really want to undeploy the survey "+$("#dep option:selected").text()+" ?");
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
                  get_deployment();$("#alrmodal_2").modal('hide');init_surveys();
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
          <li><a href="../index.php">Log out</a></li>
        </ul></div>
        </nav>
      </nav>
    <div class="row">
    <div class="col-md-3">
      <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title">Survey Deployment</h3>
      </div>
      <div class="panel-body">
        <p>In survey deployment part, administrators can deploy a survey and cancel the deployment.</p>
      </div>
      </div>
    <div id="tree"> </div>
    </div>
    <div class="col-md-9">
      <ul class="nav nav-tabs" id="survey_tabs">
        <li class="active"><a a href="#manage" data-toggle="tab">Deployment</a></li></ul>
      <div class="tab-content">
        <div class="tab-pane fade in active" id="manage"><br>
          <div class="row">
            <div class="col-md-5">
              <span class="info">Inactive Surveys</span><br>
              <select class="form-control" size="15" id="undep"></select></div>
            <div class="col-md-2"><center>
            <button type="button" class="btn btn-primary btn-lg" id="mov">Deploy</button><br><br>
            <button type="button" class="btn btn-default btn-lg" id="remov">Retract</button></center></div>
            <div class="col-md-5">
            <span class="info">Deployed Surveys</span><br>
            <select class="form-control" size="15" id="dep" ></select></div>
          </div>
        </div>
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
              <div class="modal-body">
              <p></p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="sub_undep">Retract</button>
              </div>
            </div>
          </div>
        </div>
    </div>
  </body>
</html>
  