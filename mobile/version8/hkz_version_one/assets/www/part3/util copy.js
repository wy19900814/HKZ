//local storage: 
//json_answer_str
//survey_index
//path_index
//school_index
//json_sps_str
//json_question_str

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

function add_block_navbar(a_page,block_num,ques_num){
    page =a_page + "<div data-role='footer' data-position='fixed'>";
    page=page+"<div data-role='navbar' ><ul>";
    page=page+"<li><a href='#' id='block_ques_"+block_num+"_"+ques_num+"' class='ui-btn-active'>Survey</a></li>";
    page=page+"<li><a href='#' data-rel='dialog' id='reset_block_"+block_num+"_"+ques_num+"'>Reset</a></li>";
    page=page+"<li><a href='#' id='return_catagory'>Catagory</a></li>";
    page=page+"<li><a href='#' id='map' rel='external'>Map</a></li>";
    page=page+"</ul></div>";
    return page;
}

function create_reset_block_dialog(block_num,ques_num){
    var page="<div data-role='page' data-dialog='true' id='reset_block_dialog_"+block_num+"_"+ques_num+"' ><div data-role='header' data-theme='b'><h1>Dialog</h1></div><div role='main' class='ui-content'  style='background-color:white'><h3>Reset answers?</h3><h5>Do you want to reset all of content?</h5><a id='reset_block_yes"+"_"+block_num+"_"+ques_num+"'><input type='button' data-theme='b' value='Yes'></a><a href='#' data-rel='back'><input type='button' data-theme='b' value='Cancel' ></a></div></div>"
    //alert("create dialog");
    return page;
    
}

function remove_block_btn(block_num,ques_num){
    //remove the dialog page
    if($("#reset_block_dialog_"+block_num+"_"+ques_num).length>0){
        $("#reset_block_dialog_"+block_num+"_"+ques_num).remove();
        //alert("remove dialog");
    }
    if($("#reset_block_"+block_num+"_"+ques_num).length>0){
        $("#reset_block_"+block_num+"_"+ques_num).remove();
        //alert("reset button");
    }
    if($("#next_block_ques_"+block_num+"_"+ques_num).length>0){
        $("#next_block_ques_"+block_num+"_"+ques_num).remove();
        //alert("remove next");
    }
    if($("#return_catagory").length>0){
        $("#return_catagory").remove();
       // alert("move return catagory");
    }
    if($("#previous_block_ques_"+block_num+"_"+ques_num).length>0){
        $("#previous_block_ques_"+block_num+"_"+ques_num).remove();
        //alert("remove previous");
    }
    if($("#blockQuesUrl_"+block_num+"_"+ques_num).length>0){
        $("#blockQuesUrl_"+block_num+"_"+ques_num).remove();
        //alert("remove block page");
    }
    if($("#map").length>0){
        $("#map").remove();
        //alert("remove map");
    }
}

function add_tally_navbar(a_page,tallyQuesIndex){
    page =a_page + "<div data-role='footer' data-position='fixed'>";
    page=page+"<div data-role='navbar' ><ul>";
    page=page+"<li><a href='#' id='tally_ques_"+tallyQuesIndex+"' class='ui-btn-active'>Survey</a></li>";
    page=page+"<li><a href='#' data-rel='dialog' id='reset_tally_"+tallyQuesIndex+"'>Reset</a></li>";
    page=page+"<li><a href='#' id='return_catagory'>Catagory</a></li>";
    page=page+"<li><a href='#' id='map' rel='external'>Map</a></li>";
    page=page+"</ul></div>";
    return page;
}

function create_reset_tally_dialog(tallyQuesIndex){
    var page="<div data-role='page' data-dialog='true' id='reset_tally_dialog_"+tallyQuesIndex+"' ><div data-role='header' data-theme='b'><h1>Dialog</h1></div><div role='main' class='ui-content'  style='background-color:white'><h3>Reset answers?</h3><h5>Do you want to reset all of content?</h5><a id='reset_tally_yes_"+tallyQuesIndex+"'><input type='button' data-theme='b' value='Yes'></a><a href='#' data-rel='back'><input type='button' data-theme='b' value='Cancel' ></a></div></div>"
    //alert("create dialog");
    return page;
}

function remove_tally_btn(tallyQuesIndex){

    if($("#reset_tally_dialog_"+tallyQuesIndex).length>0){
        $("#reset_tally_dialog_"+tallyQuesIndex).remove();
        //alert("remove dialog");
    }
    if($("#reset_tally_"+tallyQuesIndex).length>0){
        $("#reset_tally_"+tallyQuesIndex).remove();
        //alert("reset button");
    }
    if($("#next_tally_ques_"+tallyQuesIndex).length>0){
        $("#next_tally_ques_"+tallyQuesIndex).remove();
        //alert("remove next");
    }
    if($("#return_catagory").length>0){
        $("#return_catagory").remove();
        //alert("remove submt");
    }
    //TallyQuesUrl_"+tallyQuesIndex
    if($("#previous_tally_ques_"+tallyQuesIndex).length>0){
        $("#previous_tally_ques_"+tallyQuesIndex).remove();
        //alert("remove previous");
    }
    if($("#TallyQuesUrl_"+tallyQuesIndex).length>0){
        $("#TallyQuesUrl_"+tallyQuesIndex).remove();
        //alert("remove tally page");
    }
    if($("#map").length>0){
        $("#map").remove();
        //alert("remove map");
    }

        
}

function add_other_navbar(a_page,otherQuesIndex){
    page =a_page + "<div data-role='footer' data-position='fixed'>";
    page=page+"<div data-role='navbar' ><ul>";
    page=page+"<li><a href='#' id='other_ques_"+otherQuesIndex+"' class='ui-btn-active'>Survey</a></li>";
    page=page+"<li><a href='#' data-rel='dialog' id='reset_other_"+otherQuesIndex+"'>Reset</a></li>";
    page=page+"<li><a href='#' id='return_catagory'>Catagory</a></li>";
    page=page+"<li><a href='#' id='map' rel='external'>Map</a></li>";
    page=page+"</ul></div>";
    return page;
}

function create_reset_other_dialog(otherQuesIndex){
    var page="<div data-role='page' data-dialog='true' id='reset_other_dialog_"+otherQuesIndex+"' ><div data-role='header' data-theme='b'><h1>Dialog</h1></div><div role='main' class='ui-content'  style='background-color:white'><h3>Reset answers?</h3><h5>Do you want to reset all of content?</h5><a id='reset_other_yes_"+otherQuesIndex+"'><input type='button' data-theme='b' value='Yes'></a><a href='#' data-rel='back'><input type='button' data-theme='b' value='Cancel' ></a></div></div>"
    //alert("create dialog");
    return page;
}

function remove_other_btn(otherQuesIndex){

    if($("#reset_other_dialog_"+otherQuesIndex).length>0){
        $("#reset_other_dialog_"+otherQuesIndex).remove();
        //alert("remove dialog");
    }
    if($("#reset_other_"+otherQuesIndex).length>0){
        $("#reset_other_"+otherQuesIndex).remove();
        //alert("reset button");
    }
    if($("#next_other_ques_"+otherQuesIndex).length>0){
        $("#next_other_ques_"+otherQuesIndex).remove();
        //alert("remove next");
    }
    if($("#return_catagory").length>0){
        $("#return_catagory").remove();
        //alert("remove submit");
    }
    if($("#previous_other_ques_"+otherQuesIndex).length>0){
        $("#previous_other_ques_"+otherQuesIndex).remove();
        //alert("remove previous");
    }
    if($("#OtherQuesUrl_"+otherQuesIndex).length>0){
        $("#OtherQuesUrl_"+otherQuesIndex).remove();
        //alert("remove other page");
    }
    if($("#map").length>0){
        $("#map").remove();
        //alert("remove map");
    }
        
}

function submit(){
    //3. Answer:
/*{“Answers” : [
    {“q_id”  :  “…”,
      “block_id”  :  “…”,  //should be 0 for tally or other questions.
      “p_id”  :  “…”,
      “a_content”  :  “…”},
    …

]}*/
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
       
        var data = { json_answer_str: json_answer_str };
        //http://letsallgetcovered.org/HKZ/store_answers_to_db.php
        //http://localhost/version1/part3/store_answers_to_db.php
        $.post("http://letsallgetcovered.org/lets6502/hkz_v1/main/post_answers.php", data, function(response){
            fadingMsg(response);
            setTimeout(function(){this.href="#homepage";
            location=this.href;},2000);
            reset();
        });
        
       /* $.post( "test_post.php", { json_answer_str: json_answer_str },function(data){
            alert(data);
        } );*/ 
        
        
       // alert("eh");
    });
    
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
        var survey_index=localStorage.getItem("survey_index");
        getCatagory(survey_index);
        //this.remove();
    });
    $("#map").click(function(){
        this.href="#page-map";
        location=this.href;
        location.reload();
        //alert("hello");
        //this.remove();
    });
}
function getLocalSurvey(){
    var survey_index=localStorage.getItem('survey_index');
    if(survey_index==""){
        alert("There is no local survey! Please start a new survey.");
    }else{
        getCatagory(survey_index);
    }
    
    //$("#localSurvey").herf="#schoolUrl";
}

