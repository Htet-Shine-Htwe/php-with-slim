<?php

namespace App\Enum;

enum SameSite :String
{
    case Lax = "lax";
    case None = "none";
    case Strict = "strict";
}
