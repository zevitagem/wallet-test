<?php

namespace App\Application\Validators;

use App\Application\Validators\BaseValidator;
use App\Domain\Enum\TransactionTypeEnum;

class TransactionValidator extends BaseValidator
{
    protected array $messages = [
        'required_type' => 'The transaction type was not sent',
        'required_amount' => 'The transaction amount was not sent',
        'required_origin' => 'The transaction origin was not sent',
        'required_destination' => 'The transaction destination was not sent',
        'invalid_type' => 'The type of transaction sent is invalid',
        'invalid_amount' => 'The transaction amount must be positive and integer',
        'invalid_origin' => 'The transaction origin must be positive and integer',
        'invalid_destination' => 'The transaction destination must be positive and integer',
    ];

    public function store()
    {
        $data = $this->getData()->toArray();

        if (!$this->validateRequired($data)) {
            return;
        }

        $this->validateInvalid($data);
    }

    private function validateInvalid(array $data): void
    {
        if (!in_array($data['type'],
                [
                    TransactionTypeEnum::DEPOSIT,
                    TransactionTypeEnum::TRANSFER,
                    TransactionTypeEnum::WITHDRAW
                ]
            )
        ) {
            $this->addError('invalid_type');
        }

        if (!is_numeric($data['amount']) || $data['amount'] <= 0) {
            $this->addError('invalid_amount');
        }
        if (!is_numeric($data['origin']) || $data['origin'] <= 0) {
            $this->addError('invalid_origin');
        }
        if (!is_numeric($data['destination']) || $data['destination'] <= 0) {
            $this->addError('invalid_destination');
        }
    }

    private function validateRequired(array $data): bool
    {
        if (!isset($data['type'])) {
            $this->addError('required_type');
        }
        if (!isset($data['amount'])) {
            $this->addError('required_amount');
        }
        if (!isset($data['origin'])) {
            $this->addError('required_origin');
        }
        if (!isset($data['destination'])) {
            $this->addError('required_destination');
        }

        return empty($this->getErrors());
    }
}