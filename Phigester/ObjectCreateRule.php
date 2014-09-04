<?php
namespace Phigester;

/**
 * Rule implementation that creates a new object and pushes it
 * onto the object stack
 *
 * When the element is complete, the object will be popped.
 *
 * @author Cam Manderson <cammanderson@gmail.com) (PHP53 port)
 * @author Olivier Henry <oliv.henry@gmail.com> (PHP5 port)
 * @author John C. Wildenauer <freed001@gmail.com> (PHP4 port)
 */
class ObjectCreateRule extends \Phigester\AbstractRule
{
  /**
   * The attribute containing an override class name if it is present
   *
   * @var string
   */
  protected $attributeName = null;

  /**
   * The class name of the object to be created
   *
   * @var string
   */
  protected $className = null;

  /**
   * Construct an object create rule with the specified class name and an
   * optional attribute name containing an override
   *
   * @param string $className Class name of the object to be created
   * @param string $attributeName Attribute name which, if present, contains
   * an override of the class name to create.
   */
  public function __construct($className, $attributeName = null)
  {
    $this->className = (string) $className;
    if (!is_null($attributeName)) $this->attributeName
        = (string) $attributeName;
  }

  /**
   * Process the beginning of this element
   *
   * @param array $attributes The attribute list of this element
   * @throws \Exception
   */
  public function begin(array $attributes)
  {
    //Identify the name of the class to instantiate
    $realClassName = $this->className;

    if (!is_null($this->attributeName)) {
      if (array_key_exists($this->attributeName, $attributes)) {
        $realClassName = $attributes[$this->attributeName];
      }
    }

    $logger = $this->digester->getLogger();
    $indentLogger = $this->digester->getIndentLogger();
    $match = $this->digester->getMatch();
    $logger->debug($indentLogger . '  [ObjectCreateRule]{' . $match
        . '} New ' . $realClassName);

    //Try to load the class
    try {
      $className = \Phigester\ClassLoader::loadClass($realClassName);
    } catch (\Exception $exception) {
      throw $exception;
    }

    //Instantiate the new object an push it on the context stack
    $object = new $className();
    $this->digester->push($object);
  }

  /**
   * Process the end of this element
   */
  public function end()
  {
    $top = $this->digester->pop();

    $logger = $this->digester->getLogger();
    $indentLogger = $this->digester->getIndentLogger();
    $match = $this->digester->getMatch();
    $logger->debug($indentLogger . "  [ObjectCreateRule]{" . $match
        . "} Pop " . get_class($top));
  }

  /**
   * Render a printable version of this Rule
   *
   * @return string
   */
  public function toString()
  {
    $sb = 'ObjectCreateRule[className=' . $this->className;
    $sb .= ', attributeName=' . $this->attributeName . ']';

    return $sb;
  }
}
