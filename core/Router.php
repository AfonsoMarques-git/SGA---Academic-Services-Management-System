<?php
/**
 * Router - Simple URL routing system
 */
class Router {
    protected $routes = [];
    protected $basePath = '/public';
    
    public function __construct($basePath = '/public') {
        $this->basePath = $basePath;
    }
    
    /**
     * Register a GET route
     */
    public function get($path, $callback) {
        $this->register('GET', $path, $callback);
    }
    
    /**
     * Register a POST route
     */
    public function post($path, $callback) {
        $this->register('POST', $path, $callback);
    }
    
    /**
     * Register a route for multiple methods
     */
    public function match($methods, $path, $callback) {
        foreach ((array)$methods as $method) {
            $this->register($method, $path, $callback);
        }
    }
    
    /**
     * Register a route
     */
    protected function register($method, $path, $callback) {
        $key = $method . ':' . $path;
        $this->routes[$key] = $callback;
    }
    
    /**
     * Dispatch the request
     */
    public function dispatch($uri, $method = 'GET') {
        // Normalize URI
        $uri = '/' . trim(str_replace($this->basePath, '', $uri), '/');
        
        // Try exact match first
        $key = $method . ':' . $uri;
        if (isset($this->routes[$key])) {
            return call_user_func($this->routes[$key]);
        }
        
        // Try pattern matching
        foreach ($this->routes as $pattern => $callback) {
            if ($this->match_pattern($pattern, $method . ':' . $uri)) {
                return call_user_func($callback);
            }
        }
        
        // Not found
        http_response_code(404);
        die('404 - Página não encontrada');
    }
    
    /**
     * Match URL pattern with parameters
     */
    protected function match_pattern($pattern, $uri) {
        $pattern = preg_quote($pattern, '#');
        $pattern = str_replace('\\{id\\}', '(?P<id>\\d+)', $pattern);
        $pattern = '#^' . $pattern . '$#';
        
        return (bool)preg_match($pattern, $uri);
    }
}
