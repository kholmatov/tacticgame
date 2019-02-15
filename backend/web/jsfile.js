/**
 * Created by kholmatov on 23/05/15.
 */
var currentPossitionId = false;
var MyeventsData = Array();
$( document ).ready(function() {
    $('#calendar').hide();
    var htmlDiv="";
    // when the section selector changes, rerender the calendar
    $('#grafic-section_item_id').on('change', function() {
        currentPossitionId = this.value || false;
        getCurrentDate = $('#calendar').fullCalendar('getDate');
        //console.log(getCurrentDate);
        if(currentPossitionId)
            $('#calendar').show();
        else
            $('#calendar').hide();
        $('#calendar').fullCalendar('destroy');
        renderCalendar();

    });

    function renderCalendar() {
        $('#calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            defaultDate: getCurrentDate,
            editable: true,
            eventLimit: true, // allow "more" link when too many events
            //events: {
            //    url: _GetEventUrl+'?sectionid='+currentPossitionId,
            //    error: function() {
            //        $('#script-warning').show();
            //    }
            //},
            events: {
                url: _GetEventUrl,
                type: 'GET',
                data: {
                    sectionid: currentPossitionId
                },
                success: function(response){
                   MyeventsData = new Array();
                    $.each(response, function(idx, item){
                        eventData = new Object();
                        eventData.id = item.id; // this should be string
                        eventData.title = item.title; // this should be string
                        eventData.start = item.start; // this should be date object
                        eventData.end = item.enddate; // this should be date object
                        eventData.color = item.color;
                        eventData.rendering= item.rendering;
                        //eventsData.backgroundColor = item.color;
                        eventData.allDay = item.allDay;
                        MyeventsData.push(eventData);
                    });
                    //$('#calendar').fullCalendar('unselect');
                    //$('#calendar').fullCalendar('addEventSource',eventsData);
                    //console.log(eventsData);
                    //$('#calendar').fullCalendar('refetchEvents');

                },
                error: function() {
                    alert('there was an error while fetching events!');
                }
            },
            eventSources:MyeventsData,
            loading: function(bool) {
                $('#loading').toggle(bool);
            },
            selectable: true,
            selectHelper:false,
            select: function(start, end) {
                   var eventHave = false;
                    $('#calendar').fullCalendar('clientEvents', function(event) {

                        if(event.start <= start && event.end >= end) {
                            $('#calendar').fullCalendar('unselect');
                            eventHave=true;
                        }
                        return false;
                    });
            var iStart = start.format('YYYY-MM-DD[T]HH:MM:SS');
            var iEnd = end.format('YYYY-MM-DD[T]HH:MM:SS');
            makeFields(iStart,iEnd);

          }
        });

    }

    function makeFields(iStart,iEnd){
        htmlDiv='<input type="hidden" id="iStart" value="'+iStart+'">'+
                '<input type="hidden" id="iEnd" value="'+iEnd+'">';

        $.ajax({
            url: _MakeFieldsUrl,
            data: 'start='+iStart+'&end='+iEnd+'&sectionid='+currentPossitionId,
            type: 'GET',
            dataType: 'json',
            success: function(response){

                htmlDiv+="<div class='row form-group'>" +
                        "<div class='col-md-2'><h5>"+_Date+"</h5></div>"+
                        "<div class='col-md-9'><h5>"+_Text+"</h5></div>"+
                        "<div class='col-md-1'><h5>"+_Public+"</h5></div>"+
                    "</div>";
                var z=0;
                var checkStatus=' ';
                $.each(response, function(idx, item){

                    if(item.active)
                        checkStatus=' checked ';
                    else
                        checkStatus=' ';

                    htmlDiv+="<div class=\"row form-group\">"+
                          "<div class=\"col-md-2\"><i class='glyphicon glyphicon-calendar'></i> "+item.start+"</div>"+
                          "<div class=\"col-md-9\"><input class='form-control form-input' type='text' data-date=\""+item.start+"\" name=\"timearray\" value=\""+item.title+"\"></div>"+
                          "<div class=\"col-md-1\"><input class='form-control' type='checkbox' data-date=\""+item.start+"\" "+checkStatus+"></div>"+
                          "</div>";
                 });

                var modalContainer = $('#my-modal');
                var modalBody = modalContainer.find('.modal-body');
                modalBody.html(htmlDiv);
                modalContainer.modal({show:true});

             },
            error: function(e){
                console.log(e.responseText);
            }
        });
        //console.log('html '+htmlDiv);
        return htmlDiv;
    }

    $(".modal-body").on("focus",".form-input",function(){
        $(this).removeClass("input-error");
    });

    $('.btn-primary').on('click',function(){
        var isError = false;
        $(".modal-body .form-input").each(function(){
            if ($(this).val().length == 0) {
                $(this).addClass("input-error");
                isError = true;
            }
        });

        if(!isError){
            var arrData = new Array();
            var chValue=0;
            var tValue=0;
            var textValue="";
            var checkBoxValue ="";
            $(".modal-body .form-control").each(function(){

                if($(this).attr('type')=='text'){
                    textValue= $(this).val();
                    tValue=1;
                }

                if($(this).attr('type')=='checkbox'){
                    checkBoxValue = (this.checked ? "1" : "0");
                    chValue=1;
                }

                if(chValue==1 && tValue==1){
                    //arrData.push([$(this).data('date'),textValue,checkBoxValue]);
                   //arrData.push(Array($(this).data('date'),textValue,checkBoxValue));
                   arrData.push({"date":$(this).data('date'), "title":textValue, "active":checkBoxValue});
                   chValue=0; tValue=0;
                   textValue=""; checkBoxValue="";

                }

             });

            sendArrayData(JSON.stringify(arrData));
        }
    });

    function sendArrayData(arrData){

        $.ajax({
            url:_AddEditUrl,
            data: 'arraydata='+arrData+'&sectionid='+currentPossitionId+'&start='+$('#iStart').val()+'&end='+$('#iEnd').val(),
            type: 'POST',
            dataType: 'json',
            success: function(response){
                //alert('hello');
                var eventsData = new Array();
                $.each(response, function(idx, item){
                    eventData = new Object();
                    eventData.id = item.id; // this should be string
                    eventData.title = item.title; // this should be string
                    eventData.start = item.start; // this should be date object
                    eventData.end = item.enddate; // this should be date object
                    eventData.color = item.color;
                    eventData.rendering= item.rendering;
                    //eventsData.backgroundColor = item.color;
                    eventData.allDay = item.allDay;
                    eventsData.push(eventData);
                });
                //console.log('Arr '+eventsData);
                $('#my-modal').modal('hide');
                $('#calendar').fullCalendar('unselect');
                $('#calendar').fullCalendar('addEventSource',eventsData);
                $('#calendar').fullCalendar('refetchEvents');


                //$('#calendar').fullCalendar('renderEvent',eventsData,true);

            },
            error: function(e){
                console.log(e.responseText);

            }
        });
    }
});