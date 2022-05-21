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

        $this->validateDefault($data);
    }

    public function save()
    {
        $data = $this->getData()->toArray();

        $this->validateDefault($data);
    }

    private function validateDefault(array $data)
    {
        $keys = [];
        if (isset($data['type'])) {
            switch ($data['type']) {
                case TransactionTypeEnum::DEPOSIT:
                    $keys = ['destination', 'amount'];
                    break;
                case TransactionTypeEnum::TRANSFER:
                    $keys = ['destination', 'origin', 'amount'];
                    break;
                case TransactionTypeEnum::WITHDRAW:
                    $keys = ['origin', 'amount'];
                    break;
            }
        }

        if (!$this->validateRequired($data, $keys)) {
            return;
        }

        $this->validateInvalid($data, $keys);
    }

    private function validateInvalid(array $data, array $keys): void
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

        foreach ($keys as $key) {
            if (!is_numeric($data[$key]) || $data[$key] <= 0) {
                $this->addError('invalid_'.$key);
            }
        }
    }

    private function validateRequired(array $data, array $keys): bool
    {
        if (!isset($data['type'])) {
            $this->addError('required_type');
        }

        foreach ($keys as $key) {
            if (!isset($data[$key])) {
                $this->addError('required_'.$key);
            }
        }

        return empty($this->getErrors());
    }
}