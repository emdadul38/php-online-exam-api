<?php
    namespace App;
    use App\Model;

    /**
     * TokenModel - This Model is consumed basically by the UserController and is also consumed by other controllers and Middlewares...
     *
     * @author      Emdadul Huq <emdadul225@gmail.com>
     * @link        https://github.com/emdadul38/php-online-exam-api/App/Model/TokenModel.php
     * @license     MIT
     */
    class TokenModel extends Model {

        /**
         * createToken
         *
         * creates a new Token
         *
         * @param array $payload  Contains all the fields that will be created.
         * @return array Anonymous
         */
        public static function createToken($payload)
        {
            $Sql = "INSERT INTO db_token (user_id, jwt_token) VALUES (:user_id, :jwt_token)";
            Parent::query($Sql);
            // Bind Params...
            Parent::bindParams('user_id', $payload['user_id']);
            Parent::bindParams('jwt_token', $payload['jwt_token']);

            $Token = Parent::execute();
            if ($Token) {
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

         /**
         * fetchToken
         *
         * fetches an existing Token using the $token
         *
         * @param string $token     The token that will be used in matching the closest token from the database.
         * @return array Anonymous
         */
        public static function fetchToken($token)
        {
            $Sql = "SELECT * FROM `db_token` WHERE jwt_token = :jwt_token";
            Parent::query($Sql);
            Parent::bindParams('jwt_token', $token);

            $Data = Parent::fetch();
            if (!empty($Data)) {
                return array(
                    'status' => true,
                    'data' => $Data
                );
            }

            return array(
                'status' => false,
                'data' => []
            );
        }
    }
?>
