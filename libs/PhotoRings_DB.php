<?php
/**
 * Class PDO_DB is a wrapper of the PDO class. It only exists to simplify the
 * creation of a PDO database object. AKA: I don't want to put the database
 * access info in every class that needs access to the DB.
 *
 * @author Chris Coley <chris at codingallnight dot com>
 */
class PhotoRings_DB extends PDO {
    // PDO settings
    private $PDO_dsn;
    private $PDO_emulatePrepares = false;
    private $PDO_errorMode = PDO::ERRMODE_SILENT;

    /**
     * Creates a PDO instance representing a connection to a database
     *
     * @see PDO::__construct()
     * @throws PDOException Throws a PDOException if the attempt to connect to the database fails.
     */
    public function __construct() {
        $settings = parse_ini_file("config.ini", TRUE);
        $this->PDO_dsn = $settings['database']['driver']
                        . ':host=' . $settings['database']['host']
                        . ((!empty($settings['database']['port'])) ? (';port=' . $settings['database']['port']) : '')
                        . ';dbname=' . $settings['database']['name']
                        . ';charset=utf8';

        parent::__construct($this->PDO_dsn,
                            $settings['database']['username'],
                            $settings['database']['password'],
                            array(
                                PDO::ATTR_EMULATE_PREPARES => $this->PDO_emulatePrepares,
                                PDO::ATTR_ERRMODE => $this->PDO_errorMode
                            ));
    }
}
?>