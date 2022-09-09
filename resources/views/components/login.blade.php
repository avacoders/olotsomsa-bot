
<!-- Modal -->
<div class="modal login" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <h1 aria-hidden="true">&times;</h1>
                </button>

                <div>
                    <div class="header mt-5 text-center">Xush kelibsiz</div>
                    <div class="login-text mt-5 text-dark text-center">Telefon raqamingizni kiriting</div>
                    <input type="text" class="login-phone" id="phone-mask" value="+998" placeholder="+998 90 123-45-67">
                    <div class="text-danger text-center mt-1" id="phone-error"></div>
                    <div class="text-primary text-center mt-1" id="success"></div>
                    <input type="text" class="login-phone" id="code" placeholder="Kod" style="display: none">
                    <input type="text" class="login-phone" id="name" placeholder="Ismingiz" style="display: none">
                    <button type="button" class="login-btn" id="login" style="display: none">
                        Kirish</button>
                    <button type="button" class="login-btn" id="send">
                        Yuborish</button>
                    <button type="button" class="login-btn" id="nameSend" style="display: none">
                        Kirish</button>

                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal login" id="order-send" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <h1 aria-hidden="true">&times;</h1>
                </button>

                <div>
                    <div class="login-text mt-2 text-dark text-center m-0"><h2 class="m-0">Buyurtmachining ma'lumotlari</h2></div>
                    <div class="login-text mt-3 text-dark text-center" >Ismingiz</div>
                    <input type="text" class="login-phone ordererName" id="orderer-name" placeholder="Ismingiz" value="Avzal">
                    <div class="login-text mt-3 text-dark text-center" >Telefon raqamingiz</div>
                    <input type="text" class="login-phone ordererPhone" id="phone-mask3" value="+998" placeholder="+998 90 123-45-67">
                    <div class="login-text mt-3 text-dark text-center" >Manzilingiz</div>
                    <textarea name="" class="login-phone ordererAddress" placeholder="Toshkent shahar..." id="comment" cols="30" rows="5"></textarea>
                    <div class="text-danger text-center mt-1" id="orderError"></div>

                    <button type="button" class="login-btn" id="sendOrder">
                        Yuborish</button>

                </div>
            </div>
        </div>
    </div>
</div>
