function getBlockFirstQues(block_index){
    getBlockOneQues(block_index,0);
}
function getBlockOneQues(block_index,question_index){
    remove_block_btn(block_index,question_index);
    block_old_index=block_index;
    question_old_index=question_index;
    
    json_question=JSON.parse(localStorage.getItem('json_question_str'));
    json_sps=JSON.parse(localStorage.getItem('json_sps_str'));
    get_all_index();
    
    localStorage.setItem('current_survey_page',"blockQuesUrl_"+block_index+"_"+question_index+"_"+survey_index+"_"+path_index+"_"+school_index); 
    
	var block_ques_len=json_question.Questions.Blocks.length;
	var block_len=json_sps.Schools[school_index].Paths[path_index].num_block;
	var p_id=json_sps.Schools[school_index].Paths[path_index].p_id;
    var block_id=parseInt(block_index)+1;

    var json_answer_str=localStorage.getItem("json_answer_str");
    var json_answer=JSON.parse(json_answer_str);
    var option_ans_arr=new Array();

    //parse a_content and show the old answer
    for(var i=0;i<json_answer.Answers.length;i++){
    	if(json_answer.Answers[i].q_id==json_question.Questions.Blocks[question_index].q_id&&json_answer.Answers[i].block_id==block_id){
    		var option_ans=json_answer.Answers[i].a_content;//o_id value=o_id
    		var index=option_ans.indexOf("_"); 		
    		if(index!=-1){
    			option_ans_arr=option_ans.split("_");
    		}else{
    			option_ans_arr[0]=option_ans;
    		}
    	}
    }
	
	var page = "<div data-role='page' data-url=blockQuesUrl_"+block_index+"_"+question_index+"_"+survey_index+"_"+path_index+"_"+school_index+" data-theme='c' id='blockQuesUrl_"+block_index+"_"+question_index+"_"+survey_index+"_"+path_index+"_"+school_index+"'><div data-role='header' data-theme='b'><a href='#' data-theme='b' data-icon='back' class='ui-btn-left' id='previous_ques'>back</a><h1>" + "block"+ block_id+ "</h1><a href='#' data-theme='b' data-icon='forward' class='ui-btn-right' id='next_ques'>next</a></div><div data-role='content'>";  

	var questions=json_question.Questions;
	var options=json_question.Questions.Blocks[question_index].options;

	page=page+'<label>'+questions.Blocks[question_index].q_heading+'</label>';
	page=page+"<form>";
	if(questions.Blocks[question_index].q_type==1){
	    //q_type=1 means single_choice, q_type==2 means mutiple_choice
	    for (var i = 0; i < options.length; i++) {
	        page = page + '<input type=\"radio\" name=\"option_'+block_index+'_'+question_index+'\" id=\"option_'+block_index+'_'+question_index+'_'+options[i].o_id+'\" value=\"'+options[i].o_id+'\" ';
	        for(var j=0;j<option_ans_arr.length;j++){
	        	if(options[i].o_id==option_ans_arr[j]){
		        	page=page + 'checked';
		        }
	        }
	        page=page+'><label for=\"option_'+block_index+'_'+question_index+'_'+options[i].o_id+'\">'+options[i].o_text+'</label>';
	        //alert("hello");
	    }
	}
//'+block_index+'_'+question_index+'
    else if(questions.Blocks[question_index].q_type==2){
        for (var i = 0; i < options.length; i++) {
            page = page + '<input type=\"checkbox\" name=\"option_'+block_index+'_'+question_index+'\" id=\"option_'+block_index+'_'+question_index+'_'+options[i].o_id+'\"  value=\"'+options[i].o_id+'\"';
            //alert("length:"+option_ans_arr.length);
	        for(var j=0;j<option_ans_arr.length;j++){
	        	if(options[i].o_id==option_ans_arr[j]){
		        	page=page + 'checked';
		        	//alert("number"+option_ans_arr[j]);
		        }
	        }
            page=page+'><label for=\"option_'+block_index+'_'+question_index+'_'+options[i].o_id+'\">'+options[i].o_text+'</label>';
            //alert("hello");
        }
    }
    
    page += "</form></div>";
    page=add_navbar(page);
    page+="</div>";
    var newPage = $(page);
    newPage.appendTo($.mobile.pageContainer);
    $.mobile.changePage(newPage);
    
    navbar_function();

    $("#reset").click(function(){
        var newDialog=$(create_reset_block_dialog(block_index,question_index));
        newDialog.appendTo($.mobile.pageContainer);
        this.href="#reset_block_dialog_"+block_index+"_"+question_index;
        $("#reset_yes").click(function(){
            this.href="#homepage";
            location=this.href;
            reset();   
            //remove_block_btn(block_index,question_index);
        });
        $("#reset_cancel").click(function(){
            this.href="#blockQuesUrl_"+block_index+"_"+question_index+"_"+survey_index+"_"+path_index+"_"+school_index;
            location=this.href;
            //alert(location);
        });
    });

    $("#previous_ques").click(function(){
        if(question_index==0){
        	if($("#catagoryUrl_"+survey_index+"_"+path_index+"_"+school_index).length>0&&localStorage.getItem("json_question_str")!=""){
        		this.href="#catagoryUrl_"+survey_index+"_"+path_index+"_"+school_index;
        		location=this.href;
                //alert("eee");
        	}else{
        		getCatagory(survey_index);   
        	}   	    	 
        }
        if(question_index!=0){
            question_index--;
            getBlockOneQues(block_index,question_index);
        }        
    });

    $("#next_ques").click(function(){
    	
        if(questions.Blocks[question_index].q_type==1){
            var radio_name="option_"+block_index+"_"+question_index;
            var radio_checked="input:radio[name='"+radio_name+"']:checked";           
            var answer='{"q_id":"'+json_question.Questions.Blocks[question_index].q_id+'","block_id":"'+block_id+'","p_id":"'+p_id+'","a_content":"'+$(radio_checked).val()+'"}';
            setBlockAnswer(question_index,block_id,answer,p_id);
            
        }else if(questions.Blocks[question_index].q_type==2){
            var checkbox_name="option_"+block_index+"_"+question_index;
            var checkbox_checked="input:checkbox[name='"+checkbox_name+"']:checked";
            var a_content="";
            $(checkbox_checked).each(function(){
                a_content+=this.value+"_";
            });
            a_content=a_content.substring(0,a_content.length-1);
            var answer='{"q_id":"'+json_question.Questions.Blocks[question_index].q_id+'","block_id":"'+block_id+'","p_id":"'+p_id+'","a_content":"'+a_content+'"}';
            setBlockAnswer(question_index,block_id,answer,p_id);
        }
 
     	if(block_index<block_len){
     		question_index++;
     		if(question_index<block_ques_len){
     			//remove_block_btn(block_old_index,question_old_index);
     			nextQues(block_index,question_index);

     			
     		}else{
     			question_index=0;
     			block_index++;
     			if(block_index<block_len){
                    alert("The following is next block questions!");
                    $("#block"+block_index).css('color','red');
                    //remove_block_btn(block_old_index,question_old_index);
                    nextQues(block_index,question_index);
     			}else{
     				alert("The following is tally questions");
                    $("#block"+block_index).css('color','red');
     				//remove_block_btn(block_old_index,question_old_index);
         			getTallyQues(0);         			                                      				
     			}
     		}
     	}
     	//remove_block_btn(block_old_index,question_old_index);
    });
}
function nextQues(block_index,question_index){
   getBlockOneQues(block_index,question_index);
}
function setBlockAnswer(question_index,block_id,answer,p_id){
    var searchStr='{"q_id":"'+json_question.Questions.Blocks[question_index].q_id+'","block_id":"'+block_id;
    var json_answer_str=localStorage.getItem("json_answer_str");

    if(json_answer_str.search(searchStr)==-1){
        json_answer_str=joinStr(json_answer_str,answer);
        localStorage.setItem("json_answer_str",json_answer_str);
        //alert(localStorage.getItem("json_answer_str"));
    }else{
        //replace the existing answer
        var json_answer=JSON.parse(json_answer_str);
        for(var i=0;i<json_answer.Answers.length;i++){
            if(json_answer.Answers[i].q_id==json_question.Questions.Blocks[question_index].q_id&&json_answer.Answers[i].block_id==block_id){
                var replaceStr='{"q_id":"'+json_question.Questions.Blocks[question_index].q_id+'","block_id":"'+block_id+'","p_id":"'+p_id+'","a_content":"'+json_answer.Answers[i].a_content+'"}';
                //alert(replaceStr);
                //alert(answer);//new answer
                json_answer_str=json_answer_str.replace(replaceStr,answer);
                localStorage.setItem("json_answer_str",json_answer_str);
                //alert(localStorage.getItem("json_answer_str"));
            }
        }      
    }
}
