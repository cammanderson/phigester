<?php
namespace Phigester;

/**
 * Utility methods for converting string scalar value to value
 * of the specified primitive type
 *
 * @author Cam Manderson <cammanderson@gmail.com) (PHP53 port)
 * @author Olivier Henry <oliv.henry@gmail.com> (PHP5 port)
 * @author John C. Wildenauer <freed001@gmail.com> (PHP4 port)
 */
class ConvertUtils {
  /**
   * Convert the specified value to a value of the specified primitive type
   *
   * @param string $value Value to be converted
   * @param string $type Primitive type to be converted to
   * @return mixed The converted value
   * @throws \Phigester\Exception\ConversionException
   */
  public static function convert($value, $type) {
    $value = (string) $value;
    $type = strtolower($type);
    
    if ($type == 'boolean') {
      $temp = strtolower($value);
      if ($temp === 'false') {
        return false;
      } else {
        if (!@settype($value, $type))
          throw new \Phigester\Exception\ConversionException();
        return $value;
      }
    } else {
      if (!@settype($value, $type))
        throw new \Phigester\Exception\ConversionException();
      return $value;
    }
  }
}
?>
