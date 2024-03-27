<?php

namespace WYB\BootstrapTable;

/**
 * BootstrapTable \ Column
 * Used for storing table column attributes
 */
class Column
{

    /**
     * @var Attribute[] attributes
     */
    private array $attributes;
    /**
     * @var string
     */
    private string $label;

    /**
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->label = $this->doLabel($name);
        $this->defaultAttributes();
        $this->setAttribute('field', $name);
    }

    /**
     * @return void
     */
    private function defaultAttributes(): void
    {
        $this->attributes = array(
            'field' => new Attribute('field', null),
            'title' => new Attribute('title', null),
            'titleTooltip' => new Attribute('titleTooltip', null),
            'class' => new Attribute('class', null),
            'width' => new Attribute('width', null),
            'widthUnit' => new Attribute('widthUnit', 'px'),
            'rowspan' => new Attribute('rowspan', null),
            'colspan' => new Attribute('colspan', null),
            'align' => new Attribute('align', null), // left, right, center
            'halign' => new Attribute('halign', null), // left, right, center
            'falign' => new Attribute('falign', null), // left, right, center
            'valign' => new Attribute('valign', null), // top, middle, bottom
            'cellStyle' => new Attribute('cellStyle', null),
            'radio' => new Attribute('radio', false),
            'checkbox' => new Attribute('checkbox', false),
            'checkboxEnabled' => new Attribute('checkboxEnabled', true),
            'clickToSelect' => new Attribute('clickToSelect', true),
            'showSelectTitle' => new Attribute('showSelectTitle', false),
            'sortable' => new Attribute('sortable', false),
            'sortName' => new Attribute('sortName', null),
            'order' => new Attribute('order', 'asc'), // asc, desc
            'sorter' => new Attribute('sorter', null),
            'visible' => new Attribute('visible', true),
            'switchable' => new Attribute('switchable', true),
            'cardVisible' => new Attribute('cardVisible', true),
            'searchable' => new Attribute('searchable', true),
            'formatter' => new Attribute('formatter', null),
            'footerFormatter' => new Attribute('footerFormatter', null),
            'detailFormatter' => new Attribute('detailFormatter', null),
            'searchFormatter' => new Attribute('searchFormatter', true),
            'searchHighlightFormatter' => new Attribute('searchHighlightFormatter', null),
            'escape' => new Attribute('escape', null),
            'events' => new Attribute('events', null)
        );
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    function setAttribute(string $key, mixed $value): static
    {
        if (isset($this->attributes[$key])) {
            $this->attributes[$key]->set('value', $value);
        } else {
            $this->attributes[$key] = new Attribute($key, null);
            $this->attributes[$key]->set('value', $value);
        }
        return $this;
    }

    /**
     * @param string $key
     * @return mixed
     */
    function getAttribute(string $key): mixed
    {
        return $this->attributes[$key]->get('value');
    }

    /**
     * @param string $key
     * @return mixed
     */
    function getAttributeDefault(string $key): mixed
    {
        return $this->attributes[$key]->get('default');
    }

    /**
     * @param string $column
     * @return string
     */
    private function doLabel(string $column): string
    {
        return ucwords(str_replace('_', ' ', $column));
    }

    /**
     * @return string
     */
    public function render(): string
    {
        $result = '<th';
        foreach ($this->attributes as $attribute) {
            $result .= $attribute->render();
        }
        $result .= '>' . $this->label . '</th>';
        return $result;
    }

}
