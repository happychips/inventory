<?php
namespace happy\inventory\app;

use happy\inventory\model\Identifier;
use rtens\domin\delivery\web\fields\IdentifierField as DominIdentifierField;
use rtens\domin\Parameter;
use rtens\domin\reflection\types\IdentifierType;
use watoki\reflect\type\ClassType;
use watoki\reflect\type\StringType;

class IdentifierField extends DominIdentifierField {

    public function handles(Parameter $parameter) {
        $type = $parameter->getType();
        return $type instanceof ClassType && is_subclass_of($type->getClass(), Identifier::class);
    }

    protected function getOptions(Parameter $parameter) {
        return parent::getOptions($this->transformParameter($parameter));
    }

    protected function getOptionType(Parameter $parameter) {
        return parent::getOptionType($this->transformParameter($parameter));
    }

    public function inflate(Parameter $parameter, $serialized) {
        /** @var ClassType $type */
        $type = $parameter->getType();
        $class = $type->getClass();
        return new $class(parent::inflate($parameter, $serialized));
    }

    /**
     * @param Parameter $parameter
     * @return Parameter
     */
    private function transformParameter(Parameter $parameter) {
        /** @var ClassType $type */
        $type = $parameter->getType();
        return $parameter->withType(new IdentifierType($type->getClass(), new StringType()));
    }
}