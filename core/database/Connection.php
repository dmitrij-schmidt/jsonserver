<?php

namespace App\Core\Database;

class Connection
{
     private $config;
     private $db;
     private $handle;
     private $dbData;

     public function __construct($config)
     {
          switch ($config['driver'] ?? null) {
               case 'json':
                    $this->config = $config;
                    $this->db = "{$this->config['connection']}{$this->config['database']}.json";
                    if (!file_exists($this->db)) {
                         throw new \Exception("Database has not been initialized");
                    }
                    break;
               case null:
                    throw new \Exception("Malformed database config provided.");
                    break;
               default:
                    throw new \Exception("Driver {$config['driver']} is not yet supported");
                    break;
          }
     }

     public function getHandle()
     {
          $this->handle = fopen($this->db,"r+");
          flock($this->handle, LOCK_EX);
     }

     public function readJSON($table)
     {
          $content = fread($this->handle, filesize($this->db));
          $dbData = json_decode($content, true);
          $this->dbData = $dbData;
          return $dbData[$table];
     }

     public function writeJSON($table, $data)
     {
          ftruncate($this->handle, 0);
          rewind($this->handle);
          $this->dbData[$table] = $data;
          fwrite($this->handle, json_encode($this->dbData));
     }

     public function closeHandle()
     {
          flock($this->handle, LOCK_UN);
     }
}
