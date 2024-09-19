<?php 

declare(strict_types=1);

namespace app\exceptions;

class notValidFileException extends \Exception{
    protected $message = 'Not a valid file';
}