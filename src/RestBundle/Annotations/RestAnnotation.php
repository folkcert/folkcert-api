<?php
namespace RestBundle\Annotations;

/**
 * @Annotation
 */
class RestAnnotation
{
	private $value;

	public function __construct($options) {
		$this->value = $options['value'];
	}

	public function getValue(){
		return $this->value;
	}
}
