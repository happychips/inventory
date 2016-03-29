<?php
namespace happy\inventory\app;

use rtens\domin\delivery\web\Element;
use rtens\domin\delivery\web\WebField;
use rtens\domin\Parameter;
use watoki\reflect\type\ClassType;

class PasswordField implements WebField {

    /**
     * @param Parameter $parameter
     * @return bool
     */
    public function handles(Parameter $parameter) {
        return $parameter->getType() == new ClassType(Password::class);
    }

    /**
     * @param Parameter $parameter
     * @param string $serialized
     * @return mixed
     */
    public function inflate(Parameter $parameter, $serialized) {
        return new Password((string)$serialized);
    }

    /**
     * @param Parameter $parameter
     * @param mixed $value
     * @return string
     */
    public function render(Parameter $parameter, $value) {
        return (string)new Element('input', array_merge([
            'class' => 'form-control',
            'type' => 'password',
            'name' => $parameter->getName(),
            'value' => $value
        ], $parameter->isRequired() ? [
            'required' => 'required'
        ] : []));
    }

    /**
     * @param Parameter $parameter
     * @return array|Element[]
     */
    public function headElements(Parameter $parameter) {
        return [];
    }
}