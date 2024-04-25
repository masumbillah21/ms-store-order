(function($) {

    "use strict";

    function mso_api_call_to_send_order(){
        $('.call_api_order').on('click', function(e){
            $(this).text('Sending...');
            e.preventDefault()
            var action = $(this).data('action')
            var id = $(this).data('order-id')
            var type = $(this).data('type')
            var formInputs = 'id=' + id + '&type=' + type;
            var formData = new FormData;
            formData.append('action', 'mso_api_order');
            formData.append('mso_api_order', formInputs);
            $.ajax({
                type: 'POST',
                url: action,
                dataType: "json",
                data: formData,
                cache: false,
                processData: false,
                contentType: false,
                success: function(response) {
                    location.reload();
                },
                error: function(xhr, status, error) {
                    console.log(xhr);
                }
            });
        })
    }


    $(document).ready(function() {
        mso_api_call_to_send_order()
        
    })



})(jQuery);