<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css"
          integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://telegram.org/js/telegram-web-app.js"></script>

    <style>

        .product-img {
            width: 100%;
            animation: product-img-animation 10s infinite linear;
        }

        @keyframes product-img-animation {
            0% {
                transform: rotate(0);
            }
            100% {
                transform: rotate(360deg);
            }
        }

        .cart {
            position: fixed;
            right: 0;
            left: 0;
            background-color: #37D200;
            color: white;
            text-align: center;
            padding: 20px;
            bottom: 0;
            border: 0;
            transition: 0.3s;
        }

        .cart:hover {
            background-color: green;
        }


        .btn-minus {
            background-color: red;
            font-weight: bold;
            width: 100px;

        }

        .counter {
            position: absolute;
            background-color: #37D200;
            color: white;
            font-weight: bold;
            border-radius: 50%;
            width: 25px;
            height: 25px;
            text-align: center;
            right: 25px;
            top: 25px;
            z-index: 100;

        }

        .btn {
            border: 0;
            border-radius: 12px;
            font-weight: bold;
        }

        .btn-plus {
            background-color: #37D200;
            font-weight: bold;
            width: 100px;
        }

        .btn-success {
            background-color: #37D200;
            color: white;
        }

        .add {
            border-radius: 50rem;
            display: block;
            margin: auto;
            border: 0;
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
        }

        .custom-content {
            border-radius: 32px;
            overflow: hidden;
        }

        .close {
            background-color: transparent;
            font-size: 30px;
            border: 0;
        }

        .fade .modal-content {
            transform: scale(0);
            opacity: 0;
            transition: all .3s ease-out;
        }

        .fade.show .modal-content {
            opacity: 1;
            transform: scale(1);
        }

    </style>

</head>
<body>


<div class="container">
    <div class="row justify-content-center" id="products">

    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content custom-content    ">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Buyurtmani tanlash</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-6">
                            <img class="w-100" id="image" alt="">
                        </div>
                        <div class="col-6">
                            <h6 class="text-center mt-4"><b> <span id="name">Osh</span> -
                                    <span id="price"> 27000</span> x </b> <span id="counter">2</span></h6>
                            <h4 class="text-center mt-2"><span id="general"> 54000 </span> so'm</h4>

                            <div class="d-flex" style="gap: 12px">
                                <button class="btn bnt-sm btn-danger btn-minus">-</button>
                                <button class="btn bnt-sm btn-success btn-plus">+</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button class="btn btn-lg btn-secondary cancel" data-dismiss="modal">Bekor qilish</button>
                <button class="btn btn-lg btn-success submit" data-dismiss="modal">Tasdiqlash</button>
            </div>
        </div>
    </div>
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
        integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.min.js"
        integrity="sha384-VHvPCCyXqtD5DqJeNxl2dtTyhF78xXNXdkwX1CZeRusQfRKp+tA7hAShOK/B/fQ2"
        crossorigin="anonymous"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<script src="{{ asset("js/axios.js") }}"></script>

<script>
    var domain = "https://olotsomsa.com";
    var selecting = null
    var selected = []
    var products = getProducts()
    var quantity = 1
    var mainBtn = window.Telegram.WebApp.MainButton



    makeProductsUI(products)

    function makeProductsUI(products) {
        var div = ""
        var item = false
        var btnClass
        for (let i = 0; i < products.length; i++) {
            item = products[i]
            btnClass = selected[item.id] ? "btn-warning" : "btn-success"
            btnText = selected[item.id] ? "OLIB TASHLASH" : "QO'SHISH";

            div += '<div class="col-4 position-relative">'
            div += '<div class="py-3" style="height: 250px">'
            div += '<div class="d-none counter" id="counter' + item.id + '">2</div>'
            div += '<img src="' + item.image + '" class="product-img" alt="">'
            div += '<h6 class="text-center mt-3 mx-auto">' + item.name + ' <br> ' + item.price + ' so\'m</h6>'
            div += '<button   data-toggle="modal" data-target="#exampleModal" data-id="' + item.id + '" id="btn' + item.id + '" class="btn add ' + btnClass + '">'
            div += "QO'SHISH"
            div += "</button>"
            div += '</div>'
            div += '</div>'
        }


        $("#products").html(div)
    }

    function calculate() {
        sum = 0
        for (let i = 0; i < selected.length; i++) {
            if (selected[i] && selected[i].quantity)
                sum += selected[i].quantity * getProductByID(i).price
        }
        return sum
    }

    function changeCounter(id) {
        $("#counter" + id).toggleClass('d-none')
        $("#counter" + id).text(selected[id] ? selected[id].quantity : 1)
    }

    function changeBtn(id) {
        btnText = selected[id] ? "-" : "QO'SHISH";
        btnClass = selected[id] ? "btn-warning" : "btn-success"
        btnClass2 = !selected[id] ? "btn-success" : "btn-warning"
        $("#btn" + id).text(btnText)

    }


    function changeMainBtn() {
        var color = selected.length > 1 ? "#37D200" : "rgb(255, 193, 7)"
        mainBtn.setParams({
            "text": "Umumiy " + calculate() + " so'm",
            "color": color,
            "text_color": "#FFFFFF",

        })
    }

    mainBtn.show()
    mainBtn.setParams({
        "text": "Umumiy " + calculate() + " So'm",
        "color": "rgb(255, 193, 7)",
        "text_color": "#FFFFFF",

    })

    mainBtn.onClick(() => {
        if (selected.length > 1) {
            axios.post('/api/bot', {
                query_id: window.Telegram.WebApp.initDataUnsafe.query_id,
                user: window.Telegram.WebApp.initDataUnsafe.user,
                orders: selected
            })
                .then(response => {
                    if (response.data.ok) window.Telegram.WebApp.close()
                })
        }

    })
    $('.add').click(function () {
        selecting = getProductByID($(this).data('id'))
        if (selecting) {
            $("#name").text(selecting.name)
            if (selecting.id == 19)
            {
                quantity = 10
                $("#counter").text(10)
                $("#general").text(selecting.price * 10)

            }
            else
            {
                $("#counter").text(1)
                $("#general").text(selecting.price)

            }

            $("#image").attr('src', selecting.image)
            $("#price").text(selecting.price)
        }

    })

    $(".btn-plus").click(function () {
        quantity++
        $("#counter").text(quantity)
        $("#general").text(quantity * selecting.price)
    })

    $(".btn-minus").click(function () {
        if (quantity > 1)
            quantity--
        if (selecting.id == 19)
        {
            if (quantity < 10)
            {
                quantity = 10
            }
        }
        $("#counter").text(quantity)
        $("#general").text(quantity * selecting.price)

    })

    $(".submit").click(function () {
        selected[selecting.id] = {
            quantity
        }
        changeCounter(selecting.id)
        changeBtn(selecting.id)
        changeMainBtn()
    })
    $(".cancel").click(function () {
        selected.splice(selecting.id)
        console.log(selected);
        changeCounter(selecting.id)
        changeBtn(selecting.id)
        changeMainBtn()

    })


    function getProductByID(id) {
        for (let i = 0; i < products.length; i++) {
            if (products[i].id === parseInt(id))
                return products[i]
        }
        return false
    }


    function getProducts() {
        var result = false

        $.ajax({
            url: domain + "/api/products/",
            dataType: "json",
            type: "get",
            async: false,
            success: function (data) {
                result = data.data
            },
            error: function (xhr, exception) {
                var msg = "";
                if (xhr.status === 0) {
                    msg = "Not connect.\n Verify Network." + xhr.responseText;
                } else if (xhr.status == 404) {
                    msg = "Requested page not found. [404]" + xhr.responseText;
                } else if (xhr.status == 500) {
                    msg = "Internal Server Error [500]." + xhr.responseText;
                } else if (exception === "parsererror") {
                    msg = "Requested JSON parse failed.";
                } else if (exception === "timeout") {
                    msg = "Time out error." + xhr.responseText;
                } else if (exception === "abort") {
                    msg = "Ajax request aborted.";
                } else {
                    msg = "Error:" + xhr.status + " " + xhr.responseText;
                }

            }
        });


        return result
    }


</script>
</body>
</html>
