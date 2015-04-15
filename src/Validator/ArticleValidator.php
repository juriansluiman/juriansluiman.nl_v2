<?php

namespace App\Validator;

use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;

class ArticleValidator
{
    protected $validator;
    protected $constraint;
    
    public function __construct()
    {
        $this->validator  = Validation::createValidator();
        $this->constraint = new Assert\Collection(array(
            'title' => new Assert\Length(['min' => 3]),
            'lead'  => new Assert\Length(['min' => 3]),
        ));
    }
 
    public function validate($input)
    {
        return $this->validator->validateValue($input, $this->constraint);
    }
}
