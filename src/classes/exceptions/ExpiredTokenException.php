<?php

class ExpiredTokenException extends Exception
{
    protected $message = 'Token has expired';
}
