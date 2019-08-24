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
         * Generates a SELECT query
         *
         * @param mysqli $mysqli
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
            mysqli $mysqli, string $table,
            array $values = [],
            string $where = null, string $where_value = null,
            string $order_by = null, string $sort_by = null,
            int $limit = null, int $limit_offset = null
        ): string
        {
            $table = $mysqli->real_escape_string($table);
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
                        $selected_values .= $mysqli->real_escape_string($value);
                        $is_first = false;
                    }
                    else
                    {
                        $selected_values .= ', ' . $mysqli->real_escape_string($value);
                    }
                }
            }

            /** @noinspection SqlNoDataSourceInspection */
            $Query = "SELECT $selected_values FROM `$table`";

            if($where != null)
            {
                if($where_value != null)
                {
                    $where = $mysqli->real_escape_string($where);
                    $where_value = $mysqli->real_escape_string($where_value);
                    $Query .= " WHERE $where='$where_value'";
                }
            }

            if($order_by !=null)
            {
                if($sort_by !=null)
                {
                    $Query .= " ORDER BY " . $mysqli->real_escape_string($order_by) . ' ' . strtoupper($sort_by);
                }
                else
                {
                    $Query .= " ORDER BY " . $mysqli->real_escape_string($order_by);
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
    }