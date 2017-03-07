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
			url: "/wp-content/themes/tokboxmed/tokbox/tokboxModerate.php",	
			method: "POST",
			data: {start : true},
			success: function( data ) {
				location.reload();
			},
	        error: function() {
	          alert("There was an error. Try again please!");
	        }
		})
	});

	$('#destroyConf').click(function(){
		$.ajax({
			url: "/wp-content/themes/tokboxmed/tokbox/tokboxModerate.php",
			method: "POST",
			data: {stop : true},
			success: function( data ) {
				location.reload();
			},
	        error: function() {
	          alert("There was an error. Try again please!");
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
			},
	        error: function() {
	          alert("There was an error. Try again please!");
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
			},
	        error: function() {
	          alert("There was an error. Try again please!");
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
			},
	        error: function() {
	          alert("There was an error. Try again please!");
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
	
	
	function archive(){
		if($('#archive_state').prop('checked')){
			$.ajax({
				url: "/wp-content/themes/tokboxmed/tokbox/tokboxModerate.php",
				method: "POST",
				data: {archive_on : true},
				success: function() {
		        },
		        error: function() {
		          alert("There was an error. Try again please!");
		        } 
			});
		}else{
			$.ajax({
				url: "/wp-content/themes/tokboxmed/tokbox/tokboxModerate.php",
				method: "POST",
				data: {archive_off : true},
				success: function() {
		        },
		        error: function() {
		          alert("There was an error. Try again please!");
		        } 
			});
		}
	}

