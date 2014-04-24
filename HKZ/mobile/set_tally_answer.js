function getTallyQues(tallyQuesIndex){
    remove_tally_btn(tallyQuesIndex);
	//tally question q_type=3
    localStorage.setItem('current_survey_page',"TallyQuesUrl_"+tallyQuesIndex);
    json_question=JSON.parse(localStorage.getItem('json_question_str'));
    jsonObj_sps=JSON.parse(localStorage.getItem('json_sps_str'));
    get_all_index();

	var tally_ques_len=json_question.Questions.Tallies.length;
	//alert(tally_ques_len);
	var tally_ques=json_question.Questions.Tallies;

	var json_answer_str=localStorage.getItem("json_answer_str");
	var json_answer=JSON.parse(json_answer_str);
	var option_ans_arr=new Array();
	var option_ans=0;
	for(var i=0;i<json_answer.Answers.length;i++){
		if(json_answer.Answers[i].q_id==json_question.Questions.Tallies[tallyQuesIndex].q_id&&json_answer.Answers[i].block_id==0){
			option_ans=json_answer.Answers[i].a_content;
			//alert(option_ans);
		}
	}
	var page = "<div data-role='page' data-url=TallyQuesUrl_"+tallyQuesIndex+"_"+survey_index+"_"+path_index+"_"+school_index+" data-theme='c' id='TallyQuesUrl_"+tallyQuesIndex+"_"+survey_index+"_"+path_index+"_"+school_index+"'><div data-role='header' data-theme='b'><a href='#' data-theme='b' data-icon='back' class='ui-btn-left' id='previous_ques'>back</a><h1>" + "Tally" + "</h1><a href='#' data-theme='b' data-icon='forward' class='ui-btn-right' id='next_ques'>next</a></div><div data-role='content'>";  

	page=page+"<label for='tallyQues"+tallyQuesIndex+"'><h3>"+tally_ques[tallyQuesIndex].q_heading+'</h3></label>';
    if(tally_ques[tallyQuesIndex].image!=""){
        page=page+'<img src="'+tally_ques[tallyQuesIndex].image+'" width=100% height=50%>';
    }
    

	page=page+" <a href=\"#\" data-role=\"button\" data-icon=\"plus\"  data-theme=\"b\"   id='plus_"+tallyQuesIndex+"'>Plus</a><input type='number' style='width:100%;text-align:center;' data-clear-btn='false' value=";
	page=page+option_ans;
	page=page+" data-inline=\"true\" id='tallyQues"+tallyQuesIndex+"'/><a href=\"#\" id='minus_"+tallyQuesIndex+"' data-role=\"button\" data-icon=\"minus\" data-theme=\"b\" data-iconshadow=\"false\">Minus</a>";
	page = page + "</div>";
    page=add_navbar(page);
    page+="</div>";
    var newPage = $(page);
    newPage.appendTo($.mobile.pageContainer);
    $.mobile.changePage(newPage);
    
    navbar_function();
    //TallyQuesUrl_"+tallyQuesIndex
    $("#previous_ques").click(function(){
        var survey_index=localStorage.getItem("survey_index");
        if(tallyQuesIndex==0){
        	if($("#catagoryUrl_"+survey_index+"_"+path_index+"_"+school_index).length>0&&localStorage.getItem("json_question_str")!=""){
        		this.href="#catagoryUrl_"+survey_index+"_"+path_index+"_"+school_index;
        		location=this.href;
        	}else{
        		getCatagory(survey_index);   
        	}   
        }
        if(tallyQuesIndex!=0){
            tallyQuesIndex--;
            getTallyQues(tallyQuesIndex);

        }
    });

    $("#reset").click(function(){
        var newDialog=$(create_reset_tally_dialog(tallyQuesIndex));
        newDialog.appendTo($.mobile.pageContainer);
        this.href="#reset_tally_dialog_"+tallyQuesIndex;
        //alert($("#reset_yes"+"_"+block_num+"_"+ques_num).html());
        $("#reset_yes").click(function(){
            this.href="#homepage";
            location=this.href;
            reset();
            
        });
        $("#reset_cancel").click(function(){
            this.href="#TallyQuesUrl_"+tallyQuesIndex+"_"+survey_index+"_"+path_index+"_"+school_index;
            location=this.href;
        });
    });


    var p_id=jsonObj_sps.Schools[school_index].Paths[path_index].p_id;
    var block_id=0;
    var a_content="#tallyQues"+tallyQuesIndex;

    $("#next_ques").click(function(){
        var answer='{"q_id":"'+json_question.Questions.Tallies[tallyQuesIndex].q_id+'","block_id":"'+0+'","p_id":"'+p_id+'","a_content":"'+$(a_content).val()+'"}';

        setTallyAnswer(tallyQuesIndex,block_id,answer,p_id);

    	tallyQuesIndex++;
    	if(tallyQuesIndex<tally_ques_len){
    		getTallyQues(tallyQuesIndex);
    	}else{
    		tallyQuesIndex=0;
    		alert("The following is other questions!");
            setTallyAndFontRed();
            getOtherQues(0);
                
    		
    	}
    });

    $("#plus_"+tallyQuesIndex).click(function(){
    	//alert("hello plus");
    	//alert($("#tallyQues"+tallyQuesIndex).val());
    	$("#tallyQues"+tallyQuesIndex).val(parseInt($("#tallyQues"+tallyQuesIndex).val())+1);
    });

    $("#minus_"+tallyQuesIndex).click(function(){
    	//alert("hello minus");
    	//alert($("#tallyQues"+tallyQuesIndex).val());
    	if(parseInt($("#tallyQues"+tallyQuesIndex).val())-1<0){
    		alert("The number can not be below zero!");

    	}else{
    		$("#tallyQues"+tallyQuesIndex).val(parseInt($("#tallyQues"+tallyQuesIndex).val())-1);
    		
    	}
    });

}
function setTallyAndFontRed(){
    var json_answer_str=localStorage.getItem("json_answer_str");
    var json_answer=JSON.parse(json_answer_str);
    var count=0;
    //alert(json_answer_str);
    for(var qi=0;qi<json_answer.Answers.length;qi++){
        if(json_answer.Answers[qi].block_id==0&&json_answer.Answers[qi].a_content!=""&&json_answer.Answers[qi].a_content!="undefined"){
            count++;
        }
    }
    if(count==json_question.Questions.Others.length+json_question.Questions.Tallies.length){
        $("#Tally").css("color","red");
        $("#Other").css("color","red");
    }else{
        $("#Tally").css("color","black");
        $("#Other").css("color","black");
    }
}
function setTallyAnswer(tallyQuesIndex,block_id,answer,p_id){
    var searchStr='{"q_id":"'+json_question.Questions.Tallies[tallyQuesIndex].q_id+'","block_id":"'+block_id;
    var json_answer_str=localStorage.getItem("json_answer_str");

    if(json_answer_str.search(searchStr)==-1){
        json_answer_str=joinStr(json_answer_str,answer);
        localStorage.setItem("json_answer_str",json_answer_str);
        //alert(localStorage.getItem("json_answer_str"));
    }else{
        //replace the existing answer
        var json_answer=JSON.parse(json_answer_str);
        for(var i=0;i<json_answer.Answers.length;i++){
            if(json_answer.Answers[i].q_id==json_question.Questions.Tallies[tallyQuesIndex].q_id&&json_answer.Answers[i].block_id==block_id){

                var replaceStr='{"q_id":"'+json_question.Questions.Tallies[tallyQuesIndex].q_id+'","block_id":"'+block_id+'","p_id":"'+p_id+'","a_content":"'+json_answer.Answers[i].a_content+'"}';
                //alert(replaceStr);
                //alert(answer);
                json_answer_str=json_answer_str.replace(replaceStr,answer);
                localStorage.setItem("json_answer_str",json_answer_str);
                //alert(localStorage.getItem("json_answer_str"));
            }
        }
        
    }

}

