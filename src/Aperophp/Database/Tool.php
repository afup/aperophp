<?php

namespace Aperophp\Database;

class Tool
{
    /**
     * Database connexion instance
     * @var TODO
     */
    protected $db;

    /**
     * @param TODO $db Database connexion instance
     */
    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Create schema from filename into current connexion
     * 
     * @param  string $schema_filename Database schema filename
     * @return boolean
     */
    public function createSchema($schema_filename)
    {
        $this->executeQuery($this->getQueryFromFile($schema_filename));
    }

    /**
     * Load fixtures into database
     * 
     * @param  string|array $fixtures Could be filename or array of filename
     * @return boolean
     */
    public function loadFixtures($fixtures)
    {
        if(!is_array($fixtures)) {
            $fixtures = array($fixtures);
        }

        foreach($fixtures as $fixture)
        {
            $this->executeQuery($this->getQueryFromFile($fixture));
        }
    }

    /**
     * Execute given SQL query into current database
     * 
     * @param  string $query SQL query string
     * @return TODO
     */
    protected function executeQuery($query)
    {
        return $this->db->query($query);
    }

    /**
     * Get content from file
     * 
     * @param  string $filename
     * @return string
     */
    protected function getQueryFromFile($filename)
    {
        if(!file_exists($filename)) {
            throw new \InvalidArgumentException(sprintf("File '%s' does not exists", $filename));
        }

        if(!is_readable($filename)) {
            throw new \InvalidArgumentException(sprintf("File '%s' is not readable", $filename));   
        }

        return file_get_contents($filename);
    }
}
