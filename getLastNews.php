<?php

namespace Developer;

class RssParser
{

    const SRC = "https://lenta.ru/rss";

    private $partition = 5;

    private $arAvailableFields = ["title", "link", "description"];
    public $arItems = [];


    /**
     * @return $this|bool
     */
    public function getSourceData()
    {
        try {
            $xml = simplexml_load_string(file_get_contents(self::SRC), null, LIBXML_NOCDATA);
            if (!empty($xml)) {

                $chanel = (array)$xml->channel;

                $parts = array_chunk($chanel["item"], $this->partition);

                foreach (current($parts) as $part) {
                    $item = [];
                    foreach ($this->arAvailableFields as $field) {
                        $item[$field] = current($part->{$field});
                    }
                    $this->arItems[] = $item;
                }
                return $this;
            }
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            return false;
        }
    }

    public function show()
    {
        Header("Content-type: application/json");
        echo json_encode($this->arItems, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        die();
    }

    /**
     * @return int
     */
    public function getPartition()
    {
        return $this->partition;
    }

    /**
     * @param int $partition
     * @return RssParser
     */
    public function setPartition($partition)
    {
        $this->partition = $partition;
        return $this;
    }
}

(new RssParser())
    ->setPartition(5)
    ->getSourceData()
    ->show();


