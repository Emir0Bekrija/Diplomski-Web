<?php

require_once __DIR__ . "/../dao/PricesDao.class.php";

class PricesService {
    private $prices_dao;

    public function __construct() {
        $this->prices_dao = new PricesDao();
    }

    public function last_id(){
        return $this->prices_dao->last_id();
    }

    public function get_prices() {
        return $this->prices_dao->get_all_prices();
    }

    public function get_properties_by_ids($ids) {
        return $this->prices_dao->get_properties_by_ids($ids);
    }

    public function product_data($product_id, $website_id){
        return $this->prices_dao->product_data($product_id, $website_id);
    }

    public function get_prices_by_website_id($website_id) {
        return $this->prices_dao->get_prices_by_website_id($website_id);
    }

    public function get_latest_product_by_product_id($product_id) {
        return $this->prices_dao->get_latest_product_by_product_id($product_id);
    }

    public function get_all_prices_from_products(){
        return $this->prices_dao->get_all_prices_from_products();
    }

    public function get_all_prices_by_product_id($product_id, $website_id){
        return $this->prices_dao->get_all_prices_by_product_id($product_id, $website_id);
    }

    public function lowest_prices() {
        return $this->prices_dao->lowest_prices();
    }
}
