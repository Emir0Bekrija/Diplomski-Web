<?php

use OpenApi\Annotations as OA;
require_once __DIR__ . '/../services/ProductsService.class.php';

Flight::set('products_service', new ProductsService());


Flight::group('/products', function() {

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
    Flight::route('GET /all', function() {
        $products_service = Flight::get('products_service');
        $data = $products_service->get_products();
        Flight::json(['data' => $data]);
    });

    /**
     * @OA\Get(
     *     path="/properties/{id}",
     *     summary="Get a property by ID",
     *     tags={"properties"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Property details",
     *         @OA\JsonContent(ref="#/components/schemas/Property")
     *     )
     * )
     */
    Flight::route('GET /@id', function($id) {
        $products_service = Flight::get('products_service');

        if (!empty($id)) {
            $product = $products_service->get_product_by_id($id);
            Flight::json($product);
        } else {
            Flight::json(['error' => 'Bad request'], 400);
        }
    });

    Flight::route('POST /check_if_exists', function() {
        $products_service = Flight::get('products_service');
        $data = Flight::request()->data->getData();
        $website_id = $data['website_id'];
        $products = $products_service->check_product_exists_on_website($website_id);
        Flight::json($products);
    });

    Flight::route('POST /product', function() {
        $products_service = Flight::get('products_service');
        $data = Flight::request()->data->getData();
        try{
            $products_service->insert_product($data);
            if($products_service->last_id() > 0){
                Flight::json(['message' => 'Product inserted successfully']);
            }
            else{
                Flight::json(['message' => 'Product wasnt inserted']);
            }
        } catch (Exception $e){
            Flight::json([
                'message' => 'An error occured while inserting the product',
                'error' => $e->getMessage()
            ]);
        }
    });

    Flight::route('POST /details', function() {
    $products_service = Flight::get('products_service');
    $data = Flight::request()->data->getData();

    try {
        $products_service->insert_product_detail($data);

        if ($products_service->last_id() > 0) {
            Flight::json(['message' => 'Product inserted successfully']);
        } else {
            Flight::json(['message' => 'Product wasn’t inserted. Please check the input data.'], 400);
        }
    } catch (Exception $e) {
        // Handle any unexpected errors
        Flight::json([
            'message' => 'An error occurred while inserting the product.',
            'error' => $e->getMessage()
        ], 500);
    }
});


});


