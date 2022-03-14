<?php

namespace sega\Status\API;

use pocketmine\utils\TextFormat;
use sega\Status\API\Webhook;

class Message implements Discord {

    const message =  [
        "username" => Webhook::USERNAME,
        "content" => " "
    ];

    /** @var array $content */
    private array $content;

    public function __construct() {
        $this->content = self::message;
    }

    /**
     * @param string $message
     * @return $this
     */
    public function setMessage(string $message) : self {
        $this->content["content"] = TextFormat::clean($message);
        return $this;
    }

    /** @return string */
    public function getContent() : string {
        return json_encode($this->content);
    }
}