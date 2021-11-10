<?php

namespace NewProject\App\Traits;

use NewProject\Framework\Validator\ValidationException;
use NewProject\Framework\Validator\Validator;

trait ValidatorTrait
{
    protected $validator;

    public function validateData(array $data, array $rules, array $messages = [])
    {
        $this->validator = new Validator($data, $rules, $messages);
        $this->validator = $this->validator->validate();

        if ($this->validator->fails()) {
            $errors = $this->beautifyErrors();
            throw new ValidationException(
                'Unprocessable Entity!',
                422,
                null,
                $errors
            );
        }
    }

    public function beautifyErrors()
    {
        $errors = $this->validator->errors();

        $b_errors = array_map(function ($key, $row) {
            $keys = array_keys($row);
            return [$key => $row[$keys[0]]];
        }, array_keys($errors), $errors);

        return $b_errors;
    }
}
