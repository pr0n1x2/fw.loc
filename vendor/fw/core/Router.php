<?php

namespace fw\core;

class Router
{
    private static $routes = [];
    private static $route = [];

    public static function add($regexp, $route = [])
    {
        self::$routes[$regexp] = $route;
    }

    public static function getRoutes()
    {
        return self::$routes;
    }

    public static function getRoute()
    {
        return self::$route;
    }

    public static function dispatch($url)
    {
        $url = self::removeQueryString($url);

        if (self::matchRoute($url)) {
            $controller = 'app\controllers\\' . self::$route['prefix'] .  self::$route['controller'] . "Controller";

            if (class_exists($controller)) {
                $cObj = new $controller(self::$route);
                $action = self::lowerCamelCase(self::$route['action']) . 'Action';

                if (method_exists($cObj, $action)) {
                    $cObj->$action();
                    $cObj->getView();
                } else {
                    throw new \Exception("Метод $controller::$action не найден", 404);
                }
            } else {
                throw new \Exception("Контроллер $controller не найден.", 404);
            }
        } else {
            throw new \Exception("Страница не найдена", 404);
        }
    }

    private static function matchRoute($url)
    {
        foreach (self::$routes as $pattern => $route) {
            if (preg_match("#$pattern#", $url, $matches)) {
                foreach ($matches as $key => $value) {
                    if (is_string($key)) {
                        $route[$key] = $value;
                    }
                }

                if (!isset($route['action'])) {
                    $route['action'] = 'index';
                }

                if (!isset($route['prefix'])) {
                    $route['prefix'] = '';
                } else {
                    $route['prefix'] .= '\\';
                }

                $route['controller'] = self::upperCamelCase($route['controller']);
                self::$route = $route;

                return true;
            }
        }

        return false;
    }

    private static function upperCamelCase($name)
    {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $name)));
    }

    private static function lowerCamelCase($name)
    {
        return lcfirst(self::upperCamelCase($name));
    }

    private static function removeQueryString($url)
    {
        if ($url) {
            $params = explode('&', $url, 2);

            if (false === strpos($params[0], '=')) {
                return rtrim($params[0], '/');
            } else {
                return '';
            }
        }
    }
}
