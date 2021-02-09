$('#domicilioCheckbox').on('change',function(){
    statusCheckbox = $(this).prop('checked');
    switch(statusCheckbox) {
        case true : 
            $('#wiLoadDomicilio').slideDown(300);
        break;
        case false : 
            $('#wiLoadDomicilio').slideUp(300);
        break;
    }
});
$('#zipCodeTrigger').focusout(function(event) {
    var address = encodeURIComponent($("#zipCodeTrigger").val());
    function length(obj) {
        return Object.keys(obj).length;
    }    
    $.ajax({
      type: "GET",
      url: "http://maps.googleapis.com/maps/api/geocode/json?address=" + address + "+México&key=AIzaSyDvpAmpwULiU1ozaR4sNAChxqWFYk05u64",
      dataType: "json",
      success: function(data) {
        if(length(data.results[0].address_components) === 1) {
            $('#paisTarget').val(data.results[0].address_components[0].long_name);
        } else if(length(data.results[0].address_components) <= 4) {
            $('#ciudadTarget').val(data.results[0].address_components[1].long_name);
            $('#estadoTarget').val(data.results[0].address_components[2].long_name);
            $('#paisTarget').val(data.results[0].address_components[3].long_name);
        } else {
            $('#ciudadTarget').val(data.results[0].address_components[2].long_name);
            $('#estadoTarget').val(data.results[0].address_components[3].long_name);
            $('#paisTarget').val(data.results[0].address_components[4].long_name);
        }  
      }
    })
});
