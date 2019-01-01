$(document).ready(function() {

    function goBack() {
        if (window.history.length <= 2) {
            //window.location.replace('/');
            Turbolinks.visit("/", { action: "replace" });
        } else {
            window.history.back()
        }
    }

    $("form").submit(function(event) {
        event.preventDefault(); 
    });
    
    $('#home_btn').mousedown(function() {
        if ((window.location.toString() != window.location.protocol+'//'+window.location.host+'/') && (window.location.toString() != window.location.protocol+'//'+window.location.host+'/#')) {
            //window.location.replace('/');
            Turbolinks.visit("/", { action: "replace" });
        }
    });
    
    $('#back_btn_sm').mousedown(function() {
        goBack();
    });

    $('#back_btn_md').mousedown(function() {
        goBack();
    });

    $('#lgn_btn').click(function(event) {
        $('#lgn_btn').attr("disabled", "disabled");
        $('#formMessage').addClass('sr-only');
        $('#formMessage').text('');
        $('#inputEmailError').removeClass('border-danger');
        $('#inputEmailErrorText').text('');
        $('#inputPasswordError').removeClass('border-danger');
        $('#inputPasswordErrorText').text('');
        var data = {
            'email': $('#inputEmail').val(),
            'password': $('#inputPassword').val(),
            'remember_me': $('#inputRememberMe').prop('checked'),
        }
        data[window.csrf_token_name] = window.csrf_hash;
        var request = $.ajax({
            url: "/internal-api/guest/login",
            method: "POST",
            data: data,
            dataType: "json"
        });
        request.done(function(data) {
            if (data.message != undefined) {
                alert(data.message);
            }
            if (data.redirect != undefined) {
                //.replace(data.redirect);
                Turbolinks.visit(data.redirect, { action: "replace" })
            }
        });
        request.fail(function(jqXHR) {
            if (jqXHR.responseJSON != undefined) {
                if (jqXHR.responseJSON.message != undefined) {
                    $('#formMessage').text(jqXHR.responseJSON.message);
                    $('#formMessage').removeClass('sr-only');
                }
                if (jqXHR.responseJSON.errors != undefined) {
                    if (jqXHR.responseJSON.errors.password != undefined) {
                        $('#inputPasswordError').addClass('border-danger')
                        $('#inputPasswordErrorText').text(jqXHR.responseJSON.errors.password);
                    }
                    if (jqXHR.responseJSON.errors.email != undefined) {
                        $('#inputEmailError').addClass('border-danger');
                        $('#inputEmailErrorText').text(jqXHR.responseJSON.errors.email);
                    }
                }
            }
        });
        request.always(function() {
            $('#lgn_btn').removeAttr("disabled");
        });
    });

    $('#rgstr_btn').click(function(event) {
        $('#rgstr_btn').attr("disabled", "disabled");
        $('#formMessage').addClass('sr-only');
        $('#formMessage').text('');
        $('#inputUsernameError').removeClass('border-danger');
        $('#inputUsernameErrorText').text('');
        $('#inputEmailError').removeClass('border-danger');
        $('#inputEmailErrorText').text('');
        $('#inputPasswordError').removeClass('border-danger');
        $('#inputPasswordErrorText').text('');
        $('#inputConfirmPasswordError').removeClass('border-danger');
        $('#inputConfirmPasswordErrorText').text('');
        var data = {
            'username': $('#inputUsername').val(),
            'email': $('#inputEmail').val(),
            'password': $('#inputPassword').val(),
            'confirm_password': $('#inputConfirmPassword').val(),
        }
        data[window.csrf_token_name] = window.csrf_hash;
        var request = $.ajax({
            url: "/internal-api/guest/register",
            method: "POST",
            data: data,
            dataType: "json"
        });
        request.done(function(data) {
            if (data.message != undefined) {
                alert(data.message);
            }
            if (data.redirect != undefined) {
                //window.location.replace(data.redirect);
                Turbolinks.visit(data.redirect, { action: "replace" })
            }
        });
        request.fail(function(jqXHR) {
            if (jqXHR.responseJSON != undefined) {
                if (jqXHR.responseJSON.message != undefined) {
                    $('#formMessage').text(jqXHR.responseJSON.message);
                    $('#formMessage').removeClass('sr-only');
                }
                if (jqXHR.responseJSON.errors != undefined) {
                    if (jqXHR.responseJSON.errors.confirm_password != undefined) {
                        $('#inputConfirmPasswordError').addClass('border-danger')
                        $('#inputConfirmPasswordErrorText').text(jqXHR.responseJSON.errors.confirm_password);
                    }
                    if (jqXHR.responseJSON.errors.password != undefined) {
                        $('#inputPasswordError').addClass('border-danger')
                        $('#inputPasswordErrorText').text(jqXHR.responseJSON.errors.password);
                    }
                    if (jqXHR.responseJSON.errors.email != undefined) {
                        $('#inputEmailError').addClass('border-danger');
                        $('#inputEmailErrorText').text(jqXHR.responseJSON.errors.email);
                    }
                    if (jqXHR.responseJSON.errors.username != undefined) {
                        $('#inputUsernameError').addClass('border-danger');
                        $('#inputUsernameErrorText').text(jqXHR.responseJSON.errors.username);
                    }
                }
            }
        });
        request.always(function() {
            $('#rgstr_btn').removeAttr("disabled");
        });
    });

    $('#frgt_pswd_btn').click(function(event) {
        $('#frgt_pswd_btn').attr("disabled", "disabled");
        $('#formMessage').addClass('sr-only');
        $('#formMessage').text('');
        $('#inputEmailError').removeClass('border-danger');
        $('#inputEmailErrorText').text('');
        var data = {
            'email': $('#inputEmail').val(),
        }
        data[window.csrf_token_name] = window.csrf_hash;
        var request = $.ajax({
            url: "/internal-api/guest/forgot_password",
            method: "POST",
            data: data,
            dataType: "json"
        });
        request.done(function(data) {
            if (data.message != undefined) {
                alert(data.message);
            }
            if (data.redirect != undefined) {
                //window.location.replace(data.redirect);
                Turbolinks.visit(data.redirect, { action: "replace" })
            }
        });
        request.fail(function(jqXHR) {
            if (jqXHR.responseJSON != undefined) {
                if (jqXHR.responseJSON.message != undefined) {
                    $('#formMessage').text(jqXHR.responseJSON.message);
                    $('#formMessage').removeClass('sr-only');
                }
                if (jqXHR.responseJSON.errors != undefined) {
                    if (jqXHR.responseJSON.errors.email != undefined) {
                        $('#inputEmailError').addClass('border-danger');
                        $('#inputEmailErrorText').text(jqXHR.responseJSON.errors.email);
                    }
                }
            }
        });
        request.always(function() {
            $('#frgt_pswd_btn').removeAttr("disabled");
        });
    });

    $('#actvt_acct_btn').click(function(event) {
        $('#actvt_acct_btn').attr("disabled", "disabled");
        $('#formMessage').addClass('sr-only');
        $('#formMessage').text('');
        $('#inputEmailError').removeClass('border-danger');
        $('#inputEmailErrorText').text('');
        var data = {
            'email': $('#inputEmail').val(),
        }
        data[window.csrf_token_name] = window.csrf_hash;
        var request = $.ajax({
            url: "/internal-api/guest/activate-account",
            method: "POST",
            data: data,
            dataType: "json"
        });
        request.done(function(data) {
            if (data.message != undefined) {
                alert(data.message);
            }
            if (data.redirect != undefined) {
                //.replace(data.redirect);
                Turbolinks.visit(data.redirect, { action: "replace" })
            }
        });
        request.fail(function(jqXHR) {
            if (jqXHR.responseJSON != undefined) {
                if (jqXHR.responseJSON.message != undefined) {
                    $('#formMessage').text(jqXHR.responseJSON.message);
                    $('#formMessage').removeClass('sr-only');
                }
                if (jqXHR.responseJSON.errors != undefined) {
                    if (jqXHR.responseJSON.errors.email != undefined) {
                        $('#inputEmailError').addClass('border-danger');
                        $('#inputEmailErrorText').text(jqXHR.responseJSON.errors.email);
                    }
                }
            }
        });
        request.always(function() {
            $('#actvt_acct_btn').removeAttr("disabled");
        });
    });

    $('#rst_btn').click(function(event) {
        $('#rst_btn').attr("disabled", "disabled");
        $('#formMessage').addClass('sr-only');
        $('#formMessage').text('');
        $('#inputNewPasswordError').removeClass('border-danger');
        $('#inputNewPasswordErrorText').text('');
        $('#inputConfirmPasswordError').removeClass('border-danger');
        $('#inputConfirmPasswordErrorText').text('');
        var data = {
            'new_password': $('#inputNewPassword').val(),
            'confirm_password': $('#inputConfirmPassword').val(),
        }
        data[window.csrf_token_name] = window.csrf_hash;
        var request = $.ajax({
            url: "/internal-api/guest/reset-password",
            method: "POST",
            data: data,
            dataType: "json"
        });
        request.done(function(data) {
            if (data.message != undefined) {
                alert(data.message);
            }
            if (data.redirect != undefined) {
                //.replace(data.redirect);
                Turbolinks.visit(data.redirect, { action: "replace" })
            }
        });
        request.fail(function(jqXHR) {
            if (jqXHR.responseJSON != undefined) {
                if (jqXHR.responseJSON.message != undefined) {
                    $('#formMessage').text(jqXHR.responseJSON.message);
                    $('#formMessage').removeClass('sr-only');
                }
                if (jqXHR.responseJSON.errors != undefined) {
                    if (jqXHR.responseJSON.errors.confirm_password != undefined) {
                        $('#inputConfirmPasswordError').addClass('border-danger')
                        $('#inputConfirmPasswordErrorText').text(jqXHR.responseJSON.errors.confirm_password);
                    }
                    if (jqXHR.responseJSON.errors.new_password != undefined) {
                        $('#inputNewPasswordError').addClass('border-danger');
                        $('#inputNewPasswordErrorText').text(jqXHR.responseJSON.errors.new_password);
                    }
                }
            }
        });
        request.always(function() {
            $('#rst_btn').removeAttr("disabled");
        });
    });

    $('#uptd_btn').click(function(event) {
        $('#uptd_btn').attr("disabled", "disabled");
        $('#formMessage').addClass('sr-only');
        $('#formMessage').text('');
        $('#inputOldPasswordError').removeClass('border-danger');
        $('#inputOldPasswordErrorText').text('');
        $('#inputNewPasswordError').removeClass('border-danger');
        $('#inputNewPasswordErrorText').text('');
        $('#inputConfirmPasswordError').removeClass('border-danger');
        $('#inputConfirmPasswordErrorText').text('');
        var data = {
            'old_password': $('#inputOldPassword').val(),
            'new_password': $('#inputNewPassword').val(),
            'confirm_password': $('#inputConfirmPassword').val(),
        }
        data[window.csrf_token_name] = window.csrf_hash;
        var request = $.ajax({
            url: "/internal-api/auth/update-password",
            method: "POST",
            data: data,
            dataType: "json"
        });
        request.done(function(data) {
            if (data.message != undefined) {
                alert(data.message);
            }
            if (data.redirect != undefined) {
                //.replace(data.redirect);
                Turbolinks.visit(data.redirect, { action: "replace" })
            }
        });
        request.fail(function(jqXHR) {
            if (jqXHR.responseJSON != undefined) {
                if (jqXHR.responseJSON.message != undefined) {
                    $('#formMessage').text(jqXHR.responseJSON.message);
                    $('#formMessage').removeClass('sr-only');
                }
                if (jqXHR.responseJSON.errors != undefined) {
                    if (jqXHR.responseJSON.errors.confirm_password != undefined) {
                        $('#inputConfirmPasswordError').addClass('border-danger')
                        $('#inputConfirmPasswordErrorText').text(jqXHR.responseJSON.errors.confirm_password);
                    }
                    if (jqXHR.responseJSON.errors.new_password != undefined) {
                        $('#inputNewPasswordError').addClass('border-danger');
                        $('#inputNewPasswordErrorText').text(jqXHR.responseJSON.errors.new_password);
                    }
                    if (jqXHR.responseJSON.errors.old_password != undefined) {
                        $('#inputOldPasswordError').addClass('border-danger');
                        $('#inputOldPasswordErrorText').text(jqXHR.responseJSON.errors.old_password);
                    }
                }
            }
        });
        request.always(function() {
            $('#uptd_btn').removeAttr("disabled");
        });
    });

    $('#logout_btn').click(function(event) {
        if (confirm('Are you sure to logout ?') == false) {
            return;
        }
        //$('#logout_btn').attr("disabled", "disabled");
        //var data = {
        //    'email': $('#inputEmail').val(),
        //    'password': $('#inputPassword').val(),
        //}
        //data[window.csrf_token_name] = window.csrf_hash;
        var request = $.ajax({
            url: "/internal-api/auth/log-out",
            method: "GET",
            dataType: "json"
        });
        request.done(function(data) {
            if (data.message != undefined) {
                alert(data.message);
            }
            if (data.redirect != undefined) {
                //.replace(data.redirect);
                Turbolinks.visit(data.redirect, { action: "replace" })
            }
        });
        request.fail(function(jqXHR) {
            console.log(jqXHR.responseJSON);
        });
        request.always(function() {
            //$('#logout_btn').removeAttr("disabled");
        });
    });
});
