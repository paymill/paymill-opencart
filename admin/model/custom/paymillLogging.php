<?php

/**
 * paymillLogging
 *
 * @category   PayIntelligent
 * @package    Expression package is undefined on line 6, column 18 in Templates/Scripting/PHPClass.php.
 * @copyright  Copyright (c) 2011 PayIntelligent GmbH (http://payintelligent.de)
 */
class ModelCustompaymillLogging extends Model
{

    private $_pageSize = 10;

    public function getTotal()
    {
        $sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "pigmbh_paymill_logging`";
        $query = $this->db->query($sql);
        return (int) $query->row["total"];
    }

    public function getEntries($page, $searchValue, $connectedSearch)
    {
        // TODO: PDO nutzen, das unten kann man doch keinem zumuten >_<
        $sql = "SELECT * FROM `" . DB_PREFIX . "pigmbh_paymill_logging`";
        $where = '';
        if (!is_null($searchValue)) {
            $where = ' WHERE `date` LIKE "%' . $searchValue . '%"'
                . ' OR `message` LIKE "%' . $searchValue . '%"'
                . ' OR `debug` LIKE "%' . $searchValue . '%"'
                . ' OR `identifier` LIKE "%' . $searchValue . '%"';
        }


        if ($connectedSearch === "on" && !is_null($searchValue)) {
            $where = ' WHERE `identifier`= (' . "SELECT `identifier` FROM `" . DB_PREFIX . "pigmbh_paymill_logging`" . $where . ' LIMIT 0,1)';
        }
        $sql .= $where;
        if ($connectedSearch === "on" && !is_null($searchValue)) {
            $start = $page * $this->_pageSize;
            $sql .= ' LIMIT ' . $start . ',' . $this->_pageSize;
        }


        $query = $this->db->query($sql);
        return $query->rows;
    }

}