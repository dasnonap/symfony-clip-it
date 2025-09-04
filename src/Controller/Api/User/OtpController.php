<?php

namespace App\Controller\Api\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class OtpController extends AbstractController
{

    function __construct() {}

    #[Route('/api/otp/validate', name: 'app_auth_validate_otp', methods: ['POST'])]
    function validate(Request $request)
    {
        dd('aaa this is validate');
    }
}
