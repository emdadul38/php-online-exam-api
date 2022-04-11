<?php
    namespace App;
    use Exception;

    use App\ApplicantModel;
    use App\Controller;
    use App\JwtMiddleware;
    use App\RequestMiddleware;

    /**
     * ApplicantController - The ApplicantController. This Controller makes use of a few Models for  updating, fetching and deleting Applicant.
     *
     * @author      Emdadul Huq <emdadul225@gmail.com>
     * @link        https://github.com/emdadul38/php-online-exam-api/App/Controller/ApplicantController.php
     * @license     MIT
     */
    class ApplicantController extends Controller {
        /**
         * searchStudentWithAID
         *
         * Authenticates a User.
         *
         * @param mixed $request $response Contains the Request and Respons Object from the router.
         * @return mixed Anonymous
        */
        public function searchStudentWithAID($request, $response)
        {
            $Response = [];
            $JwtMiddleware = new JwtMiddleware();
            $jwtMiddleware = $JwtMiddleware::getAndDecodeToken();
            $aid = ($request->params())? $request->params()['aid']: "";

            if (isset($jwtMiddleware) && $jwtMiddleware == false) {
                $response->code(401)->json([
                    'status' => 401,
                    'message' => 'Sorry, the authenticity of this token could not be verified.',
                    'data' => []
                ]);
                return;
            }

            $validationObject = array(
                (Object) [
                    'validator' => 'required',
                    'data' => isset($aid) ? $aid : '',
                    'key' => 'Applicant ID'
                ],
                (Object) [
                    'validator' => 'numeric',
                    'data' => isset($aid) ? $aid : '',
                    'key' => 'Applicant ID'
                ]
            );

            $validationBag = Parent::validation($validationObject);
            if ($validationBag->status) {
                $response->code(400)->json($validationBag);
                return;
            }

            try {

                $ApplicantModel = new ApplicantModel();
                $student = $ApplicantModel::SearchApplicantID($aid);

                if ($student['status']) {

                    $Response['status'] = 200;
                    $Response['data'] = $student['data'];
                    $Response['message'] = '';

                    $response->code(200)->json($Response);
                    return;
                }

                $Response['status'] = 404;
                $Response['message'] = 'AID not found.';
                $Response['data'] = [];

                $response->code(404)->json($Response);
                return;
            } catch (Exception $e) {

                $Response['status'] = 500;
                $Response['message'] = $e->getMessage();
                $Response['data'] = [];

                $response->code(500)->json($Response);
                return;
            }
        }

        /**
         * updatePaymentStatusAID
         *
         * Authenticates a User.
         *
         * @param mixed $request $response Contains the Request and Respons Object from the router.
         * @return mixed Anonymous
        */
        public function updatePaymentStatusAID($request, $response)
        {
            $Response = [];
            $JsonMiddleware = new RequestMiddleware();
            $acceptsJson = $JsonMiddleware::acceptsJson();
            if (!$acceptsJson) {
                array_push($Response, [
                    'status' => 400,
                    'message' => 'Sorry, Only JSON Contents are allowed to access this Endpoint.',
                    'data' => []
                ]);

                $response->code(400)->json($Response);
                return;
            }

            $JwtMiddleware = new JwtMiddleware();
            $jwtMiddleware = $JwtMiddleware::getAndDecodeToken();

            if (isset($jwtMiddleware) && $jwtMiddleware == false) {
                $response->code(401)->json([
                    'status' => 401,
                    'message' => 'Sorry, the authenticity of this token could not be verified.',
                    'data' => []
                ]);
                return;
            }
            $Data = json_decode($request->body(), true);
            $validationObject = array(
                (Object) [
                    'validator' => 'required',
                    'data' => isset($Data['status']) ? $Data['status'] : '',
                    'key' => 'Payment Status'
                ],
                (Object) [
                    'validator' => 'numeric',
                    'data' => isset($Data['status']) ? $Data['status'] : '',
                    'key' => 'Payment Status'
                ],

                (Object) [
                    'validator' => 'required',
                    'data' => isset($Data['aid']) ? $Data['aid'] : '',
                    'key' => 'Applicant ID'
                ],
                (Object) [
                    'validator' => 'numeric',
                    'data' => isset($Data['status']) ? $Data['status'] : '',
                    'key' => 'Applicant ID'
                ],

                (Object) [
                    'validator' => 'required',
                    'data' => isset($Data['transaction_id']) ? $Data['transaction_id'] : '',
                    'key' => 'Transaction ID'
                ],

                (Object) [
                    'validator' => 'required',
                    'data' => isset($Data['transaction_id']) ? $Data['transaction_id'] : '',
                    'key' => 'Transaction ID'
                ],
            );

            $validationBag = Parent::validation($validationObject);
            if ($validationBag->status) {
                $response->code(400)->json($validationBag);
                return;
            }

            try {
                $Payload = [
                    'aid' => $Data['aid'],
                    'status' => $Data['status'],
                    'transaction_id' => $Data['transaction_id'],
                ];
                $ApplicantModel = new ApplicantModel();
                $fees = $ApplicantModel::updatePaymentStatus($Payload);

                if ($fees['status']) {

                    $Response['status'] = 200;
                    $Response['data'] = $ApplicantModel::SearchApplicantID($Data['aid']);
                    $Response['message'] = '';

                    $response->code(200)->json($Response);
                    return;
                }

                $Response['status'] = 500;
                $Response['message'] = 'An unexpected error occurred and your Fees could not be updated at the moment. Please, try again later.';
                $Response['data'] = [];

                $response->code(500)->json($Response);
                return;
            } catch (Exception $e) {

                $Response['status'] = 500;
                $Response['message'] = $e->getMessage();
                $Response['data'] = [];

                $response->code(500)->json($Response);
                return;
            }
        }
    }
?>
