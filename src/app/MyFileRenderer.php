<?php
namespace happy\inventory\app;

use rtens\domin\delivery\web\Element;
use rtens\domin\delivery\web\renderers\FileRenderer;
use rtens\domin\parameters\File;

class MyFileRenderer extends FileRenderer {

    protected function isImage(File $file) {
        return false;
    }

    public function render($value) {
        return (string)new Element('a', [
            'title' => $value->getName(),
            'href' => $this->createUrl($value),
            'target' => '_blank'
        ], [
            $value->getName()
        ]);
    }
}