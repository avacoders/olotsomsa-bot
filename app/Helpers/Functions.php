<?php


function lang($locale, $key)
{
    $keys = [
        'uz' => [
            "hi" => "TO'LIQ ISMINGIZNI KIRITING",
            "ask_phone1" => "TELEFON RAQAMINGIZNI KIRITING",
            "ask_phone2" => "MASALAN",
            "code1" => "USHBU ",
            "code2" => "TELEFON RAQAMIGA TASDIQLASH KODI YUBORILDI. ILTIMOS TASDIQLASH KODINI KIRITING. 📩",
            "menu" => "BIRGALIKDA BUYURTMA BERAMIZ! AXIR BU ORIGINAL OLOT SOMSA ◀️⬇️▶️️",
            "menu1" => "BUYURTMA BERISH",
            "history" => "Buyurtmalar tarixi",
            "lang" => "🇺🇿 Tilni o'zgartirish",
            "cart" => "🛒 Savatga o'tish",
            "phone" => "☎️Mening telefon raqamim",
            "select" => "Siz tanladingiz",
            "price" => "Narxi",
            "number" => "Iltimos, sonini tanlang",
            "addToCart" => "🛒 Savatga qo'shish",
            "back" => "⬅️ Ortga",
            "order" => " raqamli buyurtmangiz qabul qilindi! Iltimos, operator javobini kuting. Buyurtmangizni yetkazib berish vaqti va pulini tez orada ma'lum qilamiz! ",
            "empty" => "🤷‍♂️Savat hali bo'm-bo'sh",
            "order_time" => "Buyurtma vaqti:",
            "order_no" => "Buyurtma raqami: ",
            "general" => "Umumiy",
            "section" => "Bo'lim:",
            "select_no" => "Iltimos, soni tanlang!",
            "error" => "Kiritilgan kod noto'g'ri, qaytadan urinib ko'ring",
            "expired" => "Kod muddati tugagan. Iltimos qayta kiriting",
            "confirm3" => "NAQD PUL 💵  ✅",
            "confirm" => "TASDIQLASH  ✅",
            "confirm2" => "KARTA ORQALI 💳  ✅",
            "yana" => "Yana tanlash",
            "no_orders" => "Buyurtmalar hali mavjud emas",
            "delivery" => "🚖 Yetkazib berish",
            "sam" => "🏃‍♂️ Olib ketish",
            "select_type" => "Yetkazib berish usulini tanlang",
            "geolocation" => "📍 Geolokatsiyani jo’natish 📍 ",
            "location_text" => 'Iltimos, “📍 Geolokatsiyani jo’natish” tugmasini bosish orqali geolokatsiyangizni yuboring. Bunda telefoningizda manzilni aniqlash funksiyasi yoqilgan bo’lishi lozim."',
            "re_location" => "📍 Geolatsiyani qayta jo'natish",
            "your_address" => "Sizning manzilingiz",
            "correct_address"   => "HURMATLI MIJOZ! ILTIMOS, YASHASH MANZILINGIZNI YOZMA RAVISHDA ANIQ VA TO'G'RI YOZING !",
            "next" => "➡️ Keyingi",
            "check" => "ILTIMOS BUYURTMANGIZNI YANA BIR BOR KO'ZDAN KECHIRING",
            "payment" => "To'lov usulini tanlang",
            "settings" => "Sozlamalar / Profil",
            "name" => "Ism",
            "telefon" => "Telefon raqam",
            "til" => "Til",
            "change_til" => "Tilni o'zgartirish",
            "change_name" => "Ismni o'zgartirish",
            "change_phone" => "Telefon raqamni o'zgartirish",
            "comment" => "Izoh",
            "location" => "Geolokatsiya",
            "sum" => "so'm",
            "accepted1" => "Buyurtmangiz tayyorlanish jarayonida. Sizga ",
            "accepted2" => " 60 daqiqada yetkazib beramiz",
            "cancelled" => "Buyurtmangiz bekor qilindi",
            "dostavka" => "YETKAZIB BERISH",
            "idish" => "POSUDA",


        ],
        'ru' => [
            "hi" => "ВВЕДИТЕ СВОЕ ПОЛНОЕ ИМЯ",
            "ask_phone1" => "ВВЕДИТЕ СВОЙ НОМЕР ТЕЛЕФОНА",
            "ask_phone2" => "НАПРИМЕР",
            "code1" => "НА НОМЕР ТЕЛЕФОНА ",
            "code2" => " ОТПРАВЛЕН КОД ПРОВЕРКИ. ПОЖАЛУЙСТА, ВВЕДИТЕ КОД ПРОВЕРКИ .📩",
            "menu" => "ЗАКАЗЫВАЕМ ВМЕСТЕ! ВСЕГДА ЭТО ORIGINAL OLOT SOMSA ◀️⬇️▶️️",
            "menu1" => "ОФОРМИТЬ ЗАКАЗ",
            "history" =>"История заказов",
            "lang" => "🇺🇿 Tilni o'zgartirish",
            "cart" => "🛒 Savatga o'tish",
            "phone" => "☎️Mening telefon raqamim",
            "select" => "Siz tanladingiz",
            "price" => "Narxi",
            "number" => "Iltimos, sonini tanlang",
            "addToCart" => "🛒 Savatga qo'shish",
            "back" => "⬅️ Ortga",
            "order" => "  заказ номер получен! Пожалуйста, дождитесь ответа оператора. В ближайшее время мы сообщим вам о сроках и стоимости доставки вашего заказа!",
            "empty" => "🤷‍♂️Savat hali bo'm-bo'sh",
            "order_time" => "Время заказа: ",
            "order_no" => "Порядковый номер: ",
            "general" => "Всего",
            "section" => "Bo'lim:",
            "select_no" => "Iltimos, soni tanlang!",
            "error" => "Введен неверный код, попробуйте еще раз",
            "expired" => "Срок действия кода истек. Пожалуйста, введите повторно",
            "confirm3" => "НАЛИЧНЫМИ 💵 ✅",
            "confirm" => "ПОДТВЕРЖДЕНИЕ  ✅",
            "confirm2" => "ПО КАРТЕ 💳 ✅",
            "yana" => "Yana tanlash",
            "no_orders" => "Buyurtmalar hali mavjud emas",
            "delivery" => "🚖 Yetkazib berish",
            "sam" => "🏃‍♂️ Olib ketish",
            "select_type" => "Выберите способ доставки",
            "geolocation" => "📍 Отправить геолокацию ",
            "location_text" => 'Пожалуйста, отправьте свою геолокацию, нажав кнопку «📍 Отправить геолокацию». У вас должна быть включена функция определения местоположения на вашем телефоне».',
            "re_location" => "📍 Повторно отправить геолокацию",
            "your_address" => "Ваш адрес",
            "correct_address"   => "УВАЖАЕМЫЙ КЛИЕНТ! ПОЖАЛУЙСТА, ПИШИТЕ ВАШ АДРЕС ЧЕТКО И ПРАВИЛЬНО В ПИСЬМЕ!",
            "next" => "➡️ Следующий",
            "check" => "ПОЖАЛУЙСТА, ПРОВЕРЬТЕ ВАШ ЗАКАЗ ЕЩЕ РАЗ",
            "payment" => "Выберите способ оплаты",
            "settings" => "Настройки/Профиль",
            "name" => "Имя",
            "telefon" => "Номер телефона",
            "til" => "Язык",
            "change_til" => "Изменить язык",
            "change_name" => "Изменение имени",
            "change_phone" => "Изменить номер телефона",
            "comment" => "Комментарий",
            "location" => "Геолокация",
            "sum" => "Сум",
            "accepted1" => "Ваш заказ готовится. Доставим за ",
            "accepted2" => " минут",
            "cancelled" => "Ваш заказ отменен",
            "umumiy" => "Всего",
            "dostavka" => "ДОСТАВКА",
            "idish" => "POSUDA",

        ],
        'en' => [
            "hi" => "ASSALOMU ALAYKUM. ILTIMOS TO'LIQ ISMINGIZNI KIRITING",
            "ask_phone1" => "ILTIMOS, TELEFON RAQAMINGIZNI KIRITING:",
            "ask_phone2" => "MASALAN:",
            "code1" => "USHBU ",
            "code2" => "GA TASDIQLASH KODI YUBORILDI. ILTIMOS TASDIQLASH KODINI KIRITING. 📩",
            "menu" => "BIRGALIKDA BUYURTMA BERAMIZ 🤗 ⬇️",
            "history" => "📃 Buyurtmalar tarixi",
            "lang" => "🇺🇿 Tilni o'zgartirish",
            "cart" => "🛒 Savatga o'tish",
            "phone" => "☎️Mening telefon raqamim",
            "select" => "Siz tanladingiz",
            "price" => "Narxi",
            "number" => "Iltimos, sonini tanlang",
            "addToCart" => "🛒 Savatga qo'shish",
            "back" => "⬅️ Ortga",
            "accept" => "⬅️ raqamli buyurtmangiz qabul qilindi! Iltimos, operator qo'ng'irog'ini kutmang. Buyurtmangizni 40 daqiqa davomida yetkazib beramiz.",
            "empty" => "🤷‍♂️Savat hali bo'm-bo'sh",
            "order_time" => "Buyurtma vaqti:",
            "order_no" => "Buyurtma raqami: ",
            "general" => "Umumiy:",
            "section" => "Bo'lim:",
            "select_no" => "Iltimos, soni tanlang!",
            "error" => "Xato, qaytadan urinib ko'ring",
            "confirm" => "TASDIQLASH ✅",
            "yana" => "Yana tanlash",
            "no_orders" => "Buyurtmalar hali mavjud emas",
            "delivery" => "🚖 Yetkazib berish",
            "sam" => "🏃‍♂️ Olib ketish",
            "select_type" => "Yetkazib berish usulini tanlang",
            "geolocation" => "📍 Geolokatsiyani tanlash 📍 ",
            "location_text" => '"Iltimos, “📍 Geolokatsiyani jo’natish” tugmasini bosish orqali geolokatsiyangizni yuboring. Bunda telefoningizda manzilni aniqlash funksiyasi yoqilgan bo’lishi lozim."',
            "re_location" => "📍 Geolatsiyani qayta jo'natish",
            "your_address" => "Sizning manzilingiz",
            "correct_address"   => "HURMATLI MIJOZ! ILTIMOS, YASHASH MANZILINGIZNI YOZMA RAVISHDA ANIQ VA TO'G'RI YOZING !",
            "next" => "➡️ Keyingi"
        ]

    ];
    return $keys[$locale][$key];
}


