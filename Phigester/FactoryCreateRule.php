<?php
namespace Phigester;

/**
 * Rule implementation that uses an \Phigester\ObjectCreationFactory to create
 * a new object which it pushes onto the object stack.
 *
 * <p>When the element is complete, the object will be popped.</p>
 * <p>This rule is intented in situations where the element's attributes
 * are needed before the object can be created. A common scenario is for
 * the \Phigester\ObjectCreationFactory implementation to use the attributes
 * as parameters in a call to either a factory method or to a no-empty
 * constructor.</p>
 *
 * @author Cam Manderson <cammanderson@gmail.com) (PHP53 port)
 * @author Olivier Henry <oliv.henry@gmail.com> (PHP5 port)
 * @author John C. Wildenauer <freed001@gmail.com> (PHP4 port)
 */
class FactoryCreateRule extends \Phigester\AbstractRule
{
  /**
   * The object creation factory we will use to instantiate objects as required
   * based on the attributes specified in the matched XML element.
   *
   * @var \Phigester\ObjectCreationFactory
   */
  protected $creationFactory = null;

  public function __construct(\Phigester\ObjectCreationFactory $creationFactory)
  {
    $this->creationFactory = $creationFactory;
  }

  /**
   * Process the beginning of this element.
   *
   * @param array $attributes The attribute list of this element
   * @throws \Exception
   */
  public function begin(array $attributes)
  {
    try {
      $instance = $this->creationFactory->createObject($attributes);

      $logger = $this->digester->getLogger();
      $indentLogger = $this->digester->getIndentLogger();
      $match = $this->digester->getMatch();
      $logger->debug($indentLogger . "  [FactoryCreateRule]{" . $match
          . "} New " . get_class($instance));

      $this->digester->push($instance);
    } catch (\Exception $exception) {
      throw $exception;
    }
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
    $logger->debug($indentLogger . "  [FactoryCreateRule]{" . $match
        . "} Pop " . get_class($top));
  }

  /**
   * Render a printable version of this Rule
   *
   * @return string
   */
  public function toString()
  {
    $sb = 'FactoryCreateRule[creationFactory='
        . get_class($this->creationFactory) . ']';

    return $sb;
  }
}
