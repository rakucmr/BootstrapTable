<?php

namespace WYB\BootstrapTable;
/**
 * BootstrapTable \ Attribute
 * Used for storing Column or Table attribute
 */
class Attribute
{

    /**
     * @var string
     */
    private string $name;
    /**
     * @var mixed
     */
    private mixed $default;
    /**
     * @var mixed
     */
    private mixed $value;

    /**
     * @param string $name
     * @param mixed $default
     * @param mixed $value
     */
    public function __construct(string $name, mixed $default, mixed $value = null)
    {
        $this->name = $name;
        $this->default = $default;
        if (is_null($value)) {
            $this->value = $default;
        } else {
            $this->value = $value;
        }
    }

    /**
     * @param string $property
     * @return mixed
     */
    public function get(string $property): mixed
    {
        if (property_exists($this, $property)) {
            return $this->{$property};
        } else {
            return null;
        }
    }

    /**
     * @param string $property
     * @param mixed $value
     * @return void
     */
    public function set(string $property, mixed $value): void
    {
        if (property_exists($this, $property)) {
            $this->{$property} = $value;
        }
    }

    /**
     * @return array|false|mixed|string|string[]
     */
    private function renderValue(): mixed
    {
        if (is_array($this->value)) {
            return str_replace('"', '\'', json_encode($this->value));
        }
        if (is_bool($this->value)) {
            return json_encode($this->value);
        }
        return $this->value;
    }

    /**
     * @return string
     */
    public function render(): string
    {
        if ($this->value !== $this->default) {
            return ' data-' . strtolower(preg_replace('/(?!^)[A-Z]/', '-$0', $this->name)) . '="' . $this->renderValue() . '"';
        }
        return '';
    }

}
