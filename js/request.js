/*!
 * Code so that we can talk to the server (ajax.php)
 */
var AJAX_ENDPOINT = "ajax.php";
var REDIRECT_URL = "image-query.php?session=" + window.QUIZ_SESSION_ID;

// Sends an AJAX request to the server.
// Optionally redirects to next image afterwards.
function sendQuery(query_param, redirect) {
	$.get(AJAX_ENDPOINT, query_param, function (data) {
		if (data.status == "ok") {
			// TODO: Remove debug statements
			console.log("Success");
			console.log(query_param);
			console.log(data);
			if (!!redirect) window.location.href = REDIRECT_URL;
		} else {
			// TODO: Remove debug statements
			console.log("Error occurred when sending data to server.");
			console.log(query_param);
			console.log(data);
		}
	});
}

// From instructions page or new-language page.
// Requests to start the quiz (i.e.: the next language)
function startNextLanguage(language) {
	try {
		// Construct the object to be sent to the server
		var action = "proceed-to-quiz";
		var timestamp = Math.floor (Date.now() / 1000);
		var session_id = window.QUIZ_SESSION_ID;	// this is global
		
		var query_param = {
			type: action,
			timestamp: timestamp,
			session: session_id,
			language: language
		};
		
		// Send it!
		sendQuery(query_param, true);
	} catch (e) {
		console.log ("Exception!");
		console.log (e);
	}
}

// From image-query page. Sends request to record-result
// TODO: Should save sound-file too. But for now just save reaction time
function recordResult(reaction_time) {
	try {
		// Construct the object to be sent to the server
		var action = "record-result";
		var timestamp = Math.floor (Date.now() / 1000);
		var session_id = window.QUIZ_SESSION_ID;
		var language = window.QUIZ_CURRENT_LANGUAGE;
		var question = window.QUIZ_CURRENT_QUESTION;
		
		var query_param = {
			type: action,
			timestamp: timestamp,
			session: session_id,
			language: language,
			question: question,
			value: reaction_time
		};
		
		// Send it!
		sendQuery(query_param, true);
	} catch (e) {
		console.log ("Exception!");
		console.log (e);
	}
}