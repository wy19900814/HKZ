
$('#page-map').live("pageinit", function() {
        
    function fadingMsg (locMsg) {
        $("<div class='ui-overlay-shadow ui-body-e ui-corner-all mds-popup-center' id='fadingmsg'>" + locMsg + "</div>")
        .css({ 'display': 'block', 'opacity': '0.9', 'z-index' : '9999999' })
        .appendTo( $.mobile.pageContainer )
        .delay( 2400 )
        .fadeOut( 1200, function(){
            $(this).remove();
        });
    }          
 
    // Define a default location and create the map
    var defaultLoc = new google.maps.LatLng(32.802955, -96.769923);
    $('#map_canvas').gmap( { 'center': defaultLoc, 'zoom' : 14, 'zoomControlOptions': {'position':google.maps.ControlPosition.LEFT_TOP} })
    .bind('init', function(evt, map) {
        // Try to get current location to center on, else stay at defaultLoc
        $('#map_canvas').gmap('getCurrentPosition', function(pos, status) {
            if (status === "OK") {
                var latLng = new google.maps.LatLng(pos.coords.latitude, pos.coords.longitude);
                $('#map_canvas').gmap('option', 'center', latLng);
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
                $('#markerdiv').append('<div class="mclass' + markerId + '" style="display:none;">'                               
                  + '<div data-role="fieldcontain"><label for="tag' + markerId + '">Marker Title<br/></label><input type="text" size="24" maxlength="30" name="tag' + markerId + '" id="tag' + markerId + '" value="" /></div>'
                  + '<div data-role="fieldcontain"><label for="address' + markerId + '">Address<br/></label><input type="text" size="24" maxlength="30" name="address' + markerId + '" id="address' + markerId + '" value="" /></div>'
                  + '<div data-role="fieldcontain"><label for="state' + markerId + '">City, State<br/></label><input type="text" size="24" maxlength="30" name="state' + markerId + '" id="state' + markerId + '" value="" /></div>'
                  + '<div data-role="fieldcontain"><label for="comment' + markerId + '">Comment<br/></label><textarea maxlength="64" cols=24 rows=3 name="comment' + markerId + '" id="comment' + markerId + '" value="" /></textarea></div>'                              
                  + '</div>');
                getGeoData(marker);
				}).dragend( function() {
	                 getGeoData(this);
	             }).click(function(){
				openMarkerDialog(this);
			});
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
                        $('#markerdiv').append('<div class="mclass' + markerId + '" style="display:none;">'                               
                          + '<div data-role="fieldcontain"><label for="tag' + markerId + '">Marker Title<br/></label><input type="text" size="24" maxlength="30" name="tag' + markerId + '" id="tag' + markerId + '" value="" /></div>'
                          + '<div data-role="fieldcontain"><label for="address' + markerId + '">Address<br/></label><input type="text" size="24" maxlength="30" name="address' + markerId + '" id="address' + markerId + '" value="" /></div>'
                          + '<div data-role="fieldcontain"><label for="state' + markerId + '">City, State<br/></label><input type="text" size="24" maxlength="30" name="state' + markerId + '" id="state' + markerId + '" value="" /></div>'
                          + '<div data-role="fieldcontain"><label for="comment' + markerId + '">Comment<br/></label><textarea maxlength="64" cols=24 rows=3 name="comment' + markerId + '" id="comment' + markerId + '" value="" /></textarea></div>'                              
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
                openMarkerDialog(marker);
            } else {
                fadingMsg('Unable to get GeoSearch data.');
                openMarkerDialog(marker);
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
        .append('<h4 style="margin:0.2em;">Edit &amp; Save Marker</h4>')
        .append( $('div.mclass'+markerId).css({ 'display': 'block'})
                .append('<div data-inline="true" id="dialog-btns" ><a id="remove" class="mbtn" style="font-size:15px">Remove</a>' +
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
            
            data = {title: 	(($('#tag' + markerId).val() !== "") ? $('#tag' + markerId).val() : ('Marker ' + markerId)),
            	    position:	String(marker.getPosition()),
            	    comment:	 $('#comment'+ markerId).val() };
            $.post("http://letsallgetcovered.org/lets6502/hkz_v1/main/test3.php", data, function(data){
            	fadingMsg(data);
            });
        });

        $('#mask').click( function() {
            // If user taps mask, save marker data as is by default
            // Alternative: remove by default: $('#remove').trigger('click');
            $('#save').trigger('click');
        });            
    }
});

$('#page-marker').live("pageshow", function() {
    try {
        $("ul#marker-list").listview('refresh');
    } catch(e) { }
});
