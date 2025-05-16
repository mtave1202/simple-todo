<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Valitron\Validator;

class Validation
{
    protected $labels = [];
    protected $rules = [];
    protected $error_messages = [];
    /**
     * @var Validator $validator
     */
    protected $validator;

    public function __construct(array $config = [])
    {
        Validator::lang('ja');
        foreach ($config as $key => $value) {
            $method = "set_{$key}";
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
        return $this;
    }

    public function set_labels($labels)
    {
        $this->labels = $labels;
    }

    public function add_labels($labels)
    {
        foreach ($labels as $key => $value) {
            $this->labels[$key] = $value;
        }
    }

    public function set_rules($rules)
    {
        $this->rules = $rules;
    }

    public function get_error_messages()
    {
        return $this->error_messages;
    }

    public function get_validator()
    {
        return $this->validator;
    }

    public function add_rules($rules)
    {
        foreach ($rules as $rule => $params) {
            $now_params = key_exists($rule, $this->rules) ? $this->rules[$rule] : [];
            foreach ($params as $p) {
                $now_params[] = $p;
            }
            $this->rules[$rule] = $now_params;
        }
    }

    public function do($data)
    {
        $this->validator = new Validator($data);
        $this->validator->labels($this->labels);
        $this->validator->rules($this->rules);
        $this->error_messages = null;
        if (!$this->validator->validate()) {
            $this->error_messages = $this->validator->errors();
            return false;
        }
        return true;
    }

    public function errors($needle = "\n")
    {
        $messages = [];
        foreach ($this->error_messages as $field => $es) {
            foreach ($es as $e) {
                $messages[] = $e;
            }
        }
        return implode($needle, $messages);
    }
}
