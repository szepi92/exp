/*!
 * Code so that we can talk to the server (ajax.php)
 */
var AJAX_ENDPOINT = "ajax.php";
var REDIRECT_URL = "image-query.php";

// Sends an AJAX request to the server.
// Optionally redirects to next image afterwards.
function sendQuery(query_param, redirect) {
	$.get(AJAX_ENDPOINT, query_param, function (data) {
		if (data.status == "ok") {
			if (!!redirect) {
				if (_.isString(redirect)) window.customRedirect(redirect);
				else if (_.isBoolean(redirect)) window.customRedirect(REDIRECT_URL);
				else if (_.isFunction(redirect)) redirect(data);
				else {
					console.log("Unknown redirect or callback: ", redirect);
				}
			}
		} else {
			// TODO: Remove debug statements
			console.log("Error occurred when sending data to server.");
			console.log(query_param);
			console.log(data);
		}
	});
}
	
// This is the function that redirects
window.customRedirect = function(link) {
	window.enable_continue = false;
	window.reactionTime = undefined;
	window.resultRecorded = undefined;
	window.recordingStarted = undefined;
	
	window.voiceHeard = function() {}
	window.voiceDone = function() {}
	window.keyHandler = function(){}

	requestPage(link, function(data){
		$("#page-content").html(data);
	});
}

function requestPage(page_name, callback) {
	var action = "get-page";
	var timestamp = Math.floor (Date.now() / 1000);
	var session_id = window.QUIZ_SESSION_ID;
	
	var query_param = {
		type: action,
		timestamp: timestamp,
		session: session_id,
		value: page_name
	};
	
	$.get(AJAX_ENDPOINT, query_param, callback);
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
function recordResult(reaction_time, callback) {
	try {
		// This is a hack!
		if (!reaction_time) {
			console.log("Error while recording result");
			location.reload();
			return;
		}
		
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
		sendQuery(query_param, callback);
	} catch (e) {
		console.log ("Error while recording result! ", e);
	}
}