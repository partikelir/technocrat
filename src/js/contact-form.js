// ==== CONTACT FORM ==== //

;(function($){
  $(function(){ // Shortcut to $(document).ready(handler);

    // Exit early if WordPress didn't pass us anything
    if (typeof pendrellVars === 'undefined' || pendrellVars.CF1 === '') {
      return;
    }

    var CF1 = pendrellVars.CF1;

    // Validate the contact form; see jQuery validation plugin docs for more info: http://jqueryvalidation.org/documentation/
    $('#contact-form').validate({

      // Define the various rules by which the form will be validated
      rules: {
        from: 'required',
        email: {
          required: true,
          email: true
        },
        message: {
          required: true,
          minlength: 10
        },
        hades: {
          maxlength: 0
        }
      },
      messages: {
        from: CF1.from,
        email: {
          required: CF1.email,
          email: CF1.invalidEmail
        },
        message: {
          required: CF1.message,
          minlength: CF1.messageLength
        }
      },

      // Things to do when the user hits submit
      submitHandler: function(form) {

        // @TODO: invoke spinner

        $('#submit').hide();

        $('#contact-form').append('<div id="response"></div>');

        var formData = $(form).serialize();

        $.ajax({
          type: 'POST',
          url: pendrellVars.ajaxUrl,
          data: formData + "&action=contact_form",
          success: function(response) {
            var $response = $('#response');

            $response.hide().html(response).fadeIn('slow');

            // @TODO: hide spinner
          }
        });
      },
    });
  });
}(jQuery));
