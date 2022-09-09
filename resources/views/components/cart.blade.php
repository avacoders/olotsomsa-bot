<div class="cart cart-shopping">

    <div class="d-flex justify-content-between">
        <img src="{{ asset('image/cart.svg') }}" alt="">
        <div id="cart-counter">x0</div>
    </div>

    <button type="button" class="open-cart">0</button>

</div>

<div class="cart-mobile cart-shopping">
    <button type="button" class="cart-counter">0</button>
    <img src="{{ asset('image/cart.svg') }}" alt="">
</div>


<div class="selecteds">

    <div class="body">
        <div class="head d-flex justify-content-between align-items-center">
            <div class="header">SAVAT</div>
            <button type="button" class="close"><img src="{{ asset("image/x.svg") }}" alt=""></button>
        </div>

        <div class="content">
            <ul class="orders" id="orders">
                <li>
                    <div class="row">
                        <div class="col-lg-6 col-12">
                            <div class="d-flex align-content-between justify-content-center">
                                <img src="{{ asset('image/osh.png') }}" class="order-img" alt="">
                                <div class="w-100 h-100">
                                    <div class="order-name">Oshi Amiri</div>
                                    <div class="order-price">33.000</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 mt-3 mt-lg-0 col-12">
                            <div class="d-flex h-100 align-items-center">
                                <div class="mx-auto">
                                    <button class="minus btn mx-auto"><img src="/image/minus.svg" alt=""></button>
                                    <span class=" mx-4">x1</span>
                                    <button class="plus btn mx-auto"><img src="/image/plus.svg" alt=""></button>
                                </div>
                                <div class="sum">33.000</div>
                                <button class="trash"><img src="/image/trash.svg" alt=""></button>
                            </div>
                        </div>
                    </div>

                </li>
            </ul>


            <button class="confirm" type="button">BUYURTMA BERISH</button>
        </div>
    </div>


</div>



