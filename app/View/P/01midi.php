<?php


?>
<html>
<head>
	<link rel="stylesheet" href="css/piano_style.css">

	<script src="js/webmidi.js"></script>
	<script	src="js/jquery-3.5.1.js"></script>
	<script src="js/verovio-toolkit-light.js" type="text/javascript" ></script>
	<script src="js/piano.js"></script>


	<script>
		navigator.requestMIDIAccess()
		.then(onMIDISuccess, onMIDIFailure);

		function onMIDISuccess(midiAccess) {
			for (var input of midiAccess.inputs.values()) {
				input.onmidimessage = getMIDIMessage;
			}
		}

		function getMIDIMessage(message) {
			var command = message.data[0];
			var note = message.data[1];
			var velocity = (message.data.length > 2) ? message.data[2] : 0; // a velocity value might not be included with a noteOff command

			// console.log(command)
			switch (command) {
				case 144: // noteOn
					if (velocity > 0) {
						noteOn(note, velocity);
					} else {
						noteOff(note);
					}
					break;
				case 128: // noteOff
					noteOff(note);
					break;
				// we could easily expand this switch statement to cover other types of commands such as controllers or sysex
			}
		}

		function onMIDIFailure() {
			console.log('Could not access your MIDI devices.');
		}

		// Function to handle noteOn messages (ie. key is pressed)
		// Think of this like an 'onkeydown' event
		function noteOn(note) {
			console.log(note);
			//...
		}

		// Function to handle noteOff messages (ie. key is released)
		// Think of this like an 'onkeyup' event
		function noteOff(note) {
			//...
		}
	</script>
</head>
<h1>notes learn</h1>

<body>
<p>输入设备一览
<div id="inputList">

</div>
<p>输出设备一览
<div id="outputList">

</div>
<div id="keyboardContainer"></div><!-- this will hold the keyboard -->
<div id="svgNotesContainer"></div><!-- this will hold the SVG with rendered notes -->


<script>
	// 初始化
	$( document ).ready(function() {
		//create piano with 3 octaves, starting at C4 (lowest key)
		//shows labels and octave shift buttons
		var keyboardHTML = htmlForKeyboardWithOctaves(3, octaves.C4, true, true)
		//render the keyboard in the div
		$("#keyboardContainer").html(keyboardHTML)
		//when keys are pressed updatePreview() is called
		bindKeysToFunction(updatePreviewWithNote)
		//when the clef is changed updatePreviewWithClef() is called
		bindClefSelectionToFunction(updatePreviewWithClef)
		//set the default clef to G4
		setSelectedClef(clefs.G4)

		$("#backspaceButton").click(deleteLast)
	})

	//this stores all keyboard input
	var plaineEasieCodes = []
	var selectedClef = clefs.G4

	//this is called whenever a piano key is pressed
	function updatePreviewWithNote(sender, paeNote) {
		console.log("key pressed is " + paeNote)
		plaineEasieCodes.push(paeNote)
		updateNotesSVG()
	}

	//this is called when the user changes the clef for display
	function updatePreviewWithClef(sender, clef) {
		console.log("clef changed to " + clef)
		selectedClef = clef
		updateNotesSVG()
	}

	//delete last input
	function deleteLast() {
		plaineEasieCodes.pop()
		updateNotesSVG()
	}

	function updateNotesSVG() {
		//render the notes to an SVG using the Verovio tookit
		//width of the svg is 800px and note scaling 50%
		var notesSVG = svgNotesForPlaineEasieCode(plaineEasieCodes.join(""), selectedClef, 800, 50)
		//insert thes SVG code in our div
		var svgContainerDiv = $('#svgNotesContainer')
		svgContainerDiv.html(notesSVG)
	}
</script>

<script>
		WebMidi.enable(function () {

		// Viewing available inputs and outputs
		console.log(WebMidi.inputs);
		console.log(WebMidi.outputs);

		WebMidi.inputs.forEach(obj => {
			$("#inputList").append(obj._midiInput.name+":"+obj._midiInput.manufacturer)
		})
		WebMidi.outputs.forEach(obj => {
			$("#outputList").append(obj._midiOutput.name)
		})

		// Retrieve an input by name, id or index
		var input = WebMidi.getInputByName("USB-MIDI");
		// OR...
		// input = WebMidi.getInputById("1809568182");
		// input = WebMidi.inputs[0];

		// Listen for a 'note on' message on all channels
		input.addListener('noteon', 'all',
			function (e) {
				console.log("Received 'noteon' message (" + e.note.name + e.note.octave + ").");
			}
		);

		// Listen to pitch bend message on channel 3
		input.addListener('pitchbend', 3,
			function (e) {
				console.log("Received 'pitchbend' message.", e);
			}
		);

		// Listen to control change message on all channels
		input.addListener('controlchange', "all",
			function (e) {
				console.log("Received 'controlchange' message.", e);
			}
		);

		// Remove all listeners for 'noteoff' on all channels
		input.removeListener('noteoff');

		// Remove all listeners on the input
		input.removeListener();

		});

	</script>
</body>
</html>
