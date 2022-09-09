var items = JSON.parse(window.localStorage.getItem("items")) ?? []
var products = getProducts()

function getRotationDegrees(obj) {
    var matrix = obj.css("-webkit-transform") ||
        obj.css("-moz-transform") ||
        obj.css("-ms-transform") ||
        obj.css("-o-transform") ||
        obj.css("transform");
    if (matrix !== 'none') {
        var values = matrix.split('(')[1].split(')')[0].split(',');
        var a = values[0];
        var b = values[1];
        var angle = Math.round(Math.atan2(b, a) * (180 / Math.PI));
    } else {
        var angle = 0;
    }

    if (angle < 0) angle += 360;
    return angle;
}

$(document).ready(function () {
    function AnimateRotate(angle, repeat) {
        var duration = 1000;
        var $elem = $('.stol');

        setTimeout(function () {
            if (repeat && repeat == "infinite") {
                AnimateRotate(angle, repeat);
            } else if (repeat && repeat > 1) {
                AnimateRotate(angle, repeat - 1);
            }
        }, duration + 2000)
        var translate = $(window).width() < 960 ? "translateX(-50%)" : ''
        $({deg: getRotationDegrees($elem)}).animate({deg: getRotationDegrees($elem) + angle}, {
            duration: duration,
            step: function (now) {
                $elem.css({
                    'transform': translate + ' rotate(' + now + 'deg)'
                });
            }
        });
    }

    setTimeout(function () {
        AnimateRotate(90, "infinite");
    }, 3000)

    var text = [
        "Taomlarni tayyorlashda sifatli mahsulotlar ishlatiliniladi",
        "Har bir ishlatilgan mahsulot istemolga yaroqli holatda",
        "Biz uchun har bir mijozning fikri muhim",
        "Har bir buyurtmani manziliga o’z vaqtida yetkazamiz"
    ];
    var text2 = [
        "Mazali va kafolatlangan ta’m",
        "Sifatli mahsulotlar",
        "Mijozlar bilan fikr almashinuvi",
        "O’z vaqtida buyurtmalarni yetkazib berish"
    ];
    var text3 = [
        "Olot somsa",
        "Amiri somsa",
        "Vogguri",
        "Zig’ir osh",
    ];
    var text4 = [
        "Zig'ir oshi",
        "Olot somsa ",
        "Amiri Somsa",
        "Vogguri",
    ];


    var counter = 1;
    var counter2 = 1;
    var counter3 = 1;
    var elem = $(".text-p-switch");
    var elem2 = $(".text-h2-switch");
    var elem3 = $(".text-h1-switch");
    setInterval(change, 3000);
    setInterval(change2, 3000);
    setInterval(change3, 3000);

    function change() {
        elem.fadeOut(function () {
            elem.html(text[counter]);
            counter++;
            if (counter >= text.length) {
                counter = 0;
            }
            elem.fadeIn();
        });

    }

    function change2() {
        elem2.fadeOut(function () {
            elem2.html(text2[counter2]);
            counter2++;
            if (counter2 >= text2.length) {
                counter2 = 0;
            }
            elem2.fadeIn();
        });
    }

    function change3() {
        var text = $(window).width() > 600 ? text3 : text4
        elem3.fadeOut(function () {
            elem3.html(text[counter3]);
            counter3++;
            if (counter3 >= text.length) {
                counter3 = 0;
            }
            elem3.fadeIn();
        });
    }

    $(window).scroll(function () {
        if ($(window).scrollTop() + $(window).height() <= $(document).height()) {
            if ($("#counter").data('counted') == "0") {
                $('.counter').each(function () {
                    var $this = $(this);
                    jQuery({Counter: 0}).animate({Counter: $this.data('stop')}, {
                        duration: 3000,
                        easing: 'swing',
                        step: function (now) {
                            $this.text(Math.ceil(now));
                        }
                    });
                });
                $("#counter").data('counted', 1)
            }

        }
    });

})

$('.owl-carousel').owlCarousel({
    loop: true,
    autoplay: true,
    center: true,
    autoplayTimeout: 2000,
    margin: 60,
    nav: false,
    responsive: {
        0: {
            items: 1.5
        },
        600: {
            items: 3
        },
        1000: {
            items: 2
        }
    }
})
$('.active')


$(".open-cart").click(function () {

    $(".body").animate({
        right: 0,

    }, 500, function () {

    })
})
$(".cart-mobile").click(function () {

    $(".body").animate({
        right: 0,

    }, 500, function () {

    })
})
$(".close").click(function () {

    $(".body").animate({
        right: "-100%",

    }, 500, function () {

    })
})

function getProducts() {
    var products = null

    $.ajax({
        url: "/api/products",
        type: "GET",
        async: false,
        success: function (response) {
            products = response.data
        }
    });


    return products
}


function getProductByID(id) {
    for (let i = 0; i < products.length; i++) {
        if (products[i].id === id)
            return products[i]
    }
    return false
}


function getItemById(id) {
    var items = JSON.parse(window.localStorage.getItem("items")) ?? []
    for (let i = 0; i < items.length; i++) {
        if (items[i].id === id)
            return items[i]
    }
    return false
}

function getSum() {
    var items = JSON.parse(window.localStorage.getItem("items")) ?? []
    var sum = 0
    for (let i = 0; i < items.length; i++) {
        sum += getProductByID(items[i].id).price * items[i].quantity
    }
    $('.open-cart').text(new Intl.NumberFormat().format(sum))
    return sum
}

getSum()

function addItem(id) {
    var items = JSON.parse(window.localStorage.getItem("items")) ?? []
    var item = {
        id,
        quantity: 1
    }
    if (!getItemById(id)) {
        items.push(item)
        window.localStorage.setItem('items', JSON.stringify(items))
        setCounter()
    }
    makeProductsUI()
}


function setCounter() {
    var items = JSON.parse(window.localStorage.getItem("items")) ?? []
    $("#cart-counter").text("x" + items.length)
    $(".cart-counter").text(items.length)
    getSum()
    $(".confirm").text("Umumiy " + new Intl.NumberFormat().format(getSum()) + " so'm")
}

$(".addToCart").click(function () {
    var id = $(this).data('id')
    var product = getProductByID(id)
    $(".actions").animate({
        height: 0
    }, 200)
    if ($(".actions-" + id).data("opened") != 1) {
        $(".actions-" + id).animate({
            height: 50
        }, 200)
        $(".actions-" + id).data("opened", 1)
    } else {
        $(".actions-" + id).animate({
            height: 0
        }, 200)
        $(".actions-" + id).data("opened", 0)

    }

    $("#order-img").attr("src", product.image)
    $("#orderName").text(product.name.toLowerCase())
    $("#price").text(new Intl.NumberFormat().format(product.price))
    $("#unit").text(product.unit)
    var item = getItemById(id)
    $("#order-counter").text(1)
    if (item)
        $("#order-counter-" + id).text(item.quantity)
    else addItem(id)
    item = getItemById(id)
    $("#general-price").text(new Intl.NumberFormat().format(item.quantity * getProductByID(id).price))

})

$(".item-img").css({
    'height': '200px',
    'width': '200px',

})

function makeEffect(id) {
    var cart = window.screen.width > 600 ? $('.open-cart') : $('.cart-mobile');
    var imgtodrag = $(".product-img-" + id);

    if (imgtodrag) {
        var imgclone = imgtodrag.clone()
            .offset({
                top: imgtodrag.offset().top,
                left: imgtodrag.offset().left
            })
            .css({
                'opacity': '0.8',
                'position': 'absolute',
                'height': '100px',
                'width': '100px',
                'z-index': '100',

            })
            .appendTo($('body'))
            .animate({
                'top': cart.offset().top + 10,
                'left': cart.offset().left + 10,
                'transform': "scale(0)",
            }, 1000);
        imgclone.animate({
            'width': 0,
            'height': 0
        }, function () {
            $(this).detach()
            $(".cart-shopping").css("background-color", "#42FF00")
            setTimeout(function () {
                $(".cart-shopping").css("background-color", "red")
            }, 2000)
        });

    }
}

$(".minus1").click(function () {
    var id = parseInt($(this).data("id"))
    plusItem(id, "-")
})

$(".plus1").click(function () {
    var id = parseInt($(this).data("id"))
    plusItem(id, "+")
    makeEffect(id)

})

function makeProductsUI() {
    var div = ""
    var items = JSON.parse(window.localStorage.getItem("items")) ?? []
    for (let i = 0; i < items.length; i++) {
        div += '<li>'
        div += '<div class="row">'
        div += '<div class="col-lg-6 col-12">'
        div += '<div class="d-flex align-content-between justify-content-center">'
        div += '<img src="' + getProductByID(items[i].id).image + '" class="order-img" alt="">'
        div += '<div class="w-100 h-100">'
        div += '<div class="order-name">' + getProductByID(items[i].id).name.toLowerCase() + '</div>'
        div += '<div class="order-price">' + new Intl.NumberFormat().format(getProductByID(items[i].id).price) + '</div>'
        div += '</div>'
        div += '</div>'
        div += '</div>'
        div += '<div class="col-lg-6 mt-3 mt-lg-0 col-12">'
        div += '<div class="d-flex h-100 align-items-center">'
        div += '<div class="mx-lg-auto">'
        div += '<button class="minus btn mx-auto minus2" id="" data-id="' + items[i].id + '"><img src="/image/minus.svg" alt=""></button>'
        div += '<span class=" mx-2">x' + items[i].quantity + '</span>'
        div += '<button class="plus btn mx-auto plus2" id="" data-id="' + items[i].id + '"><img src="/image/plus.svg" alt=""></button>'
        div += '</div>'
        div += '<div class="sum">' + new Intl.NumberFormat().format(getProductByID(items[i].id).price * items[i].quantity) + '</div>'
        div += '<button class="trash"  data-id="' + items[i].id + '"><img src="/image/trash.svg" alt=""></button>'
        div += '</div>'
        div += '</div>'
        div += '</div>'
        div += '</li>'
    }
    $("#orders").html(div)
    $(".trash").click(function () {
        var id = parseInt($(this).data("id"))
        deleteItem(id)
        if (items.length)
            $(".cart-shopping").css("background-color", "red")
    })
    $(".minus2").click(function () {
        var id = parseInt($(this).data("id"))
        plusItem(id, "-")
    })

    $(".plus2").click(function () {
        var id = parseInt($(this).data("id"))
        plusItem(id, "+")
    })
}

$("#minus2").click(function () {
    var id = parseInt($(this).data("id"))
    plusItem(id, "-")
})

$("#plus2").click(function () {
    var id = parseInt($(this).data("id"))
    plusItem(id, "+")
})
$(".preConfirm").click(function () {
    $("#orderModal").modal('hide')
    makeEffect($("#plus1").data("id"))
})

makeProductsUI()
setCounter()

function plusItem(id, expression) {
    var items = JSON.parse(window.localStorage.getItem("items")) ?? []

    for (let i = 0; i < items.length; i++) {
        if (items[i].id === id) {
            if (expression === "+")
                items[i].quantity += 1;
            else if (expression === "-")
                items[i].quantity = items[i].quantity > 1 ? items[i].quantity - 1 : items[i].quantity;
            $("#order-counter-" + id).text(items[i].quantity)
            $("#general-price").text(new Intl.NumberFormat().format(items[i].quantity * getProductByID(id).price))

        }
    }
    console.log(getItemById(id).quantity);

    window.localStorage.setItem('items', JSON.stringify(items))
    makeProductsUI()
    setCounter()
}


function deleteItem(id) {
    var items = JSON.parse(window.localStorage.getItem("items")) ?? []
    items = items.filter((item) => item.id !== id);
    window.localStorage.setItem('items', JSON.stringify(items))
    makeProductsUI()
    setCounter()
}
