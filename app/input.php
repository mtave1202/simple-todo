<?php
class Input
{
    protected $headers = [];
    protected $_raw_input_stream;
    protected $_input_stream;


    public function __construct()
    {
        $this->_sanitize();
    }

    protected function _clean_input_key($str)
    {
        if (!preg_match('/^[a-z0-9:_\/|-]+$/i', $str)) {
            return FALSE;
        }
        return $str;
    }

    protected function _clean_input_value($str)
    {
        if (is_array($str)) {
            $new_array = array();
            foreach (array_keys($str) as $key) {
                $new_array[$this->_clean_input_key($key)] = $this->_clean_input_value($str[$key]);
            }
            return $new_array;
        }
        $str = remove_invisible_characters($str, FALSE);
        return $str;
    }

    protected function _sanitize()
    {
        if (is_array($_GET)) {
            foreach ($_GET as $key => $val) {
                $_GET[$this->_clean_input_key($key)] = $this->_clean_input_value($val);
            }
        }
        if (is_array($_POST)) {
            foreach ($_POST as $key => $val) {
                $_POST[$this->_clean_input_key($key)] = $this->_clean_input_value($val);
            }
        }
        if (is_array($_COOKIE)) {
            foreach ($_COOKIE as $key => $val) {
                if (($cookie_key = $this->_clean_input_key($key)) !== false) {
                    $_COOKIE[$cookie_key] = $this->_clean_input_value($val);
                } else {
                    unset($_COOKIE[$key]);
                }
            }
        }
        $_SERVER['PHP_SELF'] = strip_tags($_SERVER['PHP_SELF']);
    }

    protected function _fetch_from_array(&$array, $index = NULL)
    {
        isset($index) or $index = array_keys($array);
        if (is_array($index)) {
            $output = array();
            foreach ($index as $key) {
                $output[$key] = $this->_fetch_from_array($array, $key);
            }
            return $output;
        }

        if (isset($array[$index])) {
            $value = $array[$index];
        } elseif (($count = preg_match_all('/(?:^[^\[]+)|\[[^]]*\]/', $index, $matches)) > 1) {
            $value = $array;
            for ($i = 0; $i < $count; $i++) {
                $key = trim($matches[0][$i], '[]');
                if ($key === '') {
                    break;
                }
                if (isset($value[$key])) {
                    $value = $value[$key];
                } else {
                    return NULL;
                }
            }
        } else {
            return NULL;
        }

        return $value;
    }

    public function __get($name)
    {
        if ($name === 'raw_input_stream') {
            isset($this->_raw_input_stream) or $this->_raw_input_stream = file_get_contents('php://input');
            return $this->_raw_input_stream;
        } elseif ($name === 'ip_address') {
            return $this->ip_address;
        }
    }

    public function get($index = null)
    {
        return $this->_fetch_from_array($_GET, $index);
    }

    public function post($index = null)
    {
        return $this->_fetch_from_array($_POST, $index);
    }

    public function request($index)
    {
        return isset($_POST[$index]) ? $this->post($index) : $this->get($index);
    }

    public function cookie($index = null)
    {
        return $this->_fetch_from_array($_COOKIE, $index);
    }

    public function server($index)
    {
        return $this->_fetch_from_array($_SERVER, $index);
    }

    public function env($index = null)
    {
        return $this->_fetch_from_array($_ENV, $index);
    }

    public function input_stream($index = null)
    {
        if (!is_array($this->_input_stream)) {
            parse_str($this->raw_input_stream, $this->_input_stream);
            is_array($this->_input_stream) or $this->_input_stream = array();
        }
        return $this->_fetch_from_array($this->_input_stream, $index);
    }

    public function user_agent()
    {
        return $this->_fetch_from_array($_SERVER, 'HTTP_USER_AGENT');
    }

    public function request_header($index = null)
    {
        if (!empty($this->headers)) {
            return $this->_fetch_from_array($this->headers, $index);
        }
        if (function_exists('apache_request_headers')) {
            $this->headers = apache_request_headers();
        } else {
            isset($_SERVER['CONTENT_TYPE']) && $this->headers['Content-Type'] = $_SERVER['CONTENT_TYPE'];
            foreach ($_SERVER as $key => $val) {
                if (sscanf($key, 'HTTP_%s', $header) === 1) {
                    $header = str_replace('_', ' ', strtolower($header));
                    $header = str_replace(' ', '-', ucwords($header));
                    $this->headers[$header] = $_SERVER[$key];
                }
            }
        }
        return $this->_fetch_from_array($this->headers, $index);
    }

    public function method($upper = FALSE)
    {
        return ($upper)
            ? strtoupper($this->server('REQUEST_METHOD'))
            : strtolower($this->server('REQUEST_METHOD'));
    }

    public function is_get()
    {
        return $this->method(true) === "GET";
    }

    public function is_post()
    {
        return $this->method(true) === "POST";
    }

    public function is_ajax_request()
    {
        return $this->server('HTTP_X_REQUESTED_WITH') === 'xmlhttprequest';
    }

    public function is_cli()
    {
        return PHP_SAPI === 'cli' or defined('STDIN');
    }
}
