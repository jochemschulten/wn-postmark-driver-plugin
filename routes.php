<?php
use SchultenMedia\Postmark\Models\Postmarklog;

/*
 * Postmark webhook
 */
Route::post('/hook/postmark', function()
{
    $postData = Input::get();

    if(empty($postData)) {
        return response()->json(['No postdata']);
    }

    $postmarkLog = new Postmarklog();
    $postmarkLog->type = $postData['RecordType'];
    $postmarkLog->server_id = $postData['server_id'];
    $postmarkLog->message_stream = $postData['message_stream'];
    $postmarkLog->recipient = $postData['recipient'];
    $postmarkLog->details = $postData['details'];
    $postmarkLog->delivered_at = $postData['DeliveredAt'];
    $postmarkLog->message_stream = $postData['message_stream'];
    $postmarkLog->metadata = $postData['metadata'];
    $postmarkLog->save();

    return response()->json($postmarkLog);
});