<?php

namespace App\Http;

class AppConst{

    public const CLIENT = 1;
    public const EDITOR = 2;
    public const ADMIN = 3;
    public const CLIENT_PENDING = 0;
    public const CLIENT_PAYED = 1;
    public const EDITOR_PENDING = 0;
    public const EDITOR_PAYED = 1;
    public const UN_AWARDED_JOB = 0; //Table reference requests and job editor request
    public const AWARDED_JOB = 1; //Table reference requests and job editor request
    public const CANCEL_JOB = 2; //Table reference requests and job editor request
    public const DONE_JOB = 2; //Table reference requests and job editor request
}