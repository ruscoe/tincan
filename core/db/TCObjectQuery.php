<?php

namespace TinCan\db;

use TinCan\objects\TCObject;
use TinCan\TCException;

/**
 * Tin Can query builder service.
 *
 * @package TinCan
 * @author  Ricky Bertram <ricky@rbwebdesigns.co.uk>
 * @license MIT https://mit-license.org/
 * @link    https://github.com/ruscoe/tincan
 */
class TCObjectQuery
{
    private string $db_table;

    private string $primary_key;

    private ?array $ids = null;

    private array $conditions = [];

    private array $order = [];

    private array $counts = [];

    private int $limit = 0;

    private int $offset = 0;

    private TCDB $db;

    private TCData $tcdata;

    private TCObject $tcobject;

    public function __construct(TCDB $db, TCData $tcdata, TCObject $class)
    {
        $this->db = $db;
        $this->tcdata = $tcdata;
        $this->tcobject = $class;
        $this->db_table = $class->get_db_table();
        $this->primary_key = $class->get_primary_key();
    }

    public function setConditions(array $conditions)
    {
        $this->conditions = $conditions;
        return $this;
    }

    public function setIds(?array $ids)
    {
        $this->ids = $ids;
        return $this;
    }

    public function offset(int $offset)
    {
        $this->offset = $offset;
        return $this;
    }

    public function limit(int $limit)
    {
        $this->limit = $limit;
        return $this;
    }

    public function setOrder(array $order)
    {
        $this->order = $order;
        return $this;
    }

    public function withCount(string $relation)
    {
        $relations = $this->tcobject->get_db_relationships();
        if (!key_exists($relation, $relations)) {
            throw new TCException("Relationship $relation not found on {($this->tcobject)::class}");
        }

        $this->counts[] = $relation;
        return $this;
    }

    public function execute()
    {
        $query = "SELECT {$this->getSelectExpression()} FROM `{$this->db_table}`{$this->getJoins()}";
        
        if (!empty($this->ids)) {
            $ids_in = implode(',', $this->ids);
            $query .= " WHERE `{$this->primary_key}` IN ({$ids_in})";
        } elseif (!empty($this->conditions)) {
            foreach ($this->conditions as $key => $condition) {
                if (!$this->tcdata->validate_object_field($this->tcobject, $condition['field'])) {
                    throw new TCException("Invalid field: {$condition['field']} for table {$this->db_table}");
                }
                $condition['field'] = str_replace('.', '`.`', $condition['field']);
                // TODO: Allow conditions other than equals.
                $query .= ($key === 0 ? " WHERE " : " AND ") . "`{$condition['field']}` = '{$condition['value']}'";
            }
        }

        $query .= $this->getGroupBy();

        if (!empty($this->order)) {
            if (is_numeric(array_key_first($this->order))) {
                $order = [];
                foreach ($this->order as $os) {
                    $order[] = "{$os['field']} {$os['direction']}";
                }
                $query .= " ORDER BY " . implode(', ', $order);
            }
            else {
                $query .= " ORDER BY {$this->order['field']} {$this->order['direction']}";
            }
        }

        if ($this->limit > 0) {
            $query .= " LIMIT {$this->offset}, {$this->limit}";
        }

        $result = $this->db->query($query);

        $objects = [];

        if ($result) {
            while ($object = $result->fetch_object()) {
                $objects[] = new ($this->tcobject)($object);
            }
        }

        return $objects;

        // return $this->db->query($query);
    }

    private function getSelectExpression()
    {
        $all_relations = $this->tcobject->get_db_relationships();
        $select = '*';

        foreach ($this->counts as $relation) {
            /** @var TCObject */
            $tcobject = $all_relations[$relation]['class'];

            $select .= ", COUNT(`{$tcobject->get_db_table()}`.`{$tcobject->get_primary_key()}`) as {$relation}_count";
        }

        return $select;
    }

    private function getJoins()
    {
        $all_relations = $this->tcobject->get_db_relationships();
        $required_relations = $this->counts;

        $joins = '';
        foreach ($required_relations as $relation) {
            /** @var TCObject */
            $object = $all_relations[$relation]['class'];
            $join_table = $object->get_db_table();
            $joins = " JOIN `$join_table` ON `$join_table`.`{$this->primary_key}` = `{$this->db_table}`.`{$this->primary_key}`";
        }
        return $joins;
    }

    private function getGroupBy()
    {
        return " GROUP BY `{$this->db_table}`.`{$this->primary_key}`";
    }

}