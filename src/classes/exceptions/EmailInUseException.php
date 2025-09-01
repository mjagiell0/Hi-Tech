<?php

class EmailInUseException extends Exception
{
    protected $message = 'Email is in use';
}
