<?php

class Router
{
    static $uri;
    static $routes;

    public static function makeUri()
    {
        if (!empty($_SERVER['PATH_INFO'])) {
            self::$uri = $_SERVER['PATH_INFO'];
        } elseif (!empty($_SERVER['REQUEST_URI'])) {
            self::$uri = $_SERVER['REQUEST_URI'];

            //removing index
            if (strpos(self::$uri, 'index.php') !== false) {
                self::$uri = str_replace(self::$uri, 'index.php', '');
            }
        }

        return parse_url(trim(self::$uri, '/'), PHP_URL_PATH);
    }

    public static function matchUri($uri)
    {
        require(__DIR__ . '/../routes/routes.php');

        if (empty($routes)) {
            throw new Exception('Routes must not be empty');
        }

        self::$routes = $routes;

        $params = [];

        $uri = trim(self::checkRelativeURI($uri), '/');

        $uri = !!$uri ? $uri : 'index';
        
        foreach ($routes as $route) {

            $route_uri = trim(array_shift($route), '/');
            $route_uri = !!$route_uri ? $route_uri : 'index';
            $regex_uri = self::makeRegexUri($route_uri);

            if (!preg_match($regex_uri, $uri, $match)) {
                continue;
            } else {
                foreach ($match as $key => $value) {
                    if (is_int($key)) {
                        continue;
                    }
                    $params[$key] = $value;
                }
                //if no values are set, load default ones
                foreach ($route as $key => $value) {
                    if (!isset($params[$key])) {

                        $params[$key] = $value;
                    }
                }
                break;
            }
        }
        return $params;
    }

    private static function makeRegexUri($uri)
    {
        $reg_escape = '[.\\+*?[^\\]${}=!|]';
        $expression = preg_replace('#' . $reg_escape . '#', '\\\\$0', $uri);

        if (strpos($expression, '(') !== FALSE) {
            $expression = str_replace(array('(', ')'), array('(?:', ')?'), $expression);
        }

        $reg_segment = '[^/.,;?\n]++';
        $expression = str_replace(array('<', '>'), array('(?P<', '>' . $reg_segment . ')'), $expression);

        return '#^' . $expression . '$#uD';
    }

    /**
     * Check if script was executed in the root
     *
     * @param $uri
     * @return null
     */
    public static function checkRelativeURI($uri)
    {
        $index = $_SERVER['PHP_SELF'];

        if (strpos($index, 'index') != 1) {
            if (strpos($uri, 'web') !== false) {
                preg_match('/web(.*)/', $uri, $relativeUri);
                $uri = $relativeUri[1] ?? '';
            }
        }
        return $uri;
    }
}