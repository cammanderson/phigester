<?php
namespace Phigester;

/**
 * Rule implementation that calls a method on the (top-1) (parent) object
 * , passing the top object (child) as an argument
 *
 * It is commonly used to establish parent-child relationships.
 *
 * @author Cam Manderson <cammanderson@gmail.com) (PHP53 port)
 * @author Olivier Henry <oliv.henry@gmail.com> (PHP5 port)
 * @author John C. Wildenauer <freed001@gmail.com> (PHP4 port)
 */
class SetNextRule extends \Phigester\AbstractRule
{
    /**
     * The method name to call on the parent object
     *
     * @var string
     */
    protected $methodName = null;

    /**
     * Construct a "set next" rule with the specified method name
     *
     * @param string $methodName The method name of the parent method to call
     */
    public function __construct($methodName)
    {
        $this->methodName = (string) $methodName;
    }

    /**
     * Process the end of this element
     */
    public function end()
    {
        // Identify the objects to be used
        $child = $this->digester->peek(0);
        $parent = $this->digester->peek(1);

        $logger = $this->digester->getLogger();
        $indentLogger = $this->digester->getIndentLogger();
        $match = $this->digester->getMatch();

        if (is_null($parent)) {
            if(!empty($logger)) $loggerdebug(
                $indentLogger . '  [SetNextRule]{' . $match
                . '} Call [NULL PARENT]->' . $this->methodName . '('
                . get_class($child) . ')'
            );
        } else {
            if(!empty($logger)) $loggerdebug(
                $indentLogger . '  [SetNextRule]{' . $match
                . '} Call ' . get_class($parent) . '->' . $this->methodName . '('
                . get_class($child) . ')'
            );
        }

        // Call the specified method
        if (!is_null($child) && !is_null($parent)) {
            $methodName = $this->methodName;
            if (!method_exists($parent, $methodName)) {
                $msg = 'Class "' . get_class($parent) . '" has no method named '
                    . $methodName;
                throw new \Phigester\Exception\NoSuchMethodException($msg);
            }
            $parent->$methodName($child);
        }
    }

    /**
     * Render a printable version of this Rule
     *
     * @return string
     */
    public function toString()
    {
        $sb = 'SetNextRule[';
        $sb .= 'methodName=';
        $sb .= $this->methodName;
        $sb .= ']';

        return $sb;
    }
}
