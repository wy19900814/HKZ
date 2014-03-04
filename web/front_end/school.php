<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
  <script src="../js/jquery.js" type="text/javascript"></script>
  <script src="jQuery/jquery-ui.custom.js" type="text/javascript"></script>
  <script src="jQuery/jquery.cookie.js" type="text/javascript"></script>
  <script src="jQuery/jquery.dynatree.js" type="text/javascript"></script>
  <script src="../js/bootstrap.js" type="text/javascript"></script>
  <link rel="stylesheet" type="text/css" href="../css/bootstrap.css">
  <link href="src/skin/ui.dynatree.css" rel="stylesheet" type="text/css">
  <link rel="stylesheet" type="text/css" href="../css/bootstrap-theme.css">

  <style type="text/css">
  	@font-face{
    	font-family:Cus_font;
    	src:url("../fonts/MyriadPro Regular.otf");
    }
    body{font-family:Cus_font;}
    #main{width: 100%;}
    #left_p{float: left;width: 20%;background-color:#F8F8F8;margin: 0;padding:0;border-radius: 5px;}
    #right_p{float: right;width: 80%;}
    #tree{}
  </style>

  <script type="text/javascript">
  $(function(){
      $('#tree').dynatree({
          initAjax: {
          url: "test.json"
          }
      });

      $("#sch_tabs a[href='#add']").tab("show");
      $("#sch_tabs a").click(function(e){
        $(this).tab("show");
      });

     $("#a_school").click(function(){
      var tree=$("#tree").dynatree("getRoot");
      tree.addChild({
          title:$("#sname").val(),isFolder:true, key:"school"
      });
        $("#sname").empty();

    });

       $("#d_school").click(function(){
      var node=$("tree").dynatree("getActiveNode");
      if(node){
        node.remove();
        node.removeChildren();}
     });
}) 
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
          <li><a href="path.php" target="ifr_b">Path</a></li>
          <li><a href="survey.php" target="ifr_b">Survey</a></li>
         </ul>
        </li>
        <li><a href="deployment.php">Deployment</a></li>
        <li><a href="export.php">Export</a></li>
        <li><a href="">Log out</a></li>
        </ul></div>
      </nav>
  </nav>
  <div class="jumbotron">
      <h2>Survey Configuration</h2>
      <p>In survey configuration part, administrators can add a school, a path and associate them with surveys to be deployed. Also, administrators can modify or delete a school and a path. Moreover, the left sidebar shows the survey associations in the current database. Therefore administrators can get quick access to each association to manage.</p>
      <p><a class="btn btn-primary btn-lg" role="button">Learn more</a></p>
    </div>
  <div class="row">
  <div class="col-md-3">
    <div id="tree"> </div>
  </div>
    <div class="col-md-9">
      <ul class="nav nav-tabs" id="sch_tabs">
        <li class="active"><a href="#add" data-toggle="tab">Add School</a></li>
        <li><a href="#modify" data-toggle="tab">Modify School</a></li>
        <li><a href="#delete" data-toggle="tab">Delete School</a></li>
      </ul>

    <div class="tab-content">
      <div class="tab-pane fade in active" id="add">111</div>
      <div class="tab-pane fade" id="modify">222</div>
      <div class="tab-pane fade" id="delete">333</div>
    </div></div>
  </div>
</div></div>
</body>
</html>