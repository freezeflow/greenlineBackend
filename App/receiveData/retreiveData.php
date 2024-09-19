<?php

declare(strict_types=1);

namespace App\receiveData;

use App\handleData\handleTextData,
    App\exceptions\dataNotSet;
use App\handleData\handleUploadedFiles;

class retreiveData {
    protected $name;
    protected $mobileNum;
    protected $email;
    protected $amount;
    protected $installments;
    protected $namId;
    protected $bankStatement;
    protected $paySlip;
    protected $validateId;
    protected $validateBankStatement;
    protected $validatePaySlip;
    protected $handleTextData;

    protected function setData(): array {
        $count = 0;
        $messages = [];

        if (isset($_POST['name']) && isset($_POST['mobileNum'])) {
            $this->name = $this->sanitizeString($_POST['name']);
            $this->mobileNum = $this->sanitizeString($_POST['mobileNum']);
            $this->email = isset($_POST['email']) ? $this->sanitizeString($_POST['email']) : null;
            $this->amount = $this->sanitizeString($_POST['amount']);
            $this->installments = $this->sanitizeString($_POST['installments']);
            $this->handleTextData = new handleTextData($this->name, $this->mobileNum, $this->amount, $this->installments, $this->email);
            $count++;
        }

        if(isset($_FILES['idFile'])){
            $this->namId = $_FILES['idFile'];
            $this->validateId = new handleUploadedFiles($this->namId);
            $messages[] = $this->validateId->validateFile();
            $count++;
        }

        if (isset($_FILES['bankStatementFile'])) {
            $this->bankStatement = $_FILES['bankStatementFile'];
            $this->validateBankStatement = new handleUploadedFiles($this->bankStatement);
            $messages[] = $this->validateBankStatement->validateFile();
            $count++;
        }

        if (isset($_FILES['paySlipFile'])) {
            $this->paySlip = $_FILES['paySlipFile'];
            $this->validatePaySlip = new handleUploadedFiles($this->paySlip);
            $messages[] = $this->validatePaySlip->validateFile();
            $count++;
        }

        if ($count >= 4) {
            return [
                'status' => 'success',
            ];
        } else {
            throw new dataNotSet();
        }
    }

    private function sanitizeString(string $input): string {
        return htmlspecialchars(strip_tags(trim($input)));
    }
}