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
    function init_path(ls){
          //show school list
          var init='<option value="-1,-1"></option>';
          for(var i=0;i<SPSList.Schools.length;i++){
            if(SPSList.Schools[i].Paths.length>0){
              for(var j=0;j<SPSList.Schools[i].Paths.length;j++){
                init+='<option value='+SPSList.Schools[i].sch_id+','+SPSList.Schools[i].Paths[j].p_id+'>'+SPSList.Schools[i].Paths[j].p_name+'</option>';
              }
            }
          };
          $(ls).html(init);
    };
     function init_surveys(ls,status){
      var init="";
      flag=[];count=0;
      for(var i=0;i<SPSList.Schools.length;i++){
        if(SPSList.Schools[i].Paths.length>0){
          for(var j=0;j<SPSList.Schools[i].Paths.length;j++){
            if(SPSList.Schools[i].Paths[j].Surveys.length>0){
              for(var k=0;k<SPSList.Schools[i].Paths[j].Surveys.length;k++){
                flag[count]=SPSList.Schools[i].Paths[j].Surveys[k].s_id;
                count++;
              }
            }
          }
        }
      };
      for(var j=0; j<DBList.DBSurvey.length;j++){
        chk=0;
        for(var i=0;i<flag.length;i++){
          if(DBList.DBSurvey[j].s_id==flag[i] && DBList.DBSurvey[j].deployed==status){chk=1;}
        }
        if(chk==1){
          init+='<tr><td>'+DBList.DBSurvey[j].s_id+'</td><td>'+DBList.DBSurvey[j].s_name+'</td><td>'+DBList.DBSurvey[j].date_created+'</td><td>'+DBList.DBSurvey[j].date_modified+'</td><td>'+DBList.DBSurvey[j].num_question+'</td><td><input type="checkbox" class="check_dep"></td></tr>';
        } 
      };
      $("#all_dep").attr("checked",false);
      $(ls).html(init);
    };
    function change_box(all,sep){
      if($(all).attr("checked")=="checked"){
              $(sep).each(function(){
                $(this).attr("checked",true);
              });
            }else{
              $(sep).each(function(){
                $(this).attr("checked",false);
              });
            }
    };
    function find_path(ls){
        pos=[];
        ls+=" option:selected";
        sel_id=$(ls).val().split(",");
        if(sel_id[0]!="-1"){
          for(j=0;j<SPSList.Schools.length;j++){
              if(SPSList.Schools[j].sch_id==sel_id[0]){break;}
            };
            for(i=0;i<SPSList.Schools[j].Paths.length;i++){
              if(SPSList.Schools[j].Paths[i].p_id==sel_id[1]){break;}
            };
          pos[0]=j;pos[1]=i;
          return (pos);
        }
    };

    $(function(){
      $('#tree').dynatree({
          initAjax: {
          url: "test.json"
          }
      });
      init_path("#pth_disp");
      init_surveys("#deplist",0);

      $("#deploy_tabs a").click(function(e){
            $(this).tab("show");
        });
      $("#deploy_tabs a[href='#deploy']").click(function(e){
          init_path("#pth_disp");
          init_surveys("#deplist",0);
        });
      $("#deploy_tabs a[href='#undeploy']").click(function(e){
          init_path("#pth_list");
          init_surveys("#undeplist",1);
        });

      $("#all_dep").click(function(){
        change_box(this,".check_dep");
      });
      $("#all_undep").click(function(){
        change_box(this,".check_dep");
      });

      $("#pth_disp").change(function(){
        $("#all_dep").attr("checked",false);
        path_id=find_path("#pth_disp");
        
        if($("#pth_disp option:selected").val().split(",")[1]!="-1"){
      //  if(SPSList.Schools[path_id[0]].Paths[path_id[1]].Surveys.length>0){
                  tbl='';
                  flag=[];count=0;
                  for(var k=0;k<SPSList.Schools[path_id[0]].Paths[path_id[1]].Surveys.length;k++){
                    flag[count]=SPSList.Schools[path_id[0]].Paths[path_id[1]].Surveys[k].s_id;
                    count++;}
                  count=0;
                  for(var j=0; j<DBList.DBSurvey.length;j++){
                    chk=0;
                    for(var i=0;i<flag.length;i++){
                        if(DBList.DBSurvey[j].s_id==flag[i] && DBList.DBSurvey[j].deployed==0){chk=1;}
                    }
                    if(chk==1){
                      tbl+='<tr><td>'+DBList.DBSurvey[j].s_id+'</td><td>'+DBList.DBSurvey[j].s_name+'</td><td>'+DBList.DBSurvey[j].date_created+'</td><td>'+DBList.DBSurvey[j].date_modified+'</td><td>'+DBList.DBSurvey[j].num_question+'</td><td><input type="checkbox" class="check_dep"></td></tr>';
                      count++;
                    }
                  }
                //}else{
                  if(count==0){
                    $("#alrmodal_1").find("h4").text("There is no associated undeployed survey with the path "+SPSList.Schools[path_id[0]].Paths[path_id[1]].p_name);
                    $("#alrmodal_1").modal('show');
                  }
                  $("#deplist").html(tbl);
        }else{init_surveys("#deplist",0);}
      });
      
      $("#pth_list").change(function(){
        $("#all_undep").attr("checked",false);
        path_id=find_path("#pth_list");
        if($("#pth_list option:selected").val().split(",")[1]!="-1"){
       // if(SPSList.Schools[path_id[0]].Paths[path_id[1]].Surveys.length>0){
                  tbl='';
                  flag=[];count=0;
                  for(var k=0;k<SPSList.Schools[path_id[0]].Paths[path_id[1]].Surveys.length;k++){
                    flag[count]=SPSList.Schools[path_id[0]].Paths[path_id[1]].Surveys[k].s_id;
                    count++;}
                  count=0;
                  for(var j=0; j<DBList.DBSurvey.length;j++){
                    chk=0;
                    for(var i=0;i<flag.length;i++){
                        if(DBList.DBSurvey[j].s_id==flag[i] && DBList.DBSurvey[j].deployed==1){chk=1;}
                    }
                    if(chk==1){
                      tbl+='<tr><td>'+DBList.DBSurvey[j].s_id+'</td><td>'+DBList.DBSurvey[j].s_name+'</td><td>'+DBList.DBSurvey[j].date_created+'</td><td>'+DBList.DBSurvey[j].date_modified+'</td><td>'+DBList.DBSurvey[j].num_question+'</td><td><input type="checkbox" class="check_dep"></td></tr>';
                      count++;
                    }
                  }
                  if(count==0){
                    $("#alrmodal_1").find("h4").text("There is no associated deployed survey with the path "+SPSList.Schools[path_id[0]].Paths[path_id[1]].p_name);
                    $("#alrmodal_1").modal('show');}
                  $("#undeplist").html(tbl);
            //    }else{
                  
        }else{init_surveys("#undeplist",1);}
      });

      $("#dep").click(function(){
      dep_id=[];dep_name=[];i=0;//pth_id=[];pth_name=[];
            $(".check_dep").each(function(){
              if($(this).attr("checked")=="checked"){
                dep_id[i]=$(this).closest("td").siblings(":nth-child(1)").text();
                dep_name[i]=$(this).closest("td").siblings(":nth-child(2)").text();
               // pth_id[i]=$(this).closest("td").siblings(":nth-child(7)").text();
               // pth_name[i]=$(this).closest("td").siblings(":nth-child(3)").text();
                i++;
              };
            });
            if(dep_id.length==0){
                $("#alrmodal_1").find("h4").text("Please select at least one survey to deploy");$("#alrmodal_1").modal('show');
            }else{
              init="";
              for(var j=0;j<i;j++){
                init+=dep_name[j]+', ';
              }
              init=init.substring(0,init.length-2);
              $("#alrmodal").find("h4").text("Do you really want to deploy the following surveys?");
              $("#alrmodal").find("p").text(init);
              $("#alrmodal").modal('show');
              $(document).on("click","#sub_dep",function(){
                $.ajax({
                    url:"deployment.php",
                    data:{deplist:dep_id},
                    type:"POST",
                    async:false,
                    success:function(data){alert("success")}
                  });
                  <?php if(isset($_POST['deplist'])){
                    $dep=$_POST['deplist'];
                    for($i=0;$i<count($dep);$i++){survey_deploy($dep[$i]);}
                  } ?>
                  window.location.href="deployment.php";
              });
            }
      });
      
      $("#undep").click(function(){
      udep_id=[];udep_name=[];pth_id=[];pth_name=[];i=0;
            $(".check_dep").each(function(){
              if($(this).attr("checked")=="checked"){
                udep_id[i]=$(this).closest("td").siblings(":nth-child(1)").text();
                udep_name[i]=$(this).closest("td").siblings(":nth-child(2)").text();
              //  pth_id[i]=$(this).closest("td").siblings(":nth-child(7)").text();
              //  pth_name[i]=$(this).closest("td").siblings(":nth-child(3)").text();
                i++;
              };
            });
            if(udep_id.length==0){
                $("#alrmodal_1").find("h4").text("Please select at least one survey to undeploy");$("#alrmodal_1").modal('show');
            }else{
              init="";
              for(var j=0;j<i;j++){
                init+=udep_name[j]+', ';
              }
              init=init.substring(0,init.length-2);
              $("#alrmodal_2").find("h4").text("Do you really want to undeploy the following surveys?");
              $("#alrmodal_2").find("p").text(init);
              $("#alrmodal_2").modal('show');
              $(document).on("click","#sub_undep",function(){
                $.ajax({
                    url:"deployment.php",
                    data:{undeplist:udep_id},
                    type:"POST",
                    async:false
                  });
                  <?php if(isset($_POST['undeplist'])){
                    $undep=$_POST['undeplist'];
                    for($i=0;$i<count($undep);$i++){survey_retract($undep[$i]);}
                  } ?>
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
              <li><a href="survey.php">Survey</a></li>
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
        <ul class="nav nav-tabs" id="deploy_tabs">
        <li class="active"><a href="#deploy" data-toggle="tab">Deployment</a></li>
        <li><a href="#undeploy" data-toggle="tab">Undeployment</a></li>
        </ul>

        <div class="tab-content">
          <div class="tab-pane fade in active" id="deploy"><br>
            <div class="row">
                  <label class="col-md-1 col-md-offset-1">Path</label>
                  <div class="col-md-5"><select class="form-control" id="pth_disp"></select></div>
                  <div class="col-md-1"><button type="button" class="btn btn-primary" id="dep">Deploy</button></div>
              </div><br>
            <div><table class="table table-hover"><thead><tr><th>Survey Id</th><th>Survey Name</th><th>Created Date</th><th>Modified Date</th><th>Number of Questions</th><th><input type="checkbox" id="all_dep"></th></tr></thead><tbody id="deplist"></tbody></table></div>
          </div>
          <div class="tab-pane fade in" id="undeploy"><br>
            <div class="row">
                  <label class="col-md-1 col-md-offset-1">Path</label>
                  <div class="col-md-5"><select class="form-control" id="pth_list"></select></div>
                  <div class="col-md-2"><button type="button" class="btn btn-default" id="undep">Undeploy</button></div>
              </div><br>
            <div><table class="table table-hover"><thead><tr><th>Survey Id</th><th>Survey Name</th><th>Created Date</th><th>Modified Date</th><th>Number of Questions</th><th><input type="checkbox" id="all_undep"></th></tr></thead><tbody id="undeplist"></tbody></table></div>
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
  