<?php

/**
 * <Create your comment here>
 *
 * $Revision: $
 * $Id: $
 * $Date:  $
 *
 * @Author: $Author: $
 * @version $Revision: $
 */
class ContentClass {

    var $tabs;
    var $html = '';
    var $mode = 'main';

    public function __construct() {
        $this->tabs = array();
    }

    public function execute() {
        global $IP;

        $content = $this;
        $this->mode = $this->getVal('mode', 'main');
        $modefile = "{$IP}/modes/{$this->mode}.php";
        $template = "{$IP}/tpl/{$this->mode}.php";
        if (file_exists($modefile)) {
            include $modefile;
        }
        if (file_exists($template)) {
            include $template;
        }
    }

    public function getVal($value, $default = '') {
        if (isset($_POST[$value])) {
            return $_POST[$value];
        } elseif (isset($_GET[$value])) {
            return $_GET[$value];
        }
        return $default;
    }

    public function addTab($tabName, $options = array(), $active = false) {
        if (!isset($this->tabs[$tabName])) {
            $this->tabs[$tabName] = array(
                'html' => '',
                'options' => $options,
                'active' => $active,
            );
        }
    }

    public function getTab($tab) {
        $html = '';
        if (isset($this->tabs[$tab])) {
            $optionList = '';
            foreach ($this->tabs[$tab]['options'] as $option => $value) {
                $optionList .= "{$option}='{$value}' ";
            }
            $html = "<div class='tab-pane" . ($this->tabs[$tab]['active'] == true ? " active" : "") . "' id='{$tab}' {$optionList}>\n{$this->tabs[$tab]['html']}\n</div>\n";
        }
        return $html;
    }

    public function addHTML($html, $tabName = null) {
        if ($tabName == null) {
            $this->html .= $html;
        } elseif (isset($this->tabs[$tabName]['html'])) {
            $this->tabs[$tabName]['html'] .= $html;
        } else {
            $this->addTab($tabName);
            $this->tabs[$tabName]['html'] .= $html;
        }
    }

    static public function makeAlert($title = 'Warning', $text = 'Warning text', $type = 'block') {
        return "<div class='alert alert-{$type}'>
                <button type='button' class='close' data-dismiss='alert'>×</button>
                <h4>{$title}</h4>
                {$text}
            </div>";
    }

    static public function makeButton($class = array(), $id = '', $text = 'Warning text', $data = false, $tooltip = false) {
        $classes = 'btn ';
        if (count($class) > 0) {
            $classes = $classes . ContentClass::arrayToString($class, 'btn-');
        }
        if ($tooltip !== false) {
            $tooltip_str = " rel='tooltip' " . ContentClass::arrayToStringAssoc($tooltip, 'data-');
        }
        if (count($data) > 0) {
            $data_str = ContentClass::arrayToStringAssoc($data, 'data-');
        }
        return "<button " . ($id != '' ? "id='{$id}'" : "") . " type='button' class='{$classes}' " . ($data !== false ? "{$data_str}" : "") . " " . ($tooltip !== false ? "{$tooltip_str}" : "") . ">{$text}</button>";
    }

    static public function makeDialog($dialogName) {
        global $IP;
//        if (file_exists("{$IP}/inc/{$dialogName}Dialog.php")) {
//
//        }
    }

    static public function arrayToStringAssoc($input, $keyPrefix = '') {
        $strArray = array();
        foreach ($input as $key => $value) {
            $strArray[] = $keyPrefix . $key . "='" . $value . "'";
        }
        return implode(' ', $strArray);
    }

    static public function arrayToString($input, $prefix = '') {
        $strArray = array();
        foreach ($input as $value) {
            $strArray[] = $prefix . $value;
        }
        return implode(' ', $strArray);
    }

    function makeTableHeaders($row) {
        global $table_headers, $allowed_fields;

        $thead = "<thead>\n<tr>\n";
        foreach (get_object_vars($row) as $key => $value) {
            if (in_array($key, $allowed_fields)) {
                if (isset($table_headers[$key])) {
                    $th = $table_headers[$key];
                } else {
                    $th = $key;
                }
                $thead .= "<th class='sortable sort-desc' sort='{$key}'>{$th}</th>\n";
            }
        }
        $thead .= "</tr>\n</thead>\n";
        return $thead;
    }

    function makeTableRow($row) {
        global $allowed_fields;

        // Яндекс.Каталог
        if ($row->yc == null) {
            $row->yc = '?';
        } elseif ($row->yc == '-') {
            $row->yc = 'Не описан';
        } else {
            $row->yc = "<a href='http://yaca.yandex.by/yca/cy/ch/{$row->domain}' target='_blank' class='yaca_tooltip' rel='tooltip' data-placement='top' data-original-title='{$row->yc}'>Каталог</a>";
        }
        // Проверка на клей
        if ($row->glue == 'true') {
            $class = 'error';
            $row->glue = "<a href='#' class='a_tooltip' rel='tooltip' data-placement='left' data-original-title='Приклеен к домену:{$row->gluedom}.'>клей</a>";
        } else {
            $class = '';
            $row->glue = '';
        }
        $rows = "<tr class='{$class}'>\n";
        foreach (array_keys(get_object_vars($row)) as $field) {
            if (in_array($field, $allowed_fields)) {
                $rows .= "<td>{$row->$field}</td>\n";
            }
        }
        $rows .="</tr>\n";
        return $rows;
    }

}