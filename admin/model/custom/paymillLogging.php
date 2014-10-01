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

    private $_pageSize = 15;
    private $_searchValue;
    private $_connectedSearch;

    public function getPageSize()
    {
        return $this->_pageSize;
    }

    public function setSearchValue($string)
    {
        $this->_searchValue = $string;
    }

    public function setConnectedSearch($string)
    {
        $this->_connectedSearch = $string === "on";
    }

    public function getTotal()
    {
        $sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "pigmbh_paymill_logging`";
        $where = $this->_getWhereForSearch();
        $query = $this->db->query($sql . $where);
        return (int) $query->row["total"];
    }

    public function getEntries($page)
    {
        $sql = "SELECT * FROM `" . DB_PREFIX . "pigmbh_paymill_logging`";
        $where = $this->_getWhereForSearch();
        $limit = '';
        // no Limit for connected Search.
        if (!$this->_connectedSearch) {
            $start = ($page - 1) * $this->_pageSize;
            $limit = ' LIMIT ' . $start . ',' . $this->_pageSize;
        }
        $orderBy = " ORDER BY `date` DESC, `id` ASC";
        $query = $this->db->query($sql . $where . $orderBy . $limit);
        return $query->rows;
    }

    private function _getWhereForSearch()
    {
        $where = ' WHERE `date` LIKE "%' . $this->_searchValue . '%"'
            . ' OR `message` LIKE "%' . $this->_searchValue . '%"'
            . ' OR `debug` LIKE "%' . $this->_searchValue . '%"'
            . ' OR `identifier` LIKE "%' . $this->_searchValue . '%"';
        if ($this->_connectedSearch && !is_null($this->_searchValue)) {
            $where = ' WHERE `identifier`= (' . "SELECT `identifier` FROM `" . DB_PREFIX . "pigmbh_paymill_logging`" . $where . ' LIMIT 0,1)';
        }
        return $where;
    }

    public function deleteEntries(array $ids){
        $sql = "DELETE FROM `" . DB_PREFIX . "pigmbh_paymill_logging` WHERE `id` in(".implode(", ", $ids).")";
        $this->db->query($sql);
    }

}