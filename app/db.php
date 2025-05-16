<?php
require __DIR__ . '/config.php';
class DB
{
    public $config;
    public $dsn;
    public $hostname;
    public $username;
    public $password;
    public $database;
    public $port = NULL;
    public $char_set = 'utf8';
    public $queries = [];
    /**
     * @var PDO $pdo
     */
    public $conn_id = NULL;
    public $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_PERSISTENT => true
    ];

    /**
     * @var PDO $pdo
     */
    protected $pdo;

    public function __construct($config)
    {
        $this->config = $config;
        if (is_array($config)) {
            foreach ($config as $key => $value) {
                $this->$key = $value;
            }
        }
        $this->dsn = 'mysql:host=' . (empty($this->hostname) ? '127.0.0.1' : $this->hostname);
        empty($this->database) or $this->dsn .= ';dbname=' . $this->database;
        empty($this->port) or $this->dsn .= ';port=' . $this->port;
        empty($this->database) or $this->dsn .= ';dbname=' . $this->database;
        empty($this->char_set) or $this->dsn .= ';charset=' . $this->char_set;
        $this->pdo = $this->connect();
    }

    public static function generate_query_params($params)
    {
        $p = [];
        foreach ($params as $key => $value) {
            $p[':' . $key] = $value;
        }
        return $p;
    }

    public function connect()
    {
        try {
            return new PDO($this->dsn, $this->username, $this->password, $this->options);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return FALSE;
        }
    }

    public function query($sql, $params = [])
    {
        if (!$this->pdo) {
            return null;
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $this->queries[] = $sql;
        return $stmt;
    }

    public function last_insert_id()
    {
        return $this->pdo->lastInsertId();
    }

    public function last_query()
    {
        return $this->queries[array_key_last($this->queries)];
    }

    protected function simple_query($sql)
    {
        return $this->pdo->query($sql);
    }

    protected function _escape_str($str)
    {
        $str = $this->pdo->quote($str);
        return ($str[0] === "'") ? substr($str, 1, -1) : $str;
    }
}
