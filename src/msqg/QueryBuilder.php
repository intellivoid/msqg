<?php


    namespace msqg;


    use msqg\Abstracts\SortBy;
    use mysqli;

    /**
     * Class QueryBuilder
     * @package msqg
     */
    class QueryBuilder
    {

        /**
         * Generates a safe SELECT query
         *
         * @param mysqli $mysqli
         * @param string $table
         * @param array $values
         * @param string|null $where
         * @param string|null $where_value
         * @param string|null $order_by
         * @param string|null $sort_by
         * @param int|null $limit
         * @param int|null $limit_offset
         * @return string
         */
        public static function s_select(
            mysqli $mysqli, string $table,
            array $values = [],
            string $where = null, string $where_value = null,
            string $order_by = null, string $sort_by = null,
            int $limit = null, int $limit_offset = null
        ): string
        {
            $table = $mysqli->real_escape_string($table);

            $x_values = [];
            foreach($values as $value)
            {
                array_push($x_values, $mysqli->real_escape_string($value));
            }
            $values = $x_values;

            if($where != null)
            {
                $where = $mysqli->real_escape_string($where);
            }

            if($where_value != null)
            {
                $where_value = $mysqli->real_escape_string($where_value);
            }

            if($order_by != null)
            {
                $order_by = $mysqli->real_escape_string($order_by);
            }

            if($sort_by != null)
            {
                $sort_by = $mysqli->real_escape_string($sort_by);
            }

            return self::select(
                $table, $values, $where, $where_value, $order_by, $sort_by, $limit, $limit_offset
            );
        }

        /**
         * Generates a SELECT query
         *
         * @param string $table
         * @param array $values
         * @param string|null $where
         * @param string|null $where_value
         * @param string|null $order_by
         * @param string|SortBy|null $sort_by
         * @param int $limit
         * @param int $limit_offset
         * @return string
         */
        public static function select(
            string $table,
            array $values = [],
            string $where = null, string $where_value = null,
            string $order_by = null, string $sort_by = null,
            int $limit = null, int $limit_offset = null
        ): string
        {
            $selected_values = '';

            if(count($values) == 0)
            {
                $selected_values = '*';
            }
            else
            {
                $is_first = true;
                foreach($values as $value)
                {
                    if($is_first == true)
                    {
                        $selected_values .= $value;
                        $is_first = false;
                    }
                    else
                    {
                        $selected_values .= ', ' . $value;
                    }
                }
            }

            /** @noinspection SqlNoDataSourceInspection */
            $Query = "SELECT $selected_values FROM `$table`";

            if($where != null)
            {
                if($where_value != null)
                {
                    $Query .= " WHERE $where='$where_value'";
                }
            }

            if($order_by !=null)
            {
                if($sort_by !=null)
                {
                    $Query .= " ORDER BY " . $order_by . ' ' . strtoupper($sort_by);
                }
                else
                {
                    $Query .= " ORDER BY " . $order_by;
                }
            }

            if($limit != null)
            {
                if($limit_offset != null)
                {
                    $Query .= " LIMIT " . (int)$limit_offset . ", " . (int)$limit;
                }
                else
                {
                    $Query .= " LIMIT " . (int)$limit;
                }
            }

            return $Query . ';';
        }

        /**
         * Generates a INSERT INTO Query
         *
         * @param string $table
         * @param array $key_values
         * @return string
         */
        public static function insert_into(string $table, array $values): string
        {
            $Keys = '';
            $Values = '';

            $is_first = true;
            foreach($values as $key => $value)
            {
                if($is_first == true)
                {
                    $Keys .= $key;

                    if(is_int($value))
                    {
                        $Values .= (int)$value;
                    }
                    else
                    {
                        $Values .= "'$value'";
                    }

                    $is_first = false;
                }
                else
                {
                    $Keys .= ', ' . $key;

                    if(is_int($value))
                    {
                        $Values .= ', ' . (int)$value;
                    }
                    else
                    {
                        $Values .= ", '$value'";
                    }
                }
            }

            /** @noinspection SqlNoDataSourceInspection */
            return "INSERT INTO `$table` ($Keys) VALUES ($Values);";
        }
    }