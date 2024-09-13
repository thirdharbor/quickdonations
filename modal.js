jQuery(document).ready(function($) {

    // Handle label clicks
    $('#single-donation-label').on('click', function() {
        // Disable recurring option
        $('.donation-is-recurring').prop('checked', false).prop('disabled', true);
        // $('select[name="_subscription_period"]').val('day').change(); // Set period to 'day'

        $('#single-donation-label').addClass('selected-label');
        $('#monthly-donation-label').removeClass('selected-label');
        $('#monthly-donation-label').addClass('donation-type-label');
        $('#single-donation-label').removeClass('donation-type-label');

    });

    $('#monthly-donation-label').on('click', function() {
        // Enable recurring option
        $('.donation-is-recurring').prop('checked', true).prop('disabled', false);
        $('select[name="_subscription_period"]').val('month').change();
        $('select[name="_subscription_period_interval"]').val('1').change();
        $('select[name="_subscription_length"]').val('0').change();

        $('#monthly-donation-label').addClass('selected-label');
        $('#single-donation-label').removeClass('selected-label');
        $('#single-donation-label').addClass('donation-type-label');
        $('#monthly-donation-label').removeClass('donation-type-label');


    });

    // Handle dropdown change for the new select element
    $('#donation_type').on('change', function() {
        var selectedValue = $(this).val();

        if (selectedValue === 'single') {
            // Trigger same actions as clicking single donation label
            $('.donation-is-recurring').prop('checked', false).prop('disabled', true);
            $('select[name="_subscription_period"]').val('day').change(); // Set period to 'day'
        } else if (selectedValue === 'monthly') {
            // Trigger same actions as clicking monthly donation label
            $('.donation-is-recurring').prop('checked', true).prop('disabled', false);
            $('select[name="_subscription_period"]').val('month').change(); // Set period to 'month'
            $('select[name="_subscription_period_interval"]').val('1').change();
            $('select[name="_subscription_length"]').val('0').change();
        }
    });
            // Trigger the AJAX call after donation is added
            $(document).on('ajaxSuccess', function(event, xhr, settings) {
                var response = xhr.responseJSON;
                if (response && response.response === 'success') {
                    // Hide the campaign modal
                    var $button = $('#wc-donation-f-submit-donation');
            $button.append('<span class="loader"></span>'); // Add loader HTML
            $button.prop('disabled', true); // Disable the button to prevent multiple clicks

            $('.loader').css({
                'border': '2px solid #f3f3f3',
						'border-radius': '50%',
						'border-top': '4px solid #3498db',
						'width': '20px',
						'height': '20px',
						'animation': 'spin 1s linear infinite',
						'position': 'absolute',
						'top': '50%',
						'left': '50%',
						'transform': 'translate(-50%, -50%)'
            });
                    // Refresh the cart using WooCommerce's get_refreshed_fragments
                    $.ajax({
                        url: wc_cart_fragments_params.wc_ajax_url.toString().replace('%%endpoint%%', 'get_refreshed_fragments'),
                        type: 'POST',
                        beforeSend: function() {
                            // Show the loader
                            $('.open-modal .loader').show();
                        },
                        success: function (data) {
                            if (data && data.fragments) {
                                // Update the page with new cart fragments
                                $.each(data.fragments, function (key, value) {
                                    $(key).replaceWith(value);
                                });
                                $('.ast-site-header-cart').trigger('click');
                            }
                        },
                        complete: function() {
                            // Remove the loader and re-enable the button after the cart updates
                            $('.loader').remove();
                            $button.prop('disabled', false);
                        }
                    });
                }
            });

            // CSS for the loader animation
    const style = document.createElement('style');
    style.innerHTML = `
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    `;
    document.head.appendChild(style);

});

   



