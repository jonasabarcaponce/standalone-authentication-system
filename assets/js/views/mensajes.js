$(document).ready(function() {
    redactMsg = document.querySelector( '#wiEdit' );
    BalloonEditor
        .create( redactMsg, { removePlugins: [ 'Heading' ], toolbar: [ 'bold', 'italic', '|', 'numberedList', '|', 'undo', 'redo', ] } )
        .then ( editor => {window.editor = editor } )
        .catch( error => {
            console.error( error );
        } );

    $('#wiEdit').on('focusout', function() {
        inputRedactMsg = document.querySelector('#wiEditResults');
        inputRedactMsg.value = editor.getData();
    });
    
});

$(document).on('keypress', 'input,select', function (e) {
    if (e.which == 13) {
        e.preventDefault();
    }
});

$('#formatoSelector').on('change', function() {
    var formatoValue = $(this).val();
    switch(formatoValue) {
        case 'texto' :
            // Mostrar el editor de texto
            $('#redactarContainer').slideDown();
        break;
    }
});
        
$('#customCheck2').on('change', function() {
    switch($(this).prop('checked')) {
        case true : 
            $('#doneButton').html('<i class="fas fa-user-check"></i> Enviar a revisión');
            $('#datePickerContainer').slideUp();
        break;
        case false :
            $('#doneButton').html('<i class="fas fa-stopwatch"></i> Enviar a revisión y programar');
            $('#datePickerContainer').slideDown();
        break;
    }
});

