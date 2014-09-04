<?php
namespace Phigester;

/**
 * The ClassLoader is a way of customizing the way PHP gets its classes
 * and loads them into memory
 *
 * @author Cam Manderson <cammanderson@gmail.com) (PHP53 port)
 * @author Olivier Henry <oliv.henry@gmail.com> (PHP5 port)
 * @author John C. Wildenauer <freed001@gmail.com> (PHP4 port)
 */
class ClassLoader {
  protected static $phpExtensionFile = '.php';
  
  /**
   * @param string $phpExtensionFile
   */
  public static function setPhpExtensionFile($phpExtensionFile) {
    self::$phpExtensionFile = (string) $phpExtensionFile;
  }
  
  /**
   * Check if a fully qualified class name is valid
   *
   * @param string $name Fully qualified name of a class (with packages)
   * @return boolean Return true if the class name is valid
   */
  public static function isValidClassName($name) {
    $classPattern = '`^((([A-Z]|[a-z]|[0-9]|\_|\-)+\:{2})*)';
    $classPattern .= '(([A-Z]|[a-z]){1}([A-Z]|[a-z]|[0-9]|\_)*)$`';
    return (boolean) preg_match($classPattern, $name);
  }
  
  /**
   * Return only the class name of a fully qualified name
   *
   * @param string $name Fully qualified name of a class (with packages)
   * @return string
   */
  public static function getClassName($name) {
    $lastDot = strrpos($name, '::');
    if ($lastDot === false) {
      $className = $name;
    } else {
      $className = substr($name, -(strlen($name) - $lastDot - 2));
    }
    return $className;
  }
  
  /**
   * Load a class
   * 
   * @param string $name The fully qualified name of the class (with packages)
   * @return string Return the only class name
   * @throws \Phigester\Exception\IllegalArgumentException
   * @throws \Phigester\Exception\ClassNotFoundException
   */
  public static function loadClass($name) {
    //Check if the fully qualified class name is valid
    if (!self::isValidClassName($name))
      throw new \Phigester\Exception\IllegalArgumentException('Illegal class name ' . $name);
    
    //Get only the class name
    $className = self::getClassName($name);
    
    //Have we already loaded this class?
    if (class_exists($className)) {
      return $className;
    } else {
      //Try to load the class
      $pathClassFile = str_replace('::', '/', $name) . self::$phpExtensionFile;
      if (@include_once($pathClassFile)) {
        if (class_exists($className)) {
          return $className;
        } else {
          $msg = '"' . $name . '" class does not exist.';
          throw new \Phigester\Exception\ClassNotFoundException($msg);
        }        
      } else {
        $msg = 'PHP class file "' . $pathClassFile . '" does not exist.';
        throw new \Phigester\Exception\ClassNotFoundException($msg);
      }      
    }
  }
}
?>
