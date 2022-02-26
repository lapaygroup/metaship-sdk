<?php
namespace LapayGroup\MetaShipSdk\Entity;

class Item
{
    /** @var string|null  */
    private $article = null; // Артикул товара

    /** @var string|null  */
    private $name = null; // Наименование товара

    /** @var float|null  */
    private $price = null; // Стоимость за единицу товара

    /** @var integer|null  */
    private $count = null; // Количество товара

    /** @var float|null  */
    private $weight = null; // Вес товара в кг.

    /** @var string|null  */
    private $vat_rate = null; // Код ставки НДС


    /**
     * Формирует массив параметров для запроса
     *
     * @return array
     * @throws \InvalidArgumentException
     */
    public function asArr()
    {
        $params = [];

        foreach (array_keys(get_object_vars($this)) as $property) {
            if (is_null($this->$property))
                throw new \InvalidArgumentException('В товаре не заполнено обязательное поле '.$property);
        }

        $params['article'] = $this->article;
        $params['name'] = $this->name;
        $params['price'] = $this->price;
        $params['count'] = $this->count;
        $params['weight'] = $this->weight;
        $params['vat'] = (string)$this->vat_rate;

        return $params;
    }

    /**
     * @return string|null
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * @param string|null $article
     */
    public function setArticle($article)
    {
        $this->article = $article;
    }

    /**
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return float|null
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param float|null $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return int|null
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @param int|null $count
     */
    public function setCount($count)
    {
        $this->count = $count;
    }

    /**
     * @return float|null
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param float|null $weight
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;
    }

    /**
     * @return string|null
     */
    public function getVatRate()
    {
        return $this->vat_rate;
    }

    /**
     * @param string|null $vat_rate
     */
    public function setVatRate($vat_rate)
    {
        $this->vat_rate = $vat_rate;
    }
}