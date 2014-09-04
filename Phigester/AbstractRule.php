<?php
namespace Phigester;

/**
 * \Phigester\AbstractRule
 *
 * Concrete implementations of this class implement actions to be taken when
 * a corresponding nested pattern of XML elements has been matched.
 *
 * @author Cam Manderson <cammanderson@gmail.com) (PHP53 port)
 * @author Olivier Henry <oliv.henry@gmail.com> (PHP5 port)
 * @author John C. Wildenauer <freed001@gmail.com> (PHP4 port)
 */
abstract class AbstractRule
{
  /**
   * The Digester with which this Rule is associated
   *
   * @var \Phigester\Digester
   */
  protected $digester = null;

  /**
   * Return the Digester with which this Rule is associated
   *
   * @return \Phigester\Digester
   */
  public function getDigester()
  {
    return $this->digester;
  }

  /**
   * Set the Digester with which this Rule is associated
   *
   * @param \Phigester\Digester $digester A Digester object reference
   */
  public function setDigester(\Phigester\Digester $digester)
  {
    $this->digester = $digester;
  }

  /**
   * This method is called when the beginning of a matching XML element
   * is encountered
   *
   * @param array $attributes The attribute list of this element
   * @throws \Exception
   */
  public function begin(array $attributes)
  {
    //The default implementation does nothing
  }

  /**
   * This method is called when the body of a matching XML element
   * is encountered
   *
   * If the element has no body, this method is not called at all.
   *
   * @param string $text The text of the body of this element
   * @throws \Exception
   */
  public function body($text)
  {
    //The default implementation does nothing
  }

  /**
   * This method is called when the end of a matching XML element
   * is encountered
   *
   * @throws \Exception
   */
  public function end()
  {
    //The default implementation does nothing
  }

  /**
   * This method is called after all parsing methods have been
   * called, to allow Rules to remove temporary data.
   *
   * @throws \Exception
   */
  public function finish()
  {
    //The default implementation does nothing
  }

  /**
   * Render a printable version of this Rule
   *
   * @return string
   */
  public function toString()
  {
    $sb = get_class($this) . '[]';

    return $sb;
  }
}
