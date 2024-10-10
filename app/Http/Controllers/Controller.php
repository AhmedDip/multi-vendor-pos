<?php

namespace App\Http\Controllers;

use App\Manager\AccessControl\AccessControlTrait;

abstract class Controller
{
    use AccessControlTrait;
}
