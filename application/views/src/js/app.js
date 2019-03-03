function isURL(url) {
    try {
        if (url.charAt(0) == '#') {
            return false
        }
        var expression = /[-a-zA-Z0-9@:%_\+.~#?&//=]{2,256}\.[a-z]{2,4}\b(\/[-a-zA-Z0-9@:%_\+.~#?&//=]*)?/gi
        var regex = new RegExp(expression)
        if (url.match(regex) != null) {
            return true
        }
        var a = new URL(url)
        if (a.origin === document.location.origin) {
            return false
        }
        return true
    } catch(e) {
        return false
    }
}

function isCORS() {
    
}

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

function renderImg(src, id) {
    var img = $(id)
    img.attr('crossOrigin', 'anonymous')
    img.attr('src', src)
}

function resizePicture(element, ratio, width, height, quality, mime, cb, data, blobcb) {
    if(isURL(element) == false) {
        var pic = document.getElementById(element)
        if (pic.files.length > 0) {
            var fileName = pic.files[0].name
            var fileType = (mime||pic.files[0].type)
            var reader = new FileReader()
            reader.readAsDataURL(pic.files[0])
            reader.onload = function(event) {
                var img = new Image()
                img.src = event.target.result
                img.onload = function() {
                    var elem = document.createElement('canvas')
                    var scale = img.naturalWidth/(ratio||1)
                    elem.width = (width||(img.naturalWidth/scale))
                    elem.height = (height||(img.naturalHeight/scale))
                    var ctx = elem.getContext('2d')
                    ctx.drawImage(img, 0, 0, elem.width, elem.height)
                    if (cb != undefined && typeof cb == 'function') {
                        cb(ctx.canvas.toDataURL(fileType, quality), data)
                    }
                    if (blobcb != undefined && typeof blobcb == 'function') {
                        ctx.canvas.toBlob(function(blob) {
                            blobcb(blob, data)
                        }, fileType, 1)
                    }
                    document.getElementById(element).value = ""
                    if (!/safari/i.test(navigator.userAgent)) {
                        document.getElementById(element).type = ''
                        document.getElementById(element).type = 'file'
                    }
                }
            }
            reader.onerror = function(error) {
                console.log(error)
            }
        }
    } else {
        var img = new Image()
        img.crossOrigin = 'Anonymous'
        img.src = element
        img.onload = function() {
            var elem = document.createElement('canvas')
            var scale = img.naturalWidth/(ratio||1)
            elem.width = (width||(img.naturalWidth/scale))
            elem.height = (height||(img.naturalHeight/scale))
            var ctx = elem.getContext('2d')
            ctx.drawImage(img, 0, 0, elem.width, elem.height)
            if (cb != undefined && typeof cb == 'function') {
                cb(ctx.canvas.toDataURL(fileType, quality), data)
            }
            if (blobcb != undefined && typeof blobcb == 'function') {
                ctx.canvas.toBlob(function(blob) {
                    blobcb(blob, data)
                }, fileType, 1)
            }
        }
        img.onerror = function(e) {
            var img = new Image()
            img.src = '/static/img/android-chrome-192x192.png'
            img.onload = function() {
                var elem = document.createElement('canvas')
                var scale = img.naturalWidth/(ratio||1)
                elem.width = (width||(img.naturalWidth/scale))
                elem.height = (height||(img.naturalHeight/scale))
                var ctx = elem.getContext('2d')
                ctx.drawImage(img, 0, 0, elem.width, elem.height)
                if (cb != undefined && typeof cb == 'function') {
                    cb(ctx.canvas.toDataURL(fileType, quality), data)
                }
            }
        }
    }
}

function uploadAvatar(data, extra) {
    hideDangerMessage()
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
        } else {
            document.location.reload()
        }
    })
    request.fail(function(jqXHR) {
        loadingSpinner(false)
        $('button.enabled').removeAttr("disabled")
        if (jqXHR.responseJSON.message != undefined) {
            showDangerMessage(jqXHR.responseJSON.message)
        }
    })
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
            } else {
                document.location.reload()
            }
        })
        request.fail(function(jqXHR) {
            loadingSpinner(false)
            $('button.enabled').removeAttr("disabled")
            if (jqXHR.responseJSON.message != undefined) {
                showDangerMessage(jqXHR.responseJSON.message)
            }
        })
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
        var request = $.ajax({
            url: "/dashboard/manage_user/update_user_role",
            method: "POST",
            data: data,
            dataType: "json"
        })
        request.done(function(data) {
            console.log(data.message)
            if (data.redirect != undefined) {
                Turbolinks.visit(data.redirect, { action: "replace" })
            } else {
                document.location.reload()
            }
        })
        request.fail(function(jqXHR) {
            loadingSpinner(false)
            $('button.enabled').removeAttr("disabled")
            if (jqXHR.responseJSON.message != undefined) {
                showDangerMessage(jqXHR.responseJSON.message)
            }
        })
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
        var request = $.ajax({
            url: "/dashboard/manage_user/update_user_access_level",
            method: "POST",
            data: data,
            dataType: "json"
        })
        request.done(function(data) {
            console.log(data.message)
            if (data.redirect != undefined) {
                Turbolinks.visit(data.redirect, { action: "replace" })
            } else {
                document.location.reload()
            }
        })
        request.fail(function(jqXHR) {
            loadingSpinner(false)
            $('button.enabled').removeAttr("disabled")
            if (jqXHR.responseJSON.message != undefined) {
                showDangerMessage(jqXHR.responseJSON.message)
            }
        })
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
        var request = $.ajax({
            url: "/dashboard/manage_user/update_user_status",
            method: "POST",
            data: data,
            dataType: "json"
        })
        request.done(function(data) {
            console.log(data.message)
            if (data.redirect != undefined) {
                Turbolinks.visit(data.redirect, { action: "replace" })
            } else {
                document.location.reload()
            }
        })
        request.fail(function(jqXHR) {
            loadingSpinner(false)
            $('button.enabled').removeAttr("disabled")
            if (jqXHR.responseJSON.message != undefined) {
                showDangerMessage(jqXHR.responseJSON.message)
            }
        })
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
        var request = $.ajax({
            url: "/dashboard/manage_user/delete_user",
            method: "POST",
            data: data,
            dataType: "json"
        })
        request.done(function(data) {
            console.log(data.message)
            if (data.redirect != undefined) {
                Turbolinks.visit(data.redirect, { action: "replace" })
            } else {
                document.location.reload()
            }
        })
        request.fail(function(jqXHR) {
            loadingSpinner(false)
            $('button.enabled').removeAttr("disabled")
            if (jqXHR.responseJSON.message != undefined) {
                showDangerMessage(jqXHR.responseJSON.message)
            }
        })
    }
}

function addUser() {
    $('#add_user_btn').attr("disabled", "disabled")
    hideDangerMessage()
    $('#inputUsernameError').removeClass('border-danger')
    $('#inputUsernameErrorText').text('')
    $('#inputEmailError').removeClass('border-danger')
    $('#inputEmailErrorText').text('')
    $('#inputPasswordError').removeClass('border-danger')
    $('#inputPasswordErrorText').text('')
    $('#inputConfirmPasswordError').removeClass('border-danger')
    $('#inputConfirmPasswordErrorText').text('')
    $('#inputRoleError').removeClass('border-danger')
    $('#inputRoleErrorText').text('')
    $('#inputAccessLevelError').removeClass('border-danger')
    $('#inputAccessLevelErrorText').text('')
    $('#inputStatusError').removeClass('border-danger')
    $('#inputStatusErrorText').text('')
    var data = {
        'username': $('#inputUsername').val(),
        'email': $('#inputEmail').val(),
        'password': $('#inputPassword').val(),
        'confirm_password': $('#inputConfirmPassword').val(),
        'role': $('#inputRole').val(),
        'access_level': $('#inputAccessLevel').val(),
        'status': $('#inputStatus').val(),
    }
    data[window.csrf_token_name] = window.csrf_hash
    var request = $.ajax({
        url: "/dashboard/manage_user/register",
        method: "POST",
        data: data,
        dataType: "json"
    })
    request.done(function(data) {
        console.log(data.message)
        if (data.redirect != undefined) {
            Turbolinks.visit(data.redirect, { action: "replace" })
        } else {
            document.location.reload()
        }
    })
    request.fail(function(jqXHR) {
        loadingSpinner(false)
        $('#add_user_btn').removeAttr("disabled")
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
                if (jqXHR.responseJSON.errors.role != undefined) {
                    $('#inputRoleError').addClass('border-danger')
                    $('#inputRoleErrorText').text(jqXHR.responseJSON.errors.role)
                }
                if (jqXHR.responseJSON.errors.status != undefined) {
                    $('#inputStatusError').addClass('border-danger')
                    $('#inputStatusErrorText').text(jqXHR.responseJSON.errors.status)
                }
                if (jqXHR.responseJSON.errors.access_level != undefined) {
                    $('#inputAccessLevelError').addClass('border-danger')
                    $('#inputAccessLevelErrorText').text(jqXHR.responseJSON.errors.access_level)
                }
            }
        }
    })
}

function change_language(lang) {
    if (lang == null || lang == undefined || getCookie('lang')[0] == lang) {
        return
    }
    var data = {
        'lang': lang,
    }
    data[window.csrf_token_name] = window.csrf_hash
    var request = $.ajax({
        url: "/language",
        method: "POST",
        data: data,
        dataType: "json"
    })
    request.done(function(data) {
        document.location.reload()
    })
    request.fail(function(jqXHR) {
        loadingSpinner(false)
        console.log(jqXHR.statusText);
    })
}

function loadingSpinner(status) {
    if (status == true) {
        $('#loading_spinner').show()
    } else {
        $('#loading_spinner').hide()
    }
}
loadingSpinner(false)

function showDangerMessage(text) {
    $('#dangerMessage').text(text)
    $('#dangerMessage').append('<button type="button" class="text-white ml-2 mb-1 close" aria-label="Close" onclick="hideDangerMessage()"><span aria-hidden="true">&times;</span></button>')
    $('.toast').toast('show');
    $(window).scrollTop(0);
}

function hideDangerMessage() {
    $('.toast').toast('hide');
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

window.addEventListener("load", function() {
    loadingSpinner(false)
})

function login(redirect) {
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
        'redirect': (redirect != undefined ? redirect : true),
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
        } else {
            document.location.reload()
        }
    })
    request.fail(function(jqXHR) {
        loadingSpinner(false)
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
}

function register() {
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
        } else {
            document.location.reload()
        }
    })
    request.fail(function(jqXHR) {
        loadingSpinner(false)
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
}

function forgot_password() {
    $('#frgt_pswd_btn').attr("disabled", "disabled")
    hideDangerMessage()
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
        } else {
            document.location.reload()
        }
    })
    request.fail(function(jqXHR) {
        loadingSpinner(false)
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
}

function activate_account() {
    $('#actvt_acct_btn').attr("disabled", "disabled")
    hideDangerMessage()
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
        } else {
            document.location.reload()
        }
    })
    request.fail(function(jqXHR) {
        loadingSpinner(false)
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
}

function reset() {
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
        } else {
            document.location.reload()
        }
    })
    request.fail(function(jqXHR) {
        loadingSpinner(false)
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
}

function update_password() {
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
        } else {
            document.location.reload()
        }
    })
    request.fail(function(jqXHR) {
        loadingSpinner(false)
        $('#uptd_btn').removeAttr("disabled")
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
                if (jqXHR.responseJSON.errors.old_password != undefined) {
                    $('#inputOldPasswordError').addClass('border-danger')
                    $('#inputOldPasswordErrorText').text(jqXHR.responseJSON.errors.old_password)
                }
            }
        }
    })
}

function logout() {
    if (confirm('<?php echo lang('L_CONFIRM_LOGOUT')?>') == false) {
        return
    }
    var data = {}
    data[window.csrf_token_name] = window.csrf_hash
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
        } else {
            document.location.reload()
        }
    })
    request.fail(function(jqXHR) {
        loadingSpinner(false)
        console.log(jqXHR.responseJSON)
    })
}

$(document).ajaxStart(function(event, jqxhr, settings) {
    loadingSpinner(true)
});

$(document).ajaxComplete(function(event, jqxhr, settings) {
    if (jqxhr.statusText === 'error') {
        showDangerMessage(jqxhr.statusText)
    }
});

$(document).ready(function() {

    $('[data-toggle="tooltip"]').tooltip()

    $(function () {
        $('[data-toggle="popover"]').popover()
    })

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
})
