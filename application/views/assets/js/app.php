if (window['parseDateProto'] == undefined) {
    var parseDateProto = Object.create(HTMLElement.prototype);
    parseDateProto.createdCallback = function() {
      var t = new Date(parseInt(this.innerHTML+'000'))
      this.innerHTML = t.toLocaleString()
    };
    document.registerElement('parse-date', {prototype: parseDateProto})
}

function getQueryStringValue(key) {  
    return decodeURIComponent(window.location.search.replace(new RegExp("^(?:.*[&\\?]" + encodeURIComponent(key).replace(/[\.\+\*]/g, "\\$&") + "(?:\\=([^&]*))?)?.*$", "i"), "$1"))
}

function navigate(pathname) {
    if (pathname != window.location.pathname) {
        Turbolinks.visit(pathname, { action: "advance" })
    }
}

function select_pic() {
    $('#upload-avatar').click()
}

function process_pic(holder) {
    var pic = document.getElementById(holder);
    if (pic.files.length > 0) {
        var fileName = pic.files[0].name;
        var fileType = pic.files[0].type;
        var reader = new FileReader();
        reader.readAsDataURL(pic.files[0]);
        reader.onload = function(event) {
            var img = new Image();
            img.src = event.target.result;
            img.onload = function() {
                var elem = document.createElement('canvas');
                var scale = img.naturalWidth / 120;
                elem.width = (img.naturalWidth/scale);
                elem.height = (img.naturalHeight/scale);
                var ctx = elem.getContext('2d');
                ctx.drawImage(img, 0, 0, (img.naturalWidth/scale), (img.naturalHeight/scale));
                // console.log(ctx.canvas.toDataURL('image/jpeg', .50))
                uploadAvatar(ctx.canvas.toDataURL('image/jpeg', .50))
                document.getElementById(holder).value = "";
                if (!/safari/i.test(navigator.userAgent)) {
                    document.getElementById(holder).type = ''
                    document.getElementById(holder).type = 'file'
                }
                //ctx.canvas.toBlob(function(blob) {
                //    var file = new File([blob], fileName, {
                //        type: fileType,
                //        lastModified: Date.now()
                //    });
                //    var freader = new FileReader();
                //    freader.readAsDataURL(file);
                //    freader.onload = function(e) {
                //        console.log(e.target.result)
                //    };
                //    freader.onerror = function(error) {
                //        console.log(error);
                //    }
                //}, 'image/jpeg', 1);
            }
        };
        reader.onerror = function(error) {
            console.log(error);
        }
    }
}

function show_danger_message(text) {
    $('#dangerMessage').text(text)
    $('#dangerMessage').removeClass('sr-only')
    $('#dangerMessage').removeClass('fade')
    $('#dangerMessage').addClass('show')
    $('#dangerMessage').append('<button type="button" class="close" aria-label="Close" onclick="hide_danger_message()"><span aria-hidden="true">&times;</span></button>')
}

function hide_danger_message() {
    $('#dangerMessage').removeClass('show')
    //$('#dangerMessage').addClass('sr-only')
    $('#dangerMessage').addClass('fade')
    $('#dangerMessage').text('')
}

function goBack() {
    if (window.history.length <= 2) {
        Turbolinks.visit("/", { action: "replace" })
    } else {
        window.history.go(-1)
    }
}

function uploadAvatar(data) {
    hide_danger_message()
    var data = {
        'avatar': data,
    }
    data[window.csrf_token_name] = window.csrf_hash
    var request = $.ajax({
        url: "/authentication/upload_avatar",
        method: "POST",
        data: data,
        dataType: "json"
    })
    request.done(function(data) {
        console.log(data.message)
        if (data.redirect != undefined) {
            Turbolinks.visit(data.redirect, { action: "replace" })
        }
    })
    request.fail(function(jqXHR) {
        $('button.enabled').removeAttr("disabled")
        if (jqXHR.responseJSON.message != undefined) {
            show_danger_message(jqXHR.responseJSON.message)
        }
    })
}

function deleteToken(id) {
    var text = '<?php echo lang('L_CONFIRM_REMOVE')?>'
    if (confirm(text.replace('%s', id))) {
        $('button.enabled').attr("disabled", "disabled")
        hide_danger_message()
        var data = {
            'id': id,
        }
        data[window.csrf_token_name] = window.csrf_hash
        var request = $.ajax({
            url: "/authentication/delete_token",
            method: "POST",
            data: data,
            dataType: "json"
        })
        request.done(function(data) {
            console.log(data.message)
            if (data.redirect != undefined) {
                Turbolinks.visit(data.redirect, { action: "replace" })
            }
        })
        request.fail(function(jqXHR) {
            $('button.enabled').removeAttr("disabled")
            if (jqXHR.responseJSON.message != undefined) {
                show_danger_message(jqXHR.responseJSON.message)
            }
        })
    }
}

$(document).ready(function() {

    $("form").submit(function(event) {
        event.preventDefault()
    })

    $('#toggle_dropdown').click(function() {
        if($('#menu_dropdown').hasClass('show')) {
            $('#menu_dropdown').removeClass('show')
        } else {
            $('#menu_dropdown').addClass('show')
        }
    })

    $('#home_btn').mousedown(function() {
        if ((window.location.toString() != window.location.protocol+'//'+window.location.host+'/') && (window.location.toString() != window.location.protocol+'//'+window.location.host+'/#')) {
            Turbolinks.visit("/", { action: "replace" })
        }
    })
    
    $('#back_btn_sm').mousedown(function() {
        goBack()
    })

    $('#back_btn_md').mousedown(function() {
        goBack()
    })

    $('#lgn_btn').click(function(event) {
        $('#lgn_btn').attr("disabled", "disabled")
        hide_danger_message()
        $('#inputEmailError').removeClass('border-danger')
        $('#inputEmailErrorText').text('')
        $('#inputPasswordError').removeClass('border-danger')
        $('#inputPasswordErrorText').text('')
        var data = {
            'email': $('#inputEmail').val(),
            'password': $('#inputPassword').val(),
            'remember_me': $('#inputRememberMe').prop('checked'),
        }
        data[window.csrf_token_name] = window.csrf_hash
        var request = $.ajax({
            url: "/authentication/login",
            method: "POST",
            data: data,
            dataType: "json"
        })
        request.done(function(data) {
            console.log(data.message)
            if (data.redirect != undefined) {
                Turbolinks.visit(data.redirect, { action: "replace" })
            }
        })
        request.fail(function(jqXHR) {
            $('#lgn_btn').removeAttr("disabled")
            if (jqXHR.responseJSON != undefined) {
                if (jqXHR.responseJSON.message != undefined) {
                    show_danger_message(jqXHR.responseJSON.message)
                }
                if (jqXHR.responseJSON.errors != undefined) {
                    if (jqXHR.responseJSON.errors.password != undefined) {
                        $('#inputPasswordError').addClass('border-danger')
                        $('#inputPasswordErrorText').text(jqXHR.responseJSON.errors.password)
                    }
                    if (jqXHR.responseJSON.errors.email != undefined) {
                        $('#inputEmailError').addClass('border-danger')
                        $('#inputEmailErrorText').text(jqXHR.responseJSON.errors.email)
                    }
                }
            }
        })
    })

    $('#rgstr_btn').click(function(event) {
        $('#rgstr_btn').attr("disabled", "disabled")
        hide_danger_message()
        $('#inputUsernameError').removeClass('border-danger')
        $('#inputUsernameErrorText').text('')
        $('#inputEmailError').removeClass('border-danger')
        $('#inputEmailErrorText').text('')
        $('#inputPasswordError').removeClass('border-danger')
        $('#inputPasswordErrorText').text('')
        $('#inputConfirmPasswordError').removeClass('border-danger')
        $('#inputConfirmPasswordErrorText').text('')
        var data = {
            'username': $('#inputUsername').val(),
            'email': $('#inputEmail').val(),
            'password': $('#inputPassword').val(),
            'confirm_password': $('#inputConfirmPassword').val(),
        }
        data[window.csrf_token_name] = window.csrf_hash
        var request = $.ajax({
            url: "/authentication/register",
            method: "POST",
            data: data,
            dataType: "json"
        })
        request.done(function(data) {
            console.log(data.message)
            if (data.redirect != undefined) {
                Turbolinks.visit(data.redirect, { action: "replace" })
            }
        })
        request.fail(function(jqXHR) {
            $('#rgstr_btn').removeAttr("disabled")
            if (jqXHR.responseJSON != undefined) {
                if (jqXHR.responseJSON.message != undefined) {
                    show_danger_message(jqXHR.responseJSON.message)
                }
                if (jqXHR.responseJSON.errors != undefined) {
                    if (jqXHR.responseJSON.errors.confirm_password != undefined) {
                        $('#inputConfirmPasswordError').addClass('border-danger')
                        $('#inputConfirmPasswordErrorText').text(jqXHR.responseJSON.errors.confirm_password)
                    }
                    if (jqXHR.responseJSON.errors.password != undefined) {
                        $('#inputPasswordError').addClass('border-danger')
                        $('#inputPasswordErrorText').text(jqXHR.responseJSON.errors.password)
                    }
                    if (jqXHR.responseJSON.errors.email != undefined) {
                        $('#inputEmailError').addClass('border-danger')
                        $('#inputEmailErrorText').text(jqXHR.responseJSON.errors.email)
                    }
                    if (jqXHR.responseJSON.errors.username != undefined) {
                        $('#inputUsernameError').addClass('border-danger')
                        $('#inputUsernameErrorText').text(jqXHR.responseJSON.errors.username)
                    }
                }
            }
        })
    })

    $('#frgt_pswd_btn').click(function(event) {
        $('#frgt_pswd_btn').attr("disabled", "disabled")
        hide_danger_message()
        $('#inputEmailError').removeClass('border-danger')
        $('#inputEmailErrorText').text('')
        var data = {
            'email': $('#inputEmail').val(),
        }
        data[window.csrf_token_name] = window.csrf_hash
        var request = $.ajax({
            url: "/authentication/forgot_password",
            method: "POST",
            data: data,
            dataType: "json"
        })
        request.done(function(data) {
            console.log(data.message)
            if (data.redirect != undefined) {
                Turbolinks.visit(data.redirect, { action: "replace" })
            }
        })
        request.fail(function(jqXHR) {
            $('#frgt_pswd_btn').removeAttr("disabled")
            if (jqXHR.responseJSON != undefined) {
                if (jqXHR.responseJSON.message != undefined) {
                    show_danger_message(jqXHR.responseJSON.message)
                }
                if (jqXHR.responseJSON.errors != undefined) {
                    if (jqXHR.responseJSON.errors.email != undefined) {
                        $('#inputEmailError').addClass('border-danger')
                        $('#inputEmailErrorText').text(jqXHR.responseJSON.errors.email)
                    }
                }
            }
        })
    })

    $('#actvt_acct_btn').click(function(event) {
        $('#actvt_acct_btn').attr("disabled", "disabled")
        hide_danger_message()
        $('#inputEmailError').removeClass('border-danger')
        $('#inputEmailErrorText').text('')
        var data = {
            'email': $('#inputEmail').val(),
        }
        data[window.csrf_token_name] = window.csrf_hash
        var request = $.ajax({
            url: "/authentication/activate_account",
            method: "POST",
            data: data,
            dataType: "json"
        })
        request.done(function(data) {
            console.log(data.message)
            if (data.redirect != undefined) {
                Turbolinks.visit(data.redirect, { action: "replace" })
            }
        })
        request.fail(function(jqXHR) {
            $('#actvt_acct_btn').removeAttr("disabled")
            if (jqXHR.responseJSON != undefined) {
                if (jqXHR.responseJSON.message != undefined) {
                    show_danger_message(jqXHR.responseJSON.message)
                }
                if (jqXHR.responseJSON.errors != undefined) {
                    if (jqXHR.responseJSON.errors.email != undefined) {
                        $('#inputEmailError').addClass('border-danger')
                        $('#inputEmailErrorText').text(jqXHR.responseJSON.errors.email)
                    }
                }
            }
        })
    })

    $('#rst_btn').click(function(event) {
        $('#rst_btn').attr("disabled", "disabled")
        hide_danger_message()
        $('#inputNewPasswordError').removeClass('border-danger')
        $('#inputNewPasswordErrorText').text('')
        $('#inputConfirmPasswordError').removeClass('border-danger')
        $('#inputConfirmPasswordErrorText').text('')
        var data = {
            'token': getQueryStringValue("token"),
            'new_password': $('#inputNewPassword').val(),
            'confirm_password': $('#inputConfirmPassword').val(),
        }
        data[window.csrf_token_name] = window.csrf_hash
        var request = $.ajax({
            url: "/authentication/reset_password",
            method: "POST",
            data: data,
            dataType: "json"
        })
        request.done(function(data) {
            console.log(data.message)
            if (data.redirect != undefined) {
                Turbolinks.visit(data.redirect, { action: "replace" })
            }
        })
        request.fail(function(jqXHR) {
            $('#rst_btn').removeAttr("disabled")
            if (jqXHR.responseJSON != undefined) {
                if (jqXHR.responseJSON.message != undefined) {
                    show_danger_message(jqXHR.responseJSON.message)
                }
                if (jqXHR.responseJSON.errors != undefined) {
                    if (jqXHR.responseJSON.errors.confirm_password != undefined) {
                        $('#inputConfirmPasswordError').addClass('border-danger')
                        $('#inputConfirmPasswordErrorText').text(jqXHR.responseJSON.errors.confirm_password)
                    }
                    if (jqXHR.responseJSON.errors.new_password != undefined) {
                        $('#inputNewPasswordError').addClass('border-danger')
                        $('#inputNewPasswordErrorText').text(jqXHR.responseJSON.errors.new_password)
                    }
                    if (jqXHR.responseJSON.errors.token != undefined) {
                        show_danger_message(jqXHR.responseJSON.errors.token)
                    }
                }
            }
        })
    })

    $('#uptd_btn').click(function(event) {
        $('#uptd_btn').attr("disabled", "disabled")
        hide_danger_message()
        $('#inputOldPasswordError').removeClass('border-danger')
        $('#inputOldPasswordErrorText').text('')
        $('#inputNewPasswordError').removeClass('border-danger')
        $('#inputNewPasswordErrorText').text('')
        $('#inputConfirmPasswordError').removeClass('border-danger')
        $('#inputConfirmPasswordErrorText').text('')
        var data = {
            'old_password': $('#inputOldPassword').val(),
            'new_password': $('#inputNewPassword').val(),
            'confirm_password': $('#inputConfirmPassword').val(),
        }
        data[window.csrf_token_name] = window.csrf_hash
        var request = $.ajax({
            url: "/authentication/update_password",
            method: "POST",
            data: data,
            dataType: "json"
        })
        request.done(function(data) {
            console.log(data.message)
            if (data.redirect != undefined) {
                Turbolinks.visit(data.redirect, { action: "replace" })
            }
        })
        request.fail(function(jqXHR) {
            $('#uptd_btn').removeAttr("disabled")
            if (jqXHR.responseJSON != undefined) {
                if (jqXHR.responseJSON.message != undefined) {
                    $('#dangerMessage').text(jqXHR.responseJSON.message)
                    $('#dangerMessage').removeClass('sr-only')
                }
                if (jqXHR.responseJSON.errors != undefined) {
                    if (jqXHR.responseJSON.errors.confirm_password != undefined) {
                        $('#inputConfirmPasswordError').addClass('border-danger')
                        $('#inputConfirmPasswordErrorText').text(jqXHR.responseJSON.errors.confirm_password)
                    }
                    if (jqXHR.responseJSON.errors.new_password != undefined) {
                        $('#inputNewPasswordError').addClass('border-danger')
                        $('#inputNewPasswordErrorText').text(jqXHR.responseJSON.errors.new_password)
                    }
                    if (jqXHR.responseJSON.errors.old_password != undefined) {
                        $('#inputOldPasswordError').addClass('border-danger')
                        $('#inputOldPasswordErrorText').text(jqXHR.responseJSON.errors.old_password)
                    }
                }
            }
        })
    })

    $('#logout_btn').click(function(event) {
        if (confirm('<?php echo lang('L_CONFIRM_LOGOUT')?>') == false) {
            return
        }
        var request = $.ajax({
            url: "/authentication/log_out",
            method: "GET",
            dataType: "json"
        })
        request.done(function(data) {
            console.log(data.message)
            if (data.redirect != undefined) {
                Turbolinks.visit(data.redirect, { action: "replace" })
            }
        })
        request.fail(function(jqXHR) {
            console.log(jqXHR.responseJSON)
        })
    })
})
