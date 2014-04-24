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

    function get_association(){
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
    
    function init_school(ls){
      var init='';
      for(var i=0;i<SPSList.Schools.length;i++){
        init+='<option value='+SPSList.Schools[i].sch_id+'>'+SPSList.Schools[i].sch_name+'</option>';
      };
      $(ls).html(init);
    };

  /*	function init_path(ls){
          //show school list
          var init='<option value=-1></option><option value=-2>All path</option>';
          $(ls).html(init);
    }; */

    function init_survey(ls){
    //	$("#all").attr("checked",false);
    	var init='';
      for(var j=0;j<DBList.DBSurvey.length;j++){
        init+='<option value='+DBList.DBSurvey[j].s_id+'>'+DBList.DBSurvey[j].s_name+'</option>';
      };
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
        if(sel_id==-2){
          i=-2;
        }else{
          for(var i=0;i<SPSList.Schools[sel_sch].Paths.length;i++){
            if(SPSList.Schools[sel_sch].Paths[i].p_id==sel_id){break;}
          }
        }  
        return (i);
    };
    
  	$(function(){
  		/*$('#tree').dynatree({
          initAjax: {
          url: "test.json"
          }
      	});*/
      //init_survey("#unasso");
      get_association();

      $("#sch").change(function(){
        $("#asso").html("");
        init='';
        j=find_sch("#sch");
        if(SPSList.Schools[j].Paths.length>0){
                init+='<option value=-2>All path</option>';
                for(var i=0;i<SPSList.Schools[j].Paths.length;i++){
                  init+='<option value='+SPSList.Schools[j].Paths[i].p_id+'>'+SPSList.Schools[j].Paths[i].p_name+'</option>';
                };
              }
        $('#pth').html(init);
        $("#pth").trigger("change");
      });

      $("#pth").change(function(){
        j=find_sch("#sch");i=find_path(j,"#pth");
        init='';count=[];temp=0;
          if($("#pth option:selected").val()==-2){
            for(var k=0;k<DBList.DBSurvey.length;k++){
              cnt=0;
              for(var i=0;i<SPSList.Schools[j].Paths.length;i++){
                if(SPSList.Schools[j].Paths[i].Surveys.length>0){
                  for(var m=0;m<SPSList.Schools[j].Paths[i].Surveys.length;m++){
                    if(SPSList.Schools[j].Paths[i].Surveys[m].s_id==DBList.DBSurvey[k].s_id){cnt++;}
                  }
                }
              }
              if(cnt==SPSList.Schools[j].Paths.length){
                init+='<option value='+DBList.DBSurvey[k].s_id+'>'+DBList.DBSurvey[k].s_name+'</option>';
                count[temp]=DBList.DBSurvey[k].s_id;temp++;
              }
            }
            left_box='';
              for(i=0;i<DBList.DBSurvey.length;i++){
                chk=0;
                for(j=0;j<count.length;j++){
                  if(DBList.DBSurvey[i].s_id==count[j]){chk=1;break;}
                }
                if(!chk){
                  left_box+='<option value='+DBList.DBSurvey[i].s_id+'>'+DBList.DBSurvey[i].s_name+'</option>';
                }
              }
            $("#unasso").html(left_box);
          }else{ 
            if(SPSList.Schools[j].Paths[i].Surveys.length>0){
              for(var k=0;k<SPSList.Schools[j].Paths[i].Surveys.length;k++){
                count[k]=SPSList.Schools[j].Paths[i].Surveys[k].s_id;
                init+='<option value='+SPSList.Schools[j].Paths[i].Surveys[k].s_id+'>'+SPSList.Schools[j].Paths[i].Surveys[k].s_name+'</option>';
              }
              left_box='';
              for(i=0;i<DBList.DBSurvey.length;i++){
                chk=0;
                for(j=0;j<count.length;j++){
                  if(DBList.DBSurvey[i].s_id==count[j]){chk=1;break;}
                }
                if(!chk){
                  left_box+='<option value='+DBList.DBSurvey[i].s_id+'>'+DBList.DBSurvey[i].s_name+'</option>';
                }
              }
            $("#unasso").html(left_box);
            }else{
              init_survey("#unasso");
            }
          }
          $("#asso").html(init);
      }); 
      
      init_school("#sch");
      $("#sch").trigger("change");
      $("#pth").trigger("change");

      $("#mov").click(function(){
        if($("#unasso option:selected").val()==undefined){
            $("#alrmodal_1").find('h4').text("Please select at least one survey.");
            $("#alrmodal_1").modal('show');
          }else{
              ssur=$("#unasso option:selected").val();
              if($("#pth option:selected").val()!=-2){
                $.ajax({
                    url:"association.php",
                    data:{a_survey:ssur,pth_one:$("#pth option:selected").val()},
                    type:"POST",
                    async:false,
                    success:function(data){}
                  });
                <?php if(isset($_POST['pth_one'])){
                  association_add($_POST['pth_one'],$_POST['a_survey']);}
                ?>
              }else{
                j=find_sch("#sch");arr=[];count=0;
                for(var i=0;i<SPSList.Schools[j].Paths.length;i++){
                  var flag=0;
                  for(var k=0;k<SPSList.Schools[j].Paths[i].Surveys.length;k++){
                    if(SPSList.Schools[j].Paths[i].Surveys[k].s_id==ssur){flag=1;break;}
                  }
                  if(!flag){
                    arr[count]=SPSList.Schools[j].Paths[i].p_id;
                    count++;
                  }
                };
                $.ajax({
                    url:"association.php",
                    data:{a_survey:ssur,pths:arr},
                    type:"POST",
                    async:false
                  });
                <?php if(isset($_POST['pths'])){
                  $array=$_POST['pths'];
                  for($i=0;$i<count($array);$i++){
                    association_add($array[$i],$_POST['a_survey']);
                  }
                }?>
              }
             former_sch=$("#sch option:selected").val();
             former_pth=$("#pth option:selected").val();
             get_association();
             init_school("#sch");
             $("#sch").val(former_sch);$("#sch").trigger("change");
             $("#pth").val(former_pth);$("#pth").trigger("change");
          }
      });

      $("#remov").click(function(){
        if($("#asso option:selected").val()==undefined){
            $("#alrmodal_1").find('h4').text("Please select at least one survey.");
            $("#alrmodal_1").modal('show');
          }else{
            $("#alrmodal_2").find("h4").text("Do you really want to delete the following association ?");
            $("#alrmodal_2").find("p").text("School: "+$("#sch option:selected").text()+", Path: "+$("#pth option:selected").text()+", Survey: "+$("#asso option:selected").text());
            $("#alrmodal_2").modal('show');
            $(document).on("click","#sub_del",function(){
              if($("#pth option:selected").val()!=-2){
                $.ajax({
                    url:"association.php",
                    data:{u_survey:$("#asso option:selected").val(),pth_uone:$("#pth option:selected").val()},
                    type:"POST",
                    async:false,
                    success:function(data){} 
                  });
                <?php if(isset($_POST['pth_uone'])){
                    association_delete($_POST['pth_uone'], $_POST['u_survey']);}?>
              }else{
                j=find_sch("#sch");arr=[];
                for(var i=0;i<SPSList.Schools[j].Paths.length;i++){
                  arr[i]=SPSList.Schools[j].Paths[i].p_id;
                };
                $.ajax({
                    url:"association.php",
                    data:{u_survey:$("#asso option:selected").val(),pths_u:arr},
                    type:"POST",
                    async:false
                  });
                <?php if(isset($_POST['pths_u'])){
                  $array=$_POST['pths_u'];
                  for($i=0;$i<count($array);$i++){
                    association_delete($array[$i],$_POST['u_survey']);
                  }
                }?>
              } 
             former_sch=$("#sch option:selected").val();
             former_pth=$("#pth option:selected").val();
             get_association();
             $("#alrmodal_2").modal('hide');
             init_school("#sch");
             $("#sch").val(former_sch);$("#sch").trigger("change");
             $("#pth").val(former_pth);$("#pth").trigger("change");
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
      	<h3 class="panel-title">Association Configuration</h3>
    	</div>
    	<div class="panel-body">
      	<p>In association configuration part, administrators can see the undeployed and deployed surveys after they select a school and a path. They can also create and delete the associations between paths and surveys.</p>
    	</div>
    	</div>
    <div id="tree"> </div>
  	</div>
  	<div class="col-md-9">
  		<ul class="nav nav-tabs" id="survey_tabs">
        <li class="active"><a a href="#manage" data-toggle="tab">Manage Association</a></li></ul>
      <div class="tab-content">
        <div class="tab-pane fade in active" id="manage"><br>
        <form role="form" class="form-horizontal">
          <div class="row">
            <div class="col-md-5">
            <label for="sch">School Name</label><br>
            <select class="form-control schools" id="sch"></select>
            </div>
            <div class="col-md-2"></div>
            <div class="col-md-5">
            <label for="pth">Path Name</label>
            <select class="form-control paths" id="pth"></select><br></div>
          </div>
         </form><br>
           <div class="row fade in" id="boxes">
            <div class="col-md-5">
            <span class="info">Unassociated Surveys</span><br>
            <select class="form-control" size="15" id="unasso" ></select></div>
            <div class="col-md-2"><center>
            <button type="button" class="btn btn-primary btn-lg" id="mov">Create</button><br><br>
            <button type="button" class="btn btn-default btn-lg" id="remov">Delete</button></center></div>
            <div class="col-md-5">
              <span class="info">Associated Surveys</span><br>
              <select class="form-control" size="15" id="asso"></select></div>
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
                <button type="button" class="btn btn-primary" id="sub_asso">Associate</button>
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
                <button type="button" class="btn btn-primary" id="sub_del">Delete</button>
              </div>
            </div>
          </div>
        </div>
  	</div>
  </body>
</html>