<?php

require_once __DIR__ . "/BaseDao.class.php";

class WebsitesDao extends BaseDao {

    public function __construct() {
        parent::__construct("websites");
    }

    public function last_id(){
        return $this->connection->lastInsertId();
    }

    public function test_connection() {
        return $this->query("SELECT 1", []);
    }


    public function get_all_websites() {
        return $this->query("SELECT * FROM websites", []);
    }

    public function get_website_by_id($id) {
        return $this->query(
            "SELECT * FROM websites WHERE WebsiteID = :id", 
            ["id" => $id]);
    }

    public function get_website_by_name($name) {
        return $this->query(
            "SELECT * FROM websites WHERE Name = :name", 
            ["name" => $name]);
    }

    public function check_website_exists($website, $url) {
        return $this->query(
            "SELECT * FROM websites WHERE Name = :website or URL = :url", 
            ["website" => $website, "url" => $url]);
    }

    public function insert_website($website) {
        $this->insert("websites", $website);
    }
}
