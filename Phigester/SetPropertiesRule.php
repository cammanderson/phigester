<?php
namespace Phigester;

/**
 * SetPropertiesRule
 *
 * Rule implementation that sets properties on the object at the top of the
 * stack, based on attributes with corresponding names.
 *
 * @author Cam Manderson <cammanderson@gmail.com) (PHP53 port)
 * @author Olivier Henry <oliv.henry@gmail.com> (PHP5 port)
 * @author John C. Wildenauer <freed001@gmail.com> (PHP4 port)
 */
class SetPropertiesRule extends \Phigester\AbstractRule
{
    /**
     * Attribute names used to override natural attribute->property mapping
     *
     * @var array
     */
    private $attributeNames = null;

    /**
     * Property names used to override natural attribute->property mapping
     *
     * @var array
     */
    private $propertyNames = null;

    /**
     * ignoreMissingProperty
     *
     * Used to determine whether the parsing should fail if a property specified
     * in the XML is missing from the bean
     *
     * @var boolean
     */
    private $ignoreMissingProperty = true;

    /**
     * Constructor allows attribute->property mapping to be overriden
     *
     * <p>Two arrays are passed in.
     * One contains the attribute names and the other the property names.
     * The attribute name / property name pairs are match by position.
     * In order words, the first string in the attribute name list matches
     * to the first string in the property name list and so on.</p>
     * <p>If a property name is null or the attribute name has no matching
     * property name, then this indicates that the attibute should be ignored.</p>
     * <p><b>Example One</b><br>
     * The following constructs a rule that maps the <b>alt-city</b>
     * attribute to the <b>city</b> property and the <b>alt-state</b>
     * to the <b>state</b> property.
     * All other attributes are mapped as usual using exact name matching.</p>
     * <pre>
     *     SetPropertiesRule(
     *         array('alt-city', 'alt-state'),
     *         array('city', 'state'));
     * </pre>
     * <p><b>Example Two</b><br>
     * The following constructs a rule that maps the <b>class</b>
     * attribute to the <b>className</b> property.
     * The attribute <b>ignore-me</b> is not mapped.
     * All other attributes are mapped as usual using exact name matching.</p>
     * <pre>
     *     SetPropertiesRule(
     *         array('class', 'ignore-me'),
     *         array('className'));
     * </pre>
     *
     * @param array $attributeNames The names of attributes to map
     * @param array $propertyNames  The names of properties mapped to
     */
    public function __construct(array $attributeNames, array $propertyNames)
    {
        $this->attributeNames = $attributeNames;
        $this->propertyNames = $propertyNames;
    }

    /**
     * Process the beginning of this element
     *
     * @param array $attributes The attribute list of this element
     */
    public function begin(array $attributes)
    {
        $logger = $this->digester->getLogger();
        $indentLogger = $this->digester->getIndentLogger();
        $match = $this->digester->getMatch();

        // Build a set of attributes names and corresponding values
        $values = array();

        // Set up variables for custom names mappings
        $propNamesLength = count($this->propertyNames);

        // Loop through the xml element attribute names and corresponding values
        foreach ($attributes as $xmlAttribName => $xmlAttribValue) {
            $name = $xmlAttribName;

            // We'll now check for custom mappings
            $n = 0;
            foreach ($this->attributeNames as $attributeName) {
                if ($attributeName == $xmlAttribName) {
                    if ($n < $propNamesLength) {
                        // Set this to value from list
                        $name = $this->propertyNames[$n];
                    } else {
                        // Set name to null, we'll check for this later
                        $name = null;
                    }
                    break;
                }
                $n++;
            }

            if(!empty($logger)) $loggerdebug(
                $indentLogger . '  [SetPropertiesRule]{' . $match
                . '} Setting property "' . $name . '" to "' . $xmlAttribValue . '"'
            );

            if (!$this->ignoreMissingProperty && !is_null($name)) {
                $top = $this->digester->peek();
                $reflection = new \ReflectionClass(get_class($top));
                // Do nothing if the top object is null
                if (!is_null($top)) {
                    $property = null;
                    if (property_exists($top, $name)) {
                        $property = $reflection->getProperty($name);
                    }
                    if (!empty($property)) {
                        $propertySetter = 'set' . ucfirst($name);
                        if (!method_exists($top, $propertySetter)) {
                            $msg = 'Class "' . get_class($top) . '" has no property named '
                                . $name;
                            throw new \Phigester\Exception\NoSuchPropertyException($msg);
                        }
                    }
                }
            }

            if (!is_null($name)) {
                $values[$name] = $xmlAttribValue;
            }
        }

        // Populate the corresponding properties of the top object
        $top = $this->digester->peek();
        if(!empty($logger)) $loggerdebug(
            $indentLogger . '  [SetPropertiesRule]{' . $match
            . '} Set ' . get_class($top) . ' properties'
        );

        // Do nothing if the top object is null
        if (!is_null($top)) {
            // Build a set of attribute names and corresponding values
            $reflection = new \ReflectionClass(get_class($top));
            foreach ($values as $name => $value) {
                $reflectionProperty = null;
                if (property_exists($top, $name)) {
                    $reflectionProperty = $reflection->getProperty($name);
                }
                if (!empty($reflectionProperty) && $reflectionProperty->isPublic()) {
                    $top->$name = $value;
                } else {
                    $propertySetter = 'set' . ucfirst($name);
                    if (method_exists($top, $propertySetter)) {
                        $top->$propertySetter($value);
                    }
                }
            }
        }
    }

    /**
     * Render a printable version of this Rule
     *
     * @return string
     */
    public function toString()
    {
        $sb = 'SetPropertiesRule[]';

        return $sb;
    }

    /**
     * Are attributes found in the xml without matching properties to be ignored?
     *
     * If false, the parsing will interrupt with an <b>NoSuchPropertyException</b>
     * if a property specified in the XML is not found. The default is true.
     *
     * @return boolean True if skipping the unmatched attributes
     */
    public function isIgnoreMissingProperty()
    {
        return $this->ignoreMissingProperty;
    }

    /**
     * Sets whether attributes found in the xml without matching properties
     * should be ignored
     *
     * If set to false, the parsing will throw a <b>NoSuchPropertyException</b>
     * if an unmatched attribute is foud. This allows to trap misspellings in
     * the XML file.
     *
     * @param boolean $ignoreMissingProperty False to stop the parsing on
     *                                       unmatched attributes
     */
    public function setIgnoreMissingProperty($ignoreMissingProperty)
    {
        $this->ignoreMissingProperty = (boolean) $ignoreMissingProperty;
    }
}
