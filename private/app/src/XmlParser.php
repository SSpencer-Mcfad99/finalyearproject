<?php
/**
 * Created by PhpStorm.
 * User: p17206266
 * Date: 06/01/2020
 * Time: 13:24
 */

namespace VotingSystemsTutorial;
/**
 * class XmlParser
 *
 * Parses a given XML string and returns an associative array
 */
class XmlParser
{
    private $xml_parser;
    private $parsed_data;
    private $element_name;
    private $temporary_attributes;
    private $xml_string_to_parse;

    public function _construct() {
        $this->parsed_data = [];
    }

    public function _destruct() {
        xml_parser_free($this->parsed_data);
        unset($this->parsed_data);
    }

    /**
     * Resets the XML parser
     */
    public function resetXMLparser(){
        $this->xml_parser = null;
        $this->element_name = null;
        $this->parsed_data = [];
    }

    /**
     * @param $string_to_parse
     *
     * Passes the string that needs to be parsed
     */
    public function setXmlStringToParse($string_to_parse){
        $this->xml_string_to_parse = $string_to_parse;
    }

    /**
     * @return array
     *
     * Returns the parsed data
     */
    public function getParsedData(){
        return $this->parsed_data;
    }

    /**
     * Parses the XML string
     */
    public function parseTheXmlString(){
        $this->xml_parser = xml_parser_create();

        xml_set_object($this->xml_parser, $this);

        xml_set_element_handler($this->xml_parser, 'open_element', 'close_element');

        xml_set_character_data_handler($this->xml_parser, process_data_element);

        $this->parseTheDataString();
    }

    /**
     * This parser is used to step through the element tags
     */
    public function parseTheDataString(){
      xml_parse($this->xml_parser, $this->xml_string_to_parse);
    }

    /**
     * Process an open element event & store the tag name
     * Extract the attribute names and values, if any
     */
    public function open_element($parser, $element_name, $attributes){
        $this->element_name = $element_name;
        if (sizeof($attributes) > 0)
        {
            foreach ($attributes as $att_name => $att_value)
            {
                $tag_att = $element_name . "." . $att_name;
                $this->temporary_attributes[$tag_att] = $att_value;
            }
        }
        else
        {
            $this->temporary_attributes = [];
        }
    }

    /**
     * @param $parser
     * @param $element_data
     *
     * Process data from an element
     */
    public function process_data_element($parser, $element_data){
        if (array_key_exists($this->element_name, $this->parsed_data) === false)
        {
            $this->parsed_data[$this->element_name] = $element_data;
            if (sizeof($this->temporary_attributes) > 0)
            {
                foreach ($this->temporary_attributes as $tag_att_name => $tag_att_value)
                {
                    $this->parsed_data[$tag_att_name] = $tag_att_value;
                }
            }
        }
    }

    /**
     * Processes a close element event
     */
    public function close_element($parser, $element_name){}
}