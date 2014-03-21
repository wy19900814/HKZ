function getBlockFirstQues(block_num){
    getBlockOneQues(block_num,0);
}

function getBlockOneQues(block_num,ques_num){
    remove_block_btn(block_num,ques_num);
    
	var block_ques_len=jsonObj_ques.Questions[0].Blocks.length;
	var block_len=jsonObj_sps.Schools[school_index].Paths[path_index].num_block;
	var p_id=jsonObj_sps.Schools[school_index].Paths[path_index].p_id;
    var block_id=parseInt(block_num)+1;

    var jsonAnswerStr=localStorage.getItem("jsonAnswerStr");
    var json_localStorage=JSON.parse(jsonAnswerStr);
    var option_ans_arr=new Array();

    //parse a_content and show the old answer
    for(var i=0;i<json_localStorage.Answers.length;i++){
    	if(json_localStorage.Answers[i].q_id==jsonObj_ques.Questions[0].Blocks[ques_num].q_id&&json_localStorage.Answers[i].block_id==block_id){
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
	
	var page = "<div data-role='page' data-url=blockQuesUrl_"+block_num+"_"+ques_num+" data-theme='c' id='blockQuesUrl_"+block_num+"_"+ques_num+"'><div data-role='header' data-theme='b'><a href='#' data-theme='b' data-icon='back' class='ui-btn-left' id='previous_block_ques_"+block_num+"_"+ques_num+"'>back</a><h1>" + "HKZ" + "</h1><a href='#' data-theme='b' data-icon='forward' class='ui-btn-right' id='next_block_ques_"+block_num+"_"+ques_num+"'>next</a></div><div data-role='content'>";  

	var questions=jsonObj_ques.Questions;
	

	var options=jsonObj_ques.Questions[0].Blocks[ques_num].Options;

	page=page+'<label>'+questions[0].Blocks[ques_num].q_heading+'</label>';
	page=page+"<form>";
	if(questions[0].Blocks[ques_num].q_type==1){
	    //q_type=1 means single_choice, q_type==2 means mutiple_choice
	    for (var i = 0; i < options.length; i++) {
	        page = page + '<input type=\"radio\" name=\"option_'+block_num+'_'+ques_num+'\" id=\"option_'+block_num+'_'+ques_num+'_'+options[i].o_id+'\" value=\"'+options[i].o_id+'\" ';
	        for(var j=0;j<option_ans_arr.length;j++){
	        	if(options[i].o_id==option_ans_arr[j]){
		        	page=page + 'checked';
		        }
	        }
	        page=page+'><label for=\"option_'+block_num+'_'+ques_num+'_'+options[i].o_id+'\">'+options[i].o_text+'</label>';
	        //alert("hello");
	    }
	}
//'+block_num+'_'+ques_num+'
    else if(questions[0].Blocks[ques_num].q_type==2){
        for (var i = 0; i < options.length; i++) {
            page = page + '<input type=\"checkbox\" name=\"option_'+block_num+'_'+ques_num+'\" id=\"option_'+block_num+'_'+ques_num+'_'+options[i].o_id+'\"  value=\"'+options[i].o_id+'\"';
            //alert("length:"+option_ans_arr.length);
	        for(var j=0;j<option_ans_arr.length;j++){
	        	if(options[i].o_id==option_ans_arr[j]){
		        	page=page + 'checked';
		        	//alert("number"+option_ans_arr[j]);
		        }
	        }
            page=page+'><label for=\"option_'+block_num+'_'+ques_num+'_'+options[i].o_id+'\">'+options[i].o_text+'</label>';
            //alert("hello");
        }
    }
    
    page += "</form></div>";
    page=add_block_navbar(page,block_num,ques_num);
    page+="</div>";

    var newPage = $(page);
    newPage.appendTo($.mobile.pageContainer);
   
    $.mobile.changePage(newPage);

    submit();
    return_catagory();
    //reset navbar
    $("#reset_block_"+block_num+"_"+ques_num).click(function(){
        var newDialog=$(create_reset_block_dialog(block_num,ques_num));
        newDialog.appendTo($.mobile.pageContainer);
        this.href="#reset_block_dialog_"+block_num+"_"+ques_num;
        //alert($("#reset_yes"+"_"+block_num+"_"+ques_num).html());
        $("#reset_block_yes"+"_"+block_num+"_"+ques_num).click(function(){
            this.href="#homepage";
            alert("thhl");
            
        });
    });

    $("#previous_block_ques_"+block_num+"_"+ques_num).click(function(){
        var survey_index=localStorage.getItem("survey_index");
        if(block_num==0&&ques_num==0){
            getCatagory(survey_index);
        }
        if(ques_num!=0){
            ques_num--;
            getBlockOneQues(block_num,ques_num);

        }
        if(ques_num==0&&block_num!=0){
            block_num--;
            var json_question_str=localStorage.getItem("json_question_str");
            var block_question_num=JSON.parse(json_question_str).Questions[0].Blocks.length;
            ques_num=block_question_num-1;
            getBlockOneQues(block_num,ques_num);
        }
    });


    $("#next_block_ques_"+block_num+"_"+ques_num).click(function(){

        if(questions[0].Blocks[ques_num].q_type==1){
            var radio_name="option_"+block_num+"_"+ques_num;
       //alert(test);
            var radio_checked="input:radio[name='"+radio_name+"']:checked";
           
            var answer='{"q_id":"'+jsonObj_ques.Questions[0].Blocks[ques_num].q_id+'","block_id":"'+block_id+'","p_id":"'+p_id+'","a_content":"'+$(radio_checked).val()+'"}';
            //alert(answer);
            setBlockAnswer(ques_num,block_id,answer,p_id);
            

        }else if(questions[0].Blocks[ques_num].q_type==2){
            var checkbox_name="option_"+block_num+"_"+ques_num;
       //alert(test);
            var checkbox_checked="input:checkbox[name='"+checkbox_name+"']:checked";
            var a_content="";
            alert(a_content);
            $(checkbox_checked).each(function(){
                a_content+=this.value+"_";
                alert(this.value);
            });
            a_content=a_content.substring(0,a_content.length-1);
            alert(a_content);

            var answer='{"q_id":"'+jsonObj_ques.Questions[0].Blocks[ques_num].q_id+'","block_id":"'+block_id+'","p_id":"'+p_id+'","a_content":"'+a_content+'"}';

            setBlockAnswer(ques_num,block_id,answer,p_id);
        }
        

     	if(block_num<block_len){
     		ques_num++;
     		if(ques_num<block_ques_len){    			
     			nextQues(block_num,ques_num);
     		}else{
     			ques_num=0;
     			block_num++;
     			if(block_num<block_len){
     				alert("The following is the questions of next block");
     				nextQues(block_num,ques_num);
     			}else{
     				alert("The following is tally questions")
     				block_num=0;
     				getTallyQues(0);
     			}
     		}
     	}

    });
     //alert("234:"+ques_num);
}
//joinStr(jsonAnswerStr,answer);


function nextQues(block_num,ques_num){
   getBlockOneQues(block_num,ques_num)
  
}


function setBlockAnswer(ques_num,block_id,answer,p_id){
    var searchStr='{"q_id":"'+jsonObj_ques.Questions[0].Blocks[ques_num].q_id+'","block_id":"'+block_id;
    var jsonAnswerStr=localStorage.getItem("jsonAnswerStr");

    if(jsonAnswerStr.search(searchStr)==-1){
        jsonAnswerStr=joinStr(jsonAnswerStr,answer);
        localStorage.setItem("jsonAnswerStr",jsonAnswerStr);
        alert(localStorage.getItem("jsonAnswerStr"));
    }else{
        //replace the existing answer
        var json_localStorage=JSON.parse(jsonAnswerStr);
        for(var i=0;i<json_localStorage.Answers.length;i++){
            if(json_localStorage.Answers[i].q_id==jsonObj_ques.Questions[0].Blocks[ques_num].q_id&&json_localStorage.Answers[i].block_id==block_id){

                var replaceStr='{"q_id":"'+jsonObj_ques.Questions[0].Blocks[ques_num].q_id+'","block_id":"'+block_id+'","p_id":"'+p_id+'","a_content":"'+json_localStorage.Answers[i].a_content+'"}';
                //alert(replaceStr);
                //alert(answer);
                jsonAnswerStr=jsonAnswerStr.replace(replaceStr,answer);
                localStorage.setItem("jsonAnswerStr",jsonAnswerStr);
                alert(localStorage.getItem("jsonAnswerStr"));
            }
        }
        
    }

}
