<?php

require_once __DIR__ . "/../dao/ProductsDao.class.php";

class ProductsService {
    private $products_dao;

    public function __construct() {
        $this->products_dao = new ProductsDao();
    }

    public function last_id(){
        return $this->products_dao->last_id();
    }

    public function get_products() {
        return $this->products_dao->get_all_products();
    }

    public function get_product_by_id($id) {
        return $this->products_dao->get_product_by_id($id);
    }

    public function get_properties_by_ids($ids) {
        return $this->products_dao->get_properties_by_ids($ids);
    }

    public function check_product_exists_on_website($website_id) {
        return $this->products_dao->check_product_exists_on_website($website_id);
    }

    public function insert_product($product) {
        $this->products_dao->insert_product($product);
    }

    public function insert_product_detail($product_detail) {
        $this->products_dao->insert_product_detail($product_detail);
    }
}
