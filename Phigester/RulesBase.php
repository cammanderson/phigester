<?php
namespace Phigester;

/**
 * Default implementation of the Rules interface that supports
 * the standard rule matching behavior
 *
 * This class can also be used as a base class for specialized
 * Rules implementations
 *
 * @author Cam Manderson <cammanderson@gmail.com) (PHP53 port)
 * @author Olivier Henry <oliv.henry@gmail.com> (PHP5 port)
 * @author John C. Wildenauer <freed001@gmail.com> (PHP4 port)
 */
class RulesBase implements \Phigester\Rules {
  /**
   * The set of registered Rule instances, keyed by the matching pattern
   *
   * Each value is a List containing the Rules for that pattern, in the
   * order that they were orginally registered.
   *
   * @var array
   */
  protected $cache = array();

  /**
   * The Digester instance with which this Rules instance is associated
   *
   * @var \Phigester\Digester
   */
  protected $digester = null;

  /**
   * The set of registered Rule instances, in the order that they were
   * originally registered
   *
   * @var array
   */
  protected $rules = array();

  /**
   * Return the Digester instance with which this Rules instance is
   * associated
   *
   * @return \Phigester\Digester
   */
  public function getDigester() {
    return $this->digester;
  }

  /**
   * Set the Digester instance with which this Rules instance is associated
   *
   * @param \Phigester\Digester $digester The newly associated Digester
   * instance reference
   */
  public function setDigester(\Phigester\Digester $digester) {
    $this->digester = $digester;
    
    foreach ($this->rules as $rule) {
      $rule->setDigester($digester);
    }
  }

  /**
   * Register a new Rule instance matching the specified pattern
   *
   * @param string $pattern The nesting pattern to be matched for this Rule
   * @param \Phigester\Rule $rule The Rule instance to be registered
   */
  public function add($pattern, \Phigester\Rule $rule) {
    $pattern = (string) $pattern;
    //To help users who accidently add '/' to the end of their patterns
    if (strlen($pattern) > 1 && substr($pattern, -1) == '/') {
      $pattern = substr($pattern, 0, -1);
    }
        
    $this->cache[$pattern][] = $rule;
    $this->rules[] = $rule;
    
    if (!is_null($this->digester)) {
      $rule->setDigester($this->digester);
    }
  }

  /**
   * Clear all existing Rule instance registrations.
   */
  public function clear() {
    $this->cache = array();
    $this->rules = array();
  }

  /**
   * Return a List of all registered Rule instances that match the specified
   * nesting pattern, or a zero-length List if there are no matches
   *
   * If more than one Rule instance matches, they must be returned
   * in the order originally registered through the add()
   * method.
   *
   * @param string $pattern The nesting pattern to be matched
   * @return array
   */
  public function match($pattern) {   
    $rules = $this->lookup($pattern);

    if (is_null($rules)) {
      $longKey = '';
      $keys = array_keys($this->cache);

      foreach ($keys as $key) {
        if (substr($key, 0, 2) == '*/') {
          $lenKey = strlen(substr($key, 1));
          
          if ($pattern == substr($key, 2)
              || substr($pattern, -$lenKey) == substr($key, 1)) {
            if (strlen($key) > strlen($longKey)) {
              $rules = $this->lookup($key);
              $longKey = $key;
            }
          }
        }
      }
    }
    if (is_null($rules)) {
      $rules = array();
    }
    return $rules;
  }

  /**
   * Return a List of all registered Rule instances, or a zero-length List
   * if there are no registered Rule instances
   *
   * If more than one Rule instance has been registered, they must
   * be returned in the order originally registered through the add()
   * method
   *
   * @return array
   */
  public function rules() {
    return $this->rules;
  }

  /**
   * Return a List of Rule instances for the specified pattern
   *
   * If there are no such rules, return null.
   *
   * @param string $pattern The pattern to be matched
   * @return array
   */
  protected function lookup($pattern) {
    $pattern = (string) $pattern;
    
    if (array_key_exists($pattern, $this->cache)) {
      $rules = $this->cache[$pattern];
    } else {
      return null;
    }
    return $rules;
  }
}
?>
