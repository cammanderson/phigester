<?php
namespace Phigester;

/**
 * Interface for use with \Phigester\FactoryCreateRule.
 *
 * The rule calls createObject to create an object to be pushed onto
 * the Digester stack whenever it is matched.
 *
 * @author Olivier Henry <oliv.henry@gmail.com> (PHP5 port)
 * @author John C. Wildenauer <freed001@gmail.com> (PHP4 port)
 */
interface ObjectCreationFactory
{
  /**
   * Factory method call by \Phigester\FactoryCreateRule to supply an object
   * based on the element's attributes.
   *
   * @param array $attributes The element's attributes
   * @return object
   * @throws \Exception - Any exception thrown will be propagated upwards
   */
  public function createObject(array $attributes);

  /**
   * Returns the \Phigester\Digester that was set by the \Phigester\FactoryCreateRule
   * upon initialization.
   *
   * @return \Phigester\Digester
   */
  public function getDigester();

  /**
   * Set the \Phigester\Digester.
   *
   * @param \Phigester\Digester $digester Parent digester object
   */
  public function setDigester(\Phigester\Digester $digester);
}
