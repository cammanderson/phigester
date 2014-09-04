<?php
namespace Phigester\Exception;

/**
 * Thrown when a class is requested by reflection, but the class definition
 * cannot be found.
 *
 * @author Cam Manderson <cammanderson@gmail.com) (PHP53 port)
 * @author Olivier Henry <oliv.henry@gmail.com> (PHP5 port)
 * @author John C. Wildenauer <freed001@gmail.com> (PHP4 port)
 */
class ClassNotFoundException extends \Exception
{
}
