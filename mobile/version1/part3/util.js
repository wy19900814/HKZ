/*global variable
var jsonObj_sps=null;
var jsonObj_ques=null;
var school_index=null;
var path_index=null;
var survey_index=null;
var jsonAnswerStr="";
*/
function reset(){
    jsonObj_sps=null;
    jsonObj_ques=null;
    school_index=null;
    path_index=null;
    survey_index=null;
    jsonAnswerStr="";
}




function joinStr(jsonAnswerStr,answer){
    if(jsonAnswerStr.charAt(jsonAnswerStr.length-3)=="["){
        jsonAnswerStr=jsonAnswerStr.substring(0,jsonAnswerStr.length-2)+answer+jsonAnswerStr.substring(jsonAnswerStr.length-2,jsonAnswerStr.length);
        return jsonAnswerStr;
    }else{
        jsonAnswerStr=jsonAnswerStr.substring(0,jsonAnswerStr.length-2)+","+answer+jsonAnswerStr.substring(jsonAnswerStr.length-2,jsonAnswerStr.length);
        return jsonAnswerStr;
    }
       
}

function add_block_navbar(a_page,block_num,ques_num){
    page =a_page + "<div data-role='footer' data-position='fixed'>";
    page=page+"<div data-role='navbar' ><ul>";
    page=page+"<li><a href='#' id='block_ques_"+block_num+"_"+ques_num+"' class='ui-btn-active'>Survey</a></li>";
    page=page+"<li><a href='#' data-rel='dialog' id='reset_block_"+block_num+"_"+ques_num+"'>Reset</a></li>";
    page=page+"<li><a href='#' id='return_catagory'>Catagory</a></li>";
    page=page+"<li><a href='#'>Map</a></li>";
    page=page+"</ul></div>";
    return page;
}

function create_reset_block_dialog(block_num,ques_num){
    var page="<div data-role='page' data-dialog='true' id='reset_block_dialog_"+block_num+"_"+ques_num+"' ><div data-role='header' data-theme='b'><h1>Dialog</h1></div><div role='main' class='ui-content'  style='background-color:white'><h3>Delete page?</h3><h5>Do you want to reset all of content?</h5><a id='reset_block_yes"+"_"+block_num+"_"+ques_num+"'><input type='button' data-theme='b' value='Yes'></a><a href='#' data-rel='back'><input type='button' data-theme='b' value='Cancel' ></a></div></div>"
    alert("create dialog");
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
}

function add_tally_navbar(a_page,tallyQuesIndex){
    page =a_page + "<div data-role='footer' data-position='fixed'>";
    page=page+"<div data-role='navbar' ><ul>";
    page=page+"<li><a href='#' id='tally_ques_"+tallyQuesIndex+"' class='ui-btn-active'>Survey</a></li>";
    page=page+"<li><a href='#' data-rel='dialog' id='reset_tally_"+tallyQuesIndex+"'>Reset</a></li>";
    page=page+"<li><a href='#' id='return_catagory'>Catagory</a></li>";
    page=page+"<li><a href='#'>Map</a></li>";
    page=page+"</ul></div>";
    return page;
}

function create_reset_tally_dialog(tallyQuesIndex){
    var page="<div data-role='page' data-dialog='true' id='reset_tally_dialog_"+tallyQuesIndex+"' ><div data-role='header' data-theme='b'><h1>Dialog</h1></div><div role='main' class='ui-content'  style='background-color:white'><h3>Delete page?</h3><h5>Do you want to reset all of content?</h5><a id='reset_tally_yes_"+tallyQuesIndex+"'><input type='button' data-theme='b' value='Yes'></a><a href='#' data-rel='back'><input type='button' data-theme='b' value='Cancel' ></a></div></div>"
    alert("create dialog");
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
}

function add_other_navbar(a_page,otherQuesIndex){
    page =a_page + "<div data-role='footer' data-position='fixed'>";
    page=page+"<div data-role='navbar' ><ul>";
    page=page+"<li><a href='#' id='other_ques_"+otherQuesIndex+"' class='ui-btn-active'>Survey</a></li>";
    page=page+"<li><a href='#' data-rel='dialog' id='reset_other_"+otherQuesIndex+"'>Reset</a></li>";
    page=page+"<li><a href='#' id='return_catagory'>Catagory</a></li>";
    page=page+"<li><a href='#'>Map</a></li>";
    page=page+"</ul></div>";
    return page;
}

function create_reset_other_dialog(otherQuesIndex){
    var page="<div data-role='page' data-dialog='true' id='reset_other_dialog_"+otherQuesIndex+"' ><div data-role='header' data-theme='b'><h1>Dialog</h1></div><div role='main' class='ui-content'  style='background-color:white'><h3>Delete page?</h3><h5>Do you want to reset all of content?</h5><a id='reset_other_yes_"+otherQuesIndex+"'><input type='button' data-theme='b' value='Yes'></a><a href='#' data-rel='back'><input type='button' data-theme='b' value='Cancel' ></a></div></div>"
    alert("create dialog");
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

        jsonAnswerStr=localStorage.getItem("jsonAnswerStr");
        var json_answer=JSON.parse(jsonAnswerStr);
        var answers=json_answer.Answers;

        var json_question_str=localStorage.getItem("json_question_str");
        var block_question_num=JSON.parse(json_question_str).Questions[0].Blocks.length;
        var tally_question_num=JSON.parse(json_question_str).Questions[1].Tallies.length;
        var other_question_num=JSON.parse(json_question_str).Questions[2].Others.length;

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
        //alert("ho");
        $.post( "receive_answers.php", { json_answer_str: jsonAnswerStr },function(data){
            alert(data);
        } );   
        
       /* $.post( "test_post.php", { json_answer_str: jsonAnswerStr },function(data){
            alert(data);
        } );*/ 
        
        
       // alert("eh");
    });
}

function return_catagory(){
    $("#return_catagory").click(function(){
        var survey_index=localStorage.getItem("survey_index");
        getCatagory(survey_index);
    });
}
