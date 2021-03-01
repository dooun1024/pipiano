
<?php
$this->Html->script('p/webmidi', ['inline'=>false]);
$this->Html->script('p/verovio-toolkit-light', ['inline'=>false]);
$this->Html->script('p/piano', ['inline'=>false]);
?>

<div id="debug">
</div>
<h3>当前练习第<span id="zhoumu">0</span>周目</h3>
<div>
	<p>请输入下面的音符</p>
	<div id="currentContent"></div>
</div>
<div id="devices" style="display:none">
	<p>输入设备一览
	<div id="inputList">
	</div>
	<p>输出设备一览
	<div id="outputList">
	</div>
</div>

<div id="keyboardContainer"></div><!-- this will hold the keyboard -->
<div id="svgNotesContainer" style="max-width:900px;overflow: auto;"></div><!-- this will hold the SVG with rendered notes -->
<div id="svgNotesPlayContainer"></div>
<button id="a">AAAAAAAAA</button>

<!--Music content-->
<script>
	// 小步舞曲
	var allContent = [
		["''4D","'4G","'4A","'4B","''4C"],
		["''4D","'4G","'4G"],
		["''4E","''4C","''4D","''4E","''x4F"],
		["''4G", "'4G","'4G"],
		["''4C","''4D","''4C","'4B","'4A"],
		["'4B","''4C","'4B","'4A","'4G"],
		["'x4F","'4G","'4A","'4B","'4G"],
		["'4B","'4A"]
	];
	var contentIndex = 0;
	var currentContent;
	var completeCount = 0;

	function update周目(){
		$("#zhoumu").text(completeCount)
	}

	// 显示下一个content
	function showNextContent(){
		// 是否做完了一轮
		if (contentIndex > (allContent.length-1)) {
			contentIndex = 0;
			showNextContent();
			$("#currentContent").text(JSON.stringify(allContent));
			// 周目数+1
			completeCount++;
			update周目();
		} else {
			// 获取下一章节的内容
			plaineEasieCodes = allContent[contentIndex].slice();
			currentContent = plaineEasieCodes;
			$("#currentContent").text(JSON.stringify(currentContent));
			// 显示内容
			updateNotesSVG();
			// 章节索引递增
			contentIndex++;
		}
	}

	function noteOn(note) {
		// console.log(note);
		code2 = parseToEasieCode(note);
		// plaineEasieCodes.push(code2);
		if(currentContent[0]===code2){
			currentContent.shift();
			if (currentContent.length===0 ){
				// 如果当前的音符清空了，则显示下一个音符
				showNextContent();
			} else {
				updateNotesSVG();
				$("#currentContent").text(JSON.stringify(currentContent));
			}

		}
		console.log(plaineEasieCodes);
		// $("#debug").text(JSON.stringify(plaineEasieCodes));
	}

	$(function(){
		showNextContent();
	})

</script>

<!-- 初始化WebMIDI功能 -->
<script>
	var codes;
	var defaultLenth = 4;

	//保存所有键盘输入
	var plaineEasieCodes = [];
	var selectedClef = clefs.G4

	$(function () {
		// 获取我的配置文件
		$.get(baseUrl+"data/pianokey.json", null, function (r) {
			codes = r;
		});

	});

	// 检查MIDI是否可用
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
	// 当键盘“按下”

	// Function to handle noteOff messages (ie. key is released)
	// Think of this like an 'onkeyup' event
	// 当键盘“弹起”
	function noteOff(note) {
		//...
	}
</script>

<!-- // 初始化 乐谱显示 -->
<script>
	$( document ).ready(function() {
		//create piano with 3 octaves, starting at C4 (lowest key)
		//shows labels and octave shift buttons
		// var keyboardHTML = htmlForKeyboardWithOctaves(3, octaves.C4, true, true)
		//在 div 中渲染键盘
		// $("#keyboardContainer").html(keyboardHTML)
		//当按键按下了，就会调用 updatePreview() 函数
		// bindKeysToFunction(updatePreviewWithNote)
		//当按键变化了，就会调用 updatePreviewWithClef() 函数
		// bindClefSelectionToFunction(updatePreviewWithClef)
		//set the default clef to G4
		setSelectedClef(clefs.G4)

		$("#backspaceButton").click(deleteLast)

		$("#a").click(function(){
			console.log(123);
		})
	});


	//虚拟键盘按下时候触发
	// function updatePreviewWithNote(sender, paeNote) {
	// 	console.log("key pressed is " + paeNote)
	// 	plaineEasieCodes.push(paeNote)
	// 	updateNotesSVG()
	// }

	//切换F大调G大调
	function updatePreviewWithClef(sender, clef) {
		console.log("clef changed to " + clef)
		selectedClef = clef
		updateNotesSVG()
	}

	//删除最后一个输入
	function deleteLast() {
		plaineEasieCodes.pop()
		updateNotesSVG()
	}

	/**
	 * 更新五线谱图片
	 */
	function updateNotesSVG(data) {
		//使用 Verovio tookit 渲染音符的SVG
		// svg 宽度 800px 并且 50% 缩放
		var notesSVG = svgNotesForPlaineEasieCode(plaineEasieCodes.join(""), selectedClef, 800, 50)
		//把 SVG 插入容器
		var svgContainerDiv = $('#svgNotesContainer')
		svgContainerDiv.html(notesSVG)
	}

	function parseToEasieCode(keycode){
		return codes[keycode][defaultLenth];
	}

</script>

<!-- // 初始化webMIDI -->
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
