<?php

use OpenApi\Annotations as OA;
require_once __DIR__ . '/../services/WebsitesService.class.php';

Flight::set('websites_service', new WebsitesService());


Flight::group('/websites', function() {

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
        $websites_service = Flight::get('websites_service');
        $data = $websites_service->get_all_websites();
        Flight::json(['data' => $data]);
    });

    Flight::route('POST /website_id', function(){
        $websites_service = Flight::get('websites_service');
        $request = Flight::request()->data->getData();
        $website_id = $request['website_id'];
        if (!empty($website_id)) {
            $data = $websites_service->get_website_by_id($website_id);
            Flight::json(['data' => $data]);
        } else {
            // Return an error response if parameters are missing
            Flight::json(['error' => 'Missing required parameters: website_id'], 400);
        }
    });


    Flight::route('POST /adding_website', function(){
        $websites_service = Flight::get('websites_service');
        $request = Flight::request()->data->getData();
        $website = [
            "Name" => $request['Name'],
            "URL" => $request['URL']
        ];
        $data = $websites_service->add_website($website);
        if (isset($result['error'])) {
            Flight::json($data, 400); // Return error if website exists
        } else {
            Flight::json($data, 201); // Return success with inserted website data
        }
    });




});


