<?php

namespace sega\Status\API;

use pocketmine\utils\TextFormat;
use sega\Status\API\Webhook;

class Embed implements Discord {

    const message = [
        "username" => Webhook::USERNAME,
        "embeds" => [
            [
                "title" => "Hoku",
                "type" => "rich",
                "color" => Discord::LIGHT_PURPLE,
                "fields" => [],
                "footer" => []
            ]
        ]
    ];

    /** @var array  */
    private array $content;

    public function __construct() {
        $this->content = self::message;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title) : self {
        $this->content["embeds"][0]["title"] = TextFormat::clean($title);
        return $this;
    }

    /**
     * @param int $color
     * @return $this
     */
    public function setColor(int $color) : self {
        $this->content["embeds"][0]["color"] = $color;
        return $this;
    }

    /**
     * @param string $footer
     * @return $this
     */
    public function setFooter(string $footer) : self {
        $this->content["embeds"][0]["footer"]["text"] = TextFormat::clean($footer);
        return $this;
    }

    /**
     * @param string $field
     * @param string $value
     * @return $this
     */
    public function addField(string $field, string $value) : self {
        $field = TextFormat::clean($field);
        $value = TextFormat::clean($value);
        $embedData = [
            "name" => $field,
            "value" => $value,
            "inline" => false
        ];

        $key = count($this->content["embeds"][0]["fields"]);

        $this->content["embeds"][0]["fields"][$key] = $embedData;
        return $this;
    }

    public function setDescription(string $description) : self {
        $this->content["embeds"][0]["description"] = TextFormat::clean($description);
        return $this;
    }

    /** @return string */
    public function getContent() : string {
        return json_encode($this->content);
    }
}