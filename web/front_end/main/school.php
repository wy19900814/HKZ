<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
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
    #main{width: 100%;}
    #left_p{float: left;width: 20%;background-color:#F8F8F8;margin: 0;padding:0;border-radius: 5px;}
    #right_p{float: right;width: 80%;}
    #tree{}
  </style>

  <script type="text/javascript">
      var geocoder;
      var map,view_map;
      var schlist,flag=false;
      
      $.ajax({
            url:"get_data.php",
            cache: false,
            dataType:"json",
            data:{list:"getList"},
            type:"POST",
            async:false,
            success:function(data){
              schlist=data;
            }
      });
      //initialize Google Map
      function initialize(element){
        geocoder=new google.maps.Geocoder();
        var latlng=new google.maps.LatLng(33.985865, -118.256250);
        var mapOptions={
          zoom:17,
          center:latlng,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        map=new google.maps.Map(document.getElementById(element),mapOptions);
      };
      //google.maps.event.addDomListener(window, 'load', initialize);

      //search school on GM
      function codeAddress(address){
        geocoder.geocode({'address':address}, function(results,status){
          if(status==google.maps.GeocoderStatus.OK){
            map.setCenter(results[0].geometry.location);
            var marker=new google.maps.Marker({
            map:map,
            position:results[0].geometry.location
            });
          }else{
            flag=true;
            $("#alrmodal_1").find('h4').text("The school address you have input returns invalid results.");
            $("#alrmodal_1").modal('show');
          }
        });
    };

    function init_school(){
        init="";
        for(var i=0;i<schlist.Schools.length;i++){
        init+='<tr><td>'+schlist.Schools[i].sch_id+'</td><td>'+schlist.Schools[i].sch_name+'</td><td>'+schlist.Schools[i].sch_address+'</td><td><div class="dropdown"><button class="btn dropdown-toggle" data-toggle="dropdown" type="button"><span class="caret"></span></button><ul class="dropdown-menu" role="menu"><li><a href="#" class="modify">Modify Name</a></li><li><a href="#" class="view_sch">View School</a></li><li><a href="#" class="del">Delete School</a></li></ul></div></td></tr>';
        };
        $("#scl").html(init);
      };

  <?php include'school_path_survey.php';
        if(isset($_POST['nm'])){
          $cur_name=$_POST['nm'];$cur_addr=$_POST['ad'];
          school_add($cur_name,$cur_addr);
        };
  ?>
  

  
  $(function(){
     $('#tree').dynatree({
          initAjax: {
          url: "test.json"
          }
      });
      
      //show school list in DB
      init_school();

      //tab transition
      $("#sch_tabs a").click(function(e){
        $(this).tab("show");
      });
      $("#sch_tabs a[href='#add']").on('shown.bs.tab',function(e){
        initialize("gm");
      });
      $("#sch_tabs a[href='#mod']").tab('show');
      $("#sch_tabs a[href='#mod']").on('shown.bs.tab',function(e){
        init_school();
      });
   /*  $("#a_school").click(function(){
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
     });*/

    //add school
    $("#search").click(function(){codeAddress($("#saddr").val());});

    $('#sname').popover({
        trigger: 'manual',
        placement: 'top',
        content: function() {
            var message="You must input the school name";
            return message;
          }
      });
    $('#sname').focus(function(){
        $(this).popover('hide');
      });

    $('#saddr').popover({
        trigger: 'manual',
        placement: 'top',
        content: function() {
            var  message="You must input the valid school address";
            return message;
        }
      });
    $('#saddr').focus(function(){
        $(this).popover('hide');
      });

    $("#addschool").click(function(){
      name=$("#sname").val();addr=$("#saddr").val();
      if(name==""){$('#sname').popover('show');}
      else{
        if(addr=="" || flag){$('#saddr').popover('show');}
        else{
          $.ajax({
            url:"school.php",
            cache: false,
            data:{nm:name,ad:addr},
            type:"POST",
            async:false,
            success: function(data){alert("Success");}
          });
          window.location.reload();
        }
      }
    });

    //Modify School Name
    $("#scl").on('click','.modify',function(){
      var selected=$(this).closest("td");
      $("#view_gm").hide();
      $("#edit").html('<br><div class="form-group"><label for="mod_name" class="col-md-3" style="text-align:right">Change '+selected.siblings(":nth-child(2)").text()+' to: </label><div class="col-md-4"><input type="text" class="form-control" name="selected_sname" id="mod_name"></div><div class="col-md-2 col-md-offeset-1"><button type="button" class="btn btn-primary" id="sub_change">Submit New Name</button></div>');   
      $(document).on("click","#sub_change",function(){
        if($("#mod_name").val()==""){
          $("#alrmodal_1").find('h4').text("Please input new school name");
          $("#alrmodal_1").modal('show');
        }else{
          $.ajax({
          url:"school.php",
          cache: false,
          data:{newnm:$("#mod_name").val(),sid:selected.siblings(":nth-child(1)").text(),s_addr:selected.siblings(":nth-child(3)").text()},
          type:"POST",
          async:false
        });
        <?php
        if(isset($_POST['newnm'])){school_modify($_POST['sid'],$_POST['newnm'],$_POST['s_addr']);}
        ?>
        window.location.reload();
        } 
      }); 
    });

   $("#scl").on('click','.view_sch',function(){
      $("#view_gm").show();
      $("#edit").html("");
      var selected=$(this).closest("td");
      //alert(selected.siblings(":nth-child(3)").text());
      initialize("view_gm");
      codeAddress(selected.siblings(":nth-child(3)").text());
      
   });

   $("#scl").on('click','.del',function(){
      var selected=$(this).closest("td");
      $("#alrmodal").find("p").text("Do you really want to delete the school "+selected.siblings(":nth-child(2)").text()+" ?");
      $("#alrmodal").modal('show');
      $(document).on("click","#sub_del",function(){
       // alert(selected.siblings(":nth-child(1)").text());
        $.ajax({
          url:"school.php",
          cache: false,
          data:{sdid:selected.siblings(":nth-child(1)").text()},
          type:"POST",
          async:false
        });
        <?php if(isset($_POST['sdid'])){school_delete($_POST['sdid']);} ?>
        window.location.reload();
      });
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
      <ul class="nav nav-tabs" id="sch_tabs">
        <li><a href="#mod" data-toggle="tab">Modify School</a></li>
        <li class="active"><a href="#add" data-toggle="tab">Add School</a></li>
      <!--  <li><a href="#delete" data-toggle="tab">Delete School</a></li> -->
      </ul>

    <div class="tab-content">
      <div class="tab-pane fade in active" id="add"><br><br>
        <form role="form" class="form-horizontal">
          <div class="form-group">
            <label for="sname" class="col-md-2" style="text-align:right">School Name</label>
            <div class="col-md-6"><input type="text" class="form-control" id="sname"></div></div>
          <div class="form-group">
            <label for="saddr" class="col-md-2" style="text-align:right">School Address</label>
            <div class="col-md-6"><input type="text" class="form-control" id="saddr"></div>
            <div class="col-md-2"><button type="button" class="btn btn-default" id="search">Search School</button></div>
            <div class="col-md-2"><button type="button" class="btn btn-primary" id="addschool">Add School</button></div>
          </div>  
        </form><br>
        <div class="row" id="gm" style="height:400px"></div>
      </div><br><br>
      <div class="tab-pane fade" id="mod">
        <table class="table table-hover">
          <thead><tr><th>School Id</th><th>School Name</th><th>School Address</th><th></th></tr></thead>
          <tbody id="scl">
          </tbody>
        </table>
        <form role="form" class="form-horizontal" id="edit"></form>
        <div class="row" id="view_gm" style="height:300px"></div>
        <div class="modal fade" id="alrmodal">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-body">
              <p></p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="sub_del">Delete School</button>
              </div>
            </div>
          </div>
        </div>
      <!--<div class="tab-pane fade" id="delete"></div> -->
    </div></div>
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
  </div>
</div>
<noscript></body>
</html>