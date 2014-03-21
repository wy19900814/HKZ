function getTallyQues(tallyQuesIndex){
    remove_tally_btn(tallyQuesIndex);
	//tally question q_type=3
	var tally_ques_len=jsonObj_ques.Questions[1].Tallies.length;
	//alert(tally_ques_len);
	var tally_ques=jsonObj_ques.Questions[1].Tallies;

	var jsonAnswerStr=localStorage.getItem("jsonAnswerStr");
	var json_localStorage=JSON.parse(jsonAnswerStr);
	var option_ans_arr=new Array();
	var option_ans=0;
	for(var i=0;i<json_localStorage.Answers.length;i++){
		if(json_localStorage.Answers[i].q_id==jsonObj_ques.Questions[1].Tallies[tallyQuesIndex].q_id&&json_localStorage.Answers[i].block_id==0){
			option_ans=json_localStorage.Answers[i].a_content;
			//alert(option_ans);
		}
	}
	var page = "<div data-role='page' data-url=TallyQuesUrl_"+tallyQuesIndex+" data-theme='c' id='TallyQuesUrl_"+tallyQuesIndex+"'><div data-role='header' data-theme='b'><a href='#' data-theme='b' data-icon='back' class='ui-btn-left' id='previous_tally_ques_"+tallyQuesIndex+"'>back</a><h1>" + "HKZ" + "</h1><a href='#' data-theme='b' data-icon='forward' class='ui-btn-right' id='next_tally_ques_"+tallyQuesIndex+"'>next</a></div><div data-role='content'>";  

	//for(var i=0;i<tally_ques_len;i++){
		page=page+"<label for='tallyQues"+tallyQuesIndex+"'><h3>"+tally_ques[tallyQuesIndex].q_heading+'</h3></label>';
		page=page+" <a href=\"#\" data-role=\"button\" data-icon=\"plus\" data-iconpos=\"notext\" data-theme=\"b\" data-iconshadow=\"false\" data-inline=\"true\" id='plus_"+tallyQuesIndex+"'>Plus</a><input type='number' style='width:70%;' data-clear-btn='false' value=";
		page=page+option_ans;
		page=page+" data-inline=\"true\" id='tallyQues"+tallyQuesIndex+"'/><a href=\"#\" id='minus_"+tallyQuesIndex+"' data-role=\"button\" data-icon=\"minus\" data-iconpos=\"notext\" data-theme=\"b\" data-iconshadow=\"false\" data-inline=\"true\">Minus</a>";
	//}

	page = page + "</div>";
    //var block_index=0;
    page=add_tally_navbar(page,tallyQuesIndex);
    page+="</div>";
    var newPage = $(page);
    newPage.appendTo($.mobile.pageContainer);
    //<a id='test' href='#schoolUrl'><button>test</button></a>
   	
    $.mobile.changePage(newPage);

    submit();
    return_catagory();

    //TallyQuesUrl_"+tallyQuesIndex
    $("#previous_tally_ques_"+tallyQuesIndex).click(function(){
        var survey_index=localStorage.getItem("survey_index");
        if(tallyQuesIndex==0){
            getCatagory(survey_index);
        }
        if(tallyQuesIndex!=0){
            tallyQuesIndex--;
            getTallyQues(tallyQuesIndex);

        }
    });

    $("#reset_tally_"+tallyQuesIndex).click(function(){
        var newDialog=$(create_reset_tally_dialog(tallyQuesIndex));
        newDialog.appendTo($.mobile.pageContainer);
        this.href="#reset_tally_dialog_"+tallyQuesIndex;
        //alert($("#reset_yes"+"_"+block_num+"_"+ques_num).html());
        $("#reset_tally_yes_"+tallyQuesIndex).click(function(){
            this.href="#homepage";
            alert("thhl");
            
        });
    });


    var p_id=jsonObj_sps.Schools[school_index].Paths[path_index].p_id;
    var block_id=0;
    var a_content="#tallyQues"+tallyQuesIndex;

    $("#next_tally_ques_"+tallyQuesIndex).click(function(){
        var answer='{"q_id":"'+jsonObj_ques.Questions[1].Tallies[tallyQuesIndex].q_id+'","block_id":"'+0+'","p_id":"'+p_id+'","a_content":"'+$(a_content).val()+'"}';

        setTallyAnswer(tallyQuesIndex,block_id,answer,p_id);

    	tallyQuesIndex++;
    	if(tallyQuesIndex<tally_ques_len){
    		getTallyQues(tallyQuesIndex);
    	}else{
    		tallyQuesIndex=0;
    		alert("The following is other questions")
    		getOtherQues(0);
    	}
    });

    $("#plus_"+tallyQuesIndex).click(function(){
    	$("#tallyQues"+tallyQuesIndex).val(parseInt($("#tallyQues"+tallyQuesIndex).val())+1);
    });

    $("#minus_"+tallyQuesIndex).click(function(){
    	if(parseInt($("#tallyQues"+tallyQuesIndex).val())-1<0){
    		alert("This number can not be below zero")
    	}else{
    		$("#tallyQues"+tallyQuesIndex).val(parseInt($("#tallyQues"+tallyQuesIndex).val())-1);
    		
    	}
    });

}

function setTallyAnswer(tallyQuesIndex,block_id,answer,p_id){
    var searchStr='{"q_id":"'+jsonObj_ques.Questions[1].Tallies[tallyQuesIndex].q_id+'","block_id":"'+block_id;
    var jsonAnswerStr=localStorage.getItem("jsonAnswerStr");

    if(jsonAnswerStr.search(searchStr)==-1){
        jsonAnswerStr=joinStr(jsonAnswerStr,answer);
        localStorage.setItem("jsonAnswerStr",jsonAnswerStr);
        alert(localStorage.getItem("jsonAnswerStr"));
    }else{
        //replace the existing answer
        var json_localStorage=JSON.parse(jsonAnswerStr);
        for(var i=0;i<json_localStorage.Answers.length;i++){
            if(json_localStorage.Answers[i].q_id==jsonObj_ques.Questions[1].Tallies[tallyQuesIndex].q_id&&json_localStorage.Answers[i].block_id==block_id){

                var replaceStr='{"q_id":"'+jsonObj_ques.Questions[1].Tallies[tallyQuesIndex].q_id+'","block_id":"'+block_id+'","p_id":"'+p_id+'","a_content":"'+json_localStorage.Answers[i].a_content+'"}';
                //alert(replaceStr);
                //alert(answer);
                jsonAnswerStr=jsonAnswerStr.replace(replaceStr,answer);
                localStorage.setItem("jsonAnswerStr",jsonAnswerStr);
                alert(localStorage.getItem("jsonAnswerStr"));
            }
        }
        
    }

}

