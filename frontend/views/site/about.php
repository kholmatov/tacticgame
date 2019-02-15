<?php
use yii\helpers\Html;
#use ;
//use talma\widgets\FullCalendar;
/* @var $this yii\web\View */
$this->title = 'About';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>This is the About page. You may modify the following file to customize its content:</p>

    <code><?= __FILE__ ?></code>
    <?= \talma\widgets\FullCalendar::widget([
        'googleCalendar' => true,  // If the plugin displays a Google Calendar. Default false
        'loading' => 'Carregando...', // Text for loading alert. Default 'Loading...'
        'config' => [
                // put your options and callbacks here
                // see http://arshaw.com/fullcalendar/docs/
                'lang' => 'ru', // optional, if empty get app language

    ],
]); ?>
</div>
