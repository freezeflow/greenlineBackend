<?php 

declare(strict_types=1);

namespace App\handleData;

class handleTextData{
    private $name;
    private $mobileNum;
    private $email;
    private $amount;
    private $installments;

    function __construct($name,$mobileNum,$amount,$installments,$email = null){
        $this->name = $this->sanitizeString($name);
        $this->mobileNum = $this->sanitizeString($mobileNum);
        $this->email = $this->sanitizeString($email);
        $this->amount = $this->sanitizeString($amount);
        $this->installments = $this->sanitizeString($installments);
    }

    public function validateData(): bool{
        if($this->email){
            if(!$this->isValidEmail($this->email)){
                return false;
            }
        }
        if(!$this->isValidMobileNumber($this->mobileNum)){
            return false;
        }
        if($this->installments > 5){
            return false;
        }
        if($this->amount > 3000){
            return false;
        }
        return true;
    }

    private function sanitizeString(string $input): string {
        $sanitized = strip_tags(trim($input));
        $sanitized = preg_replace('/[^A-Za-z0-9 ]/', '', $sanitized);
        $sanitized = trim($sanitized);

        return $sanitized;
    }

    private function isValidEmail(string $email): bool {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    private function isValidMobileNumber(string $mobileNum): bool {
        $sanitized = preg_replace('/[^0-9]/', '', $mobileNum);
        return preg_match('/^(081|085|26481|26485)\d{7,10}$/', $sanitized);
    }
}