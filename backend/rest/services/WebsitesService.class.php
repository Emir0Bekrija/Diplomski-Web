<?php

require_once __DIR__ . "/../dao/WebsitesDao.class.php";

class WebsitesService {
    private $websites_dao;

    public function __construct() {
        $this->websites_dao = new WebsitesDao();
    }

    public function last_id(){
        return $this->websites_dao->last_id();
    }

    public function get_all_websites() {
        return $this->websites_dao->get_all_websites();
    }

    public function get_website_by_id($id) {
        return $this->websites_dao->get_website_by_id($id);
    }

    public function get_website_by_name($name) {
        return $this->websites_dao->get_website_by_name($name);
    }

    public function add_website($website) {
    // Check if the website already exists
    $existing_website = $this->websites_dao->check_website_exists($website["Name"], $website["URL"]);
    
    if ($existing_website) {
        // Return a message indicating the website exists
        return ["error" => "Website already exists"];
    } else {
        // Insert the website if it doesn't exist
        return $this->websites_dao->insert("websites", $website);
    }
}

}
