<?php 

declare(strict_types=1);

namespace app\exceptions;

class dataNotSet extends \Exception{
    protected $message = 'Data not set';
}