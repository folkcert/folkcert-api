<?php
namespace RestBundle\Entity;

use Doctrine\Common\Annotations\AnnotationReader;

class RestEntity
{
    /**
     * Expects to receive an array from which it will extract
     * the values it recognizes to stored them in this class.
     *
     * @param array
     */
    public function exchangeArray(array $data = array())
    {
        $annotationReader = new AnnotationReader();

        foreach ($data as $key => $value) {
            if (property_exists(get_class($this), $key)) {
                $reflectionProperty = new \ReflectionProperty(get_class($this), $key);
                $propertyAnnotations = $annotationReader->getPropertyAnnotations($reflectionProperty);

                if (property_exists(get_class($propertyAnnotations[0]), 'mappedBy')) {
                    $this->$key->clear();
                    foreach ($value as $index => $item) {
                        $namespace = $this->_getNameSpace();
                        $newClass = $namespace . $propertyAnnotations[0]->targetEntity;
                        $object = new $newClass;
                        $object->exchangeArray($item);
                        $this->$key->add($object);
                    }
                } else if (property_exists(get_class($propertyAnnotations[0]), 'targetEntity')) {
                    $namespace = $this->_getNameSpace();

                    $newClass = $namespace . $propertyAnnotations[0]->targetEntity;
                    $this->$key = new $newClass;
                    $this->$key->exchangeArray($value);
                } else if ($key === 'date' && is_string($value)) {
                    $this->$key = new \DateTime($value);
                } else {
                    $this->$key = $value;
                }
            }
        }
    }

    /**
     * Returns the namespace of the current class
     * separated with \
     *
     * @return string
     */
    protected function _getNameSpace()
    {
        $namespace = '';
        $fullNameSpace = explode('\\', get_class($this));
        for ($i = 0; $i < (count($fullNameSpace) - 1); $i++) { 
            $namespace .= $fullNameSpace[$i] . '\\';
        }

        return $namespace;
    }
}
