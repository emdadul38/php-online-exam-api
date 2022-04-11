<?php
    namespace App;

    use App\Model;

    /**
     * ApplicantModel - This Model is consumed basically by the ApplicantController and is also consumed by other controllers and Middlewares...
     *
     * @author      Emdadul Huq <emdadul225@gmail.com>
     * @link        https://github.com/emdadul38/php-online-exam-api/App/Model/ApplicantModel.php
     * @license     MIT
     */

     class ApplicantModel extends Model {
         /**
        * createUser
        *
        * creates a new User
        *
        * @param array $payload  Contains all the fields that will be created.
        * @return array Anonymous
        */
        public static function SearchApplicantID($aid)
        {
            $Sql = "SELECT f.sid, f.amount, f.aid, u.name, u.email, s.reg_no
                    FROM fees as f
                    INNER join users as u on u.ID = f.sid
                    INNER join student as s on s.ID = f.sid
                    WHERE f.aid = :aid
                    ";
            Parent::query($Sql);
            Parent::bindParams('aid', $aid);
            $student = Parent::fetch();
            if(empty($student)) {
                return array(
                    'status' => false,
                    'data' => []
                );
            }

            return array(
                'status' => true,
                'data' => $student
            );
        }
        /**
        * updatePaymentStatus
        *
        * Updates a applicant
        *
        * @param array $Payload  Contains all the fields that will be updated.
        * @return array Anonymous
        */
        public static function updatePaymentStatus($payload)
        {
            $Sql = "UPDATE `fees` SET `payment_status` = :status, `transaction_id` = :transaction_id WHERE `aid` = :aid ";
            Parent::query($Sql);
            Parent::bindParams('aid', $payload['aid']);
            Parent::bindParams('status', $payload['status']);
            Parent::bindParams('transaction_id', $payload['transaction_id']);

            $fees = Parent::execute();
            if($fees) {
                return array(
                    'status' => true,
                    'data' => $payload
                );
            }
            return array(
                'status' => false,
                'data' => []
            );
        }
     }
?>
