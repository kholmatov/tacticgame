<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\web\JsExpression;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;


/* @var $this yii\web\View */
/* @var $searchModel app\models\GraficSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Grafics');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="grafic-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <div class="form-group field-grafic-section_item_id">
        <label for="grafic-section_item_id" class="control-label"><?=Yii::t('app','Sections')?></label>
        <?= Html::activeDropDownList($model, 'section_item_id',
            ArrayHelper::map($sections,'id','stitle','ptitle'),[ 'prompt' => Yii::t('app','Please Select Sections'),'class' => 'form-control']) ?>
    </div>

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
            'dayClick' => new JsExpression("function(date,allDay, jsEvent, view) {
                if (allDay) {
                    // Clicked on the entire day
                    //$('#calendar').fullCalendar('gotoDate',date)
                    //$('#calendar').fullCalendar('changeView', 'agendaDay')
                }
                //view = $('#calendar').fullCalendar('getView');
                }"),
            'selectable' => true,
            'selectHelper'=> false,
            'select'=> new JsExpression("function(start, end) {
               var eventHave = false;
              $('#calendar').fullCalendar('clientEvents', function(event) {

                if(event.start <= start && event.end >= end) {
                    console.log('true');
                    $('#calendar').fullCalendar('unselect');
                    eventHave=true;
                }
                return false;
              });

              if(!eventHave)
                //var title = prompt(_PromtTitle);
                var title='';

              var modalContainer = $('#my-modal');
              var modalBody = modalContainer.find('.modal-body');
              modalContainer.modal({show:true});

              var iStart = start.format('YYYY-MM-DD[T]HH:MM:SS');
              var iEnd = end.format('YYYY-MM-DD[T]HH:MM:SS');

              var eventData;
			  if (title) {
                  $.ajax({
		    		url:_AddUrl,
		    		data: 'type=new&title='+title+'&startdate='+iStart+'&enddate='+iEnd,
		    		type: 'POST',
		    		dataType: 'json',
                        success: function(response){
                        var eventsData = new Array();
                        $.each(response, function(idx, item){
                            eventData = new Object();
                            eventData.id = item.id; // this should be string
                            eventData.title = item.title; // this should be string
                            eventData.start = item.start; // this should be date object
                            eventData.end = item.enddate; // this should be date object
                            eventData.color = item.color;
                            eventData.rendering= item.rendering;
                            eventData.allDay = true;
                            eventsData.push(eventData);
                         });
                         console.log(eventsData);
                         $('#calendar').fullCalendar('addEventSource',eventsData);
                	},
		    		error: function(e){
		    			console.log(e.responseText);

		    		}
		    	  });

				}
              }"),
            'editable'=> false,
            'eventLimit'=>true,
            'eventClick'=>new JsExpression("function(event, element) {
                    var title = prompt(_PromtTitle,event.title);
                    if(title){
                        event.title = title;
                    }
                    $('#calendar').fullCalendar('updateEvent', event);
            }"),
            'events'=>''
        ],
    ]); ?>
</div>
<?php
$jsVars=" var _PromtTitle='".Yii::t('app','Events')."';
          var _Date = '".Yii::t('app','Date')."';
          var _Text = '".Yii::t('app','Text')."';
          var _Public = '".Yii::t('app','Public')."';
          //Urls from JS
          var _AddUrl= '".Url::to(['addevent'])."';
          var _GetEventUrl = '".Url::to(['getevent'])."';
          var _MakeFieldsUrl = '".Url::to(['makefields'])."';
          var _AddEditUrl = '".Url::to(['addedit'])."';

";

$this->registerJs($jsVars, \yii\web\View::POS_HEAD);
$this->registerJsFile(Yii::$app->request->baseUrl.'/jsfile.js', ['depends' => [\yii\web\JqueryAsset::className()]]);

?>
<!-- Modal "Записаться на занятия" -->
<div class="modal fade bs-example-modal-lg" id="my-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="gridSystemModalLabel"><?=Yii::t('app','Grafics Add and Edit')?></h4>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?=Yii::t('app','Cancel')?></button>
                <button type="button" class="btn btn-primary"><?=Yii::t('app','Save')?></button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->