<?php
namespace Phigester;

/**
 * Rule implementation that sets an individual property on the object at the
 * top of the stack, based on attributes with specified names
 *
 * @author Cam Manderson <cammanderson@gmail.com) (PHP53 port)
 * @author Olivier Henry <oliv.henry@gmail.com> (PHP5 port)
 * @author John C. Wildenauer <freed001@gmail.com> (PHP4 port)
 */
class SetPropertyRule extends \Phigester\Rule {
  /**
   * The attribute that will contain the property name
   *
   * @var string
   */
  protected $name = null;

  /**
   * The attribute that will contain the property value
   *
   * @var string
   */
  protected $value = null;

  /**
   * Construct a "set property" rule with the specified name and value
   * attributes
   *
   * @param string $name The name of the attribute that will contain the name
   * of the property to be set
   * @param string $value The name of the attribute that will contain the value
   * to which the property should be set
   */
  public function __construct($name, $value) {
    $this->name = (string) $name;
    $this->value = (string) $value;
  }

  /**
   * Process the beginning of this element
   *
   * @param array $attributes The attribute list of this element
   * @throws \Phigester\Exception\NoSuchPropertyException - If the bean does not have
   * a writeable property of the specified name
   */
  public function begin($attributes) {
    // Identify the actual property name and value to be used
    $actualName = '';
    $actualValue = '';
    foreach ($attributes as $attribName => $attribValue) {
      if ($attribName == $this->name) {
        $actualName = $attribValue;
      } elseif ($attribName == $this->value) {
        $actualValue = $attribValue;
      }
    }
    
    // Get a reference to the top object
    $top = $this->digester->peek();
    
    // Log some debugging information
    $logger = $this->digester->getLogger();
    $indentLogger = $this->digester->getIndentLogger();
    $match = $this->digester->getMatch();
    $logger->debug($indentLogger . '  [SetPropertyRule]{' . $match . '} Set '
        . get_class($top) . ' property ' . $actualName . ' to ' . $actualValue);

    // Do nothing if the top object is null
    if (!is_null($top)) {
      $reflection = new \ReflectionClass(get_class($top));
      $reflectionProperty = null;
      if(property_exists($top, $actualName)) $reflectionProperty = $reflection->getProperty($actualName);
      if (!empty($reflectionProperty) && $reflectionProperty->isPublic()) {
        $top->$actualName = $actualValue;
      } else {
        $propertySetter = 'set' . ucfirst($actualName);
        if (method_exists($top, $propertySetter)) {
          $top->$propertySetter($actualValue);
        } else {
          $msg = 'Class "' . get_class($top) . '" has no property named "'
              . $actualName . '"';
          throw new \Phigester\Exception\NoSuchPropertyException($msg);
        }
      }
    }
  }

  /**
   * Render a printable version of this Rule
   *
   * @return string
   */
  public function toString() {
    $sb  = 'SetPropertyRule[';
    $sb .= 'name=';
    $sb .= $this->name;
    $sb .= ', value=';
    $sb .= $this->value;
    $sb .= ']';
    return $sb;
  }
}
