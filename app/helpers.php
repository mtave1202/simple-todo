<?php
if (!function_exists('eLog')) {
    function eLog($msg, $depth = 0, $logFile = null)
    {
        $w = '';
        if (is_array($msg) || is_object($msg)) {
            $msg = var_export($msg, true);
        }
        $trace = debug_backtrace();
        $dumpTrace = false;
        if ($depth < 0) {
            $dumpTrace = true;
            $depth = 0;
        }
        $w .= sprintf("[DEBUG] %s\n%s(%s) %s\n\n", $msg, $trace[$depth]['file'], $trace[$depth]['line'], date('Y/m/d H:i:s'));
        if ($dumpTrace) {
            array_shift($trace);
            $w .= "[TRACE]\n";
            for ($i = 0; $i < count($trace); $i++) {
                $w .= "\t{$trace[$i]["file"]}({$trace[$i]["line"]})\n";
            }
        }
        if (is_null($logFile)) {
            file_put_contents('php://stderr', $w);
        } else {
            $fp = @fopen($logFile, 'a');
            if ($fp === false) return "Cannot open log file '$logFile'";
            fwrite($fp, $w);
            @fclose($fp);
        }
    }
}

if (!function_exists('remove_invisible_characters')) {
    /**
     * Remove Invisible Characters
     *
     * This prevents sandwiching null characters
     * between ascii characters, like Java\0script.
     *
     * @param	string
     * @param	bool
     * @return	string
     */
    function remove_invisible_characters($str, $url_encoded = TRUE)
    {
        $non_displayables = [];

        // every control character except newline (dec 10),
        // carriage return (dec 13) and horizontal tab (dec 09)
        if ($url_encoded) {
            $non_displayables[] = '/%0[0-8bcef]/i';    // url encoded 00-08, 11, 12, 14, 15
            $non_displayables[] = '/%1[0-9a-f]/i';    // url encoded 16-31
            $non_displayables[] = '/%7f/i';    // url encoded 127
        }
        $non_displayables[] = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S';    // 00-08, 11, 12, 14-31, 127
        do {
            $str = preg_replace($non_displayables, '', $str, -1, $count);
        } while ($count);
        return $str;
    }
}

if (!function_exists("output_json")) {
    function output_json($data)
    {
        header('Content-Type: application/json');
        $json =  json_encode($data);
        if (json_last_error() == JSON_ERROR_NONE) {
            echo $json;
        } else {
            http_response_code(500);
        }
    }
}

if (!function_exists("output_success")) {
    function output_success($message = null, $data = [])
    {
        return output_json([
            "result"  => true,
            "message" => $message,
        ] + $data);
    }
}

if (!function_exists("output_error")) {
    function output_error($message = null, $data = [])
    {
        return output_json([
            "result"  => false,
            "message" => $message,
        ] + $data);
    }
}

if (!function_exists('mb_trim')) {
    function mb_trim($str, $character_mask = " \t\n\r\0\x0B")
    {
        return trim(mb_convert_kana($str, 's'), $character_mask);
    }
}

if (!function_exists('mb_trims')) {
    function mb_trims($strs, $character_mask = " \t\n\r\0\x0B")
    {
        if (!is_array($strs)) return mb_trim($strs);
        array_walk($strs, function ($value, $key) use ($character_mask) {
            if (is_array($value)) {
                return mb_trims($value);
            }
            return mb_trim($value, $character_mask);
        });
        return $strs;
    }
}
if (!function_exists('validation_message')) {
    function validation_message(array $errors, $needle = "\n")
    {
        $messages = [];
        foreach ($errors as $field => $es) {
            foreach ($es as $e) {
                $messages[] = $e;
            }
        }
        return implode($needle, $messages);
    }
}
