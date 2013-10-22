<?php
/**
 * Class PDO_DB is a wrapper of the PDO class. It only exists to simplify the
 * creation of a PDO dataabse object. AKA: I don't want to put the database
 * access info in every class that needs access to the DB.
 *
 * @author Chris Coley <chris at codingallnight dot com>
 */
class PhotoRings_DB extends PDO {
    // Database Setup
    private $hostname = 'localhost';
    private $database = 'photo_rings';
    private $username = 'pr';
    private $password = '5RTQrctz7feTCEcz';

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
        $this->PDO_dsn = "mysql:host=".$this->hostname.";dbname=".$this->database.";charset=utf8";
        parent::__construct($this->PDO_dsn,
                            $this->username,
                            $this->password,
                            array(
                                PDO::ATTR_EMULATE_PREPARES => $this->PDO_emulatePrepares,
                                PDO::ATTR_ERRMODE => $this->PDO_errorMode
                            ));
    }
}
?>