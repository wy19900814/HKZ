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
    #search,#addschool{width: 100px;}
  </style>

  <script type="text/javascript">
      var geocoder;
      var map,view_map;
      var schlist,flag;
      
      function get_school(){
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
      }
      
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
          //alert(status);
          if(status==google.maps.GeocoderStatus.OK){
            map.setCenter(results[0].geometry.location);
            var marker=new google.maps.Marker({
            map:map,
            position:results[0].geometry.location
            });
            if(flag){
              //alert("add school?");
                $.ajax({
                  url:"school.php",
                  cache: false,
                  data:{nm:name,ad:addr},
                  type:"POST",
                  async:false
                });
              get_school();
              $("#sch_tabs a[href='#view']").tab('show');
            }
            flag=false;
          }else{
            $('#saddr').popover('show');
          }
        });
    };

    function find_sch(ls){
        ls+=' option:selected';
        schoolid=$(ls).val();
        if(schoolid!=-1){
          for(j=0;j<schlist.Schools.length;j++){
              if(schlist.Schools[j].sch_id==schoolid){break;}
            };
        }
        else{j=-1;}
        return (j);
    };

    function init_school_table(element){
        init="";
        for(var i=0;i<schlist.Schools.length;i++){
        init+='<tr><td>'+schlist.Schools[i].sch_id+'</td><td>'+schlist.Schools[i].sch_name+'</td><td>'+schlist.Schools[i].sch_address+'</td><td><button class="del btn btn-primary">Delete</button></td></tr>';
        };
        $(element).html(init);
      };

    function init_school_list(element){
      init="";
      for(var i=0;i<schlist.Schools.length;i++){
        init+='<option value='+schlist.Schools[i].sch_id+'>'+schlist.Schools[i].sch_name+'</option>';
      }
      $(element).html(init);
    }

  <?php include'school_path_survey.php';
        if(isset($_POST['nm'])){
          $cur_name=$_POST['nm'];$cur_addr=$_POST['ad'];
          school_add($cur_name,$cur_addr);
        };
  ?>
  
  $(function(){
    /* $('#tree').dynatree({
          initAjax: {
          url: "test.json"
          }
      });*/
      get_school();

      //tab transition
      $("#sch_tabs a").click(function(e){
        $(this).tab("show");
      });
      $("#sch_tabs a[href='#add']").on('shown.bs.tab',function(e){
        initialize("gm");
        $("#sname").val("");$("#saddr").val("");
        flag=false;
      });
      $("#sch_tabs a[href='#view']").tab('show');
      $("#sch_tabs a[href='#view']").on('shown.bs.tab',function(e){
        init_school_list("#scl_view");
        if(schlist.Schools.length>0){
          initialize("view_gm");
          $("#schs").html("<thead><tr><th>School Id</th><th>School Name</th><th>School Address</th></tr></thead><tr><td>"+schlist.Schools[0].sch_id+"</td><td>"+schlist.Schools[0].sch_name+"</td><td>"+schlist.Schools[0].sch_address+"</td></tr>");
        codeAddress(schlist.Schools[0].sch_address);
        } 
      });
      $("#sch_tabs a[href='#mod']").on('shown.bs.tab',function(e){
        init_school_list("#scl_mod");
        if(schlist.Schools.length>0){
          $("#schsm").html("<thead><tr><th>School Name</th><th>School Address</th><th><div class='col-md-10'>New School Name</div></th><th></th></tr></thead><tr><td>"+schlist.Schools[0].sch_name+"</td><td>"+schlist.Schools[0].sch_address+"</td><td><div class='col-md-10'><input type='text' class='form-control' id='newnm'></div></td><td><button class='btn btn-primary' id='sub_change'>Update</button></td></tr>");
        }
      });
      $("#sch_tabs a[href='#delete']").on('shown.bs.tab',function(e){
        init_school_table("#scl_del");
      });

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
          flag=true;
          codeAddress(addr);
      }
    });

    //Modify School Name
    $("#scl_mod").change(function(){
      var scl=find_sch("#scl_mod");
      $("#schsm").html("<thead><tr><th>School Name</th><th>School Address</th><th><div class='col-md-10'>New School Name</div></th><th></th></tr></thead><tr><td>"+schlist.Schools[scl].sch_name+"</td><td>"+schlist.Schools[scl].sch_address+"</td><td><div class='col-md-10'><input type='text' class='form-control' id='newnm'></div></td><td><button class='btn btn-primary' id='sub_change'>Update</button></td></tr>");
    });
   
   $("#schsm").on('click','#sub_change',function(){
      if($("#newnm").val()==""){
          $("#alrmodal_1").find('h4').text("Please input new school name");
          $("#alrmodal_1").modal('show');
        }else{
          $.ajax({
          url:"school.php",
          cache: false,
          data:{nnm:$("#newnm").val(),sid:$("#scl_mod option:selected").val(),s_addr:$(this).closest("td").siblings(":nth-child(2)").text()},
          type:"POST",
          async:false
        });
          <?php
        if(isset($_POST['nnm'])){school_modify($_POST['sid'],$_POST['nnm'],$_POST['s_addr']);}
        ?>
       get_school();
       $("#sch_tabs a[href='#mod']").trigger('shown.bs.tab');
      }
   });

   $("#scl_view").change(function(){
      //$("#edit").html("");
      //alert(selected.siblings(":nth-child(3)").text());
      var scl=find_sch("#scl_view");
      $("#schs").html("<thead><tr><th>School Id</th><th>School Name</th><th>School Address</th></tr></thead><tr><td>"+schlist.Schools[scl].sch_id+"</td><td>"+schlist.Schools[scl].sch_name+"</td><td>"+schlist.Schools[scl].sch_address+"</td></tr>");
      codeAddress(schlist.Schools[scl].sch_address);
   }); 

   $("#scl_del").on('click','.del',function(){
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
        get_school();
       $("#alrmodal").modal('hide');
       $("#sch_tabs a[href='#delete']").trigger('shown.bs.tab');
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
      <h3 class="panel-title">School Configuration</h3>
    </div>
    <div class="panel-body">
      <p>In school configuration part, administrators can view a school on Google Map, create a school by searching its address, modify an existing school's name, and delete a school from database, which means delete all the associated surveys' results.</p>
    </div>
    </div>
    <div id="tree"> </div>
  </div>
    <div class="col-md-9">
      <ul class="nav nav-tabs" id="sch_tabs">
        <li><a href="#view" class="active" data-toggle="tab">View School</a></li>
        <li><a href="#add" data-toggle="tab">Create School</a></li>
        <li><a href="#mod" data-toggle="tab">Modify School</a></li>
        <li><a href="#delete" data-toggle="tab">Delete School</a></li>
      </ul>

    <div class="tab-content">
      <div class="tab-pane fade in" id="add"><br>
        <form role="form" class="form-horizontal">
          <div class="form-group">
            <div class="row">
              <div class="col-md-1"></div>
              <label for="saddr" class="col-md-2" style="text-align:right">School Address</label>
              <div class="col-md-5"><input type="text" class="form-control" id="saddr"></div>
              <div class="col-md-2"><button type="button" class="btn btn-default" id="search">Search School</button></div>
            </div><br>
            <div class="row">
              <div class="col-md-1"></div>
              <label for="sname" class="col-md-2" style="text-align:right">School Name</label>
              <div class="col-md-5"><input type="text" class="form-control" id="sname"></div>
              <div class="col-md-2"><button type="button" class="btn btn-primary" id="addschool">Add School</button></div>
            </div>
          </div>  
        </form><br>
        <div class="row" id="gm" style="height:400px"></div>
      </div><br>
      <div class="tab-pane fade in active" id="view">
        <div class="row">
          <div class="col-md-2"></div>
          <div class="col-md-1">School:</div>
          <div class="col-md-5">
            <select class="form-control" id="scl_view"></select></div>
        </div><br>
        <table class="table table-hover" id="schs"></table><br>
        <div class="row" id="view_gm" style="height:350px"></div>
      </div>
      <div class="tab-pane fade in" id="mod">
        <div class="row">
          <div class="col-md-2"></div>
          <div class="col-md-1">School:</div>
          <div class="col-md-5">
            <select class="form-control" id="scl_mod"></select></div>
        </div><br>
        <table class="table table-hover" id="schsm"></table>
      </div>
      <div class="tab-pane fade in" id="delete">
        <table class="table table-hover">
          <thead><tr><th>School Id</th><th>School Name</th><th>School Address</th><th></th></tr></thead>
          <tbody id="scl_del">
          </tbody>
        </table>
      </div>
    </div>
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
    <div class="modal fade" id="alrmodal_2">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-body">
              <h4></h4>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="sub_add">Create School</button>
              </div>
            </div>
          </div>
    </div>
  </div>
</div>
<noscript></body>
</html>