<?php

namespace Ninja;

class Markdown {

    private $string;

    public function __construct($markDown) {
        $this->string = $markDown;
    }

    public function toHtml() {
// convert $this->string to HTML
        $text = htmlspecialchars($this->string, ENT_QUOTES, 'UTF-8');
// strong (bold)
        $text = preg_replace('/__(.+?)__/s', '<strong>$1</strong>', $text);
        $text = preg_replace('/\*\*(.+?)\*\*/s', '<strong>$1</strong>', $text);
// emphasis (italic)
        $text = preg_replace('/_([^_]+)_/', '<em>$1</em>', $text);
        $text = preg_replace('/\*([^\*]+)\*/', '<em>$1</em>', $text);
        return $text;
    }

}