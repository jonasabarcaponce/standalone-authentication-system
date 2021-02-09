
$('.click-send').on('click', function(){

    var getidbutton = $(this).attr('data-id');
    var trtrash = '#' + getidbutton;

    $(trtrash).slideUp(300); 
    $(this).attr('disabled',true);

    $('#reportLast').attr('disabled',false);
    $('#reportLast').attr('data-report',getidbutton);
    $('#reportLast').html('<i class="fas fa-flag"></i> Informar');

    window.location.href = $(this).attr('data-href');
    
    // $('#undoBlast').attr('data-id',deletableID);
    // $('#undoBlast').removeAttr('disabled');
});

$('#reportLast').on('click', function() {
    $(this).html('<i class="fas fa-check"></i> Listo');
    $('#reportLast').attr('disabled',true);
    window.location.href = '/api/report/' + $(this).attr('data-report');
});

$('#loadMore').on('click', function() {
    window.location.reload();
});