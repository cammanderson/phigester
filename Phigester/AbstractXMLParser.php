<?php
namespace Phigester;

/**
 * The abstract XML parser class
 *
 * This class represents an XML parser. It is an abstract class that must be
 * implemented by the real parser that must extend this class.
 *
 * @author Olivier Henry <oliv.henry@gmail.com> (PHP5 port)
 * @author John C. Wildenauer <freed001@gmail.com> (PHP4 port)
 * @author Yannick Lecaillez <yl@seasonfive.com>
 * @author Andreas Aderhold <andi@binarycloud.com>
 */
abstract class AbstractXMLParser
{
    /**
     * Internal XML parser object
     *
     * @var object
     */
    protected $parser = null;

    /**
     * Sets options for PHP internal parser
     *
     * Must be implemented by the parser class if it should be used.
     *
     * @param mixed $opt
     * @param mixed $val
     */
    abstract public function parserSetOption($opt, $val);

    /**
     * Method that gets invoked when the parser runs over a XML start element
     *
     * This method is called by PHP's internal parser functions and registered
     * in the actual parser implementation.<br>
     * It gives control to the current active handler object by calling the
     * startElement() method.
     *
     * @param  object     $parser  The php's internal parser handle
     * @param  string     $name    The open tag name
     * @param  array      $attribs The tag's attributes if any
     * @throws \Exception
     */
    abstract public function startElementHandler($parser, $name, $attribs);

    /**
     * Method that gets invoked when the parser runs over a XML close element
     *
     * This method is called by PHP's internal parser functions and registered
     * in the actual parser implementation.<br>
     * It gives control to the current active handler object by calling the
     * endElement() method.
     *
     * @param  object    $parser The php's internal parser handle
     * @param  string    $name   The closing tag name
     * @throws \Exception
     */
    abstract public function endElementHandler($parser, $name);

    /**
     * Method that gets invoked when the parser runs over CDATA
     *
     * This method is called by PHP's internal parser functions and registered
     * in the actual parser implementation.<br>
     * It gives control to the current active handler object by calling the
     * characters() method. That processes the given CDATA.
     *
     * @param  object    $parser The php's internal parser handle
     * @param  string    $data   The CDATA
     * @throws \Exception
     */
    abstract public function characterDataHandler($parser, $data);

    /**
     * Entrypoint for parser
     *
     * This method needs to be implemented by the child class that utilizes
     * the concrete parser.
     * @param string $xmlFile Name of the file containing the XML data to be
     *                        parsed
     */
    abstract public function parse($xmlFile);
}
