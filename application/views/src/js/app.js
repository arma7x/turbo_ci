function getCookie(name) {
  var d = []
  var e = document.cookie.split(';')
  var a = RegExp(`^\\s*${name}=\\s*(.*?)\\s*$`)
  for (var b = 0; b < e.length; b++) {
    var f = e[b].match(a)
    f && d.push(f[1])
  }
  return d
}

function getQueryStringValue(key) {  
    return decodeURIComponent(window.location.search.replace(new RegExp("^(?:.*[&\\?]" + encodeURIComponent(key).replace(/[\.\+\*]/g, "\\$&") + "(?:\\=([^&]*))?)?.*$", "i"), "$1"))
}

function selectPic() {
    $('#upload-avatar').click()
}

function processPic(holder) {
    var pic = document.getElementById(holder)
    if (pic.files.length > 0) {
        var fileName = pic.files[0].name
        var fileType = pic.files[0].type
        var reader = new FileReader()
        reader.readAsDataURL(pic.files[0])
        reader.onload = function(event) {
            var img = new Image()
            img.src = event.target.result
            img.onload = function() {
                var elem = document.createElement('canvas')
                var scale = img.naturalWidth / 120
                elem.width = (img.naturalWidth/scale)
                elem.height = (img.naturalHeight/scale)
                var ctx = elem.getContext('2d')
                ctx.drawImage(img, 0, 0, (img.naturalWidth/scale), (img.naturalHeight/scale))
                // console.log(ctx.canvas.toDataURL('image/jpeg', .50))
                uploadAvatar(ctx.canvas.toDataURL('image/jpeg', .50))
                document.getElementById(holder).value = ""
                if (!/safari/i.test(navigator.userAgent)) {
                    document.getElementById(holder).type = ''
                    document.getElementById(holder).type = 'file'
                }
                //ctx.canvas.toBlob(function(blob) {
                //    var file = new File([blob], fileName, {
                //        type: fileType,
                //        lastModified: Date.now()
                //    })
                //    var freader = new FileReader()
                //    freader.readAsDataURL(file)
                //    freader.onload = function(e) {
                //        console.log(e.target.result)
                //    }
                //    freader.onerror = function(error) {
                //        console.log(error)
                //    }
                //}, 'image/jpeg', 1)
            }
        }
        reader.onerror = function(error) {
            console.log(error)
        }
    }
}

function uploadAvatar(data) {
    hideDangerMessage()
    var data = {
        'avatar': data,
    }
    data[window.csrf_token_name] = window.csrf_hash
    loadingSpinner(true)
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
            showDangerMessage(jqXHR.responseJSON.message)
        }
    })
    request.always(function() {
        loadingSpinner(false)
    });
}

function deleteToken(id) {
    var text = '<?php echo lang('L_CONFIRM_REMOVE')?>'
    if (confirm(text.replace('%s', id))) {
        $('button.enabled').attr("disabled", "disabled")
        hideDangerMessage()
        var data = {
            'id': id,
        }
        data[window.csrf_token_name] = window.csrf_hash
        loadingSpinner(true)
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
                showDangerMessage(jqXHR.responseJSON.message)
            }
        })
        request.always(function() {
            loadingSpinner(false)
        });
    }
}

function searchUser() {
    var data = {
        'keyword': $("#keyword").val(),
        'role': $("#role").val(),
        'access_level': $("#access_level").val(),
        'status': $("#status").val(),
    }
    var query = []
    for (key in data) {
        if (data[key] != '') {
            query.push(key+'='+data[key])
        }
    }
    if (query.length > 0) {
        Turbolinks.visit(document.location.pathname+'?'+query.join('&'), { action: "replace" })
    } else {
        Turbolinks.visit(document.location.pathname, { action: "replace" })
    }
}

function updateRole(id) {
    var text = '<?php echo lang('L_CONFIRM_UPDATE_ROLE')?>'
    if (confirm(text.replace('%s', id))) {
        $('button.enabled').attr("disabled", "disabled")
        hideDangerMessage()
        var data = {
            'id': id,
            'role': $('#role_'+id).val(),
        }
        data[window.csrf_token_name] = window.csrf_hash
        loadingSpinner(true)
        var request = $.ajax({
            url: "/manage_user/update_user_role",
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
                showDangerMessage(jqXHR.responseJSON.message)
            }
        })
        request.always(function() {
            loadingSpinner(false)
        });
    }
}

function updateAccessLevel(id) {
    var text = '<?php echo lang('L_CONFIRM_UPDATE_ACCESS_LEVEL')?>'
    if (confirm(text.replace('%s', id))) {
        $('button.enabled').attr("disabled", "disabled")
        hideDangerMessage()
        var data = {
            'id': id,
            'access_level': $('#access_level_'+id).val(),
        }
        data[window.csrf_token_name] = window.csrf_hash
        loadingSpinner(true)
        var request = $.ajax({
            url: "/manage_user/update_user_access_level",
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
                showDangerMessage(jqXHR.responseJSON.message)
            }
        })
        request.always(function() {
            loadingSpinner(false)
        });
    }
}

function updateStatus(id) {
    var text = '<?php echo lang('L_CONFIRM_UPDATE_STATUS')?>'
    if (confirm(text.replace('%s', id))) {
        $('button.enabled').attr("disabled", "disabled")
        hideDangerMessage()
        var data = {
            'id': id,
            'status': $('#status_'+id).val(),
        }
        data[window.csrf_token_name] = window.csrf_hash
        loadingSpinner(true)
        var request = $.ajax({
            url: "/manage_user/update_user_status",
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
                showDangerMessage(jqXHR.responseJSON.message)
            }
        })
        request.always(function() {
            loadingSpinner(false)
        });
    }
}

function deleteUser(id) {
    var text = '<?php echo lang('L_CONFIRM_REMOVE')?>'
    if (confirm(text.replace('%s', id))) {
        $('button.enabled').attr("disabled", "disabled")
        hideDangerMessage()
        var data = {
            'id': id,
        }
        data[window.csrf_token_name] = window.csrf_hash
        loadingSpinner(true)
        var request = $.ajax({
            url: "/manage_user/delete_user",
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
                showDangerMessage(jqXHR.responseJSON.message)
            }
        })
        request.always(function() {
            loadingSpinner(false)
        });
    }
}

function change_language(lang) {
    if (lang == null || lang == undefined || getCookie('lang')[0] == lang) {
        return
    }
    var data = {
        'lang': lang,
    }
    data[window.csrf_token_name] = window.csrf_hash
    loadingSpinner(true)
    var request = $.ajax({
        url: "/language",
        method: "POST",
        data: data,
        dataType: "json"
    })
    request.done(function(data) {
        console.log(data.message)
        document.location.reload()
    })
    request.fail(function(jqXHR) {
        $('button.enabled').removeAttr("disabled")
    })
    request.always(function() {
        loadingSpinner(false)
    });
}

function loadingSpinner(status) {
    if (status == true) {
        $('#loading_spinner').show()
    } else {
        $('#loading_spinner').hide()
    }
}

function showDangerMessage(text) {
    $('#dangerMessage').text(text)
    $('#dangerMessage').removeClass('sr-only')
    $('#dangerMessage').removeClass('fade')
    $('#dangerMessage').addClass('show')
    $('#dangerMessage').append('<button type="button" class="close" aria-label="Close" onclick="hideDangerMessage()"><span aria-hidden="true">&times;</span></button>')
}

function hideDangerMessage() {
    $('#dangerMessage').removeClass('show')
    // $('#dangerMessage').addClass('sr-only')
    $('#dangerMessage').addClass('fade')
    $('#dangerMessage').text('')
}

function goHome() {
    if ((window.location.toString() != window.location.protocol+'//'+window.location.host+'/') && (window.location.toString() != window.location.protocol+'//'+window.location.host+'/#')) {
        Turbolinks.visit("/", { action: "replace" })
    }
}
    
function goBack() {
    if (window.history.length <= 2) {
        Turbolinks.visit("/", { action: "replace" })
    } else {
        window.history.go(-1)
    }
}

function navigate(pathname) {
    if (pathname != window.location.pathname) {
        Turbolinks.visit(pathname, { action: "advance" })
    }
}

$(document).ready(function() {

    loadingSpinner(false)

    $("form").submit(function(event) {
        event.preventDefault()
    })

    $("#navbar-toggler").click(function() {
        if ($("#navCollapsed").hasClass('show')) {
            $("#navmenu_icon").text("menu")
        } else {
            $("#navmenu_icon").text("close")
        }
    })

    $('#toggle_dropdown').click(function() {
        if($('#menu_dropdown').hasClass('show')) {
            $('#menu_dropdown').removeClass('show')
        } else {
            $('#menu_dropdown').addClass('show')
        }
    })

    $('#lgn_btn').click(function(event) {
        $('#lgn_btn').attr("disabled", "disabled")
        hideDangerMessage()
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
        loadingSpinner(true)
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
                    showDangerMessage(jqXHR.responseJSON.message)
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
        request.always(function() {
            loadingSpinner(false)
        });
    })

    $('#rgstr_btn').click(function(event) {
        $('#rgstr_btn').attr("disabled", "disabled")
        hideDangerMessage()
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
        loadingSpinner(true)
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
                    showDangerMessage(jqXHR.responseJSON.message)
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
        request.always(function() {
            loadingSpinner(false)
        });
    })

    $('#frgt_pswd_btn').click(function(event) {
        $('#frgt_pswd_btn').attr("disabled", "disabled")
        hideDangerMessage()
        $('#inputEmailError').removeClass('border-danger')
        $('#inputEmailErrorText').text('')
        var data = {
            'email': $('#inputEmail').val(),
        }
        data[window.csrf_token_name] = window.csrf_hash
        loadingSpinner(true)
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
                    showDangerMessage(jqXHR.responseJSON.message)
                }
                if (jqXHR.responseJSON.errors != undefined) {
                    if (jqXHR.responseJSON.errors.email != undefined) {
                        $('#inputEmailError').addClass('border-danger')
                        $('#inputEmailErrorText').text(jqXHR.responseJSON.errors.email)
                    }
                }
            }
        })
        request.always(function() {
            loadingSpinner(false)
        });
    })

    $('#actvt_acct_btn').click(function(event) {
        $('#actvt_acct_btn').attr("disabled", "disabled")
        hideDangerMessage()
        $('#inputEmailError').removeClass('border-danger')
        $('#inputEmailErrorText').text('')
        var data = {
            'email': $('#inputEmail').val(),
        }
        data[window.csrf_token_name] = window.csrf_hash
        loadingSpinner(true)
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
                    showDangerMessage(jqXHR.responseJSON.message)
                }
                if (jqXHR.responseJSON.errors != undefined) {
                    if (jqXHR.responseJSON.errors.email != undefined) {
                        $('#inputEmailError').addClass('border-danger')
                        $('#inputEmailErrorText').text(jqXHR.responseJSON.errors.email)
                    }
                }
            }
        })
        request.always(function() {
            loadingSpinner(false)
        });
    })

    $('#rst_btn').click(function(event) {
        $('#rst_btn').attr("disabled", "disabled")
        hideDangerMessage()
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
        loadingSpinner(true)
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
                    showDangerMessage(jqXHR.responseJSON.message)
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
                        showDangerMessage(jqXHR.responseJSON.errors.token)
                    }
                }
            }
        })
        request.always(function() {
            loadingSpinner(false)
        });
    })

    $('#uptd_btn').click(function(event) {
        $('#uptd_btn').attr("disabled", "disabled")
        hideDangerMessage()
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
        loadingSpinner(true)
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
        request.always(function() {
            loadingSpinner(false)
        });
    })

    $('#logout_btn').click(function(event) {
        if (confirm('<?php echo lang('L_CONFIRM_LOGOUT')?>') == false) {
            return
        }
        var data = {}
        data[window.csrf_token_name] = window.csrf_hash
        loadingSpinner(true)
        var request = $.ajax({
            url: "/authentication/log_out",
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
            console.log(jqXHR.responseJSON)
        })
        request.always(function() {
            loadingSpinner(false)
        });
    })
})
