	var apiKey =  opentokVars.apiKey,
		sessionID =  opentokVars.sessionTok,
		token =  opentokVars.token,
		modeConference =  opentokVars.modeConference,
		nameOf =  opentokVars.name,
		publisher,
		session,
		mySubscribe;
$(function() {

	if(sessionID !== ''){
			// Initialize an OpenTok Session object.
				session = OT.initSession(apiKey,sessionID);
				

				// Connect to the session using your OpenTok API key and the client's token for the session
				session.connect(token, function(error) {
				  if (error) {
				    console.error(error);
				  } else {
							// Publish a stream, using the Publisher we initialzed earlier.
							// This triggers a streamCreated event on other clients.
							
						// Initialize a Publisher, and place it into the 'publisher' DOM element.
						var publisherProperties = {name: nameOf, buttonDisplayMode: 'off', insertMode: "append"};
						publisher = OT.initPublisher('WindowPublisher',publisherProperties);

						session.publish(publisher);	

						$(publisher.element).append('<div class="groupUserButt"></div>'); 					
						//delete from stream
						publisher.on("streamDestroyed", function (event) {
								location.reload();
						});
					
				  }
				});
				session.on('streamCreated', function(event) {
				  // Called when another client publishes a stream.
				  // Subscribe to the stream that caused this event.
				  
					//помещаем в разные окна модератора и остальных
				  var nameadm = event.stream.name;
				  var info = event.stream.connection.data;
				  var infoRole = info.split(',');
				  if(infoRole[1] == 'moderator'){ 
				  	mySubscribe = session.subscribe(event.stream, 'WindowModerator', {  buttonDisplayMode: 'off',insertMode: 'append', width: 400, height: 300});
				 }else{
				 		mySubscribe = session.subscribe(event.stream, 'WindowPublisher', { buttonDisplayMode: 'off',insertMode: 'append' });
				 }

				  $(mySibscribe.element).append('<div class="groupUserButt"></div>'); 

			});

			//mute
			session.on("signal:muteoff", function(event) {
					publisher.publishAudio(false);
			});
			//add voice
			session.on("signal:muteon", function(event) {
					publisher.publishAudio(true);
			});

			//add speaker
			session.on("signal:setspeaker", function(event) {
					publisher.publishAudio(true);
					$(publisher.element).find('.groupUserButt').append('<p class="spiker">Вы назначены спикером</p>');
				
			});
			//mute
			session.on("signal:muteoffOn", function(event) {
					publisher.publishAudio(false);
					$('.groupUserButt .spiker').detach();
			});


				
				
	}
	


	
});

