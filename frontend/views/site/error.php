<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = $name;
?>

<div class="wrapper">
	<div class="error-wrapper">
		<a href='/' class="logo logo--dark">
<!--			<img alt='logo' src="images/logo-dark.png">-->
			<h1><?= Html::encode($this->title) ?></h1>
		</a>

		<div class="error">
<!--			<img alt='' src='/athems/images/error.png' class="error__image">-->
			<h1 class="error__text"><?= nl2br(Html::encode($message)) ?></h1>
			<p style="color: #FFFFFF">
				The above error occurred while the Web server was processing your request.
				<br>
				Please contact us if you think this is a server error. Thank you.
			</p>
			<a href="/" class="btn btn-md btn--warning">return to homepage</a>

		</div>
	</div>

</div>
