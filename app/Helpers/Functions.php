<?php


use Illuminate\Support\Facades\Log;

function lang($locale, $key)
{
    $locale = in_array($locale, ['ru', 'uz']) ? $locale : 'ru';
    $keys = [
        'uz' => [
            "hi" => "TO'LIQ ISMINGIZNI KIRITING",
            "ask_phone1" => "TELEFON RAQAMINGIZNI KIRITING",
            "ask_phone2" => "MASALAN",
            "code1" => "USHBU ",
            "code2" => "TELEFON RAQAMIGA TASDIQLASH KODI YUBORILDI. ILTIMOS TASDIQLASH KODINI KIRITING. ๐ฉ",
            "menu" => "BIRGALIKDA BUYURTMA BERAMIZ! AXIR BU ORIGINAL OLOT SOMSA โ๏ธโฌ๏ธโถ๏ธ๏ธ",
            "menu1" => "BUYURTMA BERISH",
            "history" => "Buyurtmalar tarixi",
            "lang" => "๐บ๐ฟ Tilni o'zgartirish",
            "cart" => "๐ Savatga o'tish",
            "phone" => "โ๏ธMening telefon raqamim",
            "select" => "Siz tanladingiz",
            "price" => "Narxi",
            "number" => "Iltimos, sonini tanlang",
            "addToCart" => "๐ Savatga qo'shish",
            "back" => "โฌ๏ธ Ortga",
            "order" => " raqamli buyurtmangiz qabul qilindi! Iltimos, operator javobini kuting. Buyurtmangizni yetkazib berish vaqti va pulini tez orada ma'lum qilamiz! ",
            "empty" => "๐คทโโ๏ธSavat hali bo'm-bo'sh",
            "order_time" => "Buyurtma vaqti:",
            "order_no" => "Buyurtma raqami: ",
            "general" => "Umumiy",
            "section" => "Bo'lim:",
            "select_no" => "Iltimos, soni tanlang!",
            "error" => "Kiritilgan kod noto'g'ri, qaytadan urinib ko'ring",
            "expired" => "Kod muddati tugagan. Iltimos qayta kiriting",
            "confirm3" => "NAQD PUL ๐ต  โ",
            "confirm" => "TASDIQLASH  โ",
            "confirm2" => "KARTA ORQALI ๐ณ  โ",
            "yana" => "Yana tanlash",
            "no_orders" => "Buyurtmalar hali mavjud emas",
            "delivery" => "๐ Yetkazib berish",
            "sam" => "๐โโ๏ธ Olib ketish",
            "select_type" => "Yetkazib berish usulini tanlang",
            "geolocation" => "๐ Geolokatsiyani joโnatish ๐ ",
            "location_text" => 'Iltimos, โ๐ Geolokatsiyani joโnatish ๐ โ tugmasini bosish orqali geolokatsiyangizni yuboring. Bunda telefoningizda manzilni aniqlash funksiyasi yoqilgan boโlishi lozim."',
            "re_location" => "๐ Geolatsiyani qayta jo'natish",
            "your_address" => "Sizning manzilingiz",
            "correct_address" => "HURMATLI MIJOZ! ILTIMOS, YASHASH MANZILINGIZNI YOZMA RAVISHDA ANIQ VA TO'G'RI YOZING !",
            "next" => "โก๏ธ Keyingi",
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
            "working_hours" => "AFSUSKI, AYNI VAQTDA BUYURTMA BERISH IMKONI YO'Q. ILTIMOS, ".explode("-",config("bots.opening_hours"))[0]." DAN ".explode("-",config("bots.opening_hours"))[1]." GACHA BUYURTMA BERING!",
        ],
        'ru' => [
            "hi" => "ะะะะะะขะ ะกะะะ ะะะะะะ ะะะฏ",
            "ask_phone1" => "ะะะะะะขะ ะกะะะ ะะะะะ? ะขะะะะคะะะ",
            "ask_phone2" => "ะะะะ?ะะะะ?",
            "code1" => "ะะ ะะะะะ? ะขะะะะคะะะ ",
            "code2" => " ะะขะะ?ะะะะะ ะะะ ะะ?ะะะะ?ะะ. ะะะะะะฃะะกะขะ, ะะะะะะขะ ะะะ ะะ?ะะะะ?ะะ .๐ฉ",
            "menu" => "ะะะะะะซะะะะ ะะะะกะขะ! ะะกะะะะ ะญะขะ ORIGINAL OLOT SOMSA โ๏ธโฌ๏ธโถ๏ธ๏ธ",
            "menu1" => "ะะคะะ?ะะะขะฌ ะะะะะ",
            "history" => "ะััะพัะธั ะทะฐะบะฐะทะพะฒ",
            "lang" => "๐บ๐ฟ Tilni o'zgartirish",
            "cart" => "๐ Savatga o'tish",
            "phone" => "โ๏ธMening telefon raqamim",
            "select" => "Siz tanladingiz",
            "price" => "Narxi",
            "number" => "Iltimos, sonini tanlang",
            "addToCart" => "๐ Savatga qo'shish",
            "back" => "โฌ๏ธ Ortga",
            "order" => "  ะทะฐะบะฐะท ะฝะพะผะตั ะฟะพะปััะตะฝ! ะะพะถะฐะปัะนััะฐ, ะดะพะถะดะธัะตัั ะพัะฒะตัะฐ ะพะฟะตัะฐัะพัะฐ. ะ ะฑะปะธะถะฐะนัะตะต ะฒัะตะผั ะผั ัะพะพะฑัะธะผ ะฒะฐะผ ะพ ััะพะบะฐั ะธ ััะพะธะผะพััะธ ะดะพััะฐะฒะบะธ ะฒะฐัะตะณะพ ะทะฐะบะฐะทะฐ!",
            "empty" => "๐คทโโ๏ธSavat hali bo'm-bo'sh",
            "order_time" => "ะัะตะผั ะทะฐะบะฐะทะฐ: ",
            "order_no" => "ะะพััะดะบะพะฒัะน ะฝะพะผะตั: ",
            "general" => "ะัะตะณะพ",
            "section" => "Bo'lim:",
            "select_no" => "Iltimos, soni tanlang!",
            "error" => "ะะฒะตะดะตะฝ ะฝะตะฒะตัะฝัะน ะบะพะด, ะฟะพะฟัะพะฑัะนัะต ะตัะต ัะฐะท",
            "expired" => "ะกัะพะบ ะดะตะนััะฒะธั ะบะพะดะฐ ะธััะตะบ. ะะพะถะฐะปัะนััะฐ, ะฒะฒะตะดะธัะต ะฟะพะฒัะพัะฝะพ",
            "confirm3" => "ะะะะะงะะซะะ ๐ต โ",
            "confirm" => "ะะะะขะะะ?ะะะะะะ  โ",
            "confirm2" => "ะะ ะะะ?ะขะ ๐ณ โ",
            "yana" => "Yana tanlash",
            "no_orders" => "Buyurtmalar hali mavjud emas",
            "delivery" => "๐ Yetkazib berish",
            "sam" => "๐โโ๏ธ Olib ketish",
            "select_type" => "ะัะฑะตัะธัะต ัะฟะพัะพะฑ ะดะพััะฐะฒะบะธ",
            "geolocation" => "๐ ะัะฟัะฐะฒะธัั ะณะตะพะปะพะบะฐัะธั ",
            "location_text" => 'ะะพะถะฐะปัะนััะฐ, ะพัะฟัะฐะฒััะต ัะฒะพั ะณะตะพะปะพะบะฐัะธั, ะฝะฐะถะฐะฒ ะบะฝะพะฟะบั ยซ๐ ะัะฟัะฐะฒะธัั ะณะตะพะปะพะบะฐัะธัยป. ะฃ ะฒะฐั ะดะพะปะถะฝะฐ ะฑััั ะฒะบะปััะตะฝะฐ ััะฝะบัะธั ะพะฟัะตะดะตะปะตะฝะธั ะผะตััะพะฟะพะปะพะถะตะฝะธั ะฝะฐ ะฒะฐัะตะผ ัะตะปะตัะพะฝะตยป.',
            "re_location" => "๐ ะะพะฒัะพัะฝะพ ะพัะฟัะฐะฒะธัั ะณะตะพะปะพะบะฐัะธั",
            "your_address" => "ะะฐั ะฐะดัะตั",
            "correct_address" => "ะฃะะะะะะะซะ ะะะะะะข! ะะะะะะฃะะกะขะ, ะะะจะะขะ ะะะจ ะะะ?ะะก ะงะะขะะ ะ ะะ?ะะะะะฌะะ ะ ะะะกะฌะะ!",
            "next" => "โก๏ธ ะกะปะตะดัััะธะน",
            "check" => "ะะะะะะฃะะกะขะ, ะะ?ะะะะ?ะฌะขะ ะะะจ ะะะะะ ะะฉะ ะ?ะะ",
            "payment" => "ะัะฑะตัะธัะต ัะฟะพัะพะฑ ะพะฟะปะฐัั",
            "settings" => "ะะฐัััะพะนะบะธ/ะัะพัะธะปั",
            "name" => "ะะผั",
            "telefon" => "ะะพะผะตั ัะตะปะตัะพะฝะฐ",
            "til" => "ะฏะทัะบ",
            "change_til" => "ะะทะผะตะฝะธัั ัะทัะบ",
            "change_name" => "ะะทะผะตะฝะตะฝะธะต ะธะผะตะฝะธ",
            "change_phone" => "ะะทะผะตะฝะธัั ะฝะพะผะตั ัะตะปะตัะพะฝะฐ",
            "comment" => "ะะพะผะผะตะฝัะฐัะธะน",
            "location" => "ะะตะพะปะพะบะฐัะธั",
            "sum" => "ะกัะผ",
            "accepted1" => "ะะฐั ะทะฐะบะฐะท ะณะพัะพะฒะธััั. ะะพััะฐะฒะธะผ ะทะฐ ",
            "accepted2" => " ะผะธะฝัั",
            "cancelled" => "ะะฐั ะทะฐะบะฐะท ะพัะผะตะฝะตะฝ",
            "umumiy" => "ะัะตะณะพ",
            "dostavka" => "ะะะกะขะะะะ",
            "idish" => "POSUDA",
            "working_hours" => "ะ ะฝะฐััะพััะตะต ะฒัะตะผั ะฝะฐั ะผะฐะณะฐะทะธะฝ ะฝะต ะฟัะธะฝะธะผะฐะตั ะทะฐะบะฐะทั. ะะฐัะธ ัะฐะฑะพัะธะต ัะฐัั: ".config("bots.opening_hours"),

        ],
        'en' => [
            "hi" => "ะะะะะะขะ ะกะะะ ะะะะะะ ะะะฏ",
            "ask_phone1" => "ะะะะะะขะ ะกะะะ ะะะะะ? ะขะะะะคะะะ",
            "ask_phone2" => "ะะะะ?ะะะะ?",
            "code1" => "ะะ ะะะะะ? ะขะะะะคะะะ ",
            "code2" => " ะะขะะ?ะะะะะ ะะะ ะะ?ะะะะ?ะะ. ะะะะะะฃะะกะขะ, ะะะะะะขะ ะะะ ะะ?ะะะะ?ะะ .๐ฉ",
            "menu" => "ะะะะะะซะะะะ ะะะะกะขะ! ะะกะะะะ ะญะขะ ORIGINAL OLOT SOMSA โ๏ธโฌ๏ธโถ๏ธ๏ธ",
            "menu1" => "ะะคะะ?ะะะขะฌ ะะะะะ",
            "history" => "ะััะพัะธั ะทะฐะบะฐะทะพะฒ",
            "lang" => "๐บ๐ฟ Tilni o'zgartirish",
            "cart" => "๐ Savatga o'tish",
            "phone" => "โ๏ธMening telefon raqamim",
            "select" => "Siz tanladingiz",
            "price" => "Narxi",
            "number" => "Iltimos, sonini tanlang",
            "addToCart" => "๐ Savatga qo'shish",
            "back" => "โฌ๏ธ Ortga",
            "order" => "  ะทะฐะบะฐะท ะฝะพะผะตั ะฟะพะปััะตะฝ! ะะพะถะฐะปัะนััะฐ, ะดะพะถะดะธัะตัั ะพัะฒะตัะฐ ะพะฟะตัะฐัะพัะฐ. ะ ะฑะปะธะถะฐะนัะตะต ะฒัะตะผั ะผั ัะพะพะฑัะธะผ ะฒะฐะผ ะพ ััะพะบะฐั ะธ ััะพะธะผะพััะธ ะดะพััะฐะฒะบะธ ะฒะฐัะตะณะพ ะทะฐะบะฐะทะฐ!",
            "empty" => "๐คทโโ๏ธSavat hali bo'm-bo'sh",
            "order_time" => "ะัะตะผั ะทะฐะบะฐะทะฐ: ",
            "order_no" => "ะะพััะดะบะพะฒัะน ะฝะพะผะตั: ",
            "general" => "ะัะตะณะพ",
            "section" => "Bo'lim:",
            "select_no" => "Iltimos, soni tanlang!",
            "error" => "ะะฒะตะดะตะฝ ะฝะตะฒะตัะฝัะน ะบะพะด, ะฟะพะฟัะพะฑัะนัะต ะตัะต ัะฐะท",
            "expired" => "ะกัะพะบ ะดะตะนััะฒะธั ะบะพะดะฐ ะธััะตะบ. ะะพะถะฐะปัะนััะฐ, ะฒะฒะตะดะธัะต ะฟะพะฒัะพัะฝะพ",
            "confirm3" => "ะะะะะงะะซะะ ๐ต โ",
            "confirm" => "ะะะะขะะะ?ะะะะะะ  โ",
            "confirm2" => "ะะ ะะะ?ะขะ ๐ณ โ",
            "yana" => "Yana tanlash",
            "no_orders" => "Buyurtmalar hali mavjud emas",
            "delivery" => "๐ Yetkazib berish",
            "sam" => "๐โโ๏ธ Olib ketish",
            "select_type" => "ะัะฑะตัะธัะต ัะฟะพัะพะฑ ะดะพััะฐะฒะบะธ",
            "geolocation" => "๐ ะัะฟัะฐะฒะธัั ะณะตะพะปะพะบะฐัะธั ",
            "location_text" => 'ะะพะถะฐะปัะนััะฐ, ะพัะฟัะฐะฒััะต ัะฒะพั ะณะตะพะปะพะบะฐัะธั, ะฝะฐะถะฐะฒ ะบะฝะพะฟะบั ยซ๐ ะัะฟัะฐะฒะธัั ะณะตะพะปะพะบะฐัะธัยป. ะฃ ะฒะฐั ะดะพะปะถะฝะฐ ะฑััั ะฒะบะปััะตะฝะฐ ััะฝะบัะธั ะพะฟัะตะดะตะปะตะฝะธั ะผะตััะพะฟะพะปะพะถะตะฝะธั ะฝะฐ ะฒะฐัะตะผ ัะตะปะตัะพะฝะตยป.',
            "re_location" => "๐ ะะพะฒัะพัะฝะพ ะพัะฟัะฐะฒะธัั ะณะตะพะปะพะบะฐัะธั",
            "your_address" => "ะะฐั ะฐะดัะตั",
            "correct_address" => "ะฃะะะะะะะซะ ะะะะะะข! ะะะะะะฃะะกะขะ, ะะะจะะขะ ะะะจ ะะะ?ะะก ะงะะขะะ ะ ะะ?ะะะะะฌะะ ะ ะะะกะฌะะ!",
            "next" => "โก๏ธ ะกะปะตะดัััะธะน",
            "check" => "ะะะะะะฃะะกะขะ, ะะ?ะะะะ?ะฌะขะ ะะะจ ะะะะะ ะะฉะ ะ?ะะ",
            "payment" => "ะัะฑะตัะธัะต ัะฟะพัะพะฑ ะพะฟะปะฐัั",
            "settings" => "ะะฐัััะพะนะบะธ/ะัะพัะธะปั",
            "name" => "ะะผั",
            "telefon" => "ะะพะผะตั ัะตะปะตัะพะฝะฐ",
            "til" => "ะฏะทัะบ",
            "change_til" => "ะะทะผะตะฝะธัั ัะทัะบ",
            "change_name" => "ะะทะผะตะฝะตะฝะธะต ะธะผะตะฝะธ",
            "change_phone" => "ะะทะผะตะฝะธัั ะฝะพะผะตั ัะตะปะตัะพะฝะฐ",
            "comment" => "ะะพะผะผะตะฝัะฐัะธะน",
            "location" => "ะะตะพะปะพะบะฐัะธั",
            "sum" => "ะกัะผ",
            "accepted1" => "ะะฐั ะทะฐะบะฐะท ะณะพัะพะฒะธััั. ะะพััะฐะฒะธะผ ะทะฐ ",
            "accepted2" => " ะผะธะฝัั",
            "cancelled" => "ะะฐั ะทะฐะบะฐะท ะพัะผะตะฝะตะฝ",
            "umumiy" => "ะัะตะณะพ",
            "dostavka" => "ะะะกะขะะะะ",
            "idish" => "POSUDA", 4,
            "working_hours" => "ะ ะฝะฐััะพััะตะต ะฒัะตะผั ะฝะฐั ะผะฐะณะฐะทะธะฝ ะฝะต ะฟัะธะฝะธะผะฐะตั ะทะฐะบะฐะทั. ะะฐัะธ ัะฐะฑะพัะธะต ัะฐัั: ".config("bots.opening_hours"),
        ]
    ];
    return $keys[$locale][$key];
}


