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

    function init_survey(ls){
    	$("#all").attr("checked",false);
    	var init='';
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
    			if(DBList.DBSurvey[j].s_id==flag[i]){chk=1;}
    		}
    		if(chk==0){
    			init+='<tr><td>'+DBList.DBSurvey[j].s_id+'</td><td>'+DBList.DBSurvey[j].s_name+'</td><td>'+DBList.DBSurvey[j].date_created+'</td><td>'+DBList.DBSurvey[j].date_modified+'</td><td>'+DBList.DBSurvey[j].num_question+'</td><td><input type="checkbox" class="check_survey"></td></tr>';
    		}	
    	};
    	$(ls).html(init);	
    };

    function init_association(ls){
    	var init="";
    	for(var i=0;i<SPSList.Schools.length;i++){
    		if(SPSList.Schools[i].Paths.length>0){
    			for(var j=0;j<SPSList.Schools[i].Paths.length;j++){
    				if(SPSList.Schools[i].Paths[j].Surveys.length>0){
    					for(var k=0;k<SPSList.Schools[i].Paths[j].Surveys.length;k++){
    						init+='<tr><td>'+SPSList.Schools[i].Paths[j].Surveys[k].s_id+'</td><td>'+SPSList.Schools[i].Paths[j].Surveys[k].s_name+'</td><td>'+SPSList.Schools[i].Paths[j].p_name+'</td><td>'+SPSList.Schools[i].sch_name+'</td><td>'+SPSList.Schools[i].Paths[j].Surveys[k].num_question+'</td><td><input type="checkbox" class="check_asso"></td><td style="display:none">'+SPSList.Schools[i].Paths[j].p_id+'</td></tr>';
    					}
    				}
    			}
    		}
    	}
    	$("#all_asso").attr("checked",false);
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

  	<?php include('school_path_survey.php'); 
  	      include('survey_import.php'); 
            $arr_db=get_DBList();
            $arr=get_SPS(); ?>
    var SPSList=<?php echo $arr ?>;
    var DBList=<?php echo $arr_db ?>;
  	$(function(){
  		$('#tree').dynatree({
          initAjax: {
          url: "test.json"
          }
      	});

      	init_path("#pth_mod");
        init_path("#pth_disp");
      	init_survey("#surlist");
      	init_association("#assolist");
      	$("#survey_tabs a").click(function(e){
            $(this).tab("show");
        });
      	$("#survey_tabs a[href='#add']").click(function(e){
      		init_path("#pth_mod");
      		init_survey("#surlist");
      	});
      	$("#survey_tabs a[href='#del']").click(function(e){
      		init_association("#assolist");
      		init_path("#pth_disp");
        });

      	$('#all').click(function(){
      		change_box(this,".check_survey");
      	});

      	$('#asso').click(function(){
      		add_id=[];i=0;add_name='';
      		path_id=$("#pth_mod option:selected").val().split(",");
      		if(path_id[1]=="-1"){$("#alrmodal_1").find("h4").text("Please select one path to associate surveys");$("#alrmodal_1").modal('show');}
      		else{
      			$(".check_survey").each(function(){
              		if($(this).attr("checked")=="checked"){
                	add_id[i]=$(this).closest("td").siblings(":nth-child(1)").text();
                	add_name+=$(this).closest("td").siblings(":nth-child(2)").text()+", ";
                	i++;
              		};
            	});
            	if(add_id.length==0){
            		$("#alrmodal_1").find("h4").text("Please select at least one survey to associate");$("#alrmodal_1").modal('show');
            	}else{
            	add_name=add_name.substring(0,add_name.length-2);
            	$("#alrmodal").find("h4").text("Do you really want to associated the following surveys with the path "+$("#pth_mod option:selected").text()+" ?");
            	$("#alrmodal").find("p").text(add_name);
            	$("#alrmodal").modal('show');
            	$(document).on("click","#sub_asso",function(){
              		$.ajax({
                		url:"survey.php",
                		data:{addlist:add_id,pth:path_id[1]},
                		type:"POST",
                		async:false
                	});
              	<?php if(isset($_POST['addlist'])){
                	$urr=$_POST['addlist'];
                	for($i=0;$i<count($urr);$i++){association_add($_POST['pth'],$urr[$i]);}} ?>
              	window.location.href="survey.php";
            	});
            	};
      		}
      	});
		
		$("#pth_mod").change(function(){
			$("#all").attr("checked",false);
			path_id=find_path("#pth_mod");
			flag=[];count=0;init='';
			if($("#pth_mod option:selected").val().split(",")[1]!="-1"){
				if(SPSList.Schools[path_id[0]].Paths[path_id[1]].Surveys.length>0){
					for(var i=0;i<SPSList.Schools[path_id[0]].Paths[path_id[1]].Surveys.length;i++){
						flag[count]=SPSList.Schools[path_id[0]].Paths[path_id[1]].Surveys[i].s_id;
    					count++;
					};
					for(var j=0; j<DBList.DBSurvey.length;j++){
    					chk=0;
    					for(var i=0;i<flag.length;i++){
    						if(DBList.DBSurvey[j].s_id==flag[i]){chk=1;}
    					}
    					if(chk==0){
    					init+='<tr><td>'+DBList.DBSurvey[j].s_id+'</td><td>'+DBList.DBSurvey[j].s_name+'</td><td>'+DBList.DBSurvey[j].date_created+'</td><td>'+DBList.DBSurvey[j].date_modified+'</td><td>'+DBList.DBSurvey[j].num_question+'</td><td><input type="checkbox" class="check_survey"></td></tr>';
    					}
					}
					$("#surlist").html(init);
				}else{
					for(var j=0; j<DBList.DBSurvey.length;j++){
						init+='<tr><td>'+DBList.DBSurvey[j].s_id+'</td><td>'+DBList.DBSurvey[j].s_name+'</td><td>'+DBList.DBSurvey[j].date_created+'</td><td>'+DBList.DBSurvey[j].date_modified+'</td><td>'+DBList.DBSurvey[j].num_question+'</td><td><input type="checkbox" class="check_survey"></td></tr>';
					}
					$("#surlist").html(init);
				}
			}else{init_survey("#surlist");}
		});
		
		$("#all_asso").click(function(){
			change_box(this,".check_asso");
		});

		$("#pth_disp").change(function(){
			$("#all_asso").attr("checked",false);
			path_id=find_path("#pth_disp");
			if($("#pth_disp option:selected").val().split(",")[1]!="-1"){
				if(SPSList.Schools[path_id[0]].Paths[path_id[1]].Surveys.length>0){
                	tbl='';
                	for(var i=0;i<SPSList.Schools[path_id[0]].Paths[path_id[1]].Surveys.length;i++){
                		tbl+='<tr><td>'+SPSList.Schools[path_id[0]].Paths[path_id[1]].Surveys[i].s_id+'</td><td>'+SPSList.Schools[path_id[0]].Paths[path_id[1]].Surveys[i].s_name+'</td><td>'+SPSList.Schools[path_id[0]].Paths[path_id[1]].p_name+'</td><td>'+SPSList.Schools[path_id[0]].sch_name+'</td><td>'+SPSList.Schools[path_id[0]].Paths[path_id[1]].Surveys[i].num_question+'</td><td><input type="checkbox" class="check_asso"></td><td style="display:none">'+SPSList.Schools[path_id[0]].Paths[path_id[1]].p_id+'</td></tr>';
                	};
                	$("#assolist").html(tbl);
                }else{
                	$("#alrmodal_1").find("h4").text("There is no association with the path "+SPSList.Schools[path_id[0]].Paths[path_id[1]].p_name);
                	$("#alrmodal_1").modal('show');
                    $("#assolist").html("");}
			}else{init_association("#assolist");}
		});

		$("#del_asso").click(function(){
			del_id=[];del_name=[];pth_id=[];pth_name=[];i=0;
            $(".check_asso").each(function(){
              if($(this).attr("checked")=="checked"){
                del_id[i]=$(this).closest("td").siblings(":nth-child(1)").text();
                del_name[i]=$(this).closest("td").siblings(":nth-child(2)").text();
                pth_id[i]=$(this).closest("td").siblings(":nth-child(7)").text();
                pth_name[i]=$(this).closest("td").siblings(":nth-child(3)").text();
                i++;
              };
            });
            if(del_id.length==0){
            		$("#alrmodal_1").find("h4").text("Please select at least one association to delete");$("#alrmodal_1").modal('show');
            }else{
            	init="";
            	for(var j=0;j<i;j++){
            		init+="[ "+pth_name[j]+', '+del_name[j]+' ];  ';
            	}
            	init=init.substring(0,init.length-3);
            	$("#alrmodal_2").find("h4").text("Do you really want to delete the following associations?");
            	$("#alrmodal_2").find("p").text(init);
           		$("#alrmodal_2").modal('show');
           		$(document).on("click","#sub_del",function(){
           			$.ajax({
                		url:"survey.php",
                		data:{pthlist:pth_id,surlist:del_id},
                		type:"POST",
                		async:false
                	});
                	<?php if(isset($_POST['pthlist'])){
                		$pth=$_POST['pthlist'];$sur=$_POST['surlist'];
                		for($i=0;$i<count($pth);$i++){association_delete($pth[$i], $sur[$i]);}
                	} ?>
                	window.location.href="survey.php";
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
  		<ul class="nav nav-tabs" id="survey_tabs">
        <li class="active"><a href="#add" data-toggle="tab">Add Association</a></li>
        <li><a href="#del" data-toggle="tab">Delete Association</a></li>
      	</ul>

      	<div class="tab-content">
        	<div class="tab-pane fade in active" id="add"><br>
        		<div class="row">
              		<label class="col-md-1 col-md-offset-1">Path</label>
              		<div class="col-md-5"><select class="form-control" id="pth_mod"></select></div>
              		<div class="col-md-2"><button type="button" class="btn btn-primary" id="asso">Associate</button></div>
            	</div><br>
            	<div><table class="table table-hover"><thead><tr><th>Survey Id</th><th>Survey Name</th><th>Created Date</th><th>Modified Date</th><th>Number of Questions</th><th><input type="checkbox" id="all"></th></tr></thead><tbody id="surlist"></table></div>
        	</div>
        	<div class="tab-pane fade in" id="del"><br>
        		<div class="row">
              		<label class="col-md-1 col-md-offset-1">Path</label>
              		<div class="col-md-5"><select class="form-control" id="pth_disp"></select></div>
              		<div class="col-md-2"><button type="button" class="btn btn-primary" id="del_asso">Delete Association</button></div>
            	</div><br>
        		<div><table class="table table-hover"><thead><tr><th>Survey Id</th><th>Survey Name</th><th>Path</th><th>School</th><th>Number of Questions</th><th><input type="checkbox" id="all_asso"></th></tr></thead><tbody id="assolist"></tbody></table></div>
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