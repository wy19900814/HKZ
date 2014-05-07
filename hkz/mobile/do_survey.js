var json_sps;
var json_question;
var school_index;
var path_index;
var survey_index;
var json_answer_str;
var json_marker_str;

//alert("hh");
var json_marker;

function onload(){
	document.addEventListener("deviceready", onDeviceReady, false); 
}
function onDeviceReady() {
	document.addEventListener("backbutton", eventBackButton, false); 
}
function eventBackButton(){alert("Please press the back button on the screen");}

function getNewSurvey(){
    reset();

    var survey_index=localStorage.getItem('survey_index');
    //alert(survey_index);
    //alert(localStorage.getItem('ss'));
    if(survey_index!=""&&survey_index!=null){
        var r=confirm("There is a local survey now. Are you sure to start a new survey?");
        if(r==true){
            reset();
            if($("#schoolUrl").length>0&&localStorage.getItem("json_sps_str")!=""){
                location="#schoolUrl";
            }else{
                getSchools();
            }
        }else{
            location="#homepage";
        }
    }else{
        if($("#schoolUrl").length>0&&localStorage.getItem("json_sps_str")!=""){
            location="#schoolUrl";
        }else{
            getSchools();
        }
    }	
}
function getLocalSurvey(){
	var survey_index=localStorage.getItem('survey_index');
	if(survey_index==""){
        alert("There is no local survey! Please start a new survey.");
    }else{
    	get_all_index();
    	if($("#catagoryUrl_"+survey_index+"_"+path_index+"_"+school_index).length>0&&localStorage.getItem("json_question_str")!=""){
    		location="#catagoryUrl_"+survey_index+"_"+path_index+"_"+school_index;
    	}else{
    		getCatagory(survey_index);
    	}       
    }
}


function getSchools(){
	if($("#schoolUrl").length>0){
		$("#schoolUrl").remove();
	}
	if($("#back_to_homepage").length>0){
		$("#back_to_homepage").remove();
	}
	
	$.blockUI({ css: { 
            border: 'none', 
            padding: '15px', 
            backgroundColor: '#000', 
            '-webkit-border-radius': '10px', 
            '-moz-border-radius': '10px', 
            opacity: .5, 
            color: '#fff' 
        } }); 
    setTimeout($.unblockUI, 2500); 
    if(localStorage.getItem('json_answer_str')==""||localStorage.getItem('json_answer_str')==null){
        json_answer_str="{\"Answers\":[]}";
        localStorage.setItem('json_answer_str',json_answer_str);
    }else{
        json_answer_str=localStorage.getItem('json_answer_str');
    }
	//var url="http://localhost/mobile/part3/test2.php?jsoncallback=?";
	var url="http://letsallgetcovered.org/hkz/main/request_sps.php?jsoncallback=?";

	//var url="http://localhost/mobile/part3/sps.php"

	$.getJSON(url,function(data){
		json_sps=data;
		localStorage.setItem('json_sps_str',JSON.stringify(json_sps));
    
		var page="<div data-role='page' id='schoolUrl' data-url=schoolUrl data-theme='c' data-rel='back' ><div data-role='header' data-theme='b'><a href='#' data-theme='b' data-icon='back' class='ui-btn-left' id='back_to_homepage'>back</a><h1>" + "School List" + "</h1></div><div data-role='content'><ul data-role='listview' data-inset='false' id='school-list'>";
        //alert(JSON.stringify(json_sps));
		if(json_sps.Schools.length<1){
			alert("Sorry, there is no school!!");
			return;
		}
		//check the schools
		var flag=false;
        for(var i=0;i<json_sps.Schools.length;i++){
			if(json_sps.Schools[i].Paths.length>0){
				for(var j=0;j<json_sps.Schools[i].Paths.length;j++){
					if(json_sps.Schools[i].Paths[j].Surveys.length>0){
						flag=true;                        
					}
				}
			}
			if(flag){
				page+='<li><a href="javascript:void(0)" onclick="getPaths(\'' + i + '\')">' + json_sps.Schools[i].sch_name + '</a></li>';
			}else{
                alert("Sorry, please deploy surveys with paths!!");
                return;
            }
			flag=false;
		}
		page+="</ul></div>";
		page+="</div>";		
        var newPage = $(page);
        newPage.appendTo($.mobile.pageContainer);
        $.mobile.changePage(newPage);
        $("#back_to_homepage").click(function(){
        	this.href="#homepage";
        	//this.remove();
        })

	});
}

function getPaths(sch_index){
	if($("#back_to_school_list").length>0){
		$("#back_to_school_list").remove();
	}
	if($("#pathUrl_"+sch_index).length>0){
		$("#pathUrl_"+sch_index).remove();
	}
	school_index=sch_index;
	localStorage.setItem("school_index",school_index);
	
	var paths=json_sps.Schools[sch_index].Paths;
	var page = "<div data-role='page' id='pathUrl_"+sch_index+"' data-url=pathUrl_" + sch_index + " data-theme='c' data-rel='back'><div data-role='header' data-theme='b'><a href='#' data-theme='b' data-icon='back' class='ui-btn-left' id='back_to_school_list'>back</a><h1>" + "Path List" + "</h1></div><div data-role='content'><ul data-role='listview' data-inset='false' id='path-list'>";
	for (var i = 0; i < paths.length; i++) {
		if(json_sps.Schools[school_index].Paths[i].Surveys.length>0){	
			page = page + '<li><a href="javascript:void(0)" onclick="showPath(\'' + i + '\')">' + paths[i].p_name + '</a></li>';
		}
    }    
    page+="</ul></div>";
	page+="</div>";
    var newPage = $(page);
    newPage.appendTo($.mobile.pageContainer);
    $.mobile.changePage(newPage);

    $("#back_to_school_list").click(function(){
    	if($("#schoolUrl").length>0){
    		this.href="#schoolUrl";
    	}else{
    		getSchools();
    	}
    	//this.remove(); 	
    });
}
function showPath(pa_index){
    path_index=pa_index;
    localStorage.setItem("path_index",pa_index);
    school_index=localStorage.getItem("school_index");
    //alert(path_index);

    location="#directions_map";
    $("#do_survey").click(function(){
        if($("#surveyUrl_"+path_index+"_"+school_index).length>0){
           this.href="#surveyUrl_"+path_index+"_"+school_index;
        }else{
            getSurveys(pa_index);
        }
    });
    $("#reselect_path").click(function(){
        if($("#pathUrl_"+school_index).length>0){
            this.href="#pathUrl_"+school_index;
        }else{
            getPaths(school_index);
        } 
    });



}
function getSurveys(pa_index){

	path_index=pa_index;
	localStorage.setItem("path_index",pa_index);
	school_index=localStorage.getItem("school_index");
	
	if($("#back_to_path_list").length>0){
		$("#back_to_path_list").remove();
	}
	if($("#surveyUrl_"+path_index+"_"+school_index).length>0){
		$("#surveyUrl_"+path_index+"_"+school_index).remove();
	}
	
	var path_id=json_sps.Schools[school_index].Paths[path_index].p_id;
	localStorage.setItem("path_id",path_id);

	var surveys=json_sps.Schools[school_index].Paths[path_index].Surveys;
	var num_b=json_sps.Schools[school_index].Paths[path_index].num_block;
	var page = "<div data-role='page' id='surveyUrl_"+path_index+"_"+school_index+"' data-url=surveyUrl_" + path_index +"_"+school_index +" data-theme='c'><div data-role='header' data-theme='b'><a href='#' data-theme='b' data-icon='back' class='ui-btn-left' id='back_to_path_list'>back</a><h1>" + "Survey List" + "</h1><a href='#' data-theme='b' data-icon='forward' class='ui-btn-right' id='view_path'>View path</a></div><div data-role='content'><ul data-role='listview' data-inset='false' id='survey-list'>";
    for (var i = 0; i < surveys.length; i++) {
        page = page + '<li><a href="javascript:void(0)" onclick="getCatagory(\'' + i + '\')">' + surveys[i].s_name + '</a></li>';
    }
    page+="</ul></div>";
	page+="</div>";
    
    var newPage = $(page);
    newPage.appendTo($.mobile.pageContainer);
    $.mobile.changePage(newPage);

    $("#back_to_path_list").click(function(){
    	if($("#pathUrl_"+school_index).length>0){
    		this.href="#pathUrl_"+school_index;
    	}else{
    		getPaths(school_index);
    	} 	
    	//this.remove();
    });
    $("#view_path").click(function(){
        showPath(pa_index);
    });
}

function getCatagory(sur_index){
    var old_survey_index=localStorage.getItem("survey_index");
    var new_survey_index=sur_index;
    if(old_survey_index!=new_survey_index){
        localStorage.setItem("json_answer_str","{\"Answers\":[]}");
    }
    survey_index=sur_index;    
    localStorage.setItem("survey_index",survey_index);
    school_index=localStorage.getItem("school_index");
    path_index=localStorage.getItem("path_index");
    json_sps=JSON.parse(localStorage.getItem("json_sps_str"));

	if($("#submit").length>0){
		$("#submit").remove();
	}
	if($("#back_to_surveys_list").length>0){
		$("#back_to_surveys_list").remove();
	}
	
	$.blockUI({ css: { 
        border: 'none', 
        padding: '15px', 
        backgroundColor: '#000', 
        '-webkit-border-radius': '10px', 
        '-moz-border-radius': '10px', 
        opacity: .5, 
        color: '#fff' 
    } }); 
    setTimeout($.unblockUI, 1500); 

	if($("#catagoryUrl_"+survey_index+"_"+path_index+"_"+school_index).length>0){
		$("#catagoryUrl_"+survey_index+"_"+path_index+"_"+school_index).remove();
    }
	
	var num_b=json_sps.Schools[school_index].Paths[path_index].num_block;
	localStorage.setItem('block_number',num_b);
	var page = "<div data-role='page' id='catagoryUrl_"+survey_index+"_"+path_index+"_"+school_index+"' data-url=catagoryUrl_"+survey_index+"_"+path_index+"_"+school_index+" data-theme='c'><div data-role='header' data-theme='b'><a href='#' data-theme='b' data-icon='back' class='ui-btn-left' id='back_to_surveys_list'>back</a><h1>" + "Catagory" + "</h1></div><div data-role='content'><ul data-role='listview' data-inset='false' id='survey-list'>";  
    var url="http://letsallgetcovered.org/hkz/main/request_questions.php?jsoncallback=?";

    var survey_id=json_sps.Schools[school_index].Paths[path_index].Surveys[survey_index].s_id;
    localStorage.setItem('survey_id',survey_id);
   
    $.getJSON(url,{s_id:survey_id},function(data){
    	json_question=data;
    	localStorage.setItem('json_question_str',JSON.stringify(json_question));
    
    	for (var i = 0; i < num_b&&json_question.Questions.Blocks.length>0; i++) {
        	var j=i+1;
            if($("#block"+j).length>0){
                $("#block"+j).remove();
                //alert("remove1");
            }
            page = page + '<li><a href="javascript:void(0)" id="block'+j+'" onclick="getBlockFirstQues(\'' + i + '\')">' + 'block' +j+ '</a></li>';
            //alert("hello");
        }    	
    	if(json_question.Questions.Tallies.length>0){
            if($("#Tally").length>0){
                $("#Tally").remove();
                //alert("remove2");
            }
    		page = page + '<li><a href="javascript:void(0)" id="Tally" onclick="getTallyQues(0)">' + 'Tally' +'</a></li>';
    	}
    	if(json_question.Questions.Others.length>0){
            if($("#Other").length>0){
                $("#Other").remove();
                //alert("remove3");
            }
    		page = page + '<li><a href="javascript:void(0)" id="Other" onclick="getOtherQues(0)">' + 'Other' + '</a></li>';
    	}
    	if(json_question.Questions.Blocks.length==0&&json_question.Questions.Tallies.length==0&&json_question.Questions.Others.length==0){
    		alert("There is no questions in this local survey! Please start a new survey.");
    	}else{
    		page+="</ul></div>";
    		page =page + "<div data-position='fixed'>";
        	page=page+"<a id='submit'><button class='ui-btn' >Submit</button></a>";
    		page+="</div>";
            var newPage = $(page);
            newPage.appendTo($.mobile.pageContainer);           
            $.mobile.changePage(newPage);
            submit();
            $("#back_to_surveys_list").click(function(){
            	if($("#surveyUrl_"+path_index+"_"+school_index).length>0&&$("#back_to_path_list").length>0){
            		this.href="#surveyUrl_"+path_index+"_"+school_index;
                    //alert("EEE");
            	}else{
            		this.href="#homepage";
            	}
            	//this.remove();
    	    });

            var json_answer_str=localStorage.getItem("json_answer_str");
            var json_answer=JSON.parse(json_answer_str);
            //alert(json_answer_str);
            var question_arr=new Array();
           // alert(question_arr[0]);
            //if(json_answer.Answers[qi])
            for(var k=0;k<num_b+1;k++){
                question_arr[k+1]=0//question_arr[1] means No. 1 block
                for(var qi=0;qi<json_answer.Answers.length;qi++){
                    if(json_answer.Answers[qi].block_id==k+1&&json_answer.Answers[qi].a_content!=""&&json_answer.Answers[qi].a_content!="undefined"){
                        question_arr[k+1]++;
                    }
                    if(json_answer.Answers[qi].block_id==0&&json_answer.Answers[qi].a_content!=""&&json_answer.Answers[qi].a_content!="undefined"){
                        question_arr[num_b+1]++;
                    }
                }
                if(question_arr[k+1]==json_question.Questions.Blocks.length&&old_survey_index==new_survey_index){
                    $("#block"+parseInt(k+1)).css("color","red");
                }else{
                    $("#block"+parseInt(k+1)).css("color","black");
                }
                if(question_arr[num_b+1]==json_question.Questions.Others.length+json_question.Questions.Tallies.length&&old_survey_index==new_survey_index){
                    $("#Tally").css("color","red");
                    $("#Other").css("color","red");
                }else{
                    $("#Tally").css("color","black");
                    $("#Other").css("color","black");
                }
            }

            
    	}
    });
}


