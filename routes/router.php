<?php

class Route
{
    public static function get( $route, $controller )
    {
        if( $_SERVER['REQUEST_METHOD'] === 'GET' && $_SERVER['REQUEST_URI'] === $route )
        {
            (new static)->callController( $controller );
        }
    }

    private function callController($data)
    {
        $controller = '';
        $method = '';

        if (is_array($data)) {
            try {
                if (count($data) < 2) {
                    throw new InvalidArgumentException("Controller or method name is missing.");
                }

                $controller = $data[0];
                $method = $data[1];

            } catch (InvalidArgumentException $e) {
                echo "Error: " . $e->getMessage();
            } catch (Exception $e) {
                echo "An unexpected error occurred: " . $e->getMessage();
            }
        } else {
            echo "Controller must me a type of array";
        }

        if( empty($controller) || empty($method) )
        {
            echo 'Controller or method name is missing.';
            return;
        }

        if( ! class_exists($controller) )
        {
            echo 'Controller does not exist.';
            return;
        }

        $controller = new $controller;

        if( ! method_exists($controller, $method) ) {
            echo 'Method does not exist.';
            return;
        }

        $controller->$method();
    }
}
