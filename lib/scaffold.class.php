<?php

class Scaffold {

    public static function GenerateList($controller_name, $visual_output = true, $private = false, $force = false) {

        if (class_exists($controller_name)) {

            ($private) ? $views_folder = ROOT . "app/views/private/" . $controller_name : $views_folder = ROOT . "app/models/public/" . $controller_name . "/";

            $temp_class = new $controller_name;

            $class_vars = get_class_vars(get_class($temp_class));

            $html = "<div><a href=\"?" . $controller_name . "/" . $controller_name . "_new\">Create</a></div>\n";

            $html .= "<table>\n";

            $html .= "<thead>\n";
            $html .= "<tr>\n";
            foreach ($class_vars as $name => $value) {
                $html .= "<th>" . $name . "</th>\n";
            }
            $html .= "<th>&nbsp;</th>\n";
            $html .= "<th>&nbsp;</th>\n";
            $html .= "</tr>\n";
            $html .= "</thead>\n";

            $html .= "<tbody>\n";
            $html .= "<tr>\n";
            foreach ($class_vars as $name => $value) {
                $html .= "<td><" . "?=" . "$" . "this->" . $name . "?" . "></td>\n";
            }
            $html .= "<td><a href=\"?" . $controller_name . "/" . $controller_name . "_edit/<?=$this->ID?>\">Edit</a></td>\n";
            $html .= "<td><a href=\"?" . $controller_name . "/" . $controller_name . "_delete/<?=$this->ID?>\">Delete</a></td>\n";

            $html .= "</tr>\n";
            $html .= "</tbody>\n";

            $html .= "</table>";

            if ($visual_output)
                return $html;
            else {
                $list_view = $views_folder . $controller_name . "_list.php";
                if (!file_exists($list_view))
                    file_put_contents($list_view, $html);
                else if (file_exists($list_view) && $force)
                    file_put_contents($list_view, $html);
                else
                    rescue::ViewAlreadyExists();
            }
        }
    }

    public static function GenerateForm($controller_name, $visual_output = true, $private = false, $force = false) {

        if (class_exists($controller_name)) {

            ($private) ? $views_folder = ROOT . "app/views/private/" . $controller_name : $views_folder = ROOT . "app/models/public/" . $controller_name . "/";

            $temp_class = new $controller_name;

            $class_vars = get_class_vars(get_class($temp_class));

            $html = "<div><a href=\"?" . $controller_name . "/" . $controller_name . "_list\">List</a></div>\n";

            $html .= "<form method=\"post\">\n";

            foreach ($class_vars as $name => $value) {
                $html .= "<".""."?= $".""."this->htmlControl->TextField(\"".$name."\", \"".$name."\"); ?>\n";
            }

            $html .= "<input type=\"submit\" name=\"submit\" value=\"Adicionar ubicaci&oacute;n\" />\n";
            
            $html .= "</form>";

            if ($visual_output)
                return $html;
            else {
                $list_view = $views_folder . $controller_name . "_edit.php";
                if (!file_exists($list_view))
                    file_put_contents($list_view, $html);
                else if (file_exists($list_view) && $force)
                    file_put_contents($list_view, $html);
                else
                    rescue::ViewAlreadyExists();
            }
        }
    }

}
