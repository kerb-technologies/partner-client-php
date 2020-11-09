<?php

namespace Kerb\Partner;

class SetGet
{
    /**
     * container for values
     * @var array
     */
    protected $data = [];

    /**
     * set data using key-value, value can be anything
     *
     * @param string $key key of the data
     */
    public function set(string $key, $value) : void
    {
        $this->data[$key] = $value;
    }

    /**
     * Get data based on key
     *
     * @param string $key
     * @return mixed|null
     */
    public function get(string $key)
    {
        if (array_key_exists($key, $this->data)) {
            return $this->data[$key];
        }

        return null;
    }

    /**
     * Get all data
     *
     * @return array
     */
    public function all() : array
    {
        return $this->data;
    }
}
