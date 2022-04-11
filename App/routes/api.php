<?php
    namespace App;
    use App\UserController;
    use App\ApplicantController;

    $Klein = new \Klein\Klein();

    /******************** User Routes || Authentication Routes **********************/
    $Klein->respond('POST', '/api/v1/user', [ new UserController(), 'createNewUser' ]);
    $Klein->respond('POST', '/api/v1/user-auth', [ new UserController(), 'login' ]);

    $Klein->respond(['GET', 'HEAD' ], '/api/v1/user/[:id]', [ new UserController(), 'testAuth' ]);

    $Klein->respond(['GET', 'HEAD' ], '/api/v1/applicant', [ new ApplicantController(), 'searchStudentWithAID' ]);
    $Klein->respond(['PATCH', 'PUT'], '/api/v1/applicant', [ new ApplicantController(), 'updatePaymentStatusAID' ]);

    // Dispatch all routes....
    $Klein->dispatch();
 ?>
