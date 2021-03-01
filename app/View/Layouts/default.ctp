<?php
/** @var $baseUrl string */
?>
<!DOCTYPE html>
<html lang="cn">
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $this->fetch('title'); ?>
	</title>
	<?php
	echo $this->Html->meta('icon');
	echo $this->fetch('meta');
	echo $this->fetch('css');
	echo $this->fetch('script');

	echo $this->Html->script(['jquery', 'bootstrap']);
	echo $this->Html->css(array(
		'bootstrap',
	));
	?>
	<script>var baseUrl = "<?php echo $baseUrl; ?>";</script>
</head>
<body>
<div id="header">
	<nav class="navbar navbar-dark" style="background-color: #a4a4a4;">
		<?php
		echo $this->Html->image('back.svg', [
			'alt' => 'back',
			'style'=>'width:50px;',
			'class'=>'navbar-brand',
			'url' => array('controller' => 'p', 'action' => 'index')
		]);
		?>PianoProj v1.01 dooun
	</nav>
</div>
<div class="container" style="margin-top:20px;">
	<?php echo $this->Flash->render(); ?>
	<?php echo $this->fetch('content'); ?>
	<div id="debug-info">
	</div>
	<div id="footer"></div>
</div>
</body>
</html>
