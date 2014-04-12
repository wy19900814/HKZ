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
  </style>

  <script type="text/javascript">
    var DBList;

    function init_s(ls){
      
      var init='<option value="-1"></option>';
      for(var i=0;i<DBList.DBSurvey.length;i++){
        init+='<option value='+DBList.DBSurvey[i].s_id+'>'+DBList.DBSurvey[i].s_name+'</option>';
      };
      $(ls).html(init);
    };     

    $(function(){

      $.ajax({
            url:"get_data.php",
            cache: false,
            dataType:"json",
            data:{list_db:"getDBList"},
            type:"POST",
            async:false,
            success:function(data){
              DBList=data;
            }
      });   

      init_s('#svy_mod');

      $("#exp").click(function(){
          var s_id = $("#svy_mod option:selected").val();
          window.open('survey_export.php?s_id='+s_id);
      });

      $("#exp_mar").click(function(){
          window.open('marker_export.php');
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
      <p>In survey export part, administrators can export result data of a selected survey into a csv format file.</p>
      <p><a class="btn btn-primary" role="button">Learn more</a></p>
    </div>
    </div>
    </div>
    <div class="col-md-9">
      <ul class="nav nav-tabs" id="export_tabs">
        <li class="active"><a a href="#export" data-toggle="tab">Export</a></li></ul>
      <div class="tab-content">
        <div class="tab-pane fade in active" id="export"><br>
          <div class="row">
            <label class="col-md-1 col-md-offset-1">Survey</label>
            <div class="col-md-5"><select class="form-control" id="svy_mod"></select></div>
            <div class="col-md-2"><button type="button" class="btn btn-primary" id="exp">Export Survey</button></div>
            <div class="col-md-2"><button type="button" class="btn btn-primary" id="exp_mar">Export Marker</button></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>