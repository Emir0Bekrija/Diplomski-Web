<?php

use OpenApi\Annotations as OA;
require_once __DIR__ . '/../services/PricesService.class.php';

Flight::set('prices_service', new PricesService());
Flight::route('/test-db', function() {
        $dao = new ProductsDao();
        Flight::json($dao->test_connection());
});

Flight::group('/prices', function() {

    Flight::route('/test-db', function() {
        $dao = new ProductsDao();
        Flight::json($dao->test_connection());
    });


    /**
     * @OA\Get(
     *      path="/properties",
     *      tags={"properties"},
     *      summary="Get all properties",
     *      @OA\Response(
     *           response=200,
     *           description="Array of all properties in the database"
     *      )
     * )
     */
    Flight::route('GET /', function() {
        $prices_service = Flight::get('prices_service');
        $data = $prices_service->get_prices();
        Flight::json(['data' => $data]);
    });


    //na ovaj nacin dobijam konkretan item sa zadnjom cijenom
    Flight::route('POST /product_from_website', function() {
        $prices_service = Flight::get('prices_service');
        $data = Flight::request()->data->getData();
        $product_id=$data['product_id'];
        $website_id=$data['website_id'];
        
        if (!empty($product_id) && !empty($website_id)) {
            $data = $prices_service->product_data($product_id, $website_id);
            Flight::json(['data' => $data]);
        } else {
            // Return an error response if parameters are missing
            Flight::json(['error' => 'Missing required parameters: product_id and website_id'], 400);
        }
    });

    //na ovaj nacin dobijam sve iteme sa zadnjom cijenom sa odredjenog website-a
    Flight::route('POST /all_from_website', function() {
        $prices_service = Flight::get('prices_service');
        $data = Flight::request()->data->getData();
        $website_id=$data['website_id'];
        
        if (!empty($website_id)) {
            $data = $prices_service->get_prices_by_website_id($website_id);
            Flight::json(['data' => $data]);
        } else {
            // Return an error response if parameters are missing
            Flight::json(['error' => 'Missing required parameters: website_id'], 400);
        }
    });

    Flight::route('POST /all_prices_for_product', function() {
        $prices_service = Flight::get('prices_service');
        $data = Flight::request()->data->getData();
        $product_id=$data['product_id'];
    
        if (!empty($product_id)) {
            $data = $prices_service->get_prices_by_product_id($product_id);
            Flight::json(['data' => $data]);
        } else {
            // Return an error response if parameters are missing
            Flight::json(['error' => 'Missing required parameters: product_id'], 400);
        }
    });

    Flight::route('POST /get_all_prices_by_product_id', function() {
        $prices_service = Flight::get('prices_service');
        $data = Flight::request()->data->getData();
        $product_id=$data['product_id'];
        $website_id=$data['website_id'];
    
        if (!empty($product_id)) {
            $data = $prices_service->get_all_prices_by_product_id($product_id, $website_id);
            Flight::json(['data' => $data]);
        } else {
            // Return an error response if parameters are missing
            Flight::json(['error' => 'Missing required parameters: product_id'], 400);
        }
    });


    //sa ovom rutom dobijam detalje svi produkta sa svih website zadnjom cijenom
    Flight::route('GET /all_prices_from_products', function() {
        $prices_service = Flight::get('prices_service');
        $data = $prices_service->get_all_prices_from_products();
        Flight::json(['data' => $data]);
    });

    //sa ovom rutom dobijam sve produkte sa zadnjom cijenom
    Flight::route('GET /lowest_prices', function() {
        $prices_service = Flight::get('prices_service');
        $data = $prices_service->lowest_prices();
        Flight::json(['data' => $data]);
    });




});


