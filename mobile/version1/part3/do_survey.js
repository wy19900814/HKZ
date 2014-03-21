var jsonObj_sps=null;
var jsonObj_ques=null;
var school_index=null;
var path_index=null;
var survey_index=null;
var jsonAnswerStr="";
//var questionStr="";
//localStorage.setItem('jsonAnswerStr',jsonAnswerStr);


function getSchools(){
    if(localStorage.getItem('jsonAnswerStr')==""||localStorage.getItem('jsonAnswerStr')==null){
        jsonAnswerStr="{\"Answers\":[]}";
        localStorage.setItem('jsonAnswerStr',jsonAnswerStr);
        //alert("hehl");
    }else{
        jsonAnswerStr=localStorage.getItem('jsonAnswerStr');
        alert(localStorage.getItem('jsonAnswerStr'));
        //alert("1");
    }
	//var url="http://localhost/mobile/part3/test2.php?jsoncallback=?";
	var url="http://letsallgetcovered.org/lets6502/hkz/mobile/school_path_survey.php?jsoncallback=?";

	//var url="http://localhost/mobile/part3/sps.php"

	$.getJSON(url,function(data){

		var flag=true;//path or school or survey=0

		jsonObj_sps=data;
		localStorage.setItem('json_sps_str',JSON.stringify(jsonObj_sps));
		
		var page="<div data-role='page' id='schoolUrl' data-url=schoolUrl data-theme='c' data-rel='back' data-add-back-btn='true' id='pathlist'><div data-role='header' data-theme='b'><h1>" + "HKZ" + "</h1></div><div data-role='content'><ul data-role='listview' data-inset='false' id='school-list'>";

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


	});
}

function getPaths(sch_index){
	school_index=sch_index;
	localStorage.setItem("school_index",sch_index);
	var paths=jsonObj_sps.Schools[sch_index].Paths;

	var page = "<div data-role='page' id='pathUrl_"+sch_index+"' data-url=pathUrl_" + sch_index + " data-theme='c' data-rel='back' data-add-back-btn='true'><div data-role='header' data-theme='b'><h1>" + "HKZ" + "</h1></div><div data-role='content'><ul data-role='listview' data-inset='false' id='path-list'>";

	for (var i = 0; i < paths.length; i++) {
        page = page + '<li><a href="javascript:void(0)" onclick="getSurveys(\'' + i + '\')">' + paths[i].p_name + '</a></li>';
    }
    
    page+="</ul></div>";
	//page=addNavbar(page);
	page+="</div>";
    var newPage = $(page);
    newPage.appendTo($.mobile.pageContainer);
    $.mobile.changePage(newPage);


}

function getSurveys(pa_index){
	path_index=pa_index;
	localStorage.setItem("path_index",pa_index);
	var surveys=jsonObj_sps.Schools[school_index].Paths[path_index].Surveys;
	var num_b=jsonObj_sps.Schools[school_index].Paths[path_index].num_block;
	var page = "<div data-role='page' id='surveyUrl_"+path_index+"' data-url=surveyUrl_" + path_index + " data-theme='c' data-add-back-btn='true' id='surveylist'><div data-role='header' data-theme='b'><h1>" + "HKZ" + "</h1></div><div data-role='content'><ul data-role='listview' data-inset='false' id='survey-list'>";
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

}

function getCatagory(sur_index){
	if($("#submit").length>0){
        $("#submit").remove();
        alert("move submt");
    }
    
    /*if($("submit_dialog").length>0){
    	$("#submit").remove();
        alert("move submt page");
    }*/
	survey_index=sur_index;
	localStorage.setItem("survey_index",sur_index);
	//alert(localStorage.getItem("json_sps_str"));
	school_index=localStorage.getItem("school_index");
	//alert(school_index);
	path_index=localStorage.getItem("path_index");
	//alert(path_index);
	jsonObj_sps=JSON.parse(localStorage.getItem("json_sps_str"));
	var num_b=jsonObj_sps.Schools[school_index].Paths[path_index].num_block;
	localStorage.setItem('block_number',num_b);
	//alert(num_b);
	var page = "<div data-role='page' id='catagoryUrl_"+sur_index+"' data-url=catagoryUrl_"+sur_index+" data-theme='c' data-add-back-btn='true' id='catagory'><div data-role='header' data-theme='b'><h1>" + "HKZ" + "</h1></div><div data-role='content'><ul data-role='listview' data-inset='false' id='survey-list'>";
    for (var i = 0; i < num_b; i++) {
        page = page + '<li><a href="javascript:void(0)" onclick="getBlockFirstQues(\'' + i + '\')">' + 'block' +i+ '</a></li>';
        //alert("hello");
    }
    page = page + '<li><a href="javascript:void(0)" onclick="getTallyQues(0)">' + 'Tally' +'</a></li>';
    page = page + '<li><a href="javascript:void(0)" onclick="getOtherQues(0)">' + 'Other' + '</a></li>';
    
    //var url="http://letsallgetcovered.org/lets6502/hkz/mobile/request_questions.php?jsoncallback=?";

//{"s_id":"46790148","s_name":"HKZ_ONE"}

	var url="http://localhost/mobile/part3/question_test.php";
   
    var survey_id=jsonObj_sps.Schools[school_index].Paths[path_index].Surveys[survey_index].s_id;
    //alert(survey_id);

    $.getJSON(url,function(data){
    	jsonObj_ques=data;
    	localStorage.setItem('json_question_str',JSON.stringify(jsonObj_ques));
    	
    	page+="</ul></div>";
		page =page + "<div data-position='fixed'>";
    	page=page+"<a id='submit'><button class='ui-btn' >Submit</button></a>";
		page+="</div>";
        var newPage = $(page);
        newPage.appendTo($.mobile.pageContainer);
        $.mobile.changePage(newPage);
        submit();

    })

}


