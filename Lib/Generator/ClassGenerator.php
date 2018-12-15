<?php
/**
 * Created by PhpStorm.
 * User: istvan
 * Date: 2018. 11. 20.
 * Time: 10:49
 */

namespace Lib\Generator;

use Lib\Util;
use Nette\PhpGenerator\PhpFile;

/**
 * Class ClassGenerator
 *
 * General class to generate Wheel classes
 *
 * @package Lib
 */
abstract class ClassGenerator
{
    /**
     * @var array
     */
    protected $pathElements;

    /**
     * @var string
     */
    protected $className;
    /**
     * @var string
     */
    protected $parentClass;
    /**
     * @var PhpFile
     */
    protected $file;
    /**
     * @var \Nette\PhpGenerator\PhpNamespace
     */
    public $nameSpace;
    /**
     * @var \Nette\PhpGenerator\ClassType
     */
    public $class;
    /**
     * @var \Nette\PhpGenerator\Method
     */
    protected $constructor;

    /**
     * ClassGenerator constructor.
     *
     * Initializes class source: adds doc block, namespace, and a class with a constructor
     *
     * @param $pathElements
     */
    public function __construct($pathElements)
    {
        $this->pathElements = $pathElements;
        $this->file = new PhpFile();
        $this->className = GeneratorPathBuilderService::getClassName($this->pathElements);

        GeneratorService::addFileCommentBlock($this->file);
        $this->nameSpace = $this->file->addNamespace(GeneratorPathBuilderService::buildNameSpace($this->pathElements));
        $this->nameSpace->addUse('Wheel\\Core\\PrototypeService');

        $this->class = $this->nameSpace
            ->addClass($this->className)
            ->setExtends($this->parentClass)
            ->addComment($this->getClassDocBlock());

        $this->constructor = $this->class->addMethod('__construct')
            ->addComment('Constructor')
            ->setVisibility('public');
    }

    /**
     * @return array
     */
    final protected function getSourceFileDescriptor()
    {
        return [
            'path' => GeneratorPathBuilderService::buildPath($this->pathElements),
            'content' => $this->file
        ];
    }

    public function getClass()
    {
        return $this->class;
    }

    /**
     * @return mixed
     */
    protected abstract function getClassDocBlock();

    /**
     * todo 08: This should be only in CRA class generators
     *
     * @return mixed
     */
    protected abstract function buildWheelDottedPath();


    public static function createInterfaceForReferenceTypes($referredRubrics)
    {
        // In case of reference we have create a new Interface, and this will be the return type / parameter type hint
        // in the generated code
        // TODO 03: clean up this code
        $interfaceName = '';
        $types = [];
        foreach ($referredRubrics as $referredRubric) {
            $interfaceName .= $referredRubric;
            $pathElements = array_merge(explode('\\', Util::getAutoGenConceptNS()), explode('.', $referredRubric));
            $type = '\\' . implode('\\', $pathElements);
            $types[] = $type;
        }
        $interfaceName = str_replace('.', '', $interfaceName);

        foreach ($types as $type) {
            SourcePool::addImplements($type, $interfaceName);
        }

        return Util::addTrailingAutoGenConceptNS($interfaceName);
    }

}