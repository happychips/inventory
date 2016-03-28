<?php
namespace happy\inventory\app;

use rtens\domin\parameters\File;
use rtens\domin\parameters\file\MemoryFile;
use rtens\domin\parameters\file\SavedFile;
use watoki\reflect\type\ClassType;
use watoki\stores\transforming\transformers\TypedValue;
use watoki\stores\transforming\transformers\TypedValueTransformer;

class FileTransformer extends TypedValueTransformer {

    /**
     * @param TypedValue $value
     * @return bool
     */
    public function canTransform($value) {
        return parent::canTransform($value) && $value->getValue() instanceof SavedFile;
    }

    /**
     * @param TypedValue $value
     * @return mixed
     */
    public function transform($value) {
        /** @var SavedFile $file */
        $file = $value->getValue();
        $memoryFile = new MemoryFile($file->getName(), $file->getType(), base64_encode($file->getContent()));
        $newValue = new TypedValue($memoryFile, new ClassType(MemoryFile::class));

        return parent::transform($newValue);
    }

    /**
     * @param TypedValue $transformed
     * @return bool
     */
    public function hasTransformed($transformed) {
        return parent::hasTransformed($transformed) && $transformed->getType() == new ClassType(File::class);
    }

    /**
     * @param TypedValue $transformed
     * @return mixed
     */
    public function revert($transformed) {
        $value = $transformed->getValue();
        $value['data']['content'] = base64_decode($value['data']['content']);
        return parent::revert(new TypedValue($value, $transformed->getType()));
    }
}