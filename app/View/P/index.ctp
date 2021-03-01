<?php

?>

<div class="row">
	<div class="col-lg-4 col-6">
		<div class="card" style="width: 18rem;">
			<?php
			echo $this->Html->image('note1.svg', [
				'alt' => 'notes',
				'class'=>'card-img-top',
				'style'=>'width:80%;margin:20px;',
				'url' => array('controller' => 'p', 'action' => 'PlaySong')
			]);
			?>
			<div class="card-body">
				<h5 class="card-title">练习识别音符</h5>
				<p class="card-text">通过完成课程，练习识别音符</p>
			</div>
		</div>
	</div>

	<div class="col-lg-4 col-6">
		<div class="card" style="width: 18rem;">
			<?php
			echo $this->Html->image('notes.svg', [
				'alt' => 'notes',
				'class'=>'card-img-top',
				'style'=>'width:80%;margin:20px;',
				'url' => array('controller' => 'p', 'action' => 'PlaySong')
			]);
			?>
			<div class="card-body">
				<h5 class="card-title">练习节奏</h5>
				<p class="card-text">通过完成课程，练习节奏</p>
			</div>
		</div>
	</div>

	<div class="col-lg-4 col-6">
		<div class="card" style="width: 18rem;">
			<?php
			echo $this->Html->image('music.svg', [
				'alt' => 'notes',
				'class'=>'card-img-top',
				'style'=>'width:80%;margin:20px;',
				'url' => array('controller' => 'p', 'action' => 'PlaySong')
			]);
			?>
			<div class="card-body">
				<h5 class="card-title">练习整曲</h5>
				<p class="card-text">通过完成课程，练习整曲</p>
			</div>
		</div>
	</div>
</div>
