<?php
namespace Phigester;

/**
 * This class is a wrapper for the PHP's internal expat parser
 *
 * It takes an XML file represented by an abstract path name, and starts
 * parsing the file and calling the different "trap" methods inherited from
 * the AbstractXMLParser class.<br>
 * Those methods then invoke the representative methods in the registered
 * handler classes.
 *
 * @author Cam Manderson <cammanderson@gmail.com) (PHP53 port)
 * @author Olivier Henry <oliv.henry@gmail.com> (PHP5 port)
 * @author John C. Wildenauer <freed001@gmail.com> (PHP4 port)
 * @author Yannick Lecaillez <yl@seasonfive.com>
 * @author Andreas Aderhold <andi@binarycloud.com>
 */
abstract class AbstractExpatParser extends \Phigester\AbstractXMLParser
{
    /**
     * Constructs a new AbstractExpatParser object
     */
    public function __construct()
    {
        $this->createParser();
    }

    /**
     * Sets up PHP's internal expat parser and options.
     */
    private function createParser()
    {
        //We need the xml parser to operate in this class
        $this->parser = xml_parser_create();
        xml_set_object($this->parser, $this);
        xml_set_element_handler(
            $this->parser,
            'startElementHandler'
            ,
            'endElementHandler'
        );
        xml_set_character_data_handler($this->parser, 'characterDataHandler');

        //Default options
        $this->parserSetOption(XML_OPTION_CASE_FOLDING, false);
    }

    /**
     * Starts the parsing process
     *
     * @param  string $xmlFile Name of the file containing the XML data to be
     * parsed
     * @throws \Phigester\Exception\ExpatParserException - If something gone
     * wrong during parsing
     * @throws \Phigester\Exception\IOException  - If XML file can not be accessed
     */
    public function parse($xmlFile)
    {
        $xmlFile = (string) $xmlFile;

        //Check the XML file to be parsed
        if (is_file($xmlFile)) {
            //Open the XML file in read-only mode
            $fp = fopen($xmlFile, 'r');
            if (is_resource($fp)) {
                //Reading the XML file
                $data = fread($fp, filesize($xmlFile));
                //Parsing the XML file
                if (xml_parse($this->parser, $data)) {
                    fclose($fp);
                    //Initialize the parser for another use
                    xml_parser_free($this->parser);
                    $this->createParser();
                } else {
                    //Get the XML parser error
                    $error = xml_error_string(xml_get_error_code($this->parser));
                    $line = xml_get_current_line_number($this->parser);
                    xml_parser_free($this->parser);
                    fclose($fp);
                    throw new \Phigester\Exception\ExpatParserException(__METHOD__ . ' : ' . $error
                        . ' on line ' . $line);
                }
            } else {
                throw new \Phigester\Exception\IOException(__METHOD__
                    . ' : XML file "' . $xmlFile . '" can not be accessed');
            }
        } else {
            throw new \Phigester\Exception\IOException(__METHOD__
                . ' : XML file "' . $xmlFile . '" can not be accessed');
        }
    }

    /**
     * Override PHP's parser default settings, created in the constructor
     *
     * @param  string  $opt The option to set
     * @param  mixed   $val The value to set
     * @return boolean True if the option could be set, otherwise false
     */
    public function parserSetOption($opt, $val)
    {
        return xml_parser_set_option($this->parser, $opt, $val);
    }
}
