"use strict";

//Plaeholder handler
$(function () {
        if (!Modernizr.input.placeholder) {             //placeholder for old brousers and IE

            $('[placeholder]').focus(function () {
                var input = $(this);
                if (input.val() == input.attr('placeholder')) {
                    input.val('');
                    input.removeClass('placeholder');
                }
            }).blur(function () {
                var input = $(this);
                if (input.val() == '' || input.val() == input.attr('placeholder')) {
                    input.addClass('placeholder');
                    input.val(input.attr('placeholder'));
                }
            }).blur();
            $('[placeholder]').parents('form').submit(function () {
                $(this).find('[placeholder]').each(function () {
                    var input = $(this);
                    if (input.val() == input.attr('placeholder')) {
                        input.val('');
                    }
                })
            });
        }


        $('#contact-form').submit(function (e) {

            e.preventDefault();
            var error = 0;
            var self = $(this);

            var $name = self.find('[name=user-name]');
            var $email = self.find('[name=user-email]');
            var $message = self.find('[name=user-message]');
            var $phone = self.find('[name=user-phone]');
            var emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;

            if (!emailRegex.test($email.val())) {
                createErrTult(erroremail, $email)
                error++;
            }

            if ($name.val().length > 1 && $name.val() != $name.attr('placeholder')) {
                $name.removeClass('invalid_field');
            }
            else {
                createErrTult(errorname, $name)
                error++;
            }

            if ($message.val().length > 2 && $message.val() != $message.attr('placeholder')) {
                $message.removeClass('invalid_field');
            }
            else {
                createErrTult(errormessage, $message)
                error++;
            }

            if ($phone.val().length > 1 && $phone.val() != $phone.attr('placeholder')) {
                $phone.removeClass('invalid_field');
            }
            else {
                createErrTult(errorphone, $phone)
                error++;
            }


            if (error != 0)return;
            self.find('[type=submit]').attr('disabled', 'disabled');

            self.children().fadeOut(300, function () {
                $(this).remove()
            })
            $('<p class="success"><span class="success-huge">' + ithank + '</span> <br> ' + iyour + '</p>').appendTo(self)
                .hide().delay(300).fadeIn();


            var formInput = self.serialize();

            //console.log(formInput);

            $.post(self.attr('action'), formInput, function (data) {
                //alert(data);
            }); // end post
        }); // end submit

        $('#lstatus').on('click', function () {

            var error = 0;
            var self = $('#certificat-form');
            var $certid = self.find('[name=id]');
            var $phone = self.find('[name=user-phone]');
            var $email = self.find('[name=user-email]');
            var $tarif = $('.tarif-ul');
            //credit card check
            // var $ccjsnumber = $("input.ccjs-number-formatted");
            // var $ccjscsc = $("input.ccjs-csc");
            // var $ccjsname = $("input.ccjs-name");
            // var $ccjsexpiration = $(".ccjs-expiration");

            var emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;

            if ($("input[name='tariflist']:checked").size() == 0) {
                createErrTult($tarif.data("error"), $tarif);
                error++;
            }

            if (!emailRegex.test($email.val())) {
                createErrTult($email.data("emailerror"), $email);
                error++;
            }
            /*
            var msgError = "";
            var specError = 0;

            if ($ccjsnumber.val().length < 16) {
                msgError += $ccjsnumber.data("error");
                msgError += "<br>";
                specError++;
                $ccjsnumber.addClass("invalid_field");
            } else {
                $ccjsnumber.removeClass("invalid_field");
            }

            if ($ccjscsc.val().length < 2) {
                msgError += $ccjscsc.data("error");
                msgError += "<br>";
                specError++;
                $ccjscsc.addClass("invalid_field");
            } else {
                $ccjscsc.removeClass("invalid_field");
            }

            if ($ccjsname.val().length < 2) {
                msgError += $ccjsname.data("error");
                msgError += "<br>";
                specError++;
                $ccjsname.addClass("invalid_field");
            } else {
                $ccjsname.removeClass("invalid_field");
            }

            if ($(".ccjs-month option:selected").text() == 'MM' || $(".ccjs-year option:selected").text() == 'YY') {
                msgError += $ccjsexpiration.data("error");
                msgError += "<br>";
                specError++;
                if ($(".ccjs-month option:selected").text() == 'MM') $(".ccjs-month").addClass("invalid_field");
                else $(".ccjs-month").removeClass("invalid_field");

                if ($(".ccjs-year option:selected").text() == 'YY') $(".ccjs-year").addClass("invalid_field");
                else $(".ccjs-year").removeClass("invalid_field");
            } else {
                $(".ccjs-month").removeClass("invalid_field");
                $(".ccjs-year").removeClass("invalid_field");
            }

            if (specError != 0) createErrTult(msgError, $('.ccjs-card'));
            else $('.ccjs-card').removeClass("invalid_field");
            */

            if ($phone.val().length > 1 && $phone.val() != $phone.attr('placeholder')) {
                $phone.removeClass('invalid_field');
            } else {
                createErrTult($phone.data("phoneerror"), $phone)
                error++;
            }

            //if (error != 0 || specError != 0) return false;
            if (error != 0) return false
            self.find('[type=submit]').attr('disabled', 'disabled');
            var csrfToken = $('meta[name="csrf-token"]').attr("content");
            var fields = {
                cert_id: $certid.val(),
                client_email: $email.val(),
                client_phone: $phone.val(),
                client_tarif: $("input[name='tariflist']:checked").val(),
                //Payment option
                // credit_type: $(".ccjs-type-read-only").html(),
                // credit_number: $("input.ccjs-number-formatted").val(),
                // credit_csc: $("input.ccjs-csc").val(),
                // credit_name: $("input.ccjs-name").val(),
                // credit_month: $(".ccjs-month option:selected").text(),
                // credit_year: $(".ccjs-year option:selected").text(),
                id_source: 'order',
                _csrf: csrfToken
            };
            var l = Ladda.create(this);
            l.start();
            sendRequest(fields);
            /*
            self.children().fadeOut(300, function () {
                $(this).remove()
            });
            $('<p class="success"><span class="success-huge">' + ithank + '</span> <br> ' + iyour + '</p>').appendTo(self).hide().delay(300).fadeIn();
            */
        });// end submit

        function sendRequest(fields) {
            $.post("/certification/certorder", fields, function (json) {
                 var obj = $.parseJSON(json);
                 var my_form = document.createElement('FORM');
                 my_form.name = 'myForm';
                 my_form.method = 'POST';
                 my_form.action = obj.form_url;
                 var my_tb = document.createElement('INPUT');
                 my_tb.type='TEXT';
                 my_tb.name='Ds_SignatureVersion';
                 my_tb.value=obj.ds_signatureversion;
                 my_form.appendChild(my_tb);
                 var my_tb = document.createElement('INPUT');
                 my_tb.type='TEXT';
                 my_tb.name='Ds_MerchantParameters';
                 my_tb.value=obj.ds_merchantparameters;
                 my_form.appendChild(my_tb);
                 my_tb=document.createElement('INPUT');
                 my_tb.type='TEXT';
                 my_tb.name='Ds_Signature';
                 my_tb.value=obj.ds_signature;
                 my_form.appendChild(my_tb);
                 document.body.appendChild(my_form);
                 my_form.submit();
            });
        }

        $('.login').submit(function (e) {

            e.preventDefault();
            var error = 0;
            var self = $(this);

            var $email = self.find('[type=email]');
            var $pass = self.find('[type=password]');


            var emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;

            if (!emailRegex.test($email.val())) {
                createErrTult("Error! Wrong email!", $email)
                error++;
            }

            if ($pass.val().length > 1 && $pass.val() != $pass.attr('placeholder')) {
                $pass.removeClass('invalid_field');
            }
            else {
                createErrTult('Error! Wrong password!', $pass)
                error++;
            }


            if (error != 0)return;
            self.find('[type=submit]').attr('disabled', 'disabled');

            self.children().fadeOut(300, function () {
                $(this).remove()
            })
            $('<p class="login__title">sign in <br><span class="login-edition">welcome to A.Movie</span></p><p class="success">You have successfully<br> signed in!</p>').appendTo(self)
                .hide().delay(300).fadeIn();


            // var formInput = self.serialize();
            // $.post(self.attr('action'),formInput, function(data){}); // end post
        }); // end submit

        function createErrTult(text, $elem) {
            $elem.focus();
            $('<p />', {
                'class': 'inv-em alert alert-danger',
                'html': '<span class="icon-warning"></span>' + text + ' <a class="close" data-dismiss="alert" href="#" aria-hidden="true"></a>',
            })
                .appendTo($elem.addClass('invalid_field').parent())
                .insertAfter($elem)
                .delay(4000).animate({'opacity': 0}, 300, function () {
                $(this).slideUp(400, function () {
                    $(this).remove()
                })
            });
        }
});

