<?php

namespace Developer;

use Bitrix\Main\Loader;
use \Bitrix\Main\Data\Cache;

if (!Loader::includeModule('iblock')) {
    die("модуль инфоблоков не подключен!");
}

class Test
{
    private $sort = ["SORT" => "ASC"];
    private $filter = [];
    private $select = [];
    private $limit = ["nTopCount" => 10];
    private $cacheTTL = 3600;

    /**
     * @return array
     * @throws \Exception
     */
    public function getItems()
    {
        if (empty($this->filter)) {
            throw new \Exception("не задан фильтр поиска элементов");
        }

        $cache = Cache::createInstance();
        $arItems = [];

        if ($cache->initCache($this->getCacheTTL(), md5(serialize($this->filter)), "/".str_replace("\\", "/", get_class()))) {
            $vars = $cache->getVars();
            $arItems = $vars["arItems"];
        } elseif ($cache->startDataCache()) {

            $rsElements = \CIBlockElement::GetList($this->sort, $this->filter, false, $this->limit, $this->select);

            while ($result = $rsElements->GetNext()) {
                $arItems[] = $result;
            }

            $cache->endDataCache(["arItems" => $arItems]);
        }

        return $arItems;
    }

    /**
     * @return array
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * @param array $sort
     * @return Test
     */
    public function setSort($sort = [])
    {
        $this->sort = $sort;
        return $this;
    }

    /**
     * @return array
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * @param array $filter
     * @return Test
     */
    public function setFilter($filter = [])
    {
        $this->filter = $filter;
        return $this;
    }

    /**
     * @return array
     */
    public function getSelect()
    {
        return $this->select;
    }

    /**
     * @param array $select
     * @return Test
     */
    public function setSelect($select = [])
    {
        $this->select = $select;
        return $this;
    }

    /**
     * @return array
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param array $limit
     * @return Test
     */
    public function setLimit($limit = [])
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * @return int
     */
    public function getCacheTTL()
    {
        return $this->cacheTTL;
    }

    /**
     * @param int $cacheTTL
     * @return Test
     */
    public function setCacheTTL($cacheTTL)
    {
        $this->cacheTTL = $cacheTTL;
        return $this;
    }
}
