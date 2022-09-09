var phone_number = null

var user = JSON.parse(window.localStorage.getItem("user"))
var token = window.localStorage.getItem("token")

if (user && token) {
    $("#username").text(user.name)
    $("#authname").val(user.name)
    $("#phone-mask2").val(user.phone_number)
    $("#username-sm").text(user.name)
    $("#kirish").hide()
    $(".reverse-sm").hide()
    $("#username-block").show()
    $("#username-block-sm").show()
}

$(".edit").click(function () {

    if (user && token)
    {
        phone_number = $("#phone-mask2").val()
        phone_number = phone_number.replace(/\D/g, "");
        var name = $("#authname").val()
        $.ajax({
            type: "POST",
            url: "/api/update-profile",
            headers: {"Authorization": 'Bearer ' + token},
            data: {phone_number, name},
            success: function (response) {
                if (response.ok) {
                    window.localStorage.setItem('user', JSON.stringify(response.user))
                    swal("Muvaffaqiyatli", "Ismingiz o'zgartirildi", 'success')
                }
            },
            error: function (error) {
                $("#phone-error").text(JSON.parse(error.responseText).message)
            }

        })
    }

})

$(".logout").click(function () {
    console.log(23234);
    if (user && token)
        $.ajax({
            type: "POST",
            url: "/api/logout",
            headers: {"Authorization": 'Bearer ' + token},
            data: {phone_number},
            success: function (response) {
                if (response.ok) {
                    window.location.href = "/"
                    window.localStorage.clear()
                }
            },
            error: function (error) {
                $("#phone-error").text(JSON.parse(error.responseText).message)
            }

        })
})


$('#videoModal').on('hide.bs.modal', function (e) {
    var videoURL = $('iframe').prop('src');
    videoURL = videoURL.replace("&autoplay=1", "");
    $('iframe').prop('src', '');
    $('iframe').prop('src', videoURL);
})


$('.play-btn').click(function () {
    $(".video-" + $(this).data('video')).removeClass('d-none')
})


$(document).ready(function () {

    $(".confirm").click(function () {
        $(".body").animate({
            right: "-100%",

        }, 500, function () {

        })
        if (!user && !token) {
            $("#loginModal").modal('show')
        } else {
            $("#order-send").modal('show')
            $(".ordererName").val(user.name)
            $(".ordererPhone").val(user.phone_number)
        }
    })
    if ($(window).width() < 800) {
        $('.navbar-collapse').addClass("overflow-hidden")
        if (!user && !token) {
            $('#username-sm').hide()

        }
    }

    jQuery(window).on('load', function () {
        (function ($) {
            preloaderFadeOutInit();

            $('.navbar-toggler').click(function () {
                $('.toggle-line').toggleClass('border-black')
                $('.toggle-line1').toggleClass('line1')
                $('.toggle-line2').toggleClass('line2')
                $('.toggle-line3').toggleClass('line3')
                if ($('.navbar-collapse').data("opened") == "0") {
                    $('.navbar-collapse').data("opened", "1")
                    $('.navbar-collapse').animate({
                        height: "100vh"
                    }, 500)
                } else {

                    $('.navbar-collapse').data("opened", "0")

                    $('.navbar-collapse').animate({
                        height: "0"
                    }, 500)
                }

            })
            AOS.init();

            function preloaderFadeOutInit() {
                $('.preloader').fadeOut('slow');
                $('body').attr('id', '');
            }
        })(jQuery)
        var phoneMask = IMask(
            document.getElementById('phone-mask'), {
                mask: '+{998} 00 000-00-00'
            });
        if (document.getElementById('phone-mask2'))
            IMask(
                document.getElementById('phone-mask2'), {
                    mask: '+{998} 00 000-00-00'
                });
        if (document.getElementById('phone-mask3'))
            IMask(
                document.getElementById('phone-mask3'), {
                    mask: '+{998} 00 000-00-00'
                });

        $("#sendOrder").click(function () {
            phone_number = $("#phone-mask3").val()
            phone_number = phone_number.replace(/\D/g, "");
            var name = $("#orderer-name").val()
            var comment = $("#comment").val()
            var orders = JSON.parse(window.localStorage.getItem("items"))

            $(this).prop("disabled", true)
            $(this).html(
                `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Iltimos kuting...`
            )
            $.ajax({
                type: "POST",
                url: "/api/order",
                data: {phone_number, name, comment, orders},
                headers: {"Authorization": 'Bearer ' + token},

                success: function (response) {
                    if (response.ok) {
                        $(".modal").modal('hide')
                        window.localStorage.setItem("items", null)
                        swal("Muvaqqiatli", response.message, 'success')
                        setTimeout(function () {
                            window.location.href = "/"
                        }, 1000)
                    } else
                        $("#orderError").text(response.message)
                },
                error: function (error) {
                    $("#phone-error").text(JSON.parse(error.responseText).message)
                }

            })


        })
        $("#send").click(function () {
            phone_number = $("#phone-mask").val()
            phone_number = phone_number.replace(/\D/g, "");
            $("#phone-error").text("")

            $(this).prop("disabled", true)
            $(this).html(
                `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Iltimos kuting...`
            )
            $.ajax({
                type: "POST",
                url: "/api/login",
                data: {phone_number},
                success: function (response) {
                    if (response.ok) {
                        $("#success").text(response.message)
                        $("#phone-mask").hide()
                        $("#send").hide()
                        $("#code").show()
                        $("#login").show()
                    } else
                        $("#phone-error").text(response.message)

                    $("#send").text("Yuborish")
                    $("#send").prop("disabled", false)
                },
                error: function (error) {
                    $("#phone-error").text(JSON.parse(error.responseText).message)
                }

            })

        })
        $("#nameSend").click(function () {
            var name = $("#name").val();
            var code = window.localStorage.getItem('code')
            $.ajax({
                type: "POST",
                url: `/api/name/${phone_number}`,
                data: {name,code},
                success: function (response) {
                    var user = response.user
                    $("#success").hide()

                    if (response.ok) {
                        window.localStorage.setItem('token', response.access_token);
                        window.localStorage.setItem('user', JSON.stringify(user));
                        window.location.href = "/"
                    } else {
                        $("#phone-error").text(response.message)
                    }

                },
                error: function (error) {
                    $("#phone-error").text(JSON.parse(error.responseText).message)
                }

            })

        })

        $("#login").click(function () {
            $("#phone-error").text("")
            $("#success").text("")

            $(this).prop("disabled", true)
            $(this).html(
                `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Iltimos kuting...`
            )
            var code = $("#code").val()
            console.log(code);
            $.ajax({
                type: "POST",
                url: `/api/code/${phone_number}`,
                data: {code},
                success: function (response) {
                    if (response.ok) {
                        var user = response.user
                        $("#success").text(response.message)
                        $("#phone-mask").hide()
                        $("#send").hide()
                        $("#login").hide()
                        window.localStorage.setItem('code', code);

                        if (user.no_name) {
                            $("#code").hide()
                            $("#name").show()
                            $("#nameSend").show()
                        } else {
                            window.localStorage.setItem('token', response.access_token);
                            window.localStorage.setItem('user', JSON.stringify(user));
                            window.location.href = "/"
                        }
                    } else {
                        $("#phone-error").text(response.message)
                    }
                    $("#login").text("Kirish")
                    $("#login").prop("disabled", false)

                },
                error: function (error) {
                    $("#phone-error").text(JSON.parse(error.responseText).message)
                }

            })

        })

    });
})
