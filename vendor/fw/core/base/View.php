<?php

namespace fw\core\base;

class View
{
    public $route = [];
    public $scripts = [];
    public $view;
    public $layout;
    public static $meta = [];

    public function __construct($route, $layout = '', $view = '')
    {
        $this->route = $route;
        $this->view = $view;

        if ($layout === false) {
            $this->layout = false;
        } else {
            $this->layout = $layout ?: LAYOUT;
        }
    }

    public function render($vars)
    {
        $this->route['prefix'] = str_replace('\\', '/', $this->route['prefix']);

        if (is_array($vars)) {
            extract($vars);
        }

        $file_view = APP . "/views/{$this->route['prefix']}{$this->route['controller']}/{$this->view}.php";

        ob_start();

        if (is_file($file_view)) {
            require $file_view;
        } else {
            throw new \Exception("<p>Не найден вид <b>{$file_view}</b></p>", 404);
        }

        $content = ob_get_clean();

        if (false !== $this->layout) {
            $file_layout = APP . "/views/layouts/{$this->layout}.php";

            if (is_file($file_layout)) {
                $content = $this->getScript($content);
                $scripts = [];

                if (!empty($this->scripts[0])) {
                    $scripts = $this->scripts[0];
                }

                require $file_layout;
            } else {
                throw new \Exception("<p>Не найден шаблон <b>{$file_view}</b></p>", 404);
            }
        }
    }

    public static function setMeta($title = '', $description = '')
    {
        self::$meta = [
            'title' => $title,
            'description' => $description
        ];
    }

    public static function setMessage($message, $success = true)
    {
        $_SESSION['message'] = $message;
        $_SESSION['success'] = $success;
    }

    public static function getMessage()
    {
        if (isset($_SESSION['message']) && !empty($_SESSION['message'])) {
            $content = $_SESSION['message'];

            if ($_SESSION['success']) {
                require APP . "/views/layouts/flash/success.php";
            } else {
                require APP . "/views/layouts/flash/error.php";
            }

            unset($_SESSION['message']);
            unset($_SESSION['success']);
        }
    }

    protected function getScript($content)
    {
        $pattern = "#<script.*?>.*?</script>#si";
        preg_match_all($pattern, $content, $this->scripts);

        if (!empty($this->scripts)) {
            $content = preg_replace($pattern, '', $content);
        }

        return $content;
    }
}
