<?php


namespace App\Http\Webhooks;


class WebhookHandler extends \DefStudio\Telegraph\Handlers\WebhookHandler
{

    public function hi()
    {
        $this->chat->markdown("*Hi* happy to be here!")->send();
    }
}
