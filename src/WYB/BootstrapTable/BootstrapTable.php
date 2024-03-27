<?php

namespace WYB\BootstrapTable;

/**
 * BootstrapTable
 */
class BootstrapTable
{

    /**
     * @var Attribute[] attributes
     */
    private array $attributes;
    /**
     * @var Column[] columns
     */
    private array $columns;
    /**
     * @var array
     */
    private array $rows;
    /**
     * @var array
     */
    private array $groups;
    /**
     * @var bool|string
     */
    private bool|string $action;
    /**
     * @var string
     */
    private string $id;
    /**
     * @var bool
     */
    private bool $selectable;

    /**
     * @param array $columns
     * @param array $rows
     */
    public function __construct(array $columns = [], array $rows = [])
    {
        //$this->id = 'bt-' . mt_rand (0, 10000) . '-' . time();
        $this->id = str_replace('.', '', uniqid('bt', true));
        $this->setColumns($columns);
        $this->setRows($rows);
        $this->defaultAttributes();
        $this->action = false;
        $this->selectable = false;
    }

    /**
     * @return void
     */
    private function defaultAttributes(): void
    {
        $this->attributes = array(
            'search' => new Attribute('search', false),
            'pagination' => new Attribute('pagination', false),
            'pageSize' => new Attribute('pageSize', 10),
            'pageNumber' => new Attribute('pageNumber', 1),
            'pageList' => new Attribute('pageList', array(10, 25, 50, 100)),
            'showExport' => new Attribute('showExport', false),
            'exportDataType' => new Attribute('exportDataType', 'basic'), // basic, all, selected
            'exportTypes' => new Attribute('exportTypes', array('json', 'xml', 'csv', 'txt', 'sql', 'excel')), // 'json', 'xml', 'png', 'csv', 'txt', 'sql', 'doc', 'excel', 'xlsx', 'pdf'
            'showPrint' => new Attribute('showPrint', false),
            'showFooter' => new Attribute('showFooter', false),
            'height' => new Attribute('height', false),
            'url' => new Attribute('url', false),
            'checkbox-header' => new Attribute('checkbox-header', true),
            'sortName' => new Attribute('sortName', false),
            'sortOrder' => new Attribute('sortOrder', false) // undefined, 'asc' or 'desc'
        );
    }

    /**
     * @param $rows
     * @return $this
     */
    public function setRows($rows): static
    {
        $this->rows = is_array($rows) ? $rows : array();
        return $this;
    }

    /**
     * @param $columns
     * @return $this
     */
    public function setColumns($columns): static
    {
        if (is_string($columns)) {
            $columns = explode(',', $columns);
        }
        foreach ($columns as $column) {
            $this->columns[$column] = new Column($column);
        }
        return $this;
    }

    /**
     * @param $key
     * @param $value
     * @return $this
     */
    public function setAttribute($key, $value): static
    {
        if (array_key_exists($key, $this->attributes)) {
            $this->attributes[$key]->set('value', $value);
        } else {
            $this->attributes[$key] = new Attribute($key, false, $value);
        }
        return $this;
    }

    /**
     * @param $column
     * @param $attribute
     * @param $value
     * @return $this
     */
    public function setColumnAttribute($column, $attribute, $value): static
    {
        $this->columns[$column]->setAttribute($attribute, $value);
        return $this;
    }

    /**
     * @return $this
     */
    public function enableSelectable(): static
    {
        $this->setAttribute('checkbox-header', false);
        $this->setAttribute('click-to-select', true);
        $this->selectable = true;
        return $this;
    }

    /**
     * @return $this
     */
    public function disableSelectable(): static
    {
        $this->setAttribute('click-to-select', false);
        $this->setAttribute('checkbox-header', false);
        $this->selectable = false;
        return $this;
    }

    /**
     * @return $this
     */
    public function enableSearch(): static
    {
        $this->setAttribute('search', true);
        return $this;
    }

    /**
     * @return $this
     */
    public function disableSearch(): static
    {
        $this->setAttribute('search', false);
        return $this;
    }

    /**
     * @param int|bool $page
     * @return $this
     */
    public function enablePagination(int|bool $page = false): static
    {
        $this->setAttribute('pagination', true);
        if ($page) {
            $this->setAttribute('pageNumber', $page);
        }
        return $this;
    }

    /**
     * @return $this
     */
    public function disablePagination(): static
    {
        $this->setAttribute('pagination', false);
        return $this;
    }

    /**
     * @param string $column
     * @param string $direction
     * @return $this
     */
    public function sort(string $column, string $direction = 'asc'): static
    {
        $this->setAttribute('sortName', $column);
        $this->setAttribute('sortOrder', $direction);
        return $this;
    }

    /**
     * @return string
     */
    public function getID(): string
    {
        return $this->id;
    }

    /**
     * @param string $action
     * @return $this
     */
    public function setAction(string $action): static
    {
        $this->action = $action;
        return $this;
    }

    /**
     * @param array|string $columns
     * @param string $group
     * @return $this
     */
    public function groupColumns(array|string $columns, string $group): static
    {
        if (is_string($columns)) {
            $columns = explode(',', $columns);
        }
        $this->groups[$group] = $columns;
        return $this;
    }

    /**
     * @return string
     */
    public function render(): string
    {
        $table = PHP_EOL;
        $table .= '<table id="' . $this->id . '" data-toggle="table"';
        foreach ($this->attributes as $attribute) {
            $table .= $attribute->render();
        }
        $table .= '>' . PHP_EOL;
        // header
        $table .= '<thead class="thead-dark">' . PHP_EOL
            . '<tr>' . PHP_EOL;
        if ($this->selectable) {
            $table .= '<th data-field="state" data-checkbox="true"></th>' . PHP_EOL;
        }
        foreach ($this->columns as $column) {
            $table .= $column->render() . PHP_EOL;
        }
        if ($this->action) {
            $table .= '<th data-field="tableActions" data-print-ignore="true" data-formatter="' . $this->action . 'Formatter" data-events="' . $this->action . 'Events">&nbsp;</th>' . PHP_EOL;
        }
        $table .= '</tr>' . PHP_EOL
            . '</thead>' . PHP_EOL;
        // body
        $table .= '<tbody>' . PHP_EOL;
        foreach ($this->rows as $row) {
            $table .= '<tr>' . PHP_EOL;
            foreach ($this->columns as $key => $column) {
                $table .= '<td>' . $row[$key] . '</td>' . PHP_EOL;
            }
            if ($this->action) {
                $table .= '<td></td>' . PHP_EOL;
            }
            $table .= '</tr>' . PHP_EOL;
        }
        $table .= '</tbody>' . PHP_EOL;
        // end table
        $table .= '</table>' . PHP_EOL;
        return $table;
    }

    /**
     * @return string
     */
    public function renderAsJavascript(): string
    {
        $result = $this->render();
        $linii = explode(PHP_EOL, $result);
        $output = "''";
        foreach ($linii as $linie) {
            $output .= "+'" . str_replace("'", "\\'", $linie) . "' " . PHP_EOL;
        }
        return $output;
    }

}

?>