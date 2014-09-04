<?php
namespace Phigester;

/**
 * Abstract base class for ObjectCreationFactory implementations.
 *
 * @author Cam Manderson <cammanderson@gmail.com) (PHP53 port)
 * @author Olivier Henry <oliv.henry@gmail.com> (PHP5 port)
 * @author John C. Wildenauer <freed001@gmail.com> (PHP4 port)
 */
abstract class AbstractObjectCreationFactory
    implements \Phigester\ObjectCreationFactory {
  /**
   * The associated \Phigester\Digester instance that was set up
   * by \Phigester\FactoryCreateRule upon initialization.
   *
   * @var \Phigester\Digester
   */
  protected $digester = null;
    
  /**
   * Returns the \Phigester\Digester that was set by the \Phigester\FactoryCreateRule
   * upon initialization.
   * 
   * @return \Phigester\Digester
   */
  public function getDigester() {
    return $this->digester;
  }
  
  /**
   * Set the \Phigester\Digester.
   *
   * @param \Phigester\Digester $digester Parent digester object
   */
  public function setDigester(\Phigester\Digester $digester) {
    $this->digester = $digester;
  }
}
?>
