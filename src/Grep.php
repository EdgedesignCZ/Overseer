<?php

namespace Edge\Overseer;

class Grep
{

    public function invertGrep($pattern, $text)
    {
        return implode("\n", preg_grep($pattern, explode("\n", $text), PREG_GREP_INVERT));
    }

}
