var obj = null;
var schools = null;
var paths = null;
var surveys=null;
var sc_index = null;
var p_index = null;
var s_index = null;
var num_b=0;
var catagory=null;
var record=new Array();
function getSchools() {
    var ajax = new XMLHttpRequest();
    //ajax.open('GET',('https://api.usergrid.com/futuretravel/sandbox/londonplaces/?limit=999'));
    ajax.open('GET', ('http://localhost/mobile/part3/test.php')); //is completed
    //ajax.open('GET',('http://localhost/mobileV1/testlack.php'));  //test no school
    //ajax.open('GET',('http://localhost/mobileV1/testlack1.php')); //test no path
    //ajax.open('GET',('http://localhost/mobileV1/testlack2.php'));  //test no survey
    //ajax.open('GET', ('http://localhost/mobileV1/getSPS.php'));
    ajax.send();
    //alert("hello");
    ajax.onreadystatechange = function() {

        if (ajax.readyState == 4 && (ajax.status == 200)) {
            //$('#test').append(ajax.responseText);
            obj = JSON.parse(ajax.responseText);
            //var schools=obj.entities;
            schools = obj.Schools;
            var flag = true;
            var page = "<div data-role='page' data-url=schoolUrl data-theme='c' data-rel='back' data-add-back-btn='true' id='pathlist'><div data-role='header' data-theme='b'><h1>" + "HKZ" + "</h1></div><div data-role='content'><ul data-role='listview' data-inset='false' id='school-list'>";

            if (schools.length < 1) {
                flag = false;
                alert("Sorry, there is no survey!!");
                return
            } else {
                for (var i = 0; i < schools.length; i++) {
                    if (schools[i].Paths.length < 1) {
                        flag = false;
                    } else {
                        for (var j = 0; j < schools[i].Paths.length; j++) {
                            if (schools[i].Paths[j].Surveys.length < 1) {
                                flag = false;
                            }
                        }
                    }
                   
                    if (flag) {
                        page = page + '<li><a href="javascript:void(0)" onclick="getPaths(\'' + i + '\')">' + schools[i].sch_name + '</a></li>';
                    }
                    flag = true;
                }
            }

            page = page + "</ul></div></div>";
            var newPage = $(page);
            newPage.appendTo($.mobile.pageContainer);
            $.mobile.changePage(newPage);
            

        }
    }
}

function getPaths(sch_index) {
    sc_index = sch_index;
    paths = schools[sch_index].Paths;
    var page = "<div data-role='page' data-url=pathUrl_" + sch_index + " data-theme='c' data-rel='back' data-add-back-btn='true' id='pathlist'><div data-role='header' data-theme='b'><h1>" + "HKZ" + "</h1></div><div data-role='content'><ul data-role='listview' data-inset='false' id='path-list'>";

    for (var i = 0; i < paths.length; i++) {
        page = page + '<li><a href="javascript:void(0)" onclick="getSurveys(\'' + i + '\')">' + paths[i].p_name + '</a></li>';
    }
    page = page + "</ul></div></div>";
    var newPage = $(page);
    newPage.appendTo($.mobile.pageContainer);
    $.mobile.changePage(newPage);
}
function getSurveys(path_index) {
    p_index = path_index;
    surveys = paths[path_index].Surveys;
    num_b=paths[p_index].num_block;
    //alert(surveys.length);
    var page = "<div data-role='page' data-url=surveyUrl_" + sc_index + "_" + path_index + " data-theme='c' data-add-back-btn='true' id='surveylist'><div data-role='header' data-theme='b'><h1>" + "HKZ" + "</h1></div><div data-role='content'><ul data-role='listview' data-inset='false' id='survey-list'>";
    for (var i = 0; i < surveys.length; i++) {
        page = page + '<li><a href="javascript:void(0)" onclick="getCatagory(\'' + i + '\')">' + surveys[i].s_name + '</a></li>';
    }
    page = page + "</ul></div></div>";
    var newPage = $(page);
    newPage.appendTo($.mobile.pageContainer);
    //go to the newly created page
    $.mobile.changePage(newPage);

}
function getCatagory(survey_index) {
    //alert(num_b);
    s_index = survey_index;    
    var page = "<div data-role='page' data-url=catagoryUrl data-theme='c' data-add-back-btn='true' id='catagory'><div data-role='header' data-theme='b'><h1>" + "HKZ" + "</h1></div><div data-role='content'><ul data-role='listview' data-inset='false' id='survey-list'>";
    for (var i = 0; i < num_b; i++) {
        page = page + '<li><a href="javascript:void(0)" onclick="getBlockQues(\'' + i + '\')">' + 'block' +i+ '</a></li>';
        //alert("hello");
    }
    page = page + '<li><a href="javascript:void(0)" onclick="getTallyQues()">' + 'Tally' +'</a></li>';
    page = page + '<li><a href="javascript:void(0)" onclick="getOtherQues()">' + 'Other' + '</a></li>';
    
    var ajax = new XMLHttpRequest();
    ajax.open('GET', ('http://localhost/mobile/part3/question_test.php')); 
    ajax.send();
    //alert("hello1");
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 4 && (ajax.status == 200)) { 
                  // alert(ajax.responseText); 
            catagory = JSON.parse(ajax.responseText);
           // num_tally=catagory.Questions[0].Tallies.length;
            //alert(num_tally);
           // questions = catagory.Questions;            
            page = page + "</ul></div></div>";
            var newPage = $(page);
            newPage.appendTo($.mobile.pageContainer);
            $.mobile.changePage(newPage);

        }
    }

}
function getBlockQues(block_num){
    getBlockOneQues(block_num,0);
}
function getBlockOneQues(block_num,ques_num){
    //var i=ques_num;  
    var page = "<div data-role='page' data-url=blockQuesUrl_"+block_num+"_"+ques_num+" data-theme='c' data-add-back-btn='true' id='blockques'><div data-role='header' data-theme='b'><h1>" + "HKZ" + "</h1><a href='#' data-theme='b' data-icon='forward' class='ui-btn-right' id='next_ques'>next</a></div><div data-role='content'>";  
    var questions=catagory.Questions;
    var block=catagory.Questions[0];
    //指的是block第i个问题
    var options=catagory.Questions[0].Blocks[ques_num].Options;
    
    page=page+'<label>'+questions[0].Blocks[ques_num].q_heading+'</label>';
    page=page+"<form>";
    if(questions[0].Blocks[ques_num].q_type==1){
    //q_type=1 means single_choice, q_type==2 means mutiple_choice
        for (var i = 0; i < options.length; i++) {
            page = page + '<input type=\"radio\" name=\"radio-choice-0\" id=\"radio-choice-0'+i+'\" value=\"on\" >';
            page=page+'<label for=\"radio-choice-0'+i+'\">'+options[i].o_text+'</label>';
            //alert("hello");
        }
    }

    else if(questions[0].Blocks[ques_num].q_type==2){
        for (var i = 0; i < options.length; i++) {
            page = page + '<input type=\"checkbox\" name=\"checkbox-choice-0\" id=\"checkbox-choice-0'+i+'\" value=\"on\" >';
            page=page+'<label for=\"checkbox-choice-0'+i+'\">'+options[i].o_text+'</label>';
            //alert("hello");
        }

    }
    ques_num++;
    page = page + "</form></div></div>";
    var newPage = $(page);
    newPage.appendTo($.mobile.pageContainer);

   
    $.mobile.changePage(newPage);

     $("#next_ques").click(function(){
        nextQues();
    });

}
function nextQues(){
   alert("good");
   <input type='button' value='button' onclick='getBlockOneQues("+block_num+","+ques_num+")'>
}

