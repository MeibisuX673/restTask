<?php

namespace app\Controller;



use app\Controller\Traits\getDataVerbPutPatch;
use app\Service\BrendService;
use GuzzleHttp\Psr7\Request;

class BrendPutByIdController
{
    public function __construct
    (
        BrendService $brendService
    )
    {
        $this->brendService = $brendService;
    }

    public function __invoke()
    {

        $id = $_GET['brends_id'];

        if($this->brendService->getById($id) == null){
            http_response_code(404);
            return json_encode(['status'=>'404','message'=>'Brend not found']);
        }

        $file = file_get_contents('php://input');

        if (mb_strlen($file) == 0) {
            http_response_code(400);
            return json_encode(['status' => '400', 'message' => 'Bad 1Request']);
        }

        $data = $this->getData($file);

        if (count($data) > 1) {
            http_response_code(400);
            return json_encode(['status' => '400', 'message' => 'Bad Request']);
        }

        if (!array_key_exists('name', $data)) {
            http_response_code(400);
            return json_encode(['status' => '400', 'message' => 'Bad Request']);
        }

        if (mb_strlen($data['name']) == 0) {
            http_response_code(400);
            return json_encode(['status' => '400', 'message' => 'Bad Request']);
        }

        $updated = $this->brendService->setPutById($id, $data['name']);

        if ($updated) {
            http_response_code(200);
            return json_encode(['status' => '200', 'message' => 'Updated']);
        }

        http_response_code(500);
        return json_encode(['status' => '500', 'message' => 'Internal Serverr Error']);
    }

    use getDataVerbPutPatch;
}