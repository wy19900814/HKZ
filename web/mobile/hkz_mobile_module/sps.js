var obj = null;
var schools = null;
var paths = null;
var sc_index = null;
var p_index = null;
var s_index = null;
function onLoad() {
    //location.reload();
    //opener.location.reload(); 
    getSchools();
}
function getSchools() {
    var ajax = new XMLHttpRequest();
    //ajax.open('GET',('https://api.usergrid.com/futuretravel/sandbox/londonplaces/?limit=999'));
    ajax.open('GET', ('http://localhost/mobileV1/test.php')); //is completed
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
            $('#school-list').empty();

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
                        $('#school-list').append('<li><a href="javascript:void(0)' + '" onclick="getPaths(\'' + i + '\')">' + schools[i].sch_name + '</a></li>');
                    }
                    flag = true;
                }
            }
            $('#school-list').listview('refresh');

        }
    }
}

function getPaths(sch_index) {

    //create the page html template
    sc_index = sch_index;
    paths = schools[sch_index].Paths;
    var page = "<div data-role='page' data-url=pathUrl_" + sch_index + " data-theme='c' data-rel='back' data-add-back-btn='true' id='pathlist'><div data-role='header' data-theme='b'><h1>" + "HKZ" + "</h1></div><div data-role='content'><ul data-role='listview' data-inset='false' id='path-list'>";

    for (var i = 0; i < paths.length; i++) {
        page = page + '<li><a href="javascript:void(0)" onclick="getSurveys(\'' + i + '\')">' + paths[i].p_name + '</a></li>';
    }
    page = page + "</ul></div></div>";

    var moviePage = $(page);
    /*$('#path-list').empty();
			$.each(obj.schools[sch_index].Paths, function(j, path){
					$('#path-list').append(generateMovieLink(path));
   				});
			$('#path-list').listview('refresh');*/

    //append the new page to the page container
    moviePage.appendTo($.mobile.pageContainer);
    //go to the newly created page
    $.mobile.changePage(moviePage);
}
function getSurveys(path_index) {
    p_index = path_index;
    var surveys = paths[path_index].Surveys;
    //alert(surveys.length);
    var page = "<div data-role='page' data-url=surveyUrl_" + sc_index + "_" + path_index + " data-theme='c' data-add-back-btn='true' id='surveylist'><div data-role='header' data-theme='b'><h1>" + "HKZ" + "</h1></div><div data-role='content'><ul data-role='listview' data-inset='false' id='survey-list'>";
    for (var i = 0; i < surveys.length; i++) {
        page = page + '<li><a href="javascript:void(0) onclick="#">' + surveys[i].s_name + '</a></li>';
    }
    page = page + "</ul></div></div>";
    var moviePage = $(page);
    /*$('#path-list').empty();
				$.each(obj.schools[sch_index].Paths, function(j, path){
						$('#path-list').append(generateMovieLink(path));
						});
				$('#path-list').listview('refresh');*/

    //append the new page to the page container
    moviePage.appendTo($.mobile.pageContainer);
    //go to the newly created page
    $.mobile.changePage(moviePage);
}