<?php

require_once __DIR__ . "/BaseDao.class.php";

class ProductsDao extends BaseDao {

    public function __construct() {
        parent::__construct("products");
    }

    public function last_id(){
        return $this->connection->lastInsertId();
    }

    public function test_connection() {
        return $this->query("SELECT 1", []);
    }

    public function insert_product($product) {
        $this->insert("products", $product);
    }

    public function insert_product_detail($product_detail) {
        $this->insert("productdetails", $product_detail);
    }


    public function get_all_products() {
        return $this->query("SELECT * FROM products", []);
    }

    public function get_product_by_id($id) {
        return $this->query(
            "SELECT * FROM products WHERE ProductID = :id", 
            ["id" => $id]);
    }

    public function get_product_by_name($name) {
        return $this->query(
            "SELECT * FROM products WHERE ProductName = :name", 
            ["name" => $name]);
    }

    public function get_properties_by_ids($property_ids) {
        // Convert array of property IDs to a comma-separated string for the query
        $ids_placeholder = implode(',', array_fill(0, count($property_ids), '?'));

        // Create the SQL query with placeholders
        $sql = "SELECT * FROM properties WHERE idproperties IN ($ids_placeholder)";

        // Execute the query with the array of property IDs
        return $this->query($sql, $property_ids);
    }

    public function check_product_exists_on_website($website_id) {
        return $this->query(
            "SELECT p.ProductID, p.ProductName
             FROM products p
             WHERE p.ProductID NOT IN (
                 SELECT pd.ProductID 
                 FROM productdetails pd 
                 WHERE pd.WebsiteID = :website_id
            );", 
            ["website_id" => $website_id]);
    }
}
