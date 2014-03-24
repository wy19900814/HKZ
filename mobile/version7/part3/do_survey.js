var jsonObj_sps=null;
var jsonObj_ques=null;
var school_index=null;
var path_index=null;
var survey_index=null;
var json_answer_str="";

//local storage: 
//json_answer_str
//survey_index
//path_index
//school_index
//json_sps_str
//json_question_str

function getSchools(){
	$.blockUI({ css: { 
            border: 'none', 
            padding: '15px', 
            backgroundColor: '#000', 
            '-webkit-border-radius': '10px', 
            '-moz-border-radius': '10px', 
            opacity: .5, 
            color: '#fff' 
        } }); 
 
    setTimeout($.unblockUI, 500); 
    if($("#back_to_homepage").length>0){
        $("#back_to_homepage").remove();
    }
    if(localStorage.getItem('json_answer_str')==""||localStorage.getItem('json_answer_str')==null){
        json_answer_str="{\"Answers\":[]}";
        localStorage.setItem('json_answer_str',json_answer_str);
        //alert("hehl");
    }else{
        json_answer_str=localStorage.getItem('json_answer_str');
        //alert(localStorage.getItem('json_answer_str'));
        //alert("1");
    }
	//var url="http://localhost/mobile/part3/test2.php?jsoncallback=?";
	var url="http://letsallgetcovered.org/HKZ/school_path_survey.php?jsoncallback=?";

	//var url="http://localhost/mobile/part3/sps.php"

	$.getJSON(url,function(data){

		var flag=true;//path or school or survey=0

		jsonObj_sps=data;
		localStorage.setItem('json_sps_str',JSON.stringify(jsonObj_sps));
		
		var page="<div data-role='page' id='schoolUrl' data-url=schoolUrl data-theme='c' data-rel='back' ><div data-role='header' data-theme='b'><a href='#' data-theme='b' data-icon='back' class='ui-btn-left' id='back_to_homepage'>back</a><h1>" + "School List" + "</h1></div><div data-role='content'><ul data-role='listview' data-inset='false' id='school-list'>";

		//alert(jsonObj_sps.Schools.length);
		if(jsonObj_sps.Schools.length<1){
			alert("Sorry, there is no school!!");
			return;
		}
		for(var i=0;i<jsonObj_sps.Schools.length;i++){
			if(jsonObj_sps.Schools[i].Paths.length<1){
				flag=false;
			}else{
				for(var j=0;j<jsonObj_sps.Schools[i].Paths.length;j++){
					if(jsonObj_sps.Schools[i].Paths[j].Surveys.length<1){
						flag=false;
					}
				}
			}
			//alert(flag);
			if(flag){
				page+='<li><a href="javascript:void(0)" onclick="getPaths(\'' + i + '\')">' + jsonObj_sps.Schools[i].sch_name + '</a></li>';
			}
			flag=true;
			
		}
		page+="</ul></div>";
		//page=addNavbar(page);
		page+="</div>";
		
        var newPage = $(page);
        newPage.appendTo($.mobile.pageContainer);
        $.mobile.changePage(newPage);
        $("#back_to_homepage").click(function(){
        	this.href="#homepage";
        	this.remove();
        })

	});
}

function getPaths(sch_index){
	if($("#back_to_school_list").length>0){
		$("#back_to_school_list").remove();
	}

	school_index=sch_index;
	localStorage.setItem("school_index",sch_index);
	var paths=jsonObj_sps.Schools[sch_index].Paths;

	var page = "<div data-role='page' id='pathUrl_"+sch_index+"' data-url=pathUrl_" + sch_index + " data-theme='c' data-rel='back'><div data-role='header' data-theme='b'><a href='#' data-theme='b' data-icon='back' class='ui-btn-left' id='back_to_school_list'>back</a><h1>" + "Path List" + "</h1></div><div data-role='content'><ul data-role='listview' data-inset='false' id='path-list'>";

	for (var i = 0; i < paths.length; i++) {
        page = page + '<li><a href="javascript:void(0)" onclick="getSurveys(\'' + i + '\')">' + paths[i].p_name + '</a></li>';
    }
    
    page+="</ul></div>";
	//page=addNavbar(page);
	page+="</div>";
    var newPage = $(page);
    newPage.appendTo($.mobile.pageContainer);
    $.mobile.changePage(newPage);

    $("#back_to_school_list").click(function(){
    	getSchools();
    });

}

function getSurveys(pa_index){
	if($("#back_to_path_list").length>0){
		$("#back_to_path_list").remove();
	}

	path_index=pa_index;
	localStorage.setItem("path_index",pa_index);
	var path_id=jsonObj_sps.Schools[school_index].Paths[path_index].p_id;
	localStorage.setItem("path_id",path_id);

	var surveys=jsonObj_sps.Schools[school_index].Paths[path_index].Surveys;
	var num_b=jsonObj_sps.Schools[school_index].Paths[path_index].num_block;
	var page = "<div data-role='page' id='surveyUrl_"+path_index+"' data-url=surveyUrl_" + path_index + " data-theme='c'><div data-role='header' data-theme='b'><a href='#' data-theme='b' data-icon='back' class='ui-btn-left' id='back_to_path_list'>back</a><h1>" + "Survey List" + "</h1></div><div data-role='content'><ul data-role='listview' data-inset='false' id='survey-list'>";
    for (var i = 0; i < surveys.length; i++) {
        page = page + '<li><a href="javascript:void(0)" onclick="getCatagory(\'' + i + '\')">' + surveys[i].s_name + '</a></li>';
    }
    page+="</ul></div>";
	//page=addNavbar(page);
	page+="</div>";
    
    var newPage = $(page);
    newPage.appendTo($.mobile.pageContainer);
    //go to the newly created page
    $.mobile.changePage(newPage);

    $("#back_to_path_list").click(function(){
    	//alert("hello");
    	getPaths(localStorage.getItem('school_index'));
    });

}

function getCatagory(sur_index){
	$.blockUI({ css: { 
            border: 'none', 
            padding: '15px', 
            backgroundColor: '#000', 
            '-webkit-border-radius': '10px', 
            '-moz-border-radius': '10px', 
            opacity: .5, 
            color: '#fff' 
        } }); 
 
    setTimeout($.unblockUI, 500); 
    
	if($("#submit").length>0){
        $("#submit").remove();
        //alert("move submt");
    }
    if($("#back_to_surveys_catagory").length>0){
    	$("#back_to_surveys_catagory").remove();
        //alert("back_to_surveys_catagory");
    }
    
    //localStorage.setItem('current_survey_page',"catagoryUrl_"+sur_index);
	survey_index=sur_index;
	localStorage.setItem("survey_index",sur_index);

	school_index=localStorage.getItem("school_index");
	path_index=localStorage.getItem("path_index");
    //jsonObj_ques=JSON.parse(localStorage.getItem('json_question_str'));
	jsonObj_sps=JSON.parse(localStorage.getItem("json_sps_str"));

	var num_b=jsonObj_sps.Schools[school_index].Paths[path_index].num_block;
	localStorage.setItem('block_number',num_b);
	//alert(num_b);
	var page = "<div data-role='page' id='catagoryUrl_"+sur_index+"' data-url=catagoryUrl_"+sur_index+" data-theme='c'><div data-role='header' data-theme='b'><a href='#' data-theme='b' data-icon='back' class='ui-btn-left' id='back_to_surveys_catagory'>back</a><h1>" + "Catagory" + "</h1></div><div data-role='content'><ul data-role='listview' data-inset='false' id='survey-list'>";
    for (var i = 0; i < num_b; i++) {
    	var j=i+1;
        page = page + '<li><a href="javascript:void(0)" onclick="getBlockFirstQues(\'' + i + '\')">' + 'block' +j+ '</a></li>';
        //alert("hello");
    }
    page = page + '<li><a href="javascript:void(0)" onclick="getTallyQues(0)">' + 'Tally' +'</a></li>';
    page = page + '<li><a href="javascript:void(0)" onclick="getOtherQues(0)">' + 'Other' + '</a></li>';
    
    var url="http://letsallgetcovered.org/HKZ/request_questions.php?jsoncallback=?";

//{"s_id":"46790148","s_name":"HKZ_ONE"}

	//var url="http://localhost/version1/part3/question_test.php";
   
    var survey_id=jsonObj_sps.Schools[school_index].Paths[path_index].Surveys[survey_index].s_id;
    localStorage.setItem('survey_id',survey_id);
    //alert(survey_id);
    //{s_id:survey_id},
    //alert("hello");
    $.getJSON(url,{s_id:survey_id},function(data){
    	//alert("hello");
    	jsonObj_ques=data;
    	localStorage.setItem('json_question_str',JSON.stringify(jsonObj_ques));
    	//alert(localStorage.getItem('json_question_str'));

    	page+="</ul></div>";
		page =page + "<div data-position='fixed'>";
    	page=page+"<a id='submit'><button class='ui-btn' >Submit</button></a>";
		page+="</div>";
        var newPage = $(page);
        newPage.appendTo($.mobile.pageContainer);
        $.mobile.changePage(newPage);
        submit();
        $("#back_to_surveys_catagory").click(function(){
	    	getSurveys(localStorage.getItem('path_index'));
	    });

    });

    

}


