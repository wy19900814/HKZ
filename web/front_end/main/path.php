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
      var map,geocoder=new google.maps.Geocoder();
      var markersArray=[],path_points=[];
      var directionsDisplay=new google.maps.DirectionsRenderer();
      var directionsService=new google.maps.DirectionsService(); 

      function placeMarker(marker){
        var index=markersArray.push(marker)-1;
        if(index==1){
          var request = {
              origin:markersArray[0].getPosition(),
              destination:markersArray[1].getPosition(),
              travelMode: google.maps.TravelMode.WALKING
          };
          directionsService.route(request, function(result, status) {
            if (status == google.maps.DirectionsStatus.OK) {
                directionsDisplay.setDirections(result);
                markersArray[0].setMap(null);markersArray[1].setMap(null);
                markersArray.splice(0,2);
                //save path's start & end point
                path_points[0]=result.routes[0].overview_path[0];
                path_points[1]=result.routes[0].overview_path[result.routes[0].overview_path.length-1];
                directionsDisplay.setMap(map);
              }
            });
        };
        //delete marker
        google.maps.event.addListener(markersArray[index], 'dblclick', function() {
            markersArray[index].setMap(null);
            markersArray.splice(index,1);
          });
      };

      function initialize(element){
        var latlng=new google.maps.LatLng(33.985865, -118.256250);
        var mapOptions={
          zoom:16,
          center:latlng,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        map=new google.maps.Map(document.getElementById(element),mapOptions);
       // directionsDisplay.setMap(map);        
      };

      function marker_enable(gmap){
        google.maps.event.addListener(gmap,'click',function(event){
          if(markersArray.length<2){
            var marker=new google.maps.Marker({
              position:event.latLng,
              draggable:true,
              animation:google.maps.Animation.DROP,
              map:gmap
            });
            placeMarker(marker);
          }else{alert("One path only contains two points.");}
        });
      };

      function codeAddress(address){
          geocoder.geocode({'address':address},function(results,status){
              if(status==google.maps.GeocoderStatus.OK){
                map.setCenter(results[0].geometry.location);
                if(markersArray.length<2){
                  var marker=new google.maps.Marker({
                  map:map,
                  draggable:true,
                  animation:google.maps.Animation.DROP,
                  position:results[0].geometry.location
                  });
                  placeMarker(marker);
                }else{alert("One path only contains two points.");}
              }
          });
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

    function find_path(ls1,ls2){
        pos=[];
        ls1+=' option:selected';ls2+=' option:selected';
        schoolid=$(ls1).val();
          for(j=0;j<SPSList.Schools.length;j++){
              if(SPSList.Schools[j].sch_id==schoolid){break;}
            };
        pathid=$(ls2).val();
          for(i=0;i<SPSList.Schools[j].Paths.length;i++){
              if(SPSList.Schools[j].Paths[i].p_id==pathid){break;}
            };
        pos[0]=j;pos[1]=i;
        return (pos);
    }

    function init_school(ls){
          //show school list
          var init='<option value="-1"></option>';
          $(".paths").append('<option value="-1"></option>');
          for(var i=0;i<SPSList.Schools.length;i++){
            init+='<option value='+SPSList.Schools[i].sch_id+'>'+SPSList.Schools[i].sch_name+'</option>';
          };
          $(ls).html(init);
    };
    function init_path(ls){
          //show path list
          var init='<option value="-1"></option>';
          /*for(var i=0;i<SPSList.Schools[0].Paths.length;i++){
              init+='<option value='+SPSList.Schools[0].Paths[i].p_id+'>'+SPSList.Schools[0].Paths[i].p_name+'</option>';
          };*/
          $(ls).html(init);
    }

    function init_allpath(){
          tbl='';
          for(var j=0;j<SPSList.Schools.length;j++){
          for(var i=0;i<SPSList.Schools[j].Paths.length;i++){
              tbl+='<tr><td>'+SPSList.Schools[j].Paths[i].p_id+'</td><td>'+SPSList.
              Schools[j].Paths[i].p_name+'</td><td>( '+SPSList.Schools[j].Paths[i].s_latitude+', '+SPSList.Schools[j].Paths[i].s_longtitude+' )</td><td>( '+SPSList.Schools[j].Paths[i].e_latitude+', '+SPSList.Schools[j].Paths[i].e_longtitude+' )</td><td>'+SPSList.Schools[j].sch_id+'</td><td><input type="checkbox" class="check_path"></td></tr>';}}
          tbl+='</tbody>';
          $("#all").attr("checked",false);
          $("#pthlist").html(tbl);
    };

      <?php include('school_path_survey.php'); 
            $arr=get_SPS(); ?>
        var SPSList=<?php echo $arr ?>;
        $(function(){
          $('#tree').dynatree({
            initAjax:{
              url: "test.json"
            }
          });

          //tab transition
          init_school("#sch_mod");
          init_allpath();

          $("#path_tabs a").click(function(e){
            $(this).tab("show");
          });
          $("#path_tabs a[href='#mod']").click(function(e){
            init_school("#sch_mod");
            init_allpath();
            <?php $arr=get_SPS(); ?>
            SPSList=<?php echo $arr ?>;
          });
          $("#path_tabs a[href='#add']").on('shown.bs.tab', function(e){
            initialize('gm');
            marker_enable(map);
            init_school("#schs");
          });
          $("#path_tabs a[href='#dip']").on('shown.bs.tab', function(e){
            initialize('gm_mod');
            init_school(".schools");
            init_path(".paths");
          });

          $("#path_tabs a[href='#mod']").tab('show');
          
          $("#search").click(function(){codeAddress($("#paddr").val());$("#paddr").val("");});

          //popover checking
          $('#pname').popover({
            trigger: 'manual',
            placement: 'top',
            content: function() {
            var message="You must input the path name";
            return message;
            }
          });
          $('#pname').focus(function(){
            $(this).popover('hide');
          });

          $('#blocks').popover({
            trigger: 'manual',
            placement: 'top',
            content: function() {
            var message="You must input the number of blocks";
            return message;
            }
          });

          $('#blocks').focus(function(){
            $(this).popover('hide');
          });

          $('#schs').popover({
            trigger: 'manual',
            placement: 'top',
            content: function() {
            var message="You must select the associated school";
            return message;
            }
          });

          $('#schs').click(function(){
            $(this).popover('hide');
          });

          $('.schools').change(function(){
            var schoolid=this.value;
            var cor_path='<option value="-1"></option>';
            j=find_sch('.schools');
            if(j!=-1){
              for(var i=0;i<SPSList.Schools[j].Paths.length;i++){
              cor_path+='<option value='+SPSList.Schools[j].Paths[i].p_id+'>'+SPSList.Schools[j].Paths[i].p_name+'</option>';
              };
            }
           $('.paths').html(cor_path);
          });

          $('.paths').change(function(){
            arr=find_path('.schools','.paths');j=arr[0];i=arr[1];
            start=new google.maps.LatLng(SPSList.Schools[j].Paths[i].s_latitude, SPSList.Schools[j].Paths[i].s_longtitude);
            end=new google.maps.LatLng(SPSList.Schools[j].Paths[i].e_latitude, SPSList.Schools[j].Paths[i].e_longtitude);
            var requests = {
              origin:start,
              destination:end,
              travelMode: google.maps.TravelMode.WALKING
            };
           directionsService.route(requests, function(results, status) {
            if (status == google.maps.DirectionsStatus.OK) {
                directionsDisplay.setDirections(results);
                directionsDisplay.setMap(map);
              }
            });
          });

          $("#addpath").click(function(){
            if($("#pname").val()==""){$("#pname").popover('show');}
            else{
              if($("#blocks").val()==""){$("#blocks").popover('show');}
              else{
                if($('#schs option:selected').val()==-1){$('#schs').popover('show');}
                else{
                alert($("#blocks").val());
                $.ajax({
                  url:"path.php",
                  data:{pn:$("#pname").val(),sch:$('#schs option:selected').val(),nbl:$("#blocks").val(),sp_lat:path_points[0].lat(),sp_lng:path_points[0].lng(),ep_lat:path_points[1].lat(),ep_lng:path_points[1].lng()},
                  type:"POST",
                  async:false
                });
                <?php 
                  if(isset($_POST['pn'])){
                    path_add($_POST['pn'], $_POST['sp_lng'], $_POST['sp_lat'], $_POST['ep_lng'], $_POST['ep_lat'], $_POST['nbl'], $_POST['sch']); 
                  };
                ?>
                window.location.href="path.php";
              }
            }
          }
        });

          $('#chg').popover({
            trigger: 'manual',
            placement: 'top',
            content: function() {
            var message="You must input the new path name";
            return message;
            }
          });
          $('#chg').focus(function(){
            $(this).popover('hide');
          });

          $("#update").click(function(){
            if($("#chg").val()==""){$("#chg").popover('show');}
            else{
              arr=find_path('.schools','.paths');j=arr[0];i=arr[1];
              $.ajax({
                url:"path.php",
                  data:{path_id:SPSList.Schools[j].Paths[i].p_id,npn:$("#chg").val()},
                type:"POST",
                async:false,
                success:function(data){alert("success");}
              });
              <?php 
                if(isset($_POST['path_id'])){
                  path_modify($_POST['path_id'],$_POST['npn']);} ?>
              window.location.href="path.php";
            }
          });

          $("#sch_mod").change(function(){
              j=find_sch('#sch_mod');
              if(j!=-1){
                if(SPSList.Schools[j].Paths.length>0){
                tbl='';
                for(var i=0;i<SPSList.Schools[j].Paths.length;i++){
                  tbl+='<tr><td>'+SPSList.Schools[j].Paths[i].p_id+'</td><td>'+SPSList.Schools[j].Paths[i].p_name+'</td><td>( '+SPSList.Schools[j].Paths[i].s_latitude+', '+SPSList.Schools[j].Paths[i].s_longtitude+' )</td><td>( '+SPSList.Schools[j].Paths[i].e_latitude+', '+SPSList.Schools[j].Paths[i].e_longtitude+' )</td><td>'+SPSList.Schools[j].sch_id+'</td><td><input type="checkbox" class="check_path"></td></tr>';
                };
                tbl+='</tbody>';
                $("#pthlist").html(tbl);
              }else{alert("No path");}
            }else{init_allpath();}
            $("#all").attr("checked",false);
          });

          $("#all").click(function(){
            if($(this).attr("checked")=="checked"){
              $(".check_path").each(function(){
                $(this).attr("checked",true);
              });
            }else{
              $(".check_path").each(function(){
                $(this).attr("checked",false);
              });
            }
          });

          $("#del").click(function(){
            del_id=[];i=0;del_name='';
            $(".check_path").each(function(){
              if($(this).attr("checked")=="checked"){
                del_id[i]=$(this).closest("td").siblings(":nth-child(1)").text();
                del_name+=$(this).closest("td").siblings(":nth-child(2)").text()+", ";
                i++;
              };
            });
            del_name=del_name.substring(0,del_name.length-2);
            $("#alrmodal").find("h4").text("Do you really want to delete the following paths?");
            $("#alrmodal").find("p").text(del_name);
            $(document).on("click","#sub_del",function(){
              $.ajax({
                url:"path.php",
                data:{dellist:del_id},
                type:"POST",
                async:false
                });
              <?php if(isset($_POST['dellist'])){
                $urr=$_POST['dellist'];
                for($i=0;$i<count($urr);$i++){path_delete($urr[$i]);}} ?>
              window.location.href="path.php";
            });
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
    <div id="tree"></div>
    </div>
    <div class="col-md-9">
      <ul class="nav nav-tabs" id="path_tabs">
        <li class="active"><a href="#mod" data-toggle="tab">Modify Path</a></li>
        <li><a href="#add" data-toggle="tab">Add Path</a></li>
        <li><a href="#dip" data-toggle="tab">Display Path</a></li>
      <!--  <li><a href="#delete" data-toggle="tab">Delete School</a></li> -->
      </ul>

      <div class="tab-content">
      <div class="tab-pane fade in active" id="mod"><br>
            <div class="row">
              <label class="col-md-1 col-md-offset-1">School</label>
              <div class="col-md-5"><select class="form-control" id="sch_mod"></select></div>
              <div class="col-md-2"><button type="button" class="btn btn-primary" id="del" data-toggle="modal" data-target="#alrmodal">Delete Path</button></div>
            </div><br>
            <div><table class="table table-hover" id="plist"><thead><tr><th>Path Id</th><th>Path Name</th><th>Start Point</th><th>End Point</th><th>School Id</th><th><input type="checkbox" id="all"></th></tr></thead><tbody id="pthlist"></table></div>
      </div>
      <div class="tab-pane fade in" id="add"><br>
        <form role="form" class="form-horizontal">
          <div class="form-group">
            <label for="pname" class="col-md-2">Path Name</label>
            <div class="col-md-2"><input type="text" class="form-control" id="pname" placeholder="Path name"></div>
            <label for="schs" class="col-md-1">School</label>
            <div class="col-md-4"><select class="form-control" id="schs"></select></div>
            <label for="blocks" class="col-md-1">Blocks</label>
            <div class="col-md-2"><input type="text" class="form-control" id="blocks" placeholder="Number" align="left"></div>
            </div>
          <div class="form-group">
            <div class="row"></div>
            <label for="paddr" class="col-md-2">Search Point</label>
            <div class="col-md-3"><input type="text" class="form-control" id="paddr" placeholder="Point address"></div>
            <div class="col-md-2"><button type="button" class="btn btn-default" id="search">Search Point</button></div>
            <div class="col-md-2"><button type="button" class="btn btn-primary" id="addpath">Add Path</button></div>
          </div>  
        </form>
        <div class="row" id="gm" style="height:450px"></div>
      </div>
      <div class="tab-pane fade in" id="dip"><br>
        <div class="row">
        <label for="sch" class="col-md-2">School Name</label>
        <div class="col-md-4"><select class="form-control schools" id="sch"></select></div>
        <label for="pth" class="col-md-1">Path Name</label>
        <div class="col-md-4"><select class="form-control paths" id="pth"></select></div><br></div>
          <form role="form" class="form-horizontal">
            <div class="form-group">
            <label for="chg" class="col-md-2">Change Path Name</label>
            <div class="col-md-4"><input type="text" class="form-control" id="chg" placeholder="New Path Name" align="left"></div>
            <div class="col-md-2"><button type="button" class="btn btn-default" id="update">Update</button></div>
            </div>
          </form>
        <div class="col-md-12" id="gm_mod" style="height:450px"></div>
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
                <button type="button" class="btn btn-primary" id="sub_del">Delete Path</button>
              </div>
            </div>
          </div>
        </div>
  </div>
</body>
</html>