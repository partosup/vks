var apiKey =  opentokVars.apiKey,
	sessionID =  opentokVars.sessionTok,
	token =  opentokVars.token,
	modeConference =  opentokVars.modeConference,
	nameOf =  opentokVars.name,
	publisher,
	session,
	allSibscribersConnections = [],
	allSibscribersStreams = [];
		
$(function() {
	if(sessionID !== ''){
			// Initialize an OpenTok Session object.
				session = OT.initSession(apiKey,sessionID);
				

				// Connect to the session using your OpenTok API key and the client's token for the session
				session.connect(token, function(event) {
				    
					// Initialize a Publisher, and place it into the 'publisher' DOM element.
					var publisherProperties = {name: nameOf, width: 400, height: 300};
					publisher = OT.initPublisher('WindowModerator',publisherProperties);
				    session.publish(publisher);
 
				});
				
				var countSibscribers=0;
				session.on('streamCreated', function(event) {
				  // Called when another client publishes a stream.
				  // Subscribe to the stream that caused this event.
				  var info = event.stream.connection.data;
				  var infoRole = info.split(',');
				  var UserId = infoRole[2];
				  
				  var mySibscribe = session.subscribe(event.stream, 'WindowPublisher', {insertMode: 'append' });
				  
				  $(mySibscribe.element).append('<div class="groupUserButt"><button class="deleteUser btn-danger" onclick="deleteuser(this)" data-id="'+UserId+'" data-count="'+countSibscribers+'">Удалить</button><button class="setUser btn-success" onclick="setspeaker(this)" data-count="'+countSibscribers+'" data-active="off">Спикер</button></div>'); 
				
			
				//create array with all connections
				  var infoOneSibscriber = [countSibscribers,mySibscribe.stream.connection];
				  allSibscribersConnections.push(infoOneSibscriber);
				//create array with all streams
				  var infoOneStreame = [countSibscribers,mySibscribe.stream];
				  allSibscribersStreams.push(infoOneStreame);


				  countSibscribers++;
				});
	

	}

	
	$('#createConf').click(function(){
		$.ajax({
			url: "/wp-content/themes/tokboxmed/tokbox/tokboxCreate.php",		
			success: function( data ) {
				location.reload();
			}
		})
	});

	$('#destroyConf').click(function(){
		$.ajax({
			url: "/wp-content/themes/tokboxmed/tokbox/tokboxDelete.php",
			success: function( data ) {
				location.reload();
			}
		})
	});


	//list of users who want connecting
	setInterval(function() {
		$.ajax({
			url: "/wp-content/themes/tokboxmed/tokbox/ajaxUsersConnect.php",
			method: "POST",
			data: {getUsers:true},
			success: function( data ) {
			 if (data) {
				var json = $.parseJSON(data);
				var elemSplitName;
				$.each(json , function(index, element) {
					elemSplitName = element.split('=');
					$('.list-of-users').html('');
					$('.list-of-users').append('<li data-id="'+elemSplitName[0]+'"><span>'+elemSplitName[1]+'<span><button class="btn-sucess" onclick="usersolve(this);">принять</button><button class="btn-danger" onclick="userreject(this);">отклонить</button></li>');
				});
			 }
			}
		});
	}, 5000);


});




	//solve connection of user
	function usersolve(solve){
		var IdUsert = $(solve).parents('li').attr('data-id');
		$.ajax({
			url: "/wp-content/themes/tokboxmed/tokbox/ajaxUsersConnect.php",
			method: "POST",
			data: {solve : true, id : IdUsert },
			success: function( data ) {
				$('.list-of-users li[data-id='+IdUsert+']').detach();		
			}
		});	
	}
	//reject connection of user
	function userreject(reject){
		var IdUsert = $(reject).parents('li').attr('data-id');
		$.ajax({
			url: "/wp-content/themes/tokboxmed/tokbox/ajaxUsersConnect.php",
			method: "POST",
			data: {reject : true, id : IdUsert },
			success: function( data ) {
				$('.list-of-users li[data-id='+IdUsert+']').detach();		
			}
		});
	}



		//delete connection of user
	function deleteuser(user){
		var IdUsert = $(user).attr('data-id');
		var countUser = $(user).attr('data-count');

		$.ajax({
			url: "/wp-content/themes/tokboxmed/tokbox/ajaxUsersConnect.php",
			method: "POST",
			data: {reject : true, id : IdUsert },
			success: function( data ) {
				session.forceUnpublish(allSibscribersStreams[countUser][1]);		
			}
		});	
	}

	function muteallaudio(){

		if($('.muteallaudio input').prop('checked')){
			session.signal(
			{
				type:"muteon"
			},
			function(error) {
				if (error) {
							alert(error.message);
				} 
			}
			);
		}else{
			session.signal(
			{
				type:"muteoff"
			},
			function(error) {
				if (error) {
							alert(error.message);
				} 
			}
			);
		}
		
	}


	function setspeaker(spieker){
		var countUser = $(spieker).attr('data-count');
		var spiekerUser = $(spieker).attr('data-active');
		
		if (spiekerUser == 'off'){//turn on

			//mute all
			session.signal(
				{
					type:"muteoff"
				},
				function(error) {
					if (error) {
						alert(error.message);
					} 
				}
				);
		//set spieker
			session.signal(
			{
				to: allSibscribersConnections[countUser][1],
				type:"setspeaker"
			},
			function(error) {
				if (error) {
					alert(error.message);
				} else{
					$(spieker).text('Отключить');
					$(spieker).attr('data-active','on');
				}
			}
			);

		}else{//turn off

			session.signal(
				{
					type:"muteoffOn"
				},
				function(error) {
					if (error) {
						alert(error.message);
					} else{
						$(spieker).text('Спикер');
						$(spieker).attr('data-active','off');
					}
				}
				);


		}


	}
	
	
	function start_archive(){
		var date = new Date();
		var url = "https://api.opentok.com/v2/project/"+apiKey+"/archive";
		var method = "POST";
		var postData = {
				"sessionId" : sessionID,
				 "name" : ""+date.getDate()+"/"+(date.getMonth()+1)+"/"+date.getFullYear()+"",
				 "hasAudio" : true,
				 "hasVideo" : true,
				 "outputMode" : "composed",
				 };
		
		var async = true;
		var request = new XMLHttpRequest();
		request.onload = function () {
		   //get all kinds of information about the HTTP response.
		   var status = request.status; // HTTP response status, e.g., 200 for "200 OK"
		   var data = request.responseText; // Returned data, e.g., an HTML document.
		}

		request.open(method, url, async);

		request.setRequestHeader("Content-Type","application/json");
		request.setRequestHeader("X-OPENTOK-AUTH", token);

		request.send(postData);
	}

