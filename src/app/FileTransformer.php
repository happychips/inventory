<?php
namespace happy\inventory\app;

use happy\inventory\model\Identifier;
use rtens\domin\parameters\File;
use rtens\domin\parameters\file\MemoryFile;
use rtens\domin\parameters\file\SavedFile;
use watoki\reflect\type\ClassType;
use watoki\stores\transforming\TransformerRegistry;
use watoki\stores\transforming\transformers\TypedValue;
use watoki\stores\transforming\transformers\TypedValueTransformer;

class FileTransformer extends TypedValueTransformer {

    private $baseDir;

    public function __construct(TransformerRegistry $transformers, $baseDir) {
        parent::__construct($transformers);
        $this->baseDir = $baseDir;
    }

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

        $path = $this->baseDir . '/' . Identifier::generate() . '_' . $file->getName();
        $file->save($path);

        $copiedFile = new SavedFile($path, $file->getName(), $file->getType());
        $newValue = new TypedValue($copiedFile, new ClassType(MemoryFile::class));

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