<?php
namespace loicm\Forests;


class ContentMetaData
{
    protected $metadata = array();


    public function __construct($content)
    {
        $metadata = array();

        $lines = explode("\n", $content);
        foreach($lines as $line) {
            if (preg_match('/^([a-z]+)\s*:\s*(.+)/', $line, $m)) {
                $metadata[strtolower($m[1])] = $m[2];
            }
        }

        $this->metadata = $metadata;
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->metadata)) {
            return $this->metadata[$name];
        }
        return false;
    }
}