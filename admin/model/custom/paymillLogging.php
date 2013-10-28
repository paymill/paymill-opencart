<?php

/**
 * paymillLogging
 *
 * @category   PayIntelligent
 * @package    Expression package is undefined on line 6, column 18 in Templates/Scripting/PHPClass.php.
 * @copyright  Copyright (c) 2011 PayIntelligent GmbH (http://payintelligent.de)
 */
class ModelCustompaymillLogging extends Model {
    public function getTotal() {
        $sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "pigmbh_paymill_logging`";
        $query = $this->db->query($sql);
        return (int)$query->row["total"];
    }

    public function getEntries() {
        $sql = "SELECT * FROM `" . DB_PREFIX . "pigmbh_paymill_logging`";
        $query = $this->db->query($sql);
        return $query->rows;
    }
}