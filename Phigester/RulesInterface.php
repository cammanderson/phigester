<?php
namespace Phigester;

/**
 * \Phigester\Rules
 *
 * Public interface defining a collection of Rule instances (and
 * corresponding matching patterns) plus an implementation of a matching policy
 * that selects the rules that match a particular pattern of nested elements
 * discovered during parsing.
 *
 * @author Cam Manderson <cammanderson@gmail.com) (PHP53 port)
 * @author Olivier Henry <oliv.henry@gmail.com> (PHP5 port)
 * @author John C. Wildenauer <freed001@gmail.com> (PHP4 port)
 */
interface RulesInterface
{
  /**
   * Return the Digester instance with which this Rules instance
   * is associated
   *
   * @return \Phigester\Digester
   */
  public function getDigester();

  /**
   * Set the Digester instance with which this Rules instance
   * is associated
   *
   * @param \Phigester\Digester $digester The newly associated
   * Digester instance
   */
  public function setDigester(\Phigester\Digester $digester);

  /**
   * Register a new Rule instance matching the specified pattern
   *
   * @param string $pattern Nesting pattern to be matched for this Rule
   * @param \Phigester\AbstractRule $rule The Rule instance to be registered
   */
  public function add($pattern, \Phigester\AbstractRule $rule);

  /**
   * Clear all existing Rule instance registrations
   */
  public function clear();

  /**
   * Return a List of all registered Rule instances that match
   * the specified nesting pattern, or a zero-length list if there are
   * no matches
   *
   * If more than one Rule instance matches, they must be returned
   * in the order originally registered through the add() method.
   *
   * @param string $pattern Nesting pattern to be matched
   * @return array
   */
  public function match($pattern);

  /**
   * Return a List of all registered Rule instances, or a zero-length
   * list if there are no registered Rule instances
   *
   * If more than one Rule instance has been registered
   * , they must be returned in the order originally registered through
   * the add() method.
   *
   * @return array
   */
  public function rules();
}
