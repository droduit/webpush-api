<?php 
$method = $_SERVER['REQUEST_METHOD'];
$request = isset($_SERVER['PATH_INFO']) ? explode("/", substr(@$_SERVER['PATH_INFO'], 1)) : array();
$queries = explode("&", $_SERVER['QUERY_STRING']);

switch ($method) {
    case 'PUT':
        handle_put($request);  
        break;
    case 'POST':
        handle_post($request);  
        break;
    case 'GET':
        handle_get($request);  
        break;
    case 'DELETE':
        handle_delete($request);
        break;
    default:
        handle_error($request);  
        break;
}

function handle_post($request) {
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    $body = json_decode(file_get_contents('php://input'), true);
    
    $mandatoryParams = array("subscriber", "notification");
    $mandatoryParamsSubscriber = array("endpoint", "authToken", "publicKey");
    $mandatoryParamsNotification = array("body", "title");

    if(count(array_diff($mandatoryParams, array_keys($body))) == 0 && 
        count(array_diff($mandatoryParamsSubscriber, array_keys($body['subscriber']))) == 0 && 
        count(array_diff($mandatoryParamsNotification, array_keys($body['notification']))) == 0) {
        require_once 'webpush-send.php';
        http_response_code(202);
        sendNotification($body['subscriber'], $body['notification']);
    } else {
        http_response_code(400);
        echo json_encode(array("error" => "Missing argument(s). You must provide the following data: subscriber : { endpoint, authToken, publicKey }, notification : { title, body }"));
        exit();
    }
}

function handle_put($request) {
    http_response_code(405);
    echo json_encode(array("error" => "PUT request not supported"));
    exit();
}

function handle_get($request) {
    header("Access-Control-Allow-Methods: GET");
    header("Access-Control-Allow-Origin: *");
    
    if(count($request) == 0) {
        http_response_code(202);
        showInstructions();
        exit();
    } 

    header("Content-Type: application/json; charset=UTF-8");
    http_response_code(405);
    echo json_encode(array("error" => "GET request not supported"));
    exit();
}

function handle_delete($request) {
    http_response_code(405);
    echo json_encode(array("error" => "DELETE request not supported"));
    exit();
}

function handle_error($request) {
    http_response_code(404);
    echo json_encode(array("error" => "HTTP request type not supported"));
    exit();
}

function showInstructions() {
    echo '<style>pre {background: #333; padding: 12px; border-radius: 8px; color: white}</style>';
    echo '<h1>WebPush API</h1>';
    
    echo '<h3>POST</h3>';
    echo "<pre>subscriber: {\n   endpoint: \"...\",\n   authToken: \"...\",\n   publicKey: \"...\"\n},\nnotification: {\n   title: \"...\",\n   body: \"...\"\n}</pre>";
    
    echo '<h3>GET</h3>';
    echo "<pre>Not supported</pre>";

    echo '<h3>PUT</h3>';
    echo "<pre>Not supported</pre>";

    echo '<h3>DELETE</h3>';
    echo "<pre>Not supported</pre>";
}