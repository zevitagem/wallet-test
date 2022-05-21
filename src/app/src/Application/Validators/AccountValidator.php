<?php

namespace App\Application\Validators;

use App\Application\Validators\BaseValidator;

class AccountValidator extends BaseValidator
{
    protected array $messages = [
        'required_id' => 'The account id was not sent',
        'invalid_id' => 'The account id must be positive and integer'
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
        if (!is_numeric($data['id']) || $data['id'] <= 0) {
            $this->addError('invalid_id');
        }
    }

    private function validateRequired(array $data): bool
    {
        if (!isset($data['id'])) {
            $this->addError('required_id');
        }

        return empty($this->getErrors());
    }
}