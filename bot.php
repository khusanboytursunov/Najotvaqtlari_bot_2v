<?php
include 'Telegram.php';

require_once realpath(__DIR__ . "/vendor/autoload.php");

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$bot_token = $_ENV['BOT_TOKEN'];

$telegram = new Telegram($bot_token);

// user's info
$result = $telegram->getData();
$text = $result['message'] ['text'];
$chat_id = $result['message'] ['chat']['id'];
$first_name = $result['message']['chat']['first_name'];
$username = $result['message']['chat']['username'];
$date = date('Y-m-d', $result['message']['date']);

include 'database.php';


// start command
if ($text == '/start') {

    $db_insert = $db->query("SELECT chat_id FROM users WHERE chat_id = '$chat_id'")->fetch_assoc();

    if (!$db_insert){
        $db->query("insert into users (name,username,chat_id,joined_at) values ('$first_name','$username','$chat_id','$date')");
    }
    
    $reply = "Assalomu alaykum va rohmatullohi va barokatuh!

😊 Botimizga xush kelibsiz!

⏳ Vaqtlar <b>Polvonko'l jome</b> masjidida belgilangan va har <b>Juma</b> kuni yangilanadi

❗️ Namoz vaqtlarini bilish uchun <b>Namoz vaqtlari</b> tugmasini bosing.
";

    $option = array(array($telegram->buildKeyboardButton("Namoz vaqtlari")));

    $keyb = $telegram->buildKeyBoard($option, $onetime=false,$resize=true);
    $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => $reply,'parse_mode' => 'HTML');
    $telegram->sendMessage($content);
}
// about command
if ($text == '/about') {
    $sql = "SELECT COUNT(*) FROM users";
    $count = $db->query($sql);
    $row = $count->fetch_assoc();
    $num = $row["COUNT(*)"];

    $reply = "
📊 Statistika:

👤 Foydalanuvchilar: " . $num . "

📨 Bog'lanish uchun: @dastur_muhandisi_bot
";

    $content = array('chat_id' => $chat_id, 'text' => $reply,'parse_mode' => 'HTML');
    $telegram->sendMessage($content);
}
// Namoz vaqtlari button
if ($text == 'Namoz vaqtlari') {
    $vaqtlar = $db->query("SELECT * FROM vaqtlar")->fetch_assoc();
    $a = explode('-',$vaqtlar['vaqt_oraliq']);
    $b = $a[0];
    $c = $a[1];

    $reply = "🕋 Namoz vaqtlari
    
🗓 " . $b ." dan"." $c"." gacha". "

🕔 Bomdod: <b>".$vaqtlar['bomdod']."</b>
🕐 Peshin: <b>".$vaqtlar['peshin']."</b>
🕓 Asr: <b>".$vaqtlar['asr']."</b>
🕧 Shom: <b>".$vaqtlar['shom'].' (+1)'."</b>
🕗 Xufton: <b>".$vaqtlar['xufton']."</b>

❗️ Albatta, namoz mo'minlarga vaqtida farz qilingandir (Niso surasi,103-oyat).";

    $content = array('chat_id' => $chat_id, 'text' => $reply,'parse_mode' => 'HTML');
    $telegram->sendMessage($content);
}