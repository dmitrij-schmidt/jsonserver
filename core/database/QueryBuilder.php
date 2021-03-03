<?php

namespace App\Core\Database;

class QueryBuilder
{
    private $connection;
    private $table = null;
    private $model = null;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function for($model)
    {
        $this->model = $model;
        $this->table = $model::getTable();
        
        return $this; 
    }

    public function selectAll()
    {
        $this->connection->getHandle();
        $results = $this->connection->readJSON($this->table);
        $this->connection->closeHandle();

        array_walk($results, function(&$item) {
            $item = $this->model::unserialize($item);
        });

        return $results;
    }

    public function selectSortedBy($field, $order)
    {
        return $this->sortItemsBy($this->selectAll(), $field, $order);
    }

    public function append($data)
    {
        $this->connection->getHandle();
        $results = $this->connection->readJSON($this->table);
        
        $results = $this->sortItemsBy($results, 'id', 'ASC');
        $data = ['id' => (int) end($results)['id'] + 1] + $data;
        $results[] = $data;

        $this->connection->writeJSON($this->table, $results);
        $this->connection->closeHandle();

        return $data;
    }

    public function deleteBy($field, $value)
    {
        $this->connection->getHandle();
        $results = $this->connection->readJSON($this->table);
        
        $results = array_filter($results, function($item) use ($field, $value) {
            return $item[$field] !== $value;
        });

        $this->connection->writeJSON($this->table, array_values($results));
        $this->connection->closeHandle();

        return true;
    }

    private function sortItemsBy($items, $field, $order = 'DESC')
    {
        usort($items, function($a, $b) use ($field, $order) {
            if ($a[$field] == $b[$field]) {
                return 0;
            }

            if ($order == 'ASC') {
                return ($a[$field] < $b[$field]) ? -1 : 1;
            }

            return ($a[$field] > $b[$field]) ? -1 : 1;
        });
        
        return $items;
    }
}
