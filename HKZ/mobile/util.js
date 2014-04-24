//local storage: 
//json_answer_str
//survey_index
//path_index
//school_index
//json_sps_str
//json_question_str

var mobileDemo = { 'center': '57.7973333,12.0502107', 'zoom': 10 };           
$('#directions_map').live('pageinit', function() {
    //alert("ehl");
    demo.add('directions_map', function() {
       // alert("ee");
        $('#map_canvas_1').gmap({'center': mobileDemo.center, 'zoom': mobileDemo.zoom, 'disableDefaultUI':true, 'callback': function() {
            var self = this;
            self.set('getCurrentPosition', function() {
                self.refresh();
                self.getCurrentPosition( function(position, status) {
                    if ( status === 'OK' ) {
                        json_sps=JSON.parse(localStorage.getItem("json_sps_str"));
                        school_index=localStorage.getItem("school_index");
                        path_index=localStorage.getItem("path_index");
                        var path=json_sps.Schools[school_index].Paths[path_index];
                        var s_latlng = path.s_latitude+','+path.s_longtitude;
                        var e_latlng = path.e_latitude+','+path.e_longtitude;
                        //self.get('map').panTo(latlng);
                        //$('#from').val("2658 Menlo, Ave., la, ca, us");
                        self.displayDirections({ 'origin': s_latlng, 'destination': e_latlng, 'travelMode': google.maps.DirectionsTravelMode.WALKING }, { 'panel': document.getElementById('directions')}, function(response, status) {
                            ( status === 'OK' ) ? $('#results').show() : $('#results').hide();
                        });
                    } else {
                        alert('Unable to get current position');
                    }
                });
            });
        }});
    }).load('directions_map');
});

$('#directions_map').live('pageshow', function() {
    demo.add('directions_map', $('#map_canvas_1').gmap('get', 'getCurrentPosition')).load('directions_map');
    //alert("ehl");
});
            


function reset(){
    jsonObj_sps=null;
    jsonObj_ques=null;
    school_index=null;
    path_index=null;
    survey_index=null;
    json_answer_str="";
    localStorage.setItem('survey_index',"");
    localStorage.setItem('path_index',"");
    localStorage.setItem('school_index',"");
    localStorage.setItem('json_sps_str',"");
    localStorage.setItem('json_question_str',"");
    localStorage.setItem('json_answer_str',"");

}

function joinStr(json_answer_str,answer){
    if(json_answer_str.charAt(json_answer_str.length-3)=="["){
        json_answer_str=json_answer_str.substring(0,json_answer_str.length-2)+answer+json_answer_str.substring(json_answer_str.length-2,json_answer_str.length);
        return json_answer_str;
    }else{
        json_answer_str=json_answer_str.substring(0,json_answer_str.length-2)+","+answer+json_answer_str.substring(json_answer_str.length-2,json_answer_str.length);
        return json_answer_str;
    }
       
}

function add_navbar(a_page){
    page =a_page + "<div data-role='footer' data-position='fixed'>";
    page=page+"<div data-role='navbar' ><ul>";
    page=page+"<li><a href='#' id='survey' class='ui-btn-active'>Survey</a></li>";
    page=page+"<li><a href='#' data-rel='dialog' id='reset'>Reset</a></li>";
    page=page+"<li><a href='#' id='return_catagory'>Catagory</a></li>";
    page=page+"<li><a href='#' id='map' rel='external'>Map</a></li>";
    page=page+"</ul></div>";
    return page;
}
function get_all_index(){
	school_index=localStorage.getItem("school_index");
	survey_index=localStorage.getItem("survey_index");
	path_index=localStorage.getItem("path_index");
}

function create_reset_block_dialog(block_index,question_index){
	if($("#reset_cancel").length>0){
    	$("#reset_cancel").remove();    	
    }
    if($("#reset_yes").length>0){
    	$("#reset_yes").remove();  	
    }
    if($("#reset_block_dialog_"+block_index+"_"+question_index).length>0){
        $("#reset_block_dialog_"+block_index+"_"+question_index).remove();
        //alert("remove dialog");
    }
    //setTimeout(function(){},500);
    var page="<div data-role='page' data-dialog='true' id='reset_block_dialog_"+block_index+"_"+question_index+"' ><div data-role='header' data-theme='b'><h1>Dialog</h1></div><div role='main' class='ui-content'  style='background-color:white'><h3>Reset answers?</h3><h5>Do you want to reset all of content?</h5><a id='reset_yes'><input type='button' data-theme='b' value='Yes'></a><a id='reset_cancel'><input type='button' data-theme='b' value='Cancel' ></a></div></div>"
    //alert("create dialog");
    return page;
    
}
function remove_grobal_btn(){
    if($("#reset").length>0){
        $("#reset").remove();
        //alert("reset button");
    }
    if($("#survey").length>0){
    	$("#survey").remove();
    }
    if($("#next_ques").length>0){
        $("#next_ques").remove();
        //alert("remove next");
    }
    if($("#return_catagory").length>0){
        $("#return_catagory").remove();
       // alert("move return catagory");
    }
    if($("#previous_ques").length>0){
        $("#previous_ques").remove();
        //alert("remove previous");
    }
    if($("#map").length>0){
        $("#map").remove();
        //alert("remove map");
    }	
}

function remove_block_btn(block_index,question_index){
	if($("#blockQuesUrl_"+block_index+"_"+question_index+"_"+localStorage.getItem("survey_index")+"_"+localStorage.getItem("path_index")+"_"+localStorage.getItem("school_index")).length>0){
		$("#blockQuesUrl_"+block_index+"_"+question_index+"_"+localStorage.getItem("survey_index")+"_"+localStorage.getItem("path_index")+"_"+localStorage.getItem("school_index")).remove();
    	//alert("remove");
    }
	remove_grobal_btn();
}

function create_reset_tally_dialog(tallyQuesIndex){
	if($("#reset_cancel").length>0){
    	$("#reset_cancel").remove();    	
    }
    if($("#reset_yes").length>0){
    	$("#reset_yes").remove();  	
    }
	if($("#reset_tally_dialog_"+tallyQuesIndex).length>0){
        $("#reset_tally_dialog_"+tallyQuesIndex).remove();
        //alert("remove dialog");
    }
    var page="<div data-role='page' data-dialog='true' id='reset_tally_dialog_"+tallyQuesIndex+"' ><div data-role='header' data-theme='b'><h1>Dialog</h1></div><div role='main' class='ui-content'  style='background-color:white'><h3>Reset answers?</h3><h5>Do you want to reset all of content?</h5><a id='reset_yes'><input type='button' data-theme='b' value='Yes'></a><a id='reset_cancel'><input type='button' data-theme='b' value='Cancel' ></a></div></div>"
    //alert("create dialog");
    return page;
}

function remove_tally_btn(tallyQuesIndex){
    remove_grobal_btn();
    if($("#plus_"+tallyQuesIndex).length>0){
    	$("#plus_"+tallyQuesIndex).remove();
    	//alert("plus");
    }
    if($("#minus_"+tallyQuesIndex).length>0){
    	$("#minus_"+tallyQuesIndex).remove();
    	//alert("minus");
    }
    if($("#tallyQues"+tallyQuesIndex).length>0){
    	$("#tallyQues"+tallyQuesIndex).remove();
    }
    if($("#TallyQuesUrl_"+tallyQuesIndex+"_"+survey_index+"_"+path_index+"_"+school_index).length>0){
        $("#TallyQuesUrl_"+tallyQuesIndex+"_"+survey_index+"_"+path_index+"_"+school_index).remove();
    }     
}

function create_reset_other_dialog(otherQuesIndex){
	if($("#reset_cancel").length>0){
    	$("#reset_cancel").remove();    	
    }
    if($("#reset_yes").length>0){
    	$("#reset_yes").remove();  	
    }
    if($("#reset_other_dialog_"+otherQuesIndex).length>0){
    	$("#reset_other_dialog_"+otherQuesIndex).remove();
    }
    var page="<div data-role='page' data-dialog='true' id='reset_other_dialog_"+otherQuesIndex+"' ><div data-role='header' data-theme='b'><h1>Dialog</h1></div><div role='main' class='ui-content'  style='background-color:white'><h3>Reset answers?</h3><h5>Do you want to reset all of content?</h5><a id='reset_yes'><input type='button' data-theme='b' value='Yes'></a><a href='#' id='reset_cancel'><input type='button' data-theme='b' value='Cancel' ></a></div></div>"
    //alert("create dialog");
    return page;
}

function remove_other_btn(otherQuesIndex){
	remove_grobal_btn();
    if($("#OtherQuesUrl_"+otherQuesIndex+"_"+survey_index+"_"+path_index+"_"+school_index).length>0){
        $("#OtherQuesUrl_"+otherQuesIndex+"_"+survey_index+"_"+path_index+"_"+school_index).remove();
    }
}

function submit(){
    $("#submit").click(function(){
       if($("#submit_dialog").length>0){
            $("#submit_dialog").remove();
        }

        json_answer_str=localStorage.getItem("json_answer_str");
        //alert(json_answer_str);
        var json_answer=JSON.parse(json_answer_str);
        var answers=json_answer.Answers;

        var json_question_str=localStorage.getItem("json_question_str");
        var block_question_num=JSON.parse(json_question_str).Questions.Blocks.length;
        var tally_question_num=JSON.parse(json_question_str).Questions.Tallies.length;
        var other_question_num=JSON.parse(json_question_str).Questions.Others.length;

        var tally_and_other=tally_question_num+other_question_num;
        //alert(tally_and_other);
        var block_number=localStorage.getItem('block_number');
        
        var count_block_answer=new Array(block_number+1);
        for(var i=1;i<=block_number;i++){
            count_block_answer[i]=0;
        }
        var count_tally_and_other_answer=0;
        var block_id;
        for(var i=0;i<answers.length;i++){
            if(answers[i].block_id==0&&(answers[i].a_content!=""&&answers[i].a_content!="undefined")){
                count_tally_and_other_answer++;
            }else if(answers[i].block_id!=0&&(answers[i].a_content!=""&&answers[i].a_content!="undefined")){
                block_id=answers[i].block_id;
                count_block_answer[block_id]++;
            }
        }
        var flag=true;
        //alert(block_number);
        //alert(count_block_answer[1]);
        //alert(block_question_num);
        for(var i=1;i<=block_number;i++){
                //tally or other
            if(count_block_answer[i]<block_question_num){

                var page="<div data-role='page' data-dialog='true' id='submit_dialog' ><div data-role='header' data-theme='b'><h1>Dialog</h1></div><div role='main' class='ui-content'  style='background-color:white'><h3>Attention</h3><h5>You have not finished block "+i+" questions.</h5><a href='#' data-rel='back'><input type='button' data-theme='b' value='Ok' ></a></div></div>";
                var newPage = $(page);
                newPage.appendTo($.mobile.pageContainer);
                this.href="#submit_dialog";
                flag=false; 
                return;

            }
        }
        if(flag){
            if(count_tally_and_other_answer<tally_and_other){
            //block questions
                var page="<div data-role='page' data-dialog='true' id='submit_dialog' ><div data-role='header' data-theme='b'><h1>Dialog</h1></div><div role='main' class='ui-content'  style='background-color:white'><h3>Attention</h3><h4>You have not finished the tally or other questions.</h4><a href='#' data-rel='back'><input type='button' data-theme='b' value='Ok' ></a></div></div>";
                var newPage = $(page);
                newPage.appendTo($.mobile.pageContainer);
                this.href="#submit_dialog";
                return;
            }
        }
       
        
        //http://letsallgetcovered.org/HKZ/store_answers_to_db.php
        var url="http://letsallgetcovered.org/lets6502/hkz_v1/main/post_answer_and_marker.php";

        json_answer_str=localStorage.getItem('json_answer_str');
        json_marker_str=localStorage.getItem('json_marker_str');
        //alert(json_marker_str);
        var json_answer_and_marker_str="{\"Content\":{"+json_answer_str.substring(1,json_answer_str.length-1)+","+json_marker_str.substring(1,json_marker_str.length)+"}";
        var json_answer_and_marker=JSON.parse(json_answer_and_marker_str);
        //alert(json_answer_and_marker.Content.Markers[0].m_latitude);
        var data = { json_answer_and_marker_str: json_answer_and_marker_str };

        $.post(url, data, function(response){
            fadingMsg(response);
            setTimeout(function(){this.href="#homepage";
            location=this.href;},2000);
            reset();
        });
       /* $.post("http://letsallgetcovered.org/lets6502/hkz_v1/main/post_answers.php", data, function(response){
            fadingMsg(response);
            setTimeout(function(){this.href="#homepage";
            location=this.href;},2000);
            reset();
        });*/
    });
    //this.remove();
}

function fadingMsg (locMsg) {
    $("<div class='ui-overlay-shadow ui-body-e ui-corner-all mds-popup-center' id='fadingmsg' style='font-size:20px'>" + locMsg + "</div>")
    .css({ 'display': 'block', 'opacity': '0.9', 'z-index' : '9999999' })
    .appendTo( $.mobile.pageContainer )
    .delay( 340 )
    .fadeOut( 2200, function(){
        $(this).remove();
    });
}    

function navbar_function(){
    $("#return_catagory").click(function(){
    	var school_index=localStorage.getItem("school_index");
        var survey_index=localStorage.getItem("survey_index");
        var path_index=localStorage.getItem("path_index");
        if($("#catagoryUrl_"+survey_index+"_"+path_index+"_"+school_index).length>0&&localStorage.getItem("json_question_str")!=""){
        	this.href="#catagoryUrl_"+survey_index+"_"+path_index+"_"+school_index;
        }else{
        	getCatagory(survey_index);
        }       
        //this.remove();
    });
    $("#map").click(function(){
        this.href="#page-map";
        location=this.href;
        location.reload();
        //this.remove();
    });
}

