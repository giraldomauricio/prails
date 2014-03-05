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
 * @autor Mauricio Giraldo Mutis <mgiraldo@gmail.com>
 * 
 * */
class prails extends context {

    /**
     * Prails Version
     *
     * @var string
     */
    var $_version = "1.3";

    /**
     * Storage of the HTML contents about to be rendered to the browser
     *
     * @var string
     */
    var $_html = "";

    /**
     * Name of the table from the model
     *
     * @var string
     */
    var $_table = "";

    /**
     * Database identifier for the primary key
     *
     * @var integer
     */
    var $_key = "";

    /**
     * Database identifier for a record
     *
     * @var integer
     */
    var $_id = "";

    /**
     * Complete DataSet returned by the database when a query is executed
     *
     * @var Object
     */
    var $_data_set;

    /**
     * Controller name
     *
     * @var string
     */
    var $_controller = "";

    /**
     * Action name
     *
     * @var string
     */
    var $_action = "";

    /**
     * View name
     *
     * @var string
     */
    var $_view = "";

    /**
     * Indicator if the action renders a private page
     *
     * @var boolean
     */
    var $_private = false;

    /**
     * Indicator if the action renders a Prails backend page
     *
     * @var boolean
     */
    var $_backend = false;

    /**
     * Name of the layout used as a base to render a view
     *
     * @var string
     */
    var $_layout = "layout";

    /**
     * List of the available assets in an associative array
     *
     * @var array
     */
    var $_assets = array();

    /**
     * List of the assets that need to be loaded before any other one. This is useful if you are using jQuery.
     *
     * @var array
     */
    var $_assets_priority = array("_jquery.js", "_jquery_tools.min.js", "jquery.jqplot.min.js");

    /**
     * Database name
     *
     * @var string
     */
    var $_db;

    /**
     * Security token to validate that the requests come from the same session
     *
     * @var string
     */
    var $_token;

    /**
     * Indicates if the Renderers will use the CMS
     *
     * @var boolean
     */
    var $_cms = false;

    /**
     * Enclosing tag used by the CMS
     *
     * @var string
     */
    var $_cms_tag = "tag";

    /**
     * Associative array with the contents to be replaced by the CMS
     *
     * @var AssociativeArray
     */
    var $_contents;

    /**
     * List of error generated by the application itself
     *
     * @var ObjectArray
     */
    var $_errors = array();

    /**
     * Variable to hold the sql queries
     *
     * @var String
     */
    var $sql = "";

    /**
     * Variable to hold the sql queries
     *
     * @var String
     */
    var $htmlControl;

    /**
     * Variable to hold the page where you redirect users when need to login
     *
     * @var String
     */
    var $private_login_page = "login";

    /**
     * Maximum number of records to load in a DataSet to prevent memory leaks
     *
     * @var Int
     */
    var $max_dataset_size = 1000;

    /**
     * Index is the default main action when no Controller is declared.
     * The application renders a default HTML.
     *
     * @return void
     */
    public function index() {
        $this->_html = "<div style=\"color:blue\"><strong>Welcome to Prails</strong></div><hr /><p><strong>Congratulations, you have Prails Up and Running!</strong></p>If you see this message, the application is using the default action and controller.</i>";
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
            if (file_exists(ROOT . "app/views/private/" . $this->private_login_page . ".php")) {
                $this->_view = ROOT . "app/views/private/" . $this->private_login_page . ".php";
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
            } else if ($this->_backend) {
                if (file_exists(ROOT . "app/views/_prails/" . $this->_controller . "/" . $this->_action . ".php")) {
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
            if ($this->_private) {
                $view_to_include = ROOT . "app/views/private/" . $this->_controller . "/" . $this->_view . ".php";
            } else if ($this->_backend) {
                $view_to_include = ROOT . "app/views/_prails/" . $this->_controller . "/" . $this->_view . ".php";
            } else {
                $view_to_include = ROOT . "app/views/public/" . $this->_controller . "/" . $this->_view . ".php";
            }
            if (file_exists($view_to_include))
                include $view_to_include;

            $this->_html = ob_get_contents();
            ob_end_clean();
        }
        if ($this->_cms)
            $this->RunCMS();
        print $this->_html;
    }

    /**
     * Renders the assets inclussion for Javascripts and StyleSheets
     *
     * @return string The assets headers
     */
    public function RenderAssets($assets_location = "") {

        $headers = "";

        foreach ($this->_assets_priority as $name) {
            //$headers .= "<script src=\"" . $public_path . "\"></script>\n";
        }

        $this->GetAssets($assets_location);

        foreach ($this->_assets as $name => $path) {
            $public_path = str_replace(ROOT . "app/", "app/", $path);
            if (strpos(strtolower($public_path), ".css")) {
                $headers .= "<link rel=\"stylesheet\" media=\"screen\" href=\"" . $public_path . "\" />\n";
            }
            if (strpos(strtolower($public_path), ".eot") || strpos(strtolower($public_path), ".svg") || strpos(strtolower($public_path), ".ttf") || strpos(strtolower($public_path), ".woff")) {
                $headers .= "<link rel=\"stylesheet\" media=\"screen\" href=\"" . $public_path . "\" />\n";
            }
            if (strpos(strtolower($public_path), ".js")) {
                $headers .= "<script src=\"" . $public_path . "\"></script>\n";
            }
        }
        print $headers;
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
        elseif ($this->_backend)
            $folder = "_prails";
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
        if (!$layout_file != "" || !file_exists($layout_file)) {
            $layout_file = ROOT . "app/views/" . $folder . "/" . $this->_controller . "/" . $this->_view . ".php";
        }

        ob_start();
        include $layout_file;
        $this->_html = ob_get_contents();
        ob_end_clean();

        $hidden_field = "<input type=\"hidden\" id=\"PRAILS_POST\" name=\"PRAILS_POST\" value=\"TRUE\" />\n";
        $token = $this->Tokenize();
        $hidden_token = "<input type=\"hidden\" id=\"PRAILS_TOKEN\" name=\"PRAILS_TOKEN\" value=\"" . $token . "\" />\n";
        // Add prails tokens if nor present in forms
        if (!strpos($this->_html, "PRAILS_POST") && !strpos($this->_html, "PRAILS_TOKEN"))
            $this->_html = str_replace("</form>", $hidden_field . $hidden_token . "</form>\n", $this->_html);

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

    public function DynamicCall() {

        $this->htmlControl = new PrailsHtmlControls();
        $this->htmlControl->object = $this;

        $this->Connect();

        foreach ($_REQUEST as $key => $value) {
            $this->$key = stripslashes($value);
        }

        if (isset($_REQUEST["doCall"]) && method_exists($this, $_REQUEST["doCall"])) {
            call_user_func(array($this, $_REQUEST["doCall"]));
        }

        $initial_query_string = $_SERVER["QUERY_STRING"];
        $request = explode("/", $initial_query_string);
        if (isset($request[2]))
            $query_string = $request[2];
        else
            $query_string = "";
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
     * @param string $sql Optional The query to execute
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
     * @param string $sql Optional The query to execute
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

    /**
     * Counts the number of records in the recordset
     *
     * @return int The number of records
     */
    public function Count() {
        $this->recordCount = $this->GetRowsCount();
        return $this->recordCount;
    }

    /**
     * Returns the ID (Primary Key) of the last inserted records from the current session
     *
     * @return int The record ID (Primary Key)
     */
    public function GetLastId() {
        $this->last_id = $this->GetInsertId();
        return $this->last_id;
    }

    /**
     * Returns the number of records affected by the last query of the current session
     *
     * @return int The number of records affected
     */
    public function Affected() {
        return $this->GetRowsAffected();
    }

    /**
     * Tries to load the next available record in the recordset.
     * At the same time, the object is loaded with the fields in the corresponding properties.
     *
     * @return boolean True if the record was loaded, false if there are no more records to load
     */
    public function Load() {
        $this->field = $this->GetRecordObject();
        if ($this->field) {
            foreach ($this->field as $key => $value) {
                $this->$key = $value;
            }
            $this->htmlControl->object = $this;
            return true;
        } else
            return false;
    }

    /**
     * Returns the recorset pointer to the beginning
     *
     * @return void
     */
    public function Reset() {
        $this->ResetRecord();
    }

    /**
     * Returns the entire recordset in a single object
     *
     * @return Object The recordset
     */
    public function GetDataSet() {
        $result = array();
        $dataset_counter = 0;
        while (($row = $this->GetRecordObject()) && ($dataset_counter <= $this->max_dataset_size)) {
            array_push($result, $row);
            $dataset_counter++;
        }
        return $result;
    }

    /**
     * Inserts one record into the database based on the input values
     *
     * @return boolean True if the query was successful
     */
    public function InsertOne() {
        $qb = new query_builder($this);
        $this->sql = $qb->Insert();
        if ($this->Query()) {
            $this->lastID = $this->GetInsertId();
            return $this->lastID;
        } else
            return false;
    }

    /**
     * Returns all the records of the active model
     *
     * @return Object The poiter to the recordset
     */
    public function GetAll() {
        $qb = new query_builder($this);
        $this->sql = $qb->Parse($qb->SelectAll()->From($this->_table));
        return $this->Query();
    }

    /**
     * Returns the record where the key matches the valie provided
     *
     * @param int $id The id (Primary Key) of the record
     * 
     * @return Object The recordset
     */
    public function GetOne($id = "") {
        if ($id == "")
            $id = $this->_id;
        $qb = new query_builder($this);
        $this->sql = $qb->SelectAll()->From($this->_table)->Where(array($this->_key => $id));
        return $this->Query();
    }

    /**
     * Alias of GetOne
     *
     * @param int $id The id (Primary Key) of the record
     * 
     * @return Object The recordset
     */
    public function FindById($id) {
        return $this->GetOne($id);
    }

    /**
     * Finds a record based on a delta definition
     *
     * @param string $delta The delta of fields to find. Deltas are defines the following way: id:1, name:"John"
     * @param string $fields Optional The fields to select in the query
     * 
     * @return void
     */
    public function Find($delta, $fields = "*") {
        $qb = new query_builder($this);
        $this->sql = $qb->SelectAll($fields)->From($this->_table)->Where($delta);
        $this->Query();
    }

    /**
     * Updates one record into the database based on the input values and the id (Primary key) specified
     *
     * @param int The id (Primary key) of the record to update 
     * 
     * @return boolean True if the query was successful
     */
    public function UpdateOne($id) {
        $qb = new query_builder($this);
        $this->sql = $qb->Update($id);
        return $this->Query();
    }

    /**
     * Deletes one record into the database based on the id (Primary key) specified
     *
     * @param int The id (Primary key) of the record to update 
     * 
     * @return boolean True if the query was successful
     */
    public function DeleteOne($id) {
        $qb = new query_builder($this);
        $this->sql = $qb->Delete()->From($this->_table)->Where($this->_key . ":" . $id);
        return $this->Query();
    }

    /**
     * Checks if a model is valid based on the constraints and requirements
     * 
     * @return boolean True if the model is valid
     */
    public function IsValid() {
        $result = true;
        // Check requirements
        $required = explode(",", $this->_required);
        foreach ($required as $field) {
            if ($_REQUEST[$field] == "") {
                $this->Error("Model Validation Error: Requirement", $field . " is required.", "Required", $field);
                $result = FALSE;
            }
        }
        // Check constraints
        $constraints = Utils::ParseDelta($this->_constraints);
        foreach ($constraints as $field => $contraint) {
            $field = trim($field);
            $contraint = trim($contraint);
            if ($contraint != "required") {
                if (gettype($_REQUEST[$field]) != $contraint) {
                    $this->Error("Model Validation Error: Constraint", $field . " has to be " . $contraint . ". Given value is [" . $_REQUEST[$field] . "], wich is " . gettype($_REQUEST[$field]), $field);
                    $result = FALSE;
                }
            } else {
                if ($_REQUEST[$field] == "" && $contraint == "required") {
                    $this->Error("Model Validation Error: " . $field . " cannot be empty.", "Required", $field);
                    $result = FALSE;
                }
            }
        }
        return $result;
    }

    /**
     * Redirects the call to a different view/controller.
     * 
     * @param string $view The name of the view to render
     * @param string $controller The name of the controller to use. If no value is given, the current controller is used
     * @id int Id of the record to retrieve
     * 
     * @return boolean True if the model is valid
     */
    public function Redirect($view, $controller = "", $id = 0, $extra_params = "") {
        if ($controller == "")
            $controller = $this->_controller;
        if (!$id)
            $header = "Location: ?" . $controller . "/" . $view;
        else if ($extra_params != "" && $id)
            $header = "Location: ?" . $controller . "/" . $view . "/?id=" . $id . "&" . $extra_params;
        else if ($extra_params != "" && !$id)
            $header = "Location: ?" . $controller . "/" . $view . "/?" . $extra_params;
        else
            $header = "Location: ?" . $controller . "/" . $view . "/" . $id;
        header($header);
    }

    /**
     * Creates a session token used to validate that a request come from the same source
     * 
     * @return string Token
     */
    public function Tokenize() {
        $token = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
                // 32 bits for "time_low"
                mt_rand(0, 0xffff), mt_rand(0, 0xffff),
                // 16 bits for "time_mid"
                mt_rand(0, 0xffff),
                // 16 bits for "time_hi_and_version",
                // four most significant bits holds version number 4
                mt_rand(0, 0x0fff) | 0x4000,
                // 16 bits, 8 bits for "clk_seq_hi_res",
                // 8 bits for "clk_seq_low",
                // two most significant bits holds zero and one for variant DCE1.1
                mt_rand(0, 0x3fff) | 0x8000,
                // 48 bits for "node"
                mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
        $_SESSION["prails_token"] = $token;
        return $token;
    }

    /**
     * Validates if a token is valid based on the current session
     * 
     * @param string $token The token to validate
     * 
     * @return boolean True if the token is valid, False if not
     */
    public function ValidateToken($token) {
        return ($token == $_SESSION["prails_token"]);
    }

    /**
     * Get the application assets and stores the paths in an array
     * 
     * @return void
     */
    public function GetAssets($assets_root = "") {

        if ($assets_root == "") {
            $assets_root = "app/assets";
        }

        if (is_dir(ROOT . $assets_root)) {

            $path = (ROOT . $assets_root);
            $objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::SELF_FIRST);
            foreach ($objects as $file => $object) {
                if (is_file($file)) {
                    $this->_assets[$object->getFileName()] = $object->getPathName();
                }
            }
        }

        // Reorder based on the priority
        $high_priority = array();
        $lower_priority = array();

        if (count($this->_assets_priority) > 0) {

            foreach ($this->_assets_priority as $priority_asset) {
                if (isset($this->_assets[$priority_asset]))
                    array_push($high_priority, $this->_assets[$priority_asset]);
            }



            $lower_priority = array_diff($this->_assets, $high_priority);


            $prioritized_assets = array_merge($high_priority, $lower_priority);
            $this->_assets = $prioritized_assets;
        }
    }

    /**
     * Returns the asset no matter the location
     * 
     * @return Stream The asset called
     */
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

    /**
     * Stores Prails specific errors in an Object array
     * 
     * @param string $message The text to store
     * @param string $detail Optional Additional information about the error
     * 
     * @return void
     */
    public function Error($message, $detail = "*", $field = "") {
        $logger = new logFactory();
        $error = new PrailsErrors();
        $error->class = __CLASS__;
        $error->file = __FILE__;
        $error->line = __LINE__;
        $error->method = __METHOD__;
        $error->message = $message;
        $error->detail = $detail;
        $error->field = $field;
        array_push($this->_errors, $error);
        logFactory::error(__CLASS__, $message);
    }

    /**
     * Displays Errors
     * 
     * @return string The Errors in HTML Format
     */
    public function DisplayErrors() {
        foreach ($this->_errors as $error) {
            if ($error->message != "")
                $errors .= "&raquo;" . $error->message . ":" . $error->detail . "<br/>";
        }
        return $errors;
    }
    
    /**
     * Serialized data to Json
     * 
     * @return string Json equivalent
     */
    public function ToJson($data) {
        header('Content-type: application/json');
        print json_encode($data);
    }
    
    /**
     * Serializes the current DataSet to Json
     * 
     * @return string Json equivalent of the Dataset
     */
    public function DataSetToJson() {
        header('Content-type: application/json');
        print json_encode($this->GetDataSet());
    }

}

?>