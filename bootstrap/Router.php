<?php

class Router
{
    static $uri;
    static $routes;

    public static function makeUri()
    {
        if(!empty($_SERVER['PATH_INFO']))
        {
            self::$uri = $_SERVER['PATH_INFO'];
        }
        elseif (!empty($_SERVER['REQUEST_URI']))
        {
            self::$uri = $_SERVER['REQUEST_URI'];

            //removing index
            if (strpos(self::$uri, 'index.php') !== FALSE)
            {
                self::$uri = str_replace(self::$uri, 'index.php', '');
            }
        }

        return parse_url(trim(self::$uri, '/'), PHP_URL_PATH);
    }

    public static function matchUri($uri)
    {
        require(__DIR__ . '/../routes/routes.php');

        if (empty($routes))
        {
            \Error::throw_error('Routes must not be empty');
        }

        self::$routes = $routes;

        $params = array();

        foreach ($routes as $route)
        {
            $route_uri = array_shift($route);

            $regex_uri = self::makeRegexUri($route_uri);

            if (!preg_match($regex_uri, $uri, $match))
            {
                continue;
            }
            else
            {
                foreach ($match as $key => $value)
                {
                    if (is_int($key))
                    {
                        //removing preg_match digit keys
                        continue;
                    }

                    $params[$key] = $value;
                }

                //if no values are set, load default ones
                foreach ($route as $key => $value)
                {
                    if (!isset($params[$key]))
                    {
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
        $expression = preg_replace('#'.$reg_escape.'#', '\\\\$0', $uri);

        if (strpos($expression, '(') !== FALSE)
        {
            $expression = str_replace(array('(', ')'), array('(?:', ')?'), $expression);
        }

        $reg_segment = '[^/.,;?\n]++';
        $expression = str_replace(array('<', '>'), array('(?P<', '>'.$reg_segment.')'), $expression);

        return '#^'.$expression.'$#uD';
    }
}