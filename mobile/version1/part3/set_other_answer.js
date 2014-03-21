function getOtherQues(otherQuesIndex){
    remove_other_btn(otherQuesIndex);
	var other_ques_len=jsonObj_ques.Questions[2].Others.length;
	//alert(tally_ques_len);
	var other_ques=jsonObj_ques.Questions[2].Others;

	var jsonAnswerStr=localStorage.getItem("jsonAnswerStr");
	var json_localStorage=JSON.parse(jsonAnswerStr);
	var option_ans_arr=new Array();
	for(var i=0;i<json_localStorage.Answers.length;i++){
		if(json_localStorage.Answers[i].q_id==jsonObj_ques.Questions[2].Others[otherQuesIndex].q_id&&json_localStorage.Answers[i].block_id==0){
			var option_ans=json_localStorage.Answers[i].a_content;//o_id value=o_id
			var index=option_ans.indexOf("_");
			
			if(index!=-1){
				option_ans_arr=option_ans.split("_");
				//alert("hello1:"+option_ans);
				
			}else{
				option_ans_arr[0]=option_ans;
				//alert("hello:"+option_ans);
			}
		}
	}

	var page = "<div data-role='page' data-url=OtherQuesUrl_"+otherQuesIndex+" data-theme='c' data-add-back-btn='true' id='OtherQuesUrl_"+otherQuesIndex+"'><div data-role='header' data-theme='b'><a href='#' data-theme='b' data-icon='back' class='ui-btn-left' id='previous_other_ques_"+otherQuesIndex+"'>back</a><h1>" + "HKZ" + "</h1><a href='#' data-theme='b' data-icon='forward' class='ui-btn-right' id='next_other_ques_"+otherQuesIndex+"'>next</a></div><div data-role='content'>";  

	page=page+'<label>'+other_ques[otherQuesIndex].q_heading+'</label>';
	page=page+"<form>";

	var options=jsonObj_ques.Questions[2].Others[otherQuesIndex].Options;

	//4 :single-choice 5: multi-choice
	if(other_ques[otherQuesIndex].q_type==4){
	    for (var i = 0; i < options.length; i++) {
	        page = page + '<input type=\"radio\" name=\"option_'+otherQuesIndex+'\" id=\"other_radio-choice-0'+i+'\" value=\"'+options[i].o_id+'\" ';
			for(var j=0;j<option_ans_arr.length;j++){
			    if(options[i].o_id==option_ans_arr[j]){
			        page=page + 'checked';
			    }
			}
	        page=page+'><label for=\"other_radio-choice-0'+i+'\">'+options[i].o_text+'</label>';
	        //alert("hello");
	    }
	}

    else if(other_ques[otherQuesIndex].q_type==5){
        for (var i = 0; i < options.length; i++) {
            page = page + '<input type=\"checkbox\" name=\"option_'+otherQuesIndex+'\" id=\"other_checkbox-choice-0'+i+'\" value=\"'+options[i].o_id+'\"';
            for(var j=0;j<option_ans_arr.length;j++){
			    if(options[i].o_id==option_ans_arr[j]){
			        page=page + 'checked';
			    }
			}
            page=page+'><label for=\"other_checkbox-choice-0'+i+'\">'+options[i].o_text+'</label>';
            //alert("hello");
        }
    }
    
    page = page + "</form></div>";
    page=add_other_navbar(page,otherQuesIndex);
    page=page+"</div>";
    var newPage = $(page);
    newPage.appendTo($.mobile.pageContainer);
    //<a id='test' href='#schoolUrl'><button>test</button></a>
   	
    $.mobile.changePage(newPage);

    submit();
    return_catagory();

    $("#previous_other_ques_"+otherQuesIndex).click(function(){
        var survey_index=localStorage.getItem("survey_index");
        if(otherQuesIndex==0){
            getCatagory(survey_index);
        }
        if(otherQuesIndex!=0){
            otherQuesIndex--;
            getOtherQues(otherQuesIndex);

        }
    });

    $("#reset_other_"+otherQuesIndex).click(function(){
        var newDialog=$(create_reset_other_dialog(otherQuesIndex));
        newDialog.appendTo($.mobile.pageContainer);
        this.href="#reset_other_dialog_"+otherQuesIndex;
        //alert($("#reset_yes"+"_"+block_num+"_"+ques_num).html());
        $("#reset_other_yes_"+otherQuesIndex).click(function(){
            this.href="#homepage";
            alert("thhl");
            
        });
    });

    var block_id=0
    var p_id=jsonObj_sps.Schools[school_index].Paths[path_index].p_id;

    $("#next_other_ques_"+otherQuesIndex).click(function(){
        if(other_ques[otherQuesIndex].q_type==4){


            var radio_name="option_"+otherQuesIndex;
           //alert(test);
            var radio_checked="input:radio[name='"+radio_name+"']:checked";

            var answer='{"q_id":"'+jsonObj_ques.Questions[2].Others[otherQuesIndex].q_id+'","block_id":"'+0+'","p_id":"'+p_id+'","a_content":"'+$(radio_checked).val()+'"}';

            setOtherAnswer(otherQuesIndex,block_id,answer,p_id);
        }else if(other_ques[otherQuesIndex].q_type==5){
            var checkbox_name="option_"+otherQuesIndex;
       //alert(test);
            var checkbox_checked="input:checkbox[name='"+checkbox_name+"']:checked";
            var a_content="";
            $(checkbox_checked).each(function(){
                a_content+=this.value+"_";
                //alert(this.value);
            });
            a_content=a_content.substring(0,a_content.length-1);

            var answer='{"q_id":"'+jsonObj_ques.Questions[2].Others[otherQuesIndex].q_id+'","block_id":"'+0+'","p_id":"'+p_id+'","a_content":"'+a_content+'"}';

            setOtherAnswer(otherQuesIndex,block_id,answer,p_id);
        }

    	otherQuesIndex++;
    	if(otherQuesIndex<other_ques_len){
    		getOtherQues(otherQuesIndex);
    	}else{
    		otherQuesIndex=0;
    		alert("You have already finished the \"other\" questions");
    		this.href="#catagoryUrl";

    	}
    });

}

function setOtherAnswer(otherQuesIndex,block_id,answer,p_id){
    var searchStr='{"q_id":"'+jsonObj_ques.Questions[2].Others[otherQuesIndex].q_id+'","block_id":"'+block_id;
    var jsonAnswerStr=localStorage.getItem("jsonAnswerStr");

    if(jsonAnswerStr.search(searchStr)==-1){
        jsonAnswerStr=joinStr(jsonAnswerStr,answer);
        localStorage.setItem("jsonAnswerStr",jsonAnswerStr);
        alert(localStorage.getItem("jsonAnswerStr"));
    }else{
        //replace the existing answer
        var json_localStorage=JSON.parse(jsonAnswerStr);
        for(var i=0;i<json_localStorage.Answers.length;i++){
            if(json_localStorage.Answers[i].q_id==jsonObj_ques.Questions[2].Others[otherQuesIndex].q_id&&json_localStorage.Answers[i].block_id==block_id){

                var replaceStr='{"q_id":"'+jsonObj_ques.Questions[2].Others[otherQuesIndex].q_id+'","block_id":"'+block_id+'","p_id":"'+p_id+'","a_content":"'+json_localStorage.Answers[i].a_content+'"}';
                alert(replaceStr);
                alert(answer);
                jsonAnswerStr=jsonAnswerStr.replace(replaceStr,answer);
                localStorage.setItem("jsonAnswerStr",jsonAnswerStr);
                alert(localStorage.getItem("jsonAnswerStr"));
            }
        }
        
    }

}