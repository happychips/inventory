<?php
namespace happy\inventory;

use happy\inventory\app\Command;
use happy\inventory\model\MaterialQuantity;

class TransformMaterial extends Command {

    /** @var MaterialQuantity[] */
    private $inputs;
    /** @var MaterialQuantity[] */
    private $outputs;

    /**
     * @param MaterialQuantity[] $inputs
     * @param MaterialQuantity[] $outputs
     * @param \DateTimeImmutable $when
     * @throws \Exception
     */
    public function __construct(array $inputs, array $outputs, \DateTimeImmutable $when = null) {
        parent::__construct($when);

        if (!$inputs) {
            throw new \Exception('Input cannot be empty.');
        }
        if (!$outputs) {
            throw new \Exception('Output cannot be empty.');
        }

        $this->inputs = $inputs;
        $this->outputs = $outputs;
    }

    /**
     * @return MaterialQuantity[]
     */
    public function getInputs() {
        return $this->inputs;
    }

    /**
     * @return MaterialQuantity[]
     */
    public function getOutputs() {
        return $this->outputs;
    }
}