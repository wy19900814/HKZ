
$('#page-map').live("pageinit", function() {
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

    if(localStorage.getItem('json_marker_str')==""||localStorage.getItem('json_marker_str')==null){
        json_marker_str="{\"Markers\":[]}";
        localStorage.setItem("json_marker_str",json_marker_str);
    }
    //$('#map_canvas').gmap().bind('init', function(evt, map) { 
                                                                                                                                                                                                                               
    //});
    // Define a default location and create the map
    var defaultLoc = new google.maps.LatLng(32.802955, -96.769923);
    $('#map_canvas').gmap( { 'center': defaultLoc, 'zoom' : 14, 'zoomControloptions': {'position':google.maps.ControlPosition.LEFT_TOP} })
    .bind('init', function(evt, map) {
        // Try to get current location to center on, else stay at defaultLoc
        $('#map_canvas').gmap('getCurrentPosition', function(pos, status) {
            if (status === "OK") {
                
                //$('#map_canvas').gmap('option', 'center', latLng);
                json_sps=JSON.parse(localStorage.getItem("json_sps_str"));
                school_index=localStorage.getItem("school_index");
                path_index=localStorage.getItem("path_index");
                var path=json_sps.Schools[school_index].Paths[path_index];
                var s_latlng = path.s_latitude+','+path.s_longtitude;
                var e_latlng = path.e_latitude+','+path.e_longtitude;
                //alert(localStorage.getItem('json_marker_str'));
                $('#map_canvas').gmap('displayDirections', { 'origin': s_latlng, 'destination': e_latlng, 'travelMode': google.maps.DirectionsTravelMode.WALKING }, { 'panel': document.getElementById('panel') }, function(result, status) {
                    if ( status === 'OK' ) {
                        var marker_id;
                        var current_pos=pos.coords.latitude+","+pos.coords.longitude;
                        $('#map_canvas').gmap('addMarker', { /*id:'m_1',*/ 'position': current_pos, 'bounds': true } ).click(function(){$('#map_canvas').gmap('openInfoWindow', { 'content': 'Your position' }, this);});
                        json_marker=JSON.parse(localStorage.getItem('json_marker_str'));
                        $.each(json_marker.Markers,function(i,marker){
                            var marker_pos=marker.m_latitude+","+marker.m_longtitude;
                            var marker_title=marker.title;
                            var marker_comment=marker.comment;
                            //alert(localStorage.getItem('json_marker_str'));
                            //console.log("comment:"+marker_comment);
                            $('#map_canvas').gmap('addMarker', { /*id:'m_1',*/ 'position': marker_pos, 'bounds': true },function(map,marker){
                                
                                var markerId = marker.__gm_id;
                                
                                
                                $('#markerdiv').append('<div class="mclass' + markerId + '" style="display:none; width:90%">'                               
                                  + '<div data-role="fieldcontain"><label for="tag' + markerId + '" class="map_text">Marker Title<br/></label><input type="text" class="map_text" maxlength="30" name="tag' + markerId + '" id="tag' + markerId + '" value="'+marker_title+'" /></div>'
                                  + '<div data-role="fieldcontain"><label for="address' + markerId + '" class="map_text">Address<br/></label><input type="text" class="map_text" maxlength="30" name="address' + markerId + '" id="address' + markerId + '" value="" /></div>'
                                  + '<div data-role="fieldcontain"><label for="state' + markerId + '" class="map_text">City, State<br/></label><input type="text" class="map_text" maxlength="30" name="state' + markerId + '" id="state' + markerId + '" value="" /></div>'
                                  + '<div data-role="fieldcontain"><label for="comment' + markerId + '" class="map_text">Comment<br/></label><textarea maxlength="64" class="map_text" rows=5 name="comment' + markerId + '" id="comment' + markerId + '" value="" /></textarea></div>'                              
                                  + '</div>');
                                $("#comment"+markerId).val(marker_comment);
                                //alert($("#comment"+markerId).val());
                                $('#map_canvas').gmap('search', { 'location': marker.getPosition() }, function(results, status) {
                                    if ( status === 'OK' ) {
                                        var addr = results[0].formatted_address.split(', ', 4);    
                                        //alert(addr);
                                                
                                        $('#address' + marker.__gm_id).val(addr[0]);
                                        //alert($('#address' + marker.__gm_id).val()); 
                                        $('#state' + marker.__gm_id).val(addr[1] + ", " + addr[2]);
                                        $('#li-placeholder').css('display', 'none'); 
                                        $('<li id="item' + markerId + '"><a href="#page-map"><h4>' +
                                        (($('#tag' + markerId).val() !== "") ? $('#tag' + markerId).val() : ('Marker ' + markerId)) +
                                        '</h4><p>' + $('#address' + markerId).val() + '<br/>' +
                                        $('#state' + markerId).val() + 
                                        '<br/>' + marker.getPosition() + '<br/>' +
                                        $('#comment'+ markerId).val() +
                                        '</p></a><a href="#page-map" data-direction="reverse" id="edit' + markerId + '">Edit</a></li>').appendTo('ul#marker-list');
                                    } 
                                }); 
                                 
                               
                                // Bind click handler: center map on the selected marker or open dialog to edit
                                $('li#item' + markerId).click( function() {
                                    $('#map_canvas').gmap('option', 'center', marker.getPosition());
                                    marker.setAnimation(google.maps.Animation.DROP);
                                });
                                $('#edit' + markerId).click( function() {
                                    $('#map_canvas').gmap('option', 'center', marker.getPosition());
                                    $.mobile.changePage($('#page-map'));
                                    openMarkerDialog(marker);
                                    return true;
                                });
                                              
                                try {
                                    $("ul#marker-list").listview('refresh');
                                } catch(e) { }   

                            }).click(function(){
                                openMarkerDialog(this);     
                            });  

                        })
                    }
                });
               
            } else {
                fadingMsg ("<span style='color:#f33;'>Error</span> while getting location. Device GPS/location may be disabled.");
            }                    
        }, { timeout: 6000, enableHighAccuracy: true } );
        
        $('#map_canvas').gmap('addControl', 'controls', google.maps.ControlPosition.BOTTOM_CENTER);
        document.getElementById('controls').style.display = 'inline';
            
        // attach map click handler and marker event handlers
        $(map).click( function(event) {
            $('#map_canvas').gmap('option', 'center', event.latLng);
            $('#map_canvas').gmap('addMarker', {
				'position': event.latLng, 
				'draggable': true, 
				'bounds': false
			},function(map, marker) {
				var markerId = marker.__gm_id;
                //alert(markerId);
                $('#markerdiv').append('<div class="mclass' + markerId + '" style="display:none; width:90%">'                               
                  + '<div data-role="fieldcontain"><label for="tag' + markerId + '" class="map_text">Marker Title<br/></label><input type="text" maxlength="30" name="tag' + markerId + '" id="tag' + markerId + '" value="" class="map_text" /></div>'
                  + '<div data-role="fieldcontain"><label for="address' + markerId + '" class="map_text">Address<br/></label><input type="text" maxlength="30" name="address' + markerId + '" id="address' + markerId + '" value="" class="map_text"/></div>'
                  + '<div data-role="fieldcontain"><label for="state' + markerId + '" class="map_text">City, State<br/></label><input type="text" maxlength="30" name="state' + markerId + '" id="state' + markerId + '" value="" class="map_text"/></div>'
                  + '<div data-role="fieldcontain"><label for="comment' + markerId + '" class="map_text">Comment<br/></label><textarea maxlength="64" rows=5 name="comment' + markerId + '" id="comment' + markerId + '" value="" class="map_text"/></textarea></div>'                              
                  + '</div>');
                getGeoData(marker);
				}).dragend( function() {
	                 getGeoData(this);
	             }).click(function(){
				    openMarkerDialog(this);
			    });
        });
        
        $('#return_to_survey').click(function(){
            var arr=localStorage.getItem("current_survey_page").split("_");
            if(arr[0]=="blockQuesUrl"){
                getBlockOneQues(arr[1],arr[2]);
            }
            else if(arr[0]=="TallyQuesUrl"){
                getTallyQues(arr[1]);
            }else if(arr[0]=="OtherQuesUrl"){                
                getOtherQues(arr[1]);
            }
        });

        $('#current_pos_marker').click( function() {
            $('#mask').css({'width':screen.width,'height':screen.height});
            $('#mask').fadeTo("slow",0.6);

            fadingMsg ("Using device geolocation service to find location.");
                
            // See extension defined in jquery.mobile/jquery.ui.map.extensions.js
            $('#map_canvas').gmap('getCurrentPosition', function(pos, status) {
                if (status === "OK") {
                    var latLng = new google.maps.LatLng(pos.coords.latitude, pos.coords.longitude);
                    $('#map_canvas').gmap('option', 'center', latLng);
                    $('#fadingmsg').remove();
                    
                    $('#map_canvas').gmap('addMarker', {
        				'position': latLng, 
        				'draggable': true, 
        				'bounds': false
        			},function(map, marker) {
        				var markerId = marker.__gm_id;
                        $('#markerdiv').append('<div class="mclass' + markerId + '" style="display:none; width:90%">'                               
                          + '<div data-role="fieldcontain"><label for="tag' + markerId + '" class="map_text">Marker Title<br/></label><input type="text" class="map_text" maxlength="30" name="tag' + markerId + '" id="tag' + markerId + '" value="" /></div>'
                          + '<div data-role="fieldcontain"><label for="address' + markerId + '" class="map_text">Address<br/></label><input type="text" class="map_text" maxlength="30" name="address' + markerId + '" id="address' + markerId + '" value="" /></div>'
                          + '<div data-role="fieldcontain"><label for="state' + markerId + '" class="map_text">City, State<br/></label><input type="text" class="map_text" maxlength="30" name="state' + markerId + '" id="state' + markerId + '" value="" /></div>'
                          + '<div data-role="fieldcontain"><label for="comment' + markerId + '" class="map_text">Comment<br/></label><textarea maxlength="64" class="map_text" rows=5 name="comment' + markerId + '" id="comment' + markerId + '" value="" /></textarea></div>'                              
                          + '</div>');
                        getGeoData(marker);
        				}).dragend( function() {
        	                 getGeoData(this);
        	             }).click(function(){
        				openMarkerDialog(this);     
        			});        
                    
                } else {
                    fadingMsg ("<span style='color:#f33;'>Error</span> while getting current location. Not supported in browser or GPS/location disabled.");
                    $('#mask').hide();                        
                }    
                $('#current_pos_marker').removeClass("ui-btn-active");
            }, { timeout: 6000, enableHighAccuracy: true } );
        });           
    }); // end .bind
        
        
    function getGeoData (marker) {
        // Make Reverse Geocoding request (latlng to address)
        // Note: 'region' option not used here, include for region code biasing
        $('#map_canvas').gmap('search', { 'location': marker.getPosition() }, function(results, status) {
            if ( status === 'OK' ) {
                var addr = results[0].formatted_address.split(', ', 4);                     
                $('#address' + marker.__gm_id).val(addr[0]);
                $('#state' + marker.__gm_id).val(addr[1] + ", " + addr[2]);
                //openMarkerDialog(marker);
            } else {
                fadingMsg('Unable to get GeoSearch data.');
               // openMarkerDialog(marker);
            }
        }); 
    }
                
    function openMarkerDialog(marker) {
        var markerId = marker.__gm_id;
        var lastAddress = $('#address' + markerId).val(), lastCityState = $('#state' + markerId).val(); 
        
        $('#mask').css({'width':screen.width,'height':screen.height});
        $('#mask').fadeTo("slow",0.6);  
            
        // Remove this marker and placeholder from ul#marker-list
        $('li#item' + markerId).remove();
        $('#li-placeholder').css('display', 'none');
                           
        $("<div class='ui-overlay ui-overlay-shadow ui-body-e ui-corner-all mds-editsave' id='dialog" + markerId + "'></div>")
         .css({ 'display': 'block', 'opacity': '0.9', 'z-index' : '9999999' })
        .append('<h6 style="margin:0.2em;">Edit &amp; Save Marker</h6>')
        .append( $('div.mclass'+markerId).css({ 'display': 'block'})
                .append('<div data-inline="true" id="dialog-btns" ><a id="remove" class="mbtn">Remove</a>' +
                        '<a id="save" class="mbtn">&nbsp;&nbsp;&nbsp;Save&nbsp;&nbsp;&nbsp;</a></div>') )
                        .appendTo( $.mobile.pageContainer );

       
        $('#remove').click( function () {
            // If list is empty, show placeholder text again
            if ($('ul#marker-list').find('li').length === 2) {
                $('#li-placeholder').css('display', 'block'); 
            }
                
            // remove marker from map
            marker.setMap(null);                            
            // Remove entire dialog, including div mclass{id} (the marker data)
            $('#dialog' + markerId).remove();
            $('#mask').hide();
            operate_str(marker,"remove");
        });

        $('#save').click( function () {
            marker.set('labelContent', 
                    (($('#tag' + markerId).val() !== "") ? $('#tag' + markerId).val() : ('Marker ' + markerId)));
                
            // Remove Save and Remove buttons
            $('#dialog-btns').remove();
                        
            // Store the div mclass{id} in markerdiv
            $('.mclass' + markerId)
            .css({ 'display': 'none'})
            .appendTo('#markerdiv');

            // Test if user changed any part of marker address
            if ( (lastAddress !== $('#address' + markerId).val()) ||
                    (lastCityState !== $('#state' + markerId).val() ) ) {
                    
                // Make Geocoding requestion (commas-separated address to lat/lng)
                $('#map_canvas').gmap('search', { 'address': $('#address' + markerId).val() + ', ' +
                    $('#state' + markerId).val() }, 
                    function(results, status) {
                        if ( status === 'OK' ) {
                            marker.setPosition(results[0].geometry.location);
                            $('#map_canvas').gmap('option', 'center', results[0].geometry.location);
                        } else {
                            fadingMsg('Unable to get GeoSearch data. Marker remains in same place.');
                        }
                    });                
            }
                
            // Put marker info in ul#marker-list on page-marker 
            $('<li id="item' + markerId + '"><a href="#page-map"><h4>' +
                    (($('#tag' + markerId).val() !== "") ? $('#tag' + markerId).val() : ('Marker ' + markerId)) +
                    '</h4><p>' + $('#address' + markerId).val() + '<br/>' +
                    $('#state' + markerId).val() + 
                    '<br/>' + marker.getPosition() + '<br/>' +
                    $('#comment'+ markerId).val() +
                    '</p></a><a href="#page-map" data-direction="reverse" id="edit' + markerId + '">Edit</a></li>').appendTo('ul#marker-list');
                
            // Bind click handler: center map on the selected marker or open dialog to edit
            $('li#item' + markerId).click( function() {
                $('#map_canvas').gmap('option', 'center', marker.getPosition());
                marker.setAnimation(google.maps.Animation.DROP);
            });
            $('#edit' + markerId).click( function() {
                $('#map_canvas').gmap('option', 'center', marker.getPosition());
                $.mobile.changePage($('#page-map'));
                openMarkerDialog(marker);
                return true;
            });
                          
            try {
                $("ul#marker-list").listview('refresh');
            } catch(e) { }
                
            // Remove the remaining bits of dialog and mask
            $('#dialog' + markerId).remove();
            $('#mask').hide();
            //save as json
            operate_str(marker,"replace");
            
            data = {title: 	(($('#tag' + markerId).val() !== "") ? $('#tag' + markerId).val() : ('Marker ' + markerId)),
            	    position:	String(marker.getPosition()),
            	    comment:	 $('#comment'+ markerId).val(),
                    survey_id: localStorage.getItem('survey_id'),
                    path_id: localStorage.getItem('path_id') };
           /* $.post("http://letsallgetcovered.org/lets6502/hkz_v1/main/store_markers.php", data, function(data){
            	fadingMsg(data);
               
            });*/
        });

        $('#mask').click( function() {
            // If user taps mask, save marker data as is by default
            // Alternative: remove by default: $('#remove').trigger('click');
           // $('#save').trigger('click');
        });            
    }
});

$('#page-marker').live("pageshow", function() {
    try {
        $("ul#marker-list").listview('refresh');
    } catch(e) { }
});

function operate_str(marker,operation){
    var markerId=marker.__gm_id;
    var title=(($('#tag' + markerId).val() !== "") ? $('#tag' + markerId).val() : ('Marker ' + markerId));
    var position=String(marker.getPosition());
    var pos_arr=position.split(",");

    var m_latitude=pos_arr[0].substring(1);
    var m_longtitude=pos_arr[1].substring(0,pos_arr[1].length-1);
    var comment= $('#comment'+ markerId).val();
    var s_id=localStorage.getItem('survey_id');
    var p_id=localStorage.getItem('path_id');
    //alert(title);
    //var json_marker_str="{\"Markers\":[]}";
    var one_marker_str="{\"m_latitude\":\""+m_latitude+"\",\"m_longtitude\":\""+m_longtitude+"\",\"s_id\":\""+s_id+"\",\"p_id\":\""+p_id+"\",\"comment\":\""+comment+"\",\"title\":\""+title+"\"}";
    //alert(one_marker_str);
    var part_str="{\"m_latitude\":\""+m_latitude+"\",\"m_longtitude\":\""+m_longtitude+"\",\"s_id\":\""+s_id+"\",\"p_id\":\""+p_id+"\"";
    json_marker_str=localStorage.getItem("json_marker_str");
    
    //alert("old"+json_marker_str);
    
    json_marker=JSON.parse(json_marker_str);
    //alert(json_marker_str);
    if(operation=="replace"){
        if(json_marker_str.search(part_str)==-1){
            json_marker_str=joinStr(json_marker_str,one_marker_str);
        }else{
            //replace the existing answer
            var json_marker=JSON.parse(json_marker_str);
            for(var i=0;i<json_marker.Markers.length;i++){
                if(json_marker.Markers[i].m_latitude==m_latitude&&json_marker.Markers[i].m_longtitude==m_longtitude&&json_marker.Markers[i].s_id==s_id&&json_marker.Markers[i].p_id==p_id){
                    var replace_one_marker_str="{\"m_latitude\":\""+m_latitude+"\",\"m_longtitude\":\""+m_longtitude+"\",\"s_id\":\""+s_id+"\",\"p_id\":\""+p_id+"\",\"comment\":\""+json_marker.Markers[i].comment+"\",\"title\":\""+json_marker.Markers[i].title+"\"}";
                    //alert(replace_one_marker_str);
                   // alert(one_marker_str);//new answer
                    
                    json_marker_str=json_marker_str.replace(replace_one_marker_str,one_marker_str);
                }
            }      
        }
    }else if(operation=="remove"){
        
        if(json_marker_str.search(part_str)!=-1){
            for(var i=0;i<json_marker.Markers.length;i++){
                if(json_marker.Markers[i].m_latitude==m_latitude&&json_marker.Markers[i].m_longtitude==m_longtitude&&json_marker.Markers[i].s_id==s_id&&json_marker.Markers[i].p_id==p_id){
                    var remove_one_marker_str="{\"m_latitude\":\""+m_latitude+"\",\"m_longtitude\":\""+m_longtitude+"\",\"s_id\":\""+s_id+"\",\"p_id\":\""+p_id+"\",\"comment\":\""+json_marker.Markers[i].comment+"\",\"title\":\""+json_marker.Markers[i].title+"\"}";
                    var h=json_marker_str.search(part_str);
                    if(json_marker_str.charAt(h-1)=="["){
                        if(json_marker_str.charAt(h+remove_one_marker_str.length)==","){
                            var str=remove_one_marker_str+","
                            json_marker_str=json_marker_str.replace(str,"");
                            //alert(json_marker_str);
                        }else if(json_marker_str.charAt(h+remove_one_marker_str.length)=="]"){
                            json_marker_str=json_marker_str.replace(remove_one_marker_str,"");
                        }
                    }else if(json_marker_str.charAt(h-1)==","){
                        if(json_marker_str.charAt(h+remove_one_marker_str.length)==","){
                            var str=remove_one_marker_str+","
                            json_marker_str=json_marker_str.replace(str,"");
                            
                        }else if(json_marker_str.charAt(h+remove_one_marker_str.length)=="]"){
                            json_marker_str=json_marker_str.replace(","+remove_one_marker_str,"");
                        }

                    }
                   
                   
                    
                }
            }   
            //json_marker_str=json_marker_str.replace(one_marker_str,"");
        }else{
            alert("error");
        }
        
    }
    localStorage.setItem("json_marker_str",json_marker_str);
    //alert(json_marker_str);
        
}
