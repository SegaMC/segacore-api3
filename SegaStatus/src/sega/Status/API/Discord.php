<?php

namespace sega\Status\API;

interface Discord
{

    public function getContent(): string;

    const WHITE = 16777215;
    const GREEN = 0x32CD32;
    const RED = 0xFF0000;
    const LIGHT_BLUE = 0x87CEFA;
    const BLUE = 0x0000FF;
    const PURPLE = 0x5440cd;
    const LIGHT_PURPLE = 0xFF00FF;
}
