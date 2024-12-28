<?php

require_once __DIR__ . "/BaseDao.class.php";

class PricesDao extends BaseDao {

    public function __construct() {
        parent::__construct("prices");
    }

    public function last_id(){
        return $this->connection->lastInsertId();
    }

    public function test_connection() {
        return $this->query("SELECT 1", []);
    }


    public function get_all_prices() {
        return $this->query("SELECT * FROM prices", []);
    }

    public function product_data($product_id, $website_id){
        return $this->query_unique(
            "SELECT prdet.Name, pri.Price, prdet.Specification, pri.ScrapedDate
            FROM productdetails prdet
            JOIN prices pri ON prdet.ProductID = pri.ProductID
            WHERE prdet.ProductID = :product_id AND pri.WebsiteID = :website_id
            ORDER BY pri.ScrapedDate DESC
            LIMIT 1;",
            ["product_id" => $product_id, "website_id" => $website_id]
        );
    }

    public function get_last_price_by_id($id) {
        return $this->query_unique(
            "SELECT * FROM products WHERE ProductID = :id", 
            ["id" => $id]);
    }

    public function get_properties_by_ids($property_ids) {
        // Convert array of property IDs to a comma-separated string for the query
        $ids_placeholder = implode(',', array_fill(0, count($property_ids), '?'));

        // Create the SQL query with placeholders
        $sql = "SELECT * FROM properties WHERE idproperties IN ($ids_placeholder)";

        // Execute the query with the array of property IDs
        return $this->query($sql, $property_ids);
    }


    public function get_prices_by_website_id($website_id){
        return $this->query(
            "SELECT pri.WebsiteID, pri.ProductID, prdet.Name, pri.Price, prdet.Specification, pri.ScrapedDate
            FROM productdetails prdet
            JOIN prices pri ON prdet.ProductID = pri.ProductID and prdet.WebsiteID = pri.WebsiteID
            WHERE pri.WebsiteID = :website_id
            AND pri.ScrapedDate = (
                SELECT MAX(p.ScrapedDate)
                FROM prices p
                WHERE p.ProductID = pri.ProductID AND p.WebsiteID = pri.WebsiteID
            )
            ORDER BY ProductID;",
            ["website_id" => $website_id]
        );
    }

    public function get_latest_product_by_product_id($productID) {
        return $this->query(
            "SELECT 
                prdet.Name AS ProductName,
                pri.Price,
                web.Name AS WebsiteName,
                prdet.ProductID,
                prdet.WebsiteID,
                pri.ScrapedDate,
                pr.Picture
            FROM 
                productdetails prdet
            JOIN 
                prices pri ON prdet.ProductID = pri.ProductID
            JOIN 
                websites web ON prdet.WebsiteID = web.WebsiteID
            JOIN
                products pr ON prdet.ProductID = pr.ProductID
            WHERE 
                pri.ProductID = ? 
                AND pri.ScrapedDate = (
                    SELECT MAX(p.ScrapedDate)
                    FROM prices p
                    WHERE p.ProductID = pri.ProductID
                    AND p.WebsiteID = pri.WebsiteID
                )
            ORDER BY 
                pri.ScrapedDate DESC, 
                prdet.WebsiteID ASC;",
            [$productID]
        );
    }

    public function get_all_prices_by_product_id($product_id, $website_id){
        return $this->query(
            "SELECT 
                pri.Price,
                pri.ScrapedDate
            FROM 
                productdetails prdet
            JOIN 
                prices pri ON prdet.ProductID = pri.ProductID
            JOIN 
                websites web ON prdet.WebsiteID = web.WebsiteID
            JOIN
                products pr ON prdet.ProductID = pr.ProductID
            WHERE 
                pri.ProductID = ? AND pri.WebsiteID = ?
            ORDER BY 
                pri.ScrapedDate DESC, 
                prdet.WebsiteID ASC;",
            [$product_id, $website_id]
        );
    }


    public function get_all_prices_from_products(){
        return $this->query(
            "SELECT 
                prdet.Name AS ProductName,
                pri.Price,
                web.Name AS WebsiteName,
                prdet.ProductID,
                prdet.WebsiteID,
                pri.ScrapedDate,
                pr.Picture
            FROM 
                productdetails prdet
            JOIN 
                prices pri ON prdet.ProductID = pri.ProductID
            JOIN 
                websites web ON prdet.WebsiteID = web.WebsiteID
            JOIN
                products pr on prdet.ProductID = pr.ProductID
            WHERE 
                pri.ScrapedDate = (
                    SELECT MAX(p.ScrapedDate)
                    FROM prices p
                    WHERE p.ProductID = pri.ProductID
                )
            ORDER BY 
                pri.ScrapedDate DESC, 
                prdet.WebsiteID ASC;",[]
        );
    }

    public function lowest_prices(){
        return $this->query(
            "SELECT 
	            DISTINCT(pr.ProductID), web.WebsiteId, pr.ProductName, pr.Picture, pri.Price, web.Name
            FROM 
                products pr
            JOIN 
                prices pri ON pr.ProductID = pri.ProductID
            JOIN
                websites web ON pri.WebsiteID = web.WebsiteID
            WHERE
                pri.Price = (SELECT MIN(Price) FROM prices pp WHERE pp.ProductID = pr.ProductID) ",[]
        );
    }

    public function get_prices_for_product($product_id){
        
    }


}
