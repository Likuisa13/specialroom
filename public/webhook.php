<?php

use Src\Controller\BookingController;
use Src\Model\Room;
use Src\System\DatabaseConnector;
use TelegramBot\Api\Client;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;

require_once "../vendor/autoload.php";

/* get class db */
$dbConnection = (new DatabaseConnector())->getConnection();

try {

    $bot = new Client('1192843998:AAHDWGK76wFEzGsRXFw570FB84f-ekFutG0');

    $bot->command('ping', function ($message) use ($bot) {
        $bot->sendMessage($message->getChat()->getId(), 'pong!');
    });

    $bot->command('start_inline_keyboard', function ($message) use ($bot) {
        $keyboard = new InlineKeyboardMarkup(
            [
                [
                    ['text' => 'link', 'callback_data' => 'tests'],
                    ['text' => 'link', 'url' => 'https://core.telegram.org']
                ]
            ]
        );

        $bot->sendMessage($message->getChat()->getId(), "This is inline keyboard", null, false, null, $keyboard);
    });

    $bot->command('start_reply_keyboard', function ($message) use ($bot) {

        $keyboard = array(
            "inline_keyboard" => array(array(array("text" => "My Button Text", "callback_data" => "myCallbackData")))
        );

        $keyboard = new ReplyKeyboardMarkup(array(array("one", "two", "three")), true); // true for one-time keyboard

        $bot->sendMessage($message->getChat()->getId(), "This is reply keyboard", null, false, null, $keyboard);
    });

    $bot->command('start', function ($message) use ($bot) {

        $responseMessage = '';
        $responseMessage .= "Hi, Special Buddy\n";
        $responseMessage .= "\n";
        $responseMessage .= "Selamat datang di layanan ChatBot ini.\n";
        $responseMessage .= "Anda bisa panggil saya Maya..\n";
        $responseMessage .= "\n";
        $responseMessage .= "sementara ini saya bisa memberikan informasi:\n";
        $responseMessage .= "\n";
        $responseMessage .= "1. Booking room\n";
        $responseMessage .= "2. Available room\n";

        $keyboard = new InlineKeyboardMarkup(
            [
                [
                    [
                        "text" => "Booking Room",
                        "callback_data" => "booking"
                    ],
                    [
                        "text" => "Available Room",
                        "callback_data" => "available"
                    ]
                ]
            ]
        );

        $bot->sendMessage($message->getChat()->getId(), $responseMessage, null, false, null, $keyboard);
    });

    $bot->callbackQuery(function ($callbackQuery) use ($bot, $dbConnection) {
        $firstName = $callbackQuery->getFrom()->getFirstName();

        if ($callbackQuery->getData() == "booking") {
            $responseMessage = 'Okey, anda akan membooking ruangan, ini format bookingnya ya:';
            $responseMessage .= "\n";
            $responseMessage .= "\n";
            $responseMessage .= "Email: \n";
            $responseMessage .= "Ruangan: \n";
            $responseMessage .= "Keperluan: \n";

            $bot->sendMessage($callbackQuery->getMessage()->getChat()->getId(), $responseMessage);
        }
        if ($callbackQuery->getData() == "available") {
            $responseMessage = "Okey $firstName,";
            $responseMessage .= "\n";
            $responseMessage .= "Kami akan mencarikan ruangan yang tersedia silahkan pilih lokasi dulu ya:";

            $keyboard = new InlineKeyboardMarkup(
                [
                    [
                        [
                            "text" => "Jakarta",
                            "callback_data" => "Jakarta"
                        ],
                        [
                            "text" => "Jogja",
                            "callback_data" => "Yogyakarta"
                        ]
                    ]
                ]
            );

            $bot->sendMessage($callbackQuery->getMessage()->getChat()->getId(), $responseMessage, null, false, null, $keyboard);

            // $booking = new BookingController($dbConnection);
            // $getData = $booking->getRoomAvailable();
            // // $jumdata = count($getData);
            // $response = '';
            // $response .= '<pre>';
            // $response .= "\n";
            // $response .= "Ruangan | Pemesan | Tanggal | Waktu \n";
            // foreach ($getData as $value) {
            //     $response .= "" . $value['room_name'] . " | " . $value['name'] . " | " . $value['booking_date'] . " | " . $value['booking_time'] . " \n";
            // }
            // $response .= '</pre>';

            // // $message = '<b>Selamat</b> datang dimenu available! ' . $jumdata;
            // $bot->sendMessage($callbackQuery->getMessage()->getChat()->getId(), $response, 'HTML');
        }

        if ($callbackQuery->getData() == "Yogyakarta") {
            $date = date("Y-m-d");
            $controller = new Room($dbConnection);
            $response = $controller->checkAvailable($date, $callbackQuery->getData());

            $responseMessage = "$firstName, ini ruangan yang tersedia untuk dibooking di Jogja ya";
            $responseMessage .= "\n";

            foreach ($response as $value) {
                $responseMessage .= "$value\n";
            }

            $bot->sendMessage($callbackQuery->getMessage()->getChat()->getId(), $responseMessage);
        }
    });

    $bot->run();
} catch (\TelegramBot\Api\Exception $e) {
    echo $e->getMessage();
}
