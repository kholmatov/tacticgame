<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model app\models\Grafic */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="grafic-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="form-group field-grafic-postion_item_id">
        <label for="grafic-postion_item_id" class="control-label"><?=Yii::t('app','Sections')?></label>
     <?= Html::activeDropDownList($model, 'postion_item_id',
        ArrayHelper::map($sections,'id','stitle','ptitle'),[ 'prompt' => Yii::t('app','Please Select'),'class' => 'form-control']) ?>
    </div>
    <?php $url=Url::to(['addevent']); ?>
    <?= \talma\widgets\FullCalendar::widget([
        'id'=>'calendar',
        'googleCalendar' => true,  // If the plugin displays a Google Calendar. Default false
        'loading' => 'Carregando...', // Text for loading alert. Default 'Loading...'
        'config' => [
            'header' =>  new JsExpression("{
                left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
			}"),
            // put your options and callbacks here
            // see http://arshaw.com/fullcalendar/docs/
            'lang' => 'ru', // optional, if empty get app language
            'dayClick' => new JsExpression("function(date, jsEvent, view) {
            // event handling code
                //alert($(this).data('date'));
            }"),
            'selectable' => true,
            'selectHelper'=> false,
            'select'=> new JsExpression(" function(start, end) {
              var title = prompt('".Yii::t('app','Event Title')."');
              var iStart = start.format('YYYY-MM-DD[T]HH:MM:SS');
              var iEnd = end.format('YYYY-MM-DD[T]HH:MM:SS');

              var eventData;
				if (title) {

                $.ajax({
		    		url:'$url',
		    		data: 'type=new&title='+title+'&startdate='+iStart+'&enddate='+iEnd,
		    		type: 'POST',
		    		dataType: 'json',
                        success: function(response){
                        var eventsData = new Array();
                        //response=JSON.parse(response);
                        $.each(response, function(idx, item){
                            eventData = new Object();
                            eventData.id = item.id; // this should be string
                            eventData.title = item.title; // this should be string
                            eventData.start = item.start; // this should be date object
                            eventData.end = item.enddate; // this should be date object
                            eventData.color = '';
                            eventData.allDay = true;
                            eventsData.push(eventData);
                         });
                         $('#calendar').fullCalendar('addEventSource',eventsData);
                       /*
                    for (i=0;i<response.lenght;i++){
                        var start_date = respons.start[i];

                        var end_date = dates.getValue();
                        var event_name = dates.getValue();
                        //var EventEntry = [ 'title: '+ event_name, 'start: '+ start_date,'end: '+ end_date ];
                        event = new Object();
                        event.title = event_name; // this should be string
                        event.start = start_date; // this should be date object
                        event.end = end_date; // this should be date object
                        event.color = '';
                        event.allDay = false;
                        this.label1.setCaption(start_date);
                        //EventArray.push(EventEntry);
                        console.log(events['title']);
                        events.push(event);

                    } */
                    //$('#calendar').fullCalendar('addEventSource',events);

                    //eventData=JSON.parse(response);
                    //		    		    eventData = {
//						    title: response.title,
//						    start: start,
//						    end: end
//					        }

		    			//$('#calendar').fullCalendar('renderEvent', eventData, true);
                        $('#calendar').fullCalendar('unselect');
		    			//event.id = response.id;
		    			//$('#calendar').fullCalendar('updateEvent',event);
		    		},
		    		error: function(e){
		    			console.log(e.responseText);

		    		}
		    	  });

//            		$.ajax({
//                        url: '$url',
//                        type: 'post',
//                        dataType: 'json',
//                        data: eventData,
//                        success: function(data) {
//                          $('#calendar').fullCalendar('renderEvent', eventData, true);
//                          $('#calendar').fullCalendar('unselect');
//                        }
//                    });
				}
              }"),
            'editable'=> false,
            'eventLimit'=>true,
            'eventClick'=>new JsExpression("function(event, element) {
                    var title = prompt('".Yii::t('app','Event Title')."',event.title);
                    if(title){
                        event.title = title;
                    }
                    $('#calendar').fullCalendar('updateEvent', event);
            }"),
            'events'=>new JsExpression("[
				{
                    title: 'All Day Event',
					start: '2015-05-01'
				},
				{
                    title: 'Long Event',
					start: '2015-05-07',
					end: '2015-05-10',
					rendering: 'background',
					color: '#ff9f89'
				},
				{
                    id: 999,
					title: 'Repeating Event',
					start: '2015-05-09T16:00:00'
				},
				{
                    id: 999,
					title: 'Repeating Event',
					start: '2015-05-16T16:00:00'
				},
				{
                    title: 'Conference',
					start: '2015-05-11',
					end: '2015-05-13'
				},
				{
                    title: 'Meeting',
					start: '2015-05-12T10:30:00',
					end: '2015-05-12T12:30:00'
				},
				{
                    title: 'Lunch',
					start: '2015-05-12T12:00:00'
				},
				{
                    title: 'Meeting',
					start: '2015-05-12T14:30:00'
				},
				{
                    title: 'Happy Hour',
					start: '2015-05-12T17:30:00'
				},
				{
                    title: 'Dinner',
					start: '2015-05-12T20:00:00'
				},
				{
                    title: 'Birthday Party',
					start: '2015-05-13T07:00:00'
				},
				{
                    title: 'Click for Google',
					url: 'http://google.com/',
					start: '2015-05-28'
				}
			]")
        ],
    ]); ?>

    <?= $form->field($model, 'date')->textInput() ?>

    <?= $form->field($model, 'timearray')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
