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

$(document).on('keydown', 'input,select', function (e) {
    if (e.which == 13) {
        e.preventDefault();
    }
});