<?php
// @Force_Brain он сосал его ебали

$heroku_app_name = 'phhptest' // << название_приложения_хероку

define('API_KEY','5121049943:AAGpYMzfmFBL_VToZpfQ86V_8NXegZlCvaQ'); // << ТОКЕН_БОТА
function bot($method,$datas=[]){
    $url = 'https://api.telegram.org/bot'.API_KEY.'/'.$method;
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$datas);
    $res = curl_exec($ch);
    if(curl_error($ch)){
        var_dump(curl_error($ch));
        $setweb=file_get_contents('https://api.telegram.org/bot'.API_KEY.'/setWebhook?url=https://'.heroku_app_name.'.herokuapp.com&max_connections=100');
    }else{
        return json_decode($res);
    }
}

$update = json_decode(file_get_contents('php://input'));

$message = $update->message;
$user_id = $message->from->id;
$msg_id = $message->message_id;
$user_first = $message->from->first_name;
$user_last = $message->from->last_name;

$chat_id = $message->chat->id;
$chat_type = $message->chat->type;

if ($chat_type=='private'){

$start_time = round(microtime(true) * 1000);
$send=bot('sendmessage',[
'chat_id' => $chat_id,
'text' =>"$user_first $user_last,
Соединений: ... 🔌
В обработке: ... ♻
Задержка: ...⌚",
'reply_to_message_id'=>$msg_id,
])->result->message_id;
$end_time = round(microtime(true) * 1000);
$time_taken = $end_time - $start_time;

$web_info = json_decode(file_get_contents('https://api.telegram.org/bot'.API_KEY.'/getWebhookinfo'));
$web_max = $web_info->result->max_connections;
$web_update = $web_info->result->pending_update_count;
bot('editMessagetext',[
"chat_id"=>$chat_id,
"message_id"=>$send,
"text" =>"$user_first $user_last,
Соединений: $web_max 🔌
В обработке: $web_update ♻
Задержка: " . $time_taken . 'мс⌚',
]);

}
