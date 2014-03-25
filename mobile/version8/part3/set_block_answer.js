function getBlockFirstQues(block_num){
    getBlockOneQues(block_num,0);
}

function getBlockOneQues(block_num,ques_num){

    remove_block_btn(block_num,ques_num);
    localStorage.setItem('current_survey_page',"blockQuesUrl_"+block_num+"_"+ques_num);    

    jsonObj_ques=JSON.parse(localStorage.getItem('json_question_str'));
    jsonObj_sps=JSON.parse(localStorage.getItem('json_sps_str'));
    school_index=localStorage.getItem('school_index');
    path_index=localStorage.getItem('path_index');
    
	var block_ques_len=jsonObj_ques.Questions.Blocks.length;
	var block_len=jsonObj_sps.Schools[school_index].Paths[path_index].num_block;
	var p_id=jsonObj_sps.Schools[school_index].Paths[path_index].p_id;
    var block_id=parseInt(block_num)+1;

    var json_answer_str=localStorage.getItem("json_answer_str");
    var json_localStorage=JSON.parse(json_answer_str);
    var option_ans_arr=new Array();

    //parse a_content and show the old answer
    for(var i=0;i<json_localStorage.Answers.length;i++){
    	if(json_localStorage.Answers[i].q_id==jsonObj_ques.Questions.Blocks[ques_num].q_id&&json_localStorage.Answers[i].block_id==block_id){
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
	
	var page = "<div data-role='page' data-url=blockQuesUrl_"+block_num+"_"+ques_num+" data-theme='c' id='blockQuesUrl_"+block_num+"_"+ques_num+"'><div data-role='header' data-theme='b'><a href='#' data-theme='b' data-icon='back' class='ui-btn-left' id='previous_block_ques_"+block_num+"_"+ques_num+"'>back</a><h1>" + "block"+ block_id+ "</h1><a href='#' data-theme='b' data-icon='forward' class='ui-btn-right' id='next_block_ques_"+block_num+"_"+ques_num+"'>next</a></div><div data-role='content'>";  

	var questions=jsonObj_ques.Questions;
	//alert(JSON.stringify(questions));

	var options=jsonObj_ques.Questions.Blocks[ques_num].options;

	page=page+'<label>'+questions.Blocks[ques_num].q_heading+'</label>';
	page=page+"<form>";
	if(questions.Blocks[ques_num].q_type==1){
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
    else if(questions.Blocks[ques_num].q_type==2){
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
    navbar_function();

    $("#reset_block_"+block_num+"_"+ques_num).click(function(){
        var newDialog=$(create_reset_block_dialog(block_num,ques_num));
        newDialog.appendTo($.mobile.pageContainer);
        this.href="#reset_block_dialog_"+block_num+"_"+ques_num;
        //alert($("#reset_yes"+"_"+block_num+"_"+ques_num).html());
        $("#reset_block_yes"+"_"+block_num+"_"+ques_num).click(function(){
            this.href="#homepage";
            location=this.href;
            reset();
            
        });
    });

    $("#previous_block_ques_"+block_num+"_"+ques_num).click(function(){
        var survey_index=localStorage.getItem("survey_index");
        var current_survey_page=localStorage.getItem('current_survey_page');
        if(ques_num==0){
            getCatagory(survey_index);
        }
        if(ques_num!=0){
            ques_num--;
            getBlockOneQues(block_num,ques_num);

        }
    });


    $("#next_block_ques_"+block_num+"_"+ques_num).click(function(){

        if(questions.Blocks[ques_num].q_type==1){
            var radio_name="option_"+block_num+"_"+ques_num;
       //alert(test);
            var radio_checked="input:radio[name='"+radio_name+"']:checked";
           
            var answer='{"q_id":"'+jsonObj_ques.Questions.Blocks[ques_num].q_id+'","block_id":"'+block_id+'","p_id":"'+p_id+'","a_content":"'+$(radio_checked).val()+'"}';
            //alert(answer);
            setBlockAnswer(ques_num,block_id,answer,p_id);
            

        }else if(questions.Blocks[ques_num].q_type==2){
            var checkbox_name="option_"+block_num+"_"+ques_num;
       //alert(test);
            var checkbox_checked="input:checkbox[name='"+checkbox_name+"']:checked";
            var a_content="";
            //alert(a_content);
            $(checkbox_checked).each(function(){
                a_content+=this.value+"_";
                //alert(this.value);
            });
            a_content=a_content.substring(0,a_content.length-1);
            //alert(a_content);

            var answer='{"q_id":"'+jsonObj_ques.Questions.Blocks[ques_num].q_id+'","block_id":"'+block_id+'","p_id":"'+p_id+'","a_content":"'+a_content+'"}';

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
                    alert("The following is next block questions!");
                    nextQues(block_num,ques_num);

     			}else{
     				alert("The following is tally questions");
                    getTallyQues(0);
                    
     				
     			}

     		}
     	}


    });
     //alert("234:"+ques_num);
}
//joinStr(json_answer_str,answer);


function nextQues(block_num,ques_num){
   getBlockOneQues(block_num,ques_num)
  
}


function setBlockAnswer(ques_num,block_id,answer,p_id){
    var searchStr='{"q_id":"'+jsonObj_ques.Questions.Blocks[ques_num].q_id+'","block_id":"'+block_id;

    var json_answer_str=localStorage.getItem("json_answer_str");

    if(json_answer_str.search(searchStr)==-1){
        json_answer_str=joinStr(json_answer_str,answer);
        localStorage.setItem("json_answer_str",json_answer_str);
        //alert(localStorage.getItem("json_answer_str"));
    }else{
        //replace the existing answer
        var json_localStorage=JSON.parse(json_answer_str);
        for(var i=0;i<json_localStorage.Answers.length;i++){
            if(json_localStorage.Answers[i].q_id==jsonObj_ques.Questions.Blocks[ques_num].q_id&&json_localStorage.Answers[i].block_id==block_id){

                var replaceStr='{"q_id":"'+jsonObj_ques.Questions.Blocks[ques_num].q_id+'","block_id":"'+block_id+'","p_id":"'+p_id+'","a_content":"'+json_localStorage.Answers[i].a_content+'"}';
                //alert(replaceStr);
                //alert(answer);//new answer
                json_answer_str=json_answer_str.replace(replaceStr,answer);
                localStorage.setItem("json_answer_str",json_answer_str);
                //alert(localStorage.getItem("json_answer_str"));
            }
        }
        
    }

}
