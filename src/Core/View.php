<?php

namespace App\Core;

class View {
    private $template;
    private $variables = array();
    private $useLayout = true;

    public function __construct($template) {
        $this->template = $template;
    }

    public function assign($key, $value) {
        $this->variables[$key] = $value;
    }

    public function disableLayout() {
        $this->useLayout = false;
    }

    public function render() {
        extract($this->variables);

        ob_start();
        require(dirname(dirname(__FILE__)) . '/Views/' . $this->template . '.php');
        $content = ob_get_clean();

        if ($this->useLayout) {
            require(dirname(dirname(__FILE__)) . '/Views/layout.php');
        } else {
            echo $content;
        }
    }
}