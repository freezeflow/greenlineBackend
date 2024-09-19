<?php

declare(strict_types=1);

namespace App\sendData;

use App\receiveData\retreiveData,
    Symfony\Component\Mailer\MailerInterface,
    Symfony\Component\Mime\Email;

class sendEmail extends retreiveData{
    public function sendEmail(MailerInterface $mailer){
        $this->setData();
            $email = (new Email())
            ->from($this->email)
            ->to('gfloriaan616@gmail.com')
            ->subject('LOAN APPLICATION')
            ->text($this->formatDataAsString())
            ->html(`<h1
                style="
                background: #28c701;
                width: 22%;
                border-radius: 5px;
                text-align: center;
                color: #fff"
                >Loan Application</h1>
                <h2>Name: $this->name </h2>
                <div style="
                display: flex;
                flex-direction: row;
                gap: 20px">
                    <div>
                    <h3 style="color: blue;">Contact details:</h3>
                        <div
                        style="
                        display: flex;
                        flex-direction: column;">
                            <p>Mobile number:$this->mobileNum</p>
                            <p>Email: $this->email</p>
                        </div>
                    </div>
                    <div>
                        <h3 style="color: green;">Application details:</h3>
                        <div
                        style="
                        display: flex;
                        flex-direction: column;">
                            <p>Amount: $this->amount</p>
                            <p>Installments: $this->installments</p>
                        </div> 
                    </div>
                </div>
            `);

            if (isset($this->namId)) {
                $email->attachFromPath($this->namId['tmp_name'], $this->namId['name']);
            }

            if (isset($this->bankStatement)) {
                $email->attachFromPath($this->bankStatement['tmp_name'], $this->bankStatement['name']);
            }

            if (isset($this->paySlip)) {
                $email->attachFromPath($this->paySlip['tmp_name'], $this->paySlip['name']);
            }

            try {
                $mailer->send($email);
            } catch (\Exception $e) {
                return[
                    'status' => false,
                    'error' => 'Failed to send email: ',
                    'details' => $e->getMessage()
                ];
            }
    }
    
    private function formatDataAsString(): string {
        return sprintf(
            "Name: %s\nMobile Number: %s\nEmail: %s\nAmount: %s\nInstallments: %s\nID Name: %s\nBank Statement: %s\nPay Slip: %s",
            $this->name,
            $this->mobileNum,
            $this->email,
            $this->amount,
            $this->installments,
            $this->namId['name'],
            $this->bankStatement['name'],
            $this->paySlip['name']
        );
    }
}