<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <link href="{{ asset("css/aos.css") }}" rel="stylesheet">
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
            display:table-cell;
            vertical-align:middle;
            text-align:center;
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

        .chip {
            width: 100%;
            display: inline-block;
            height: 50px;
            line-height: 50px;
            font-size: 20px;
            border-radius: 12px;
            background-color: rgb(255, 193, 7);
            color: white;
            font-weight: bold;
        }

        .bottom {
            position: fixed;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #24303e;
            color: white;
            padding: 10px;
            text-align: center;
            z-index: 100;
        }

        .mainBtn {
            background-color: #37D200;
            color: white;
            border: 0;
            border-radius: 12px;
            font-weight: bold;
            width: 100%;
            font-size: 20px;
            height: 50px;
            line-height: 50px;
            cursor: pointer;
        }
    </style>

</head>
<body>


<div class="container ">
    <div class="row justify-content-center" style="margin-bottom: 100px" id="products">

    </div>
    <div class="bottom">
        <div class="row">
            <div class="col-6 pr-1">
                <div class="chip" id="summa">0 so'm</div>
            </div>
            <div class="col-6 pl-1">
                <div class="mainBtn" id="mainBtn">Buyurtma berish</div>
            </div>
        </div>
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

<!-- Modal -->
<div class="modal fade" id="no-product" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content custom-content    ">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Mahsulotlarni tanlang!</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset("js/jquery.js") }}"></script>

<script src="{{ asset("js/popper.min.js") }}"></script>
<script src="{{ asset("js/bootstrap.min.js") }}"></script>
<script src="{{ asset("js/aos.js") }}"></script>

<script src="{{ asset("js/axios.min.js") }}"></script>

<script>
    AOS.init();
    var domain = "https://olotsomsa.com";
    var selecting = null
    var selected = []
    var products = getProducts()
    var quantity = 1
    var mainBtn = window.Telegram.WebApp.MainButton

    $(doument).ready(function () {

        makeProductsUI(products)

        function makeProductsUI(products) {
            var div = ""
            var item = false
            var btnClass
            var price = 0
            for (let i = 0; i < products.length; i++) {
                item = products[i]
                btnClass = getSelectedByID([item.id]) ? "btn-warning" : "btn-success"
                btnText = getSelectedByID(item.id) ? "OLIB TASHLASH" : "QO'SHISH";
                price = numberWithCommas(item.price)

                div += '<div class="col-4 position-relative">'
                div += '<div class="py-3" style="height: 250px">'
                div += '<div class="d-none counter" id="counter' + item.id + '">2</div>'
                div += '<img  data-toggle="modal" data-target="#exampleModal" data-id="' + item.id + '"  src="' + item.image + '" class="product-img " alt="">'
                div += '<h6 class="text-center mt-3 mx-auto">' + item.name + ' <br> ' + price + ' so\'m</h6>'
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
                sum += selected[i].quantity * getProductByID(selected[i].id).price
            }
            return numberWithCommas(sum)
        }

        function changeCounter(id) {
            if (getSelectedByID(id))
                $("#counter" + id).removeClass('d-none')
            else
                $("#counter" + id).addClass('d-none')
            $("#counter" + id).text(getSelectedByID(id) ? getSelectedByID(id).quantity : 1)
        }

        function changeMainBtn() {
            $("#summa").text(calculate() + " so'm")
        }

        $("#mainBtn").click(function () {
            if (selected.length > 0) {
                axios.post('/api/bot', {
                    query_id: window.Telegram.WebApp.initDataUnsafe.query_id,
                    user: window.Telegram.WebApp.initDataUnsafe.user,
                    orders: selected
                }).then(response => {
                    if (response.data.ok) window.Telegram.WebApp.close()
                })
            }
            else $("#no-product").modal('show')

        })
        $('.add').click(function () {
            selecting = getProductByID($(this).data('id'))
            openModal(selecting)
        })
        $('.product-img').click(function () {
            selecting = getProductByID($(this).data('id'))
            openModal(selecting)
        })


        $(".btn-plus").click(function () {
            quantity++
            $("#counter").text(quantity)
            $("#general").text(numberWithCommas(quantity * selecting.price))
        })

        $(".btn-minus").click(function () {
            if (quantity >= 1)
                quantity--
            if (selecting.id == 19) {
                if (quantity < 10) {
                    quantity = 10
                }
            }
            $("#counter").text(quantity)
            $("#general").text(numberWithCommas(quantity * selecting.price))

        })
        $(".submit").click(function () {
            if (quantity)
                if (getSelectedByID(selecting.id))
                    getSelectedByID(selecting.id).quantity = quantity
                else
                    selected.push({
                        id: selecting.id,
                        quantity: quantity
                    })
            else
                removeProductFromSelected(selecting.id)

            changeCounter(selecting.id)
            changeMainBtn()
            quantity = 1
            console.log(selected);
        })
        $(".cancel").click(function () {
            removeProductFromSelected(selecting.id)
            changeCounter(selecting.id)
            changeMainBtn()

        })

        function getProductByID(id) {
            for (let i = 0; i < products.length; i++) {
                if (products[i].id === parseInt(id))
                    return products[i]
            }
            return false
        }
    })

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

    numberWithCommas = (x) => {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
    }

    function getSelectedByID(id) {
        for (var i = 0; i < selected.length; i++) {
            if (selected[i].id == id) {
                return selected[i]
            }
        }
        return null
    }

    function removeProductFromSelected(id) {
        for (var i = 0; i < selected.length; i++) {
            if (selected[i].id == id) {
                selected.splice(i, 1);
                break;
            }
        }
    }


    function openModal(selecting) {
        quantity = 1
        if (selecting) {
            $("#name").text(selecting.name)

            $("#image").attr('src', selecting.image)
            $("#price").text(numberWithCommas(selecting.price))

            if (getSelectedByID(selecting.id)) {
                quantity = getSelectedByID(selecting.id).quantity
                $("#counter").text(quantity)
                $("#general").text(numberWithCommas(quantity * selecting.price))
            } else {
                if (selecting.id == 19) {
                    quantity = 10
                    $("#counter").text(10)
                    $("#general").text(numberWithCommas(selecting.price * 10))

                } else {
                    $("#counter").text(1)
                    $("#general").text(numberWithCommas(selecting.price))
                }
            }
        }
    }

</script>
</body>
</html>
