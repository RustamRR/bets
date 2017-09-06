<?php

namespace Users\Validator;

use ZfcUser\Validator\NoRecordExists;

class NoRecordExistsEdit extends NoRecordExists
{
    public function isValid($value, $context = null)
    {
        $valid = true;
        $this->setValue($value);
        $result = $this->query($value);
        if ($result && $result->getId() != $context['id']) {
            $valid = false;
            $this->error(self::ERROR_RECORD_FOUND);
        }
        return $valid;
    }
}