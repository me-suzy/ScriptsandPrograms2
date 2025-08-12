<?php

    /* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

    /*
        Class:          JoDB_Common
        Package:        JoDB
        Description:    Common driver for JoDB
        Platform:       PHP 5
        Author:         Jari Jokinen <jari.jokinen@iki.fi>
        Homepage URL:   http://jari.sigmatic.fi/jodb/
        License:        Free for non-commercial use.
                        For commercial use, contact author.
                        Redistributing the modified source code isn't allowed!

        Version:        0.0.1
        Released:       2005/05/19
        First release:  2005/05/19
    */

    class JoDB_Common {

        private $username, $password, $database, $hostname, $hostport = NULL;
        private $connection, $result = NULL;
        private $tables, $fields, $where, $group, $having, $order, $limit,
                $values = NULL;

        // SQL methods

        public function select($tables = NULL, $fields = NULL, $where  = NULL,
                               $group  = NULL, $having = NULL, $order  = NULL,
                               $limit  = NULL) {

            // Method:  JoDB_Common::select()
            // Action:  Build SELECT statement and execute query
            // Params:  0: Tables (string) = NULL
            //          1: Fields (string) = NULL
            //          2: Where  (string) = NULL
            //          3: Group  (string) = NULL
            //          4: Having (string) = NULL
            //          5: Order  (string) = NULL
            //          6: Limit  (string) = NULL
            // Return:  Number of rows returned (integer)

            if ($fields) $query = 'SELECT ' . $fields;
            else         $query = 'SELECT ' . $this->fields;
            
            if ($tables)           $query .= ' FROM '     . $tables;
            elseif ($this->tables) $query .= ' FROM '     . $this->tables;
            if ($where)            $query .= ' WHERE '    . $where;
            elseif ($this->where)  $query .= ' WHERE '    . $this->where;
            if ($group)            $query .= ' GROUP BY ' . $group;
            elseif ($this->group)  $query .= ' GROUP BY ' . $this->group;
            if ($having)           $query .= ' HAVING '   . $having;
            elseif ($this->having) $query .= ' HAVING '   . $this->having;
            if ($order)            $query .= ' ORDER BY ' . $order;
            elseif ($this->order)  $query .= ' ORDER BY ' . $this->order;
            if ($limit)            $query .= ' LIMIT '    . $limit;
            elseif ($this->limit)  $query .= ' LIMIT '    . $this->limit;

            return $this->query($query);

        }

        public function selectRow($tables = NULL, $fields = NULL,
                                  $where  = NULL, $group  = NULL,
                                  $having = NULL, $order  = NULL,
                                  $limit  = NULL) {

            // Method:  JoDB_Common::selectRow()
            // Action:  Select single row from database
            // Params:  0: Tables (string) = NULL
            //          1: Fields (string) = NULL
            //          2: Where  (string) = NULL
            //          3: Group  (string) = NULL
            //          4: Having (string) = NULL
            //          5: Order  (string) = NULL
            //          6: Limit  (string) = NULL
            // Return:  Row (array)

            $this->select(
                $tables, $fields, $where, $group, $having, $order, $limit
            );

            return $this->getRow();
            
        }

        public function insert($tables = NULL, $fields = NULL, $values = NULL) {

            // Method:  JoDB_Common::insert()
            // Action:  Build INSERT statement and execute query
            // Params:  0: Tables (string) = NULL
            //          1: Fields (string) = NULL
            //          2: Values (string) = NULL
            // Return:  Number of rows inserted (integer)

            if (!$tables) {$tables = $this->tables;}
            if (!$fields) {$fields = $this->fields;}
            if (!$values) {$values = $this->values;}

            $query = 'INSERT INTO ' . $tables . ' (' . $fields .
                     ') VALUES ('   . $values . ' )';

            return $this->execute($query);

        }
        
        public function update($tables = NULL, $values = NULL, $where = NULL) {

            // Method:  JoDB_Common::update()
            // Action:  Build UPDATE statement and execute query
            // Params:  0: Tables (string) = NULL
            //          1: Values (string) = NULL
            // Return:  Number of rows updated (integer)

            if (!$tables) {$tables = $this->tables;}
            if (!$values) {$values = $this->values;}
            if (!$where)  {$where  = $this->where;}

            $query = 'UPDATE ' . $tables . ' SET ' . $values .
                     ' WHERE ' . $where;

            return $this->execute($query);

        }

        // Get methods

        public function getResult()  {return $this->result;}
        public function getLastID()  {return $this->lastID;}
        public function getNumCols() {return $this->numCols;}
        public function getNumRows() {return $this->numRows;}

        // Set methods

        public function setTables($tables = NULL) {$this->tables = $tables;}
        public function setFields($fields = NULL) {$this->fields = $fields;}
        public function setWhere($where   = NULL) {$this->where  = $where;}
        public function setGroup($group   = NULL) {$this->group  = $group;}
        public function setHaving($having = NULL) {$this->having = $having;}
        public function setOrder($order   = NULL) {$this->order  = $order;}
        public function setLimit($limit   = NULL) {$this->limit  = $limit;}
        public function setValues($values = NULL) {$this->values = $values;}
        public function resetSQL() {
            $this->tables = NULL;
            $this->fields = NULL;
            $this->where  = NULL;
            $this->group  = NULL;
            $this->having = NULL;
            $this->order  = NULL;
            $this->limit  = NULL;
            $this->values = NULL;
        }

    }

?>
