$( document ).ready(function() {
    $( document ).keypress(function (evt) {
        // Prevent Enter from working
        if ( evt.which === 13 ) evt.preventDefault();
        // Reset if Space is clicked
        if ( evt.which === 32 ) {
            // Have user confirm
            var submit = confirm('Space bar pressed... \n' +
                'Are you sure you want reset?');
            // If confirmed trigger click on button
            if ( submit ) $( "[name='reset']" ).trigger('click');
        }
        // Cycle through keys
        $( "[name='key']" ).each(function (i,e) {
            // This key match the key pressed?
            let val = $(e).val();
            if (val === String.fromCharCode(evt.which)){
                console.log($(e).attr('disabled'));
                // Is this disabled?
                if ( ! $(e).attr('disabled') ) {
                    // Have user confirm
                    var submit = confirm(val +
                        ' pressed... \n' +
                        'Submit letter?');
                    // If confirmed trigger click on button
                    if (submit) $(e).trigger('click');
                }
                else{
                    alert(val + ' has been selected already!')
                }
            }
        });
    });
});
