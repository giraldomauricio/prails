<?php

/**
 * Prails is the main file that contains the Core Logic of Prails.
 */

/**
 * Prails class handles all the logic of Prails related to the rendering and base data
 * loading.
 * Prails has the following Render logic/strategy:
 * 1) Controller is instantiated. Controller inherits the model, wich
 *    inherits the core class. Controller has all the required methods
 *    to operate.
 * 2) Controller->Action is invoked and executed. The Action may fill the
 *    _html variable with some content or set a view to render.
 * 3) If the view is available, _html is ignored and its filled with the view.
 * 4) The view uses the information set by the controller.
 *     
 * Prails is deigned to handle either Fat-Controllers or Fat-Models, depends on
 * where you want to put the logic.
 *
 * POST/GET methods
 * 1) Post and Get can be handled by the controller (Postback) or by
 * an API via Ajax (Requires jQuery).
 * 
 * @param string _version Prails version
 * @param string _html HTML code to be rendered
 **/
class prails extends context {

    var $_version = "1.3";
    var $_html = "";
    var $_table = "";
    var $_id = "";
    var $_data_set;
    var $_controller = "";
    var $_action = "";
    var $_view = "";
    var $_private = false;
    var $_layout = "layout";
    var $_assets = array();
    var $_types = array();
    var $_required = array();
    var $_db;
    var $_token;
    var $_cms = false;
    var $_cms_tag = "tag";
    var $_contents;

    /**
    * Index is the default main action when no Controller is declared.
    * The application renders a default HTML.
    *
    * @return void
    */
    public function index() {
        $this->_html = "<div style=\"color:blue\"><strong>Welcome to Prails</strong></div><hr />If you see this message, the application is using the default action and controller.</i>";
        $this->RenderHtml($this->_html);
    }
    
    /**
    * Prails comes with a micro-CMS plug-in to perform on-the-fly replacements based on a
    * key/value pair logic. RunCMS runs on the already processed html prior to be rendered.
    *
    * @return void
    */
    public function RunCMS() {
        foreach ($this->_contents as $key => $value) {
            $this->_html = str_replace("{" . $this->_cms_tag . ":" . $key . ":" . $this->_cms_tag . "}", $value, $this->_html);
        }
        // Cleanup remaining tags
        $this->_html = str_replace("{" . $this->_cms_tag . ":", "<!--", str_replace(":" . $this->_cms_tag . "}", "-->", $this->_html));
    }

    /**
    * Render works directly with the Controller and the private property. On every Render
    * the CMS is called if set active.
    *
    * @return void
    */
    public function Render() {
        $auth_var = AUTHENTICATION_VARIABLE;
        if ($this->_private && !$_SESSION[$auth_var]) {
            if (file_exists(ROOT . "app/views/private/_login.php")) {
                $this->_view = "/../_login";
            } else {
                rescue::ViewRequiresAuthentication($this->_action, $this->_controller);
                exit;
            }
        }
        if (!$this->_view) {
            if ($this->_private) {
                if (file_exists(ROOT . "app/views/public/" . $this->_controller . "/" . $this->_action . ".php")) {
                    $this->_view = $this->_action . ".php";
                }
            } else {
                if (file_exists(ROOT . "app/views/private/" . $this->_controller . "/" . $this->_action . ".php")) {
                    $this->_view = $this->_action . ".php";
                }
            }
        }
        if ($this->_view) {
            ob_start();
            // TODO: refactor public and private locations to set by user
            if ($this->_private)
                include ROOT . "app/views/private/" . $this->_controller . "/" . $this->_view . ".php";
            else
                include include ROOT . "app/views/public/" . $this->_controller . "/" . $this->_view . ".php";
            $this->_html = ob_get_contents();
            ob_end_clean();
        }
        if ($this->_cms)
            $this->RunCMS();
        print $this->_html;
    }

    
    /**
    * Renders the view that corresponds to the action. The name of the view can be provided too.
    * Layouts files are in the form of "_layout_name.php, but is called "layout_name".
    *
    * @param string $view_name Name of the view
    * @param string $layout Use the default layout
    *
    * @return void
    */
    
    public function RenderView($view_name = "", $layout = true) {
        if ($this->_private)
            $folder = "private";
        else
            $folder = "public";
        // If there is no layout, render the raw view
        if (!$layout) {
            $layout_file = ROOT . "app/views/" . $folder . "/" . $this->_controller . "/" . $view_name . ".php";
        } else {
            $layout_file = ROOT . "app/views/" . $folder . "/_" . $this->_layout . ".php";
            if ($view_name != "")
                $this->_view = $view_name;
        }
        // User can render the default view or a specific view.

        if ($layout_file != "" && file_exists($layout_file)) {
            ob_start();
            include $layout_file;
            $this->_html = ob_get_contents();
            ob_end_clean();
            // Digest Prails specific tags:
            $hidden_field = "<input type=\"hidden\" id=\"PRAILS_POST\" name=\"PRAILS_POST\" value=\"TRUE\" />\n";
            $token = $this->Tokenize();
            $hidden_token = "<input type=\"hidden\" id=\"PRAILS_TOKEN\" name=\"PRAILS_TOKEN\" value=\"" . $token . "\" />\n";
            // Add prails tokens if nor present in forms
            if (!strpos($this->_html, "PRAILS_POST") && !strpos($this->_html, "PRAILS_TOKEN"))
                $this->_html = str_replace("</form>", $hidden_field . $hidden_token . "</form>\n", $this->_html);
        }
        if ($this->_cms)
            $this->RunCMS();
        print $this->_html;
    }

    /**
    * Renders the HTML directly ignoring the view or controller. HTML is passed directly to bypass
    * the html created by the controller.
    *
    * @param string $html HTML content
    *
    * @return void
    */
    public function RenderHtml($html) {
        $this->_html = $html;
        if ($this->_cms)
            $this->RunCMS();
        print $this->_html;
    }

    /**
    * ProcessRepeat replaces a repeat block with the specified html.
    *
    * @param string $html HTML content
    *
    * @return string The converted HTML with the replacements removed.
    */
    public function ProcessRepeat($html) {
        $start_tag = "{start_repeat}";
        $end_tag = "{end_repeat}";
        if (strpos($html, $start_tag) && strpos($html, $end_tag)) {
            $html_to_repeat = substr($html, strpos($html, $start_tag) + strlen($start_tag), strpos($html, $end_tag) - strpos($html, $start_tag) - strlen($start_tag));
            return $html_to_repeat;
        }
        if (strpos($html, substr($start_tag, 1, strlen($start_tag) - 1)) && strpos($html, $end_tag) && strpos($html, substr($start_tag, 0, 1)) == 0) {
            $html_to_repeat = substr($html, strpos($html, $start_tag) + strlen($start_tag), strpos($html, $end_tag) - strpos($html, $start_tag) - strlen($start_tag));
            return $html_to_repeat;
        } else
            return $html;
    }

    /**
    * Connects to the database using the driver specified in the configuration, then sets the connection ID. 
    *
    * @return void
    */
    public function Connect() {
        $logger = new logFactory();
        logFactory::log($this, "Connecting to " . DBUSER . "@" . DBSERVER . "/" . DBNAME);
        $this->ID = $this->GetConnectionId(DBSERVER, DBUSER, DBPASSWORD, DBNAME);
    }

    /**
    * @deprecated
    */
    public function DynamicCall() {

        $this->Connect();

        $call = $_REQUEST["doCall"];

        foreach ($_REQUEST as $key => $value) {
            $this->$key = $value;
        }

        if (method_exists($this, $call)) {
            call_user_func(array($this, $call));
        }

        $initial_query_string = $_SERVER["QUERY_STRING"];
        $request = explode("/", $initial_query_string);
        $query_string = $request[2];
        $query_string = str_replace("?", "", $query_string);
        $query_string_array = explode("&", $query_string);
        foreach ($query_string_array as $key_value_pair) {
            $key_value_pair_array = explode("=", $key_value_pair);
            if ($key_value_pair_array[0])
                $this->$key_value_pair_array[0] = $key_value_pair_array[1];
        }
        if (count($query_string_array) == 1)
            $this->id = $query_string_array[0];
    }

    /**
    * Sends the SQL command to the database and loads the record at the same time.
    * For security reasons, the query evaluates if the command does not affects the structure.
    *
    * @return boolean true if the query is executed succesfully, false if returns an error.
    */
    public function QueryAndLoad($sql = "") {
        $logger = new logFactory();
        if (!$sql)
            $sql = $this->sql;
        $this->Query();
        if ($this->Load())
            return true;
        else
            return false;
    }

    /**
    * Sends the SQL command to the database.
    * For security reasons, the query evaluates if the command does not affects the structure.
    *
    * @return boolean true if the query is executed succesfully, false if returns an error.
    */
    public function Query($sql = "") {
        $logger = new logFactory();
        if (!$sql)
            $sql = $this->sql;
        // Validate malicious code is not present:
        if (!strpos(strtolower($sql), "alter table") && !strpos(strtolower($sql), "drop table") && !strpos(strtolower($sql), "create table")) {
            logFactory::log($this, $sql);
            $this->RES = $this->ExecuteQuery($sql);
            return true;
        } else
            return false;
    }

    public function Count() {
        $this->recordCount = $this->GetRowsCount();
        return $this->recordCount;
    }

    public function GetLastId() {
        $this->last_id = $this->GetInsertId();
        return $this->last_id;
    }

    public function Lines() {
        $this->recordCount = $this->GetRowsCount();
        return $this->recordCount;
    }

    public function Affected() {
        return $this->GetRowsAffected();
    }

    public function Load() {
        $this->field = $this->GetRecordObject();
        if ($this->field) {
            foreach ($this->field as $key => $value) {
                $this->$key = $value;
            }
            return true;
        } else
            return false;
    }
    
    public function Reset() {
        $this->ResetRecord();
    }

    public function GetDataSet() {
        $result = array();
        for ($i = 0; $i < $this->GetRowsCount(); $i++) {
            array_push($result, $this->GetRecordObject());
        }
        return $result;
    }

    public function GetParameters() {
        $class_vars = get_class_vars(get_class($this));
        foreach ($class_vars as $name => $value) {
            $this->data[$name] = $_REQUEST[$name];
        }
        return true;
    }

    public function InsertOne() {
        $qb = new query_builder($this);
        $this->sql = $qb->Insert();
        if ($this->Query()) {
            $this->lastID = $this->GetInsertId();
            return $this->lastID;
        } else
            return false;
    }

    public function GetAll() {
        $qb = new query_builder($this);
        $this->sql = $qb->Parse($qb->SelectAll()->From($this->_table));
        return $this->Query();
    }

    public function GetOne($id) {
        $qb = new query_builder($this);
        $this->sql = $qb->SelectAll()->From($this->_table)->Where(array($this->_key => $id));
        return $this->Query();
    }

    public function FindById($id) {
        return $this->GetOne($id);
    }

    public function ParseDelta($delta) {
        return Utils::ParseDelta($delta);
    }

    public function Find($delta, $fields = "*") {
        $qb = new query_builder($this);
        $this->sql = $qb->SelectAll($fields)->From($this->_table)->Where($delta);
        $this->Query();
    }

    public function UpdateOne($id) {
        $qb = new query_builder($this);
        $this->sql = $qb->Update($id);
        return $this->Query();
    }

    public function DeleteOne($id) {
        $qb = new query_builder($this);
        $this->sql = $qb->Delete()->From($this->_table)->Where($this->_key . ":" . $id);
        return $this->Query();
    }

    // TODO: Deprecated
    public function GetInsertValues() {
        $class_vars = get_class_vars(get_class($this));
        foreach ($class_vars as $name => $value) {
            if ($_REQUEST[$name] != "") {
                $keys .= $name . ",";
                if (is_array($_REQUEST[$name]))
                    $values .= $this->Enclose(implode(",", $_REQUEST[$name])) . ",";
                else
                    $values .= $this->Enclose($_REQUEST[$name]) . ",";
            }
        }
        return array(substr($keys, 0, -1), substr($values, 0, -1));
    }

    public function Enclose($data) {
        return Utils::Enclose($data);
    }

    // TODO: Deprecated
    public function GetUpdateValues() {
        $sets = "";
        $class_vars = get_class_vars(get_class($this));
        foreach ($class_vars as $name => $key) {
            if ($_REQUEST[$name] != "") {
                if (is_array($_REQUEST[$name]))
                    $sets .= $name . " = " . $this->Enclose(implode(",", $_REQUEST[$name])) . ",";
                else
                    $sets .= $name . " = " . $this->Enclose($_REQUEST[$name]) . ",";
            }
            if (is_numeric($_REQUEST[$name]) && $_REQUEST[$name] == 0)
                $sets .= $name . " = 0 ,";
        }
        return substr($sets, 0, -1);
    }

    /**
     * (Prails 1.0)<br/>
     * Checks whether a model is valid
     * @link http://prails.com/?/docs/find/isvalid
     * @param None <p>
     * Object is received via POST
     * </p>
     * <p>
     * @return bool true if the model is valid; false otherwise.
     * </p>
     */
    public function IsValid() {
        $result = true;
        foreach ($this->_required as $field) {
            if ($_POST[$field] == "")
                $result = FALSE;
        }
        return $result;
    }

    /**
     * (Prails 1.0)<br/>
     * Redirects the user to the specified location
     * @link http://prails.com/?/docs/find/redirect
     * @param view<p>
     * View to be redirected
     * </p>
     * @param controller (Optional)<p>
     * Controller to call
     * <p>
     * @return new view
     * </p>
     */
    public function Redirect($view, $controller = "") {
        if ($controller == "")
            $controller = $this->_controller;
        header("Location: ?" . $controller . "/" . $view);
    }

    /**
     * (Prails 1.0)<br/>
     * Tokeinized the session each time to validate POST requests
     * @link http://prails.com/?/docs/find/tokenize
     */
    public function Tokenize() {
        $token = sprintf('%04x-%08s-%08s-%04s-%04x%04x', $serverID, $this->clientIPToHex(), substr("00000000" . dechex($t[1]), -8), // get 8HEX of unixtime
                substr("0000" . dechex(round($t[0] * 65536)), -4), // get 4HEX of microtime
                mt_rand(0, 0xffff), mt_rand(0, 0xffff));
        $_SESSION["prails_token"] = $token;
        return $token;
    }

    function clientIPToHex($ip = "") {
        $hex = "";
        if ($ip == "")
            $ip = getEnv("REMOTE_ADDR");
        $part = explode('.', $ip);
        for ($i = 0; $i <= count($part) - 1; $i++) {
            $hex.=substr("0" . dechex($part[$i]), -2);
        }
        return $hex;
    }

    /**
     * (Prails 1.0)<br/>
     * Checks the token
     * @link http://prails.com/?/docs/find/ValidateToken
     * @param token<p>
     * Token to validate
     * </p>
     * <p>
     * @return boolean
     * true if the token is valid
     * </p>
     */
    public function ValidateToken($token) {
        return ($token == $_SESSION["prails_token"]);
    }

    public function DropDown($field_name, $id_field, $label_field, $default = 0) {
        $validator = 0;
        $res = "";
        $selected = "";
        $res .= "<select name=\"" . $field_name . "\" id=\"" . $field_name . "\"";
        if ($this->onChange != "")
            $res .= " onChange=\"" . $this->onChange . "\"";
        $res .= " >\n";
        $res .= "<option value=\"\">Please select...</option>\n";
        while ($this->load()) {
            $validator++;
            if ($this->field->$id_field == $default)
                $selected = " selected";
            else
                $selected = "";
            $res .= "<option value=\"" . $this->field->$id_field . "\"" . $selected . ">" . $this->field->$label_field . "</option>\n";
        }
        $res .= "</select>\n";
        //if($validator==0) return "No records available.";
        if ($validator == 0)
            return "&nbsp;";
        else
            return $res;
    }

    public function GetAssets() {
        $path = (ROOT . "app/assets");
        $objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::SELF_FIRST);
        foreach ($objects as $file => $object) {
            if (is_file($file)) {
                $this->_assets[$object->getFileName()] = $object->getPathName();
            }
        }
    }

    public function OpenAsset($asset) {
        if (count($this->_assets))
            $this->GetAssets();
        if (array_key_exists($asset, $this->_assets)) {
            $fp = fopen($this->_assets[$asset], 'rb');
            header("Content-Type: " . mime_content_type($asset));
            header("Content-Length: " . filesize($name));
            fpassthru($fp);
            exit;
        }
    }

}

?>
