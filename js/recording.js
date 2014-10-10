/*!
* Deon Nicholas' library for recording.
* Include this on any page where voice recognition /recording is required.
*
* Define the functions: voiceHeard() and voiceDone() to be called whenever
* the user speaks.
*/

// What to do when voice is heard
// Override these by setting "window.voiceHeard = ...", etc. on load
function voiceHeard() {
	console.log("VOICE HEARD! Override this behaviour by setting window.voiceHeard = ...");
}

function voiceDone() {
	console.log("VOICE DONE! Override this behaviour by setting window.voiceDone = ...");
}

// This handles the bulk of the audio-processing
// It calls voiceHeard and voiceDone appropriately
// It is called whenever the microphone has new data to process
// This should happen as frequently as the sample-rate
var is_speaking = false;
var quiet_timeout = false;
function processAudio(audioEvent) {
	var inputBuffer = audioEvent.inputBuffer;
	var outputBuffer = audioEvent.outputBuffer;
	var numChannels = inputBuffer.numberOfChannels;
	var numSamples = inputBuffer.length;
	
	// TODO: This depends on mic settings.
	// Different computers == different loudness values!
	// A better algorithm would be to detect a steep change in volume
	var LOUD_ENOUGH = 0.12; 
	var QUIETNESS_WAIT_TIME = 1000;
	
	// Get the average volume of these samples
	var rms = 0;	// Get the ROOT-MEAN-SQUARE (average volume) of the samples
	for (var channel=0; channel < numChannels; channel++) {
		var inputData = inputBuffer.getChannelData(channel);
		for (var sample = 0; sample < numSamples; sample++) {
			value = inputData[sample];
			rms += value*value;
		}
	}
	rms /= (numChannels * numSamples);
	rms = Math.sqrt(rms);
	
	// A loud volume implies "speaking".
	// Once speaking is over, wait for a sec, then we're "done";
	if (rms >= LOUD_ENOUGH && !is_speaking) {
		// Clear quietness timer, and call voiceHeard()
		if (!!quiet_timeout) quiet_timeout = clearTimeout(quiet_timeout);
		is_speaking = true;
		if (_.isFunction(window.voiceHeard)) window.voiceHeard();
	} else if (rms < LOUD_ENOUGH && is_speaking && !quiet_timeout) {
		// Set the quietness timer, and wait to call voiceDone()
		if (_.isFunction(window.voiceDone))
			quiet_timeout = setTimeout(window.voiceDone,QUIETNESS_WAIT_TIME);
		is_speaking = false;
	}
}


// This function checks for audio capabilities and turns on the mic.
window.initCallback = function(){}
function initMedia(callback) {
	if (!(navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia)) {
		return errorCallback("No microphone access for this computer / browser.");
	}
	
	// Cross-Browser compatibility is necessary
	navigator.getUserMedia = navigator.getUserMedia ||
		navigator.webkitGetUserMedia ||
		navigator.mozGetUserMedia ||
		navigator.msGetUserMedia;
	window.AudioContext = window.AudioContext || window.webkitAudioContext;
	
	// Define the Audio Context (handles all the sound nodes)
	context = new window.AudioContext();
		
	// Ask for microphone permissions.
	// When complete, hook up the microphone to the "processAudio()" function above
	window.initCallback = callback;
	navigator.getUserMedia({audio: true}, startRecording, errorCallback);
}

//
function startRecording(stream) {
	var microphone = context.createMediaStreamSource(stream);
	var processor = context.createScriptProcessor(1024, 1, 1);
	processor.onaudioprocess = processAudio;
	microphone.connect(processor);
	processor.connect(context.destination);
	
	window.recordingStarted = Date.now();
	
	if (_.isFunction(window.initCallback))
		window.initCallback();
}

function errorCallback(error) {
	console.log ("Error when initializing audio: ", error);
	if (error.name == "PermissionDeniedError") {
		alert("We require the microphone to conduct the experiment. "
			+ "Please click the little \"Camera\" icon on the right-side "
			+ "of your search-bar to unblock the microphone.");
	}
}

function stopRecording(stream) {
	window.recordingStopped = Date.now();
}