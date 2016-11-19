<?php

if (isset($_POST["new"])) {
//    if ($_POST["new"] == "flight_search") { //Ajax points to this, should have an if statement to determine which to point to.
//        get_flight_data();
//    }
    if (captcha()) {
        register_user($_POST["userName"], $_POST["password"], $_POST["email"]);
    }
}

if (isset($_POST["login"])) {
    login_user($_POST["userName"], $_POST["password"]);
}


/* Checks the airport code from the CML database against the one from the .csv file, then returns the airport's city from the .csv file.  */

function add_city($row) {
    if (($handle = fopen("Cities.csv", "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
//            $num = count($data);
            if ($row['OFFPOINT_T_PT_PORT_CODE'] === $data[0]) {
                $city = $data[2];
                fclose($handle);
                return $city;
            }
        }
        fclose($handle);
        return "Not specified";
    }
}

/* Checks the airport code from the CML database against the one from the .csv file, 
 * then returns the city's latitude and longitude from the .csv file.  */

function add_city_specifics($row) {
    $map_location = [];
    if (($handle = fopen("City specifics.csv", "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
//            $num = count($data);
            if ($row['OFFPOINT_T_PT_PORT_CODE'] === $data[4]) {//$data[4] is the IATA/FAA (basically the airport code). Ccolumn E in the file, 'City specifics.csv'.
                $map_location[0] = $data[6]; //$data[6] is the latiitude. Column G in the file, 'City specifics.csv'.
                $map_location[1] = $data[7]; //$data[7] is the longitude. Column H in the file, 'City specifics.csv'.
                return $map_location;
            }
        }
        fclose($handle);
        return "Not specified";
    }
}

/* Returns a connection to the MySQl database */

function DBConnect_mySQL() {
//    $conn = new PDO('mysql:host=mysql.cms.gre.ac.uk;dbname=mdb_om342;charset=utf8','om342','om342');
//    try {
//        $db_dsn = "mysql:";
//        $db_dsn .= "unix_socket=/tmp/db/mysql.ghost.sock";
//        $db_dsn .= ";dbname=";
//        $db_dsn .= "mdb_om342";
//        ini_set('max_execution_time', 180);
//        $db = new PDO($db_dsn, 'root', '');
//        $db->setAttribute(PDO :: ATTR_ERRMODE, PDO :: ERRMODE_EXCEPTION);
//        return $db;
//    } catch (Exception $e) {
//        die("Error : " . $e->getMessage());
//    }
// Create connection
    $conn = mysqli_connect("mysql.cms.gre.ac.uk", "om342", "om342", "mdb_om342");
// Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    return $conn;
}

function sql() {
    $conn = mysqli_connect("mysql.cms.gre.ac.uk", "om342", "om342", "mdb_om342");
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    return $conn;
}

function captcha() {
    session_start();
    if (isset($_POST["captcha"]) && $_POST["captcha"] != "" && $_SESSION["code"] == $_POST["captcha"]) {
        echo "Correct Code Entered <br/>";
        return true;
//Do you stuff
    } else {
        die("Wrong Code Entered");
        return false;
    }
}

function register_user($username, $password, $email_address) {
    $conn = sql();
    $sql = "INSERT INTO reg_users VALUES ('DEFAULT','$username','$password','$email_address')";
//        $result = $conn->query("INSERT INTO reg_users VALUES ('$username','$password','$email_address' ");//The function, select_query($i), uses the returned value to create a 'INSERT INTO SELECT' statement to copy data from UAT_CARRIER_INFO into UAT_RECORDS.
    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

function login_user($username, $password) {
    $conn = sql();
    $result = $conn->query("Select * from reg_users where Username='$username' and Password_='$password'");
    if ($result) {
        echo "Login was successful!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

/* Deletes everything from the UAT_RECORDS table in MySQL. */

function clear_record_count($environment) {
    $conn = DBConnect_mySQL();
    $result = $conn->query("DELETE FROM " . $environment . "_records;");
    $result->closeCursor();
}

/* Deletes everything from the UAT_CARRIER_INFO table in MySQL. */

function clear_carrier_info($environment) {
    $conn = DBConnect_mySQL();
    $result = $conn->query("DELETE FROM " . $environment . "_carrier_info;");
    $result->closeCursor();
}

/* Deletes everything from the UAT_FLIGHT_INFO table in MySQL. */

function clear_flight_info($environment) {
    $conn = DBConnect_mySQL();
    $result = $conn->query("DELETE FROM " . $environment . "_flight_info;");
    $result->closeCursor();
}

/* Deletes everything from the UAT_REGULATORY_INFO table in MySQL. */

function clear_reg_info($environment) {
    $conn = DBConnect_mySQL();
    $result = $conn->query("DELETE FROM " . $environment . "_regulatory_info;");
    $result->closeCursor();
}

/* Deletes everything from the UAT_CODESHARE table in MySQL. */

function clear_codeshare_info($environment) {
    $conn = DBConnect_mySQL();
    $result = $conn->query("DELETE FROM " . $environment . "_codeshare;");
    $result->closeCursor();
}

/* Adds new records to the UAT_RECORDS table in MySQL. */

function update_record_count($environment) {
    $conn = DBConnect_mySQL();
    for ($i = 0; $i < 6; $i++) {
        $result = $conn->query("INSERT INTO " . $environment . "_RECORDS (Carrier,Record_type, Record_count)" . select_query($i, $environment)); //The function, select_query($i), uses the returned value to create a 'INSERT INTO SELECT' statement to copy data from UAT_CARRIER_INFO into UAT_RECORDS.
        $result->closeCursor();
    }
}

/* Depending on the integer passed, it will return a specific SELECT statement. This specifies exactly what data needs to be copied from UAT_CARRIER_INFO to UAT_RECORDS. */

function select_query($i, $environment) {
    switch ($i) {
        case 0:
            $query = "SELECT Carrier, 'Han_station' ,  Count(distinct Station,Handler_code, Handler_dcs) 'Handled' FROM " . $environment . "_carrier_info WHERE Handler_dcs='1A' AND Handler_code != 'Not specified' group by Carrier;";
            return $query;
        case 1:
            $query = "SELECT Carrier, 'notHan_station' ,  Count(distinct Station,Handler_code, Handler_dcs) 'Not Handled' FROM " . $environment . "_carrier_info WHERE Handler_dcs!='1A' AND Handler_code != 'Not specified' group by Carrier;";
            return $query;
        case 2:
            $query = "SELECT  Handler_code, 'c_Han' ,  Count(distinct Carrier,Station,Handler_code, Handler_dcs) 'c_Handler_' FROM " . $environment . "_carrier_info WHERE Carrier!=Handler_code AND Handler_code != 'Not specified' group by Handler_code;";
            return $query;
        case 3:
            $query = "SELECT  Carrier, 'Han' ,  Count(distinct Station,Handler_code, Handler_dcs) 'Handler' FROM " . $environment . "_carrier_info WHERE Carrier!=Handler_code AND Handler_code != 'Not specified' group by Carrier;";
            return $query;
        case 4:
            $query = "SELECT DISTINCT Carrier, 'Reg' , Count(distinct Station)  FROM " . $environment . "_regulatory_info GROUP BY Carrier;";
            return $query;
        case 5:
            $query = "SELECT DISTINCT Carrier, 'Code' , Count(Distinct Marketing_Carrier) FROM " . $environment . "_codeshare WHERE Marketing_Carrier != Carrier GROUP BY Carrier;";
            return $query;
    }
}

/* Checks if a record type (e.g. Handled,Codeshare etc.) exists for a carrier and displays the total, if it doesn't exist, 
 * the total for that record is displayed as 0. */

function get_record_count($carrier_code, $environment) {
    $conn = DBConnect_mySQL();
    $result = $conn->query("SELECT Carrier,
    SUM(CASE WHEN Record_type = 'Han_station' THEN Record_count ELSE 0 END) Handled,
    SUM(CASE WHEN Record_type = 'notHan_station' THEN Record_count ELSE 0 END) 'Not_Handled',
    SUM(CASE WHEN Record_type = 'c_Han' THEN Record_count ELSE 0 END) 'c_Handler_',
    SUM(CASE WHEN Record_type = 'Reg' THEN Record_count ELSE 0 END) 'Destinations',
    SUM(CASE WHEN Record_type = 'Han' THEN Record_count ELSE 0 END) 'Handler_',
    SUM(CASE WHEN Record_type = 'Code' THEN Record_count ELSE 0 END) 'Codeshare'
    FROM " . $environment . "_records WHERE Carrier = '$carrier_code'  GROUP BY Carrier ORDER BY Carrier ASC;");
    $row = $result->fetchAll(PDO::FETCH_ASSOC);
    if (count($row) < 1) {
        $row2 = array_fill(0, 5, 0);
        echo json_encode($row2);
        exit();
    } else {
        while ($row) {
            echo json_encode($row);
            exit();
        }
    }
    $result->closeCursor();
}

/* Returns a populated table with the requested data from MySQL */

function get_cache_data() {
    $count = 0;
    $conn = DBConnect_mySQL(); //Stores connection from MySQL.
    $and = build_cache_query(); //Determines what need to be in the query (e.g. columns, table, 'where' clause/ 'and' operator etc.). 
    $columns = $and[4]; //Outputs the appropiate columns from the selected filter (e.g. Operating Carrier, Marketing Carrier for Codeshare partners).
    $table = $and[5]; //Outputs the appropiate table from the selected filter (e.g. UAT_CODESHARE for Codeshare partners).
    $table_header = $and[6]; //Outputs the appropiate table headers from the selected filter (e.g. <tr><th>Operating Carrier</th><th>Marketing Carrier</th></tr> for Codeshare partners).
    $table_class = $and[7]; //Changes the class of the tables for the Regulatory Impact view. 
    $a = '';
    $view = $_POST["View"];
    if ($view === 'Reg') {
        $a = "class='regg'";
    } else {
        $a = "class='normalz'";
    }
//    echo "SELECT $columns from $table $and[0] $and[1] $and[2] $and[3]";
    $result = $conn->query("SELECT DISTINCT $columns from $table $and[0] $and[1] $and[2] $and[3]");
    echo "<div $a><table class='tablesorter table-hover $table_class' id='carrierTable' align='center'>" . $table_header . "<tbody>";
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
//        $count++;
        echo"<tr id='low_level_view'>";
        foreach ($row as $item) { //each column adds data
            echo "<td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n"; //Adds the data to each column
        }
        if (isset($row['Handler_dcs'])) {
            add_details_MySQL($row); //Adds the 'System name' and 'Handled' column to the table (the 'Handled' column displays ✓ or X).
        }
        echo"</tr>";
    }
    echo "</tbody></table></div>";
    $result->closeCursor();

//    echo "<p style='color:white;'>Total row count is $count <p>";//Good to check the count is correct.
}

/* Returns data to be used for the map in the Destinations with Regulatory Impact view. */

function get_map_data() {
    $conn = DBConnect_mySQL();
    $carrier_code = $_POST["CC"];
    $environment = $_POST["environment"];

    if (isset($_POST["AC"])) {
        $airport_code = $_POST["AC"];
    }

    if ($airport_code !== '') {
        $ac = "AND Station='$airport_code'";
    } else {
        $ac = "";
    }
//    $and = build_cache_query(); //Determines what need to be in the query (e.g. columns, table, 'where' clause/ 'and' operator etc.). 
//    $table = $and[5];
//    $table_header = $and[6]; //Outputs the appropiate table headers from the selected filter.
//    echo "SELECT $columns from $table $and[0] $and[1] $and[2] $and[3]";
    $result = $conn->query("SELECT DISTINCT * from " . $environment . "_REGULATORY_INFO WHERE Carrier='$carrier_code' $ac ");
    if ($result) {
        while ($row = $result->fetchAll()) {
            echo json_encode($row);
            exit();
        }
        $result->closeCursor();
    }
    echo "</tbody></table>";
    $result->closeCursor();
}

/* Returns flight data to be displayed, when a user clicks on a table row in the low-level view */

function get_flight_data() {
    $carrier_code = $_POST["CC"];
    $airport_code = $_POST["AC"];
    $environment = $_POST["environment"];
    $conn = DBConnect_mySQL();
    $result = $conn->query("SELECT Carrier, Station, Departure, Count(Departure) 'No of flights' FROM " . $environment . "_flight_info WHERE Carrier = '$carrier_code' AND Station = '$airport_code' GROUP BY Station,Departure");
    if ($result) {
        while ($row = $result->fetchAll()) {
            $row['userName'];
            echo json_encode($row);
            exit();
        }
        $result->closeCursor();
    }
}

/* Inserts data into the UAT_CARRIER_INFO table */

function insert_carrier_data($row, $peak, $selection, $date) {
    $conn = DBConnect_mySQL();
    $sth = $conn->prepare("INSERT INTO " . $selection . "_CARRIER_INFO VALUES (:carrier, :station, :handler_code, :handling_dcs, :peak, :date)", array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $sth->execute(array(':carrier' => $row['OPERATING_T_RF_CARRIER_CODE'], ':station' => $row['BOARDPOINT_T_PT_PORT_CODE'], ':handler_code' => $row['FLIGHT_HANDLING_CARRIER'], ':handling_dcs' => $row['FLIGHT_HANDLING_SYSTEM'], ':peak' => $peak, ':date' => $date));
    unset($conn);
}

/* Inserts data into the UAT_REGULATORY_INFO table */

function insert_regulatory_data($row, $peak, $selection, $date) {
    $conn = DBConnect_mySQL();
    $city = add_city($row);
    $city_specifics = add_city_specifics($row);
    if ($city_specifics[0] !== 'N' && $city_specifics[1] !== 'N') {
//    echo "Latitude is $city_specifics[0]<br>";   
//    echo "Logitude is $city_specifics[1]<br>"; 
        $sth = $conn->prepare("INSERT INTO " . $selection . "_REGULATORY_INFO  VALUES  (:carrier, :station, :city, :latitude,:longitude,:peak, :date)", array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':carrier' => $row['OPERATING_T_RF_CARRIER_CODE'], ':station' => $row['OFFPOINT_T_PT_PORT_CODE'], ':city' => $city, ':latitude' => $city_specifics[0], ':longitude' => $city_specifics[1], ':peak' => $peak, ':date' => $date));
    }
    unset($conn);
}

/* Inserts data into the UAT_CODESHARE table */

function insert_codeshare_data($row, $peak, $selection, $date) {
    $conn = DBConnect_mySQL();
    $sth = $conn->prepare("INSERT INTO " . $selection . "_CODESHARE VALUES (:carrier, :m_carrier, :peak, :date)", array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $sth->execute(array(':carrier' => $row['OPERATING_T_RF_CARRIER_CODE'], ':m_carrier' => $row['MARKETING_T_RF_CARRIER_CODE'], ':peak' => $peak, ':date' => $date));
    unset($conn);
}

/* Inserts data into the UAT_FLIGHT_INFO table */

function insert_flight_data($row, $peak, $selection, $date) {
    $conn = DBConnect_mySQL();
    $sth = $conn->prepare("INSERT INTO " . $selection . "_FLIGHT_INFO VALUES (:carrier, :station, :departure, :peak, :date)", array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $sth->execute(array(':carrier' => $row['OPERATING_T_RF_CARRIER_CODE'], ':station' => $row['BOARDPOINT_T_PT_PORT_CODE'], ':departure' => $row["TO_CHAR(LOCAL_FLIGHT_STD_DEP_DATE,'YYYY-MM-DD')"], ':peak' => $peak, ':date' => $date));
    unset($conn);
}

/* Determines what symbol to show in the 'Handled' column. 
 * A tick with a green background for Handled, a cross with a red background for Not-Handled. */

function add_details_MySQL($row) {
    if ($row['Handler_dcs'] === '1A') {
        echo "    <td> Amadeus </td>\n";
        echo "    <td class='yes'> ✓ </td>\n ";
        return "Amadeus";
    } else {
        $dcs_name = get_dcs_name($row['Handler_dcs']);
        if ($dcs_name == '') {
            echo "    <td> Unknown  </td>\n";
            echo "    <td class='exe'>X</td>\n";
            return "Unknown";
        } else {
            echo "    <td> " . $dcs_name . " </td>\n";
            echo "    <td class='exe'>X</td>\n";
            return $dcs_name;
        }
    }
}

/* Builds query according to what fields have been filled in. 
 * E.g. If someone searches with the Airport and Handler code, but no Carrier code, the SELECT statement will adapt accordingly. */

function build_cache_query() {
    $carrier_code = $_POST["CC"]; //CC is the Carrier code input value from ajax_low_level_request() in connection_functions.js.
    if (isset($_POST["HC"]) && isset($_POST["AC"])) {//For some views (e.g. Codeshare Partners), there isn't an Airport code field. So this checks if the Airport and Handler codes have been set before extracting them. 
        $handler_code = $_POST["HC"]; //HC is the Handler code from ajax_low_level_request() in connection_functions.js.
        $airport_code = $_POST["AC"]; //AC is the Airport code from ajax_low_level_request() in connection_functions.js. 
    } else {//Since the fields don't exist for the current view, just set them as an empty string. 
        $handler_code = '';
        $airport_code = '';
    }
    $view = $_POST["View"]; //View is from ajax_low_level_request() in connection_functions.js.
    $environment = $_POST["environment"]; //environment is from ajax_low_level_request() in connection_functions.js.
    $first_query = true; //Used to determine which condition to use, a WHERE clause (when true) or a AND operator (when false) in the SELECT statement.
    $columns = "Carrier,Station,Handler_code, Handler_dcs"; //Default set of columns if it doesn't change when create_query_for_cache_filter(..) is called later on.
    $table = $environment . "_CARRIER_INFO"; //Default table if it doesn't change when create_query_for_cache_filter(..) is called later on.
    $table_header = "<thead> <tr> <th> Carrier </th> <th> Station </th>  <th>  Handler code </th> <th>  Handling DCS </th> <th> System name </th> <th> Handled </th> </th> </tr></thead>"; //Default table headers if it doesn't change when create_query_for_cache_filter(..) is called later on.
    $table_class = "normal_table"; ////Default table class, can see differences between .normal_table and .reg_table in table.css.

    if ($carrier_code === '') {
        $and_carrier_code = ""; //If no Carrier code has been entered, there's no condition with the Carrier code in the SELECT statement.
    } else if ($view === 'c_Han') {//When the selected View is 'Carriers Handled by [..] in Altea CM'.
        $and_carrier_code = check_clause($first_query) . "Handler_code = '$carrier_code'"; //The condition for the SELECT statement (e.g. WHERE/AND Handler_code = 'BA') 
        //NOTE: The Handler code field seen in the GUI is actually the Carrier code field, just with a Handler code label. (Not sure why, however this definitely needs changing.)
        $first_query = false;
    } else {
        $and_carrier_code = check_clause($first_query) . "Carrier = '$carrier_code'"; //The condition for the SELECT statement (e.g. WHERE/AND Carrier = 'BA')
        $first_query = false; //If $first_query has already been set to false, the condition will be AND.
    }

    if ($handler_code === '') {
        $and_handler_code = "";
    } else if ($view === 'Codeshare') {//When the selected View is 'Codeshare partners'.
        $and_handler_code = check_clause($first_query) . "Marketing_Carrier = '$handler_code'";
        $first_query = false; //If $first_query has already been set to false, the condition will be AND.
        //NOTE: The Handler code field seen in the GUI is actually the Marketting Carrier field, just with a Marketting Carrier label.
    } else {
        $and_handler_code = check_clause($first_query) . "Handler_code ='$handler_code'";
        $first_query = false; //If $first_query has already been set to false, the condition will be AND.
    }

    if ($airport_code === '') {
        $and_airport_code = "";
    } else {
        $and_airport_code = check_clause($first_query) . "Station ='$airport_code'"; //The condition for the SELECT statement (e.g. WHERE/AND Station = 'LHR')
        $first_query = false; //If $first_query has already been set to false, the condition will be AND.
    }

//    if ($view === "") {//If there isn't a particular filter.. //Used for All filter?
//        $and_filter = "";//Set this to blank then put search_codes in an array and we good.
//    } 
//    else {
    $search_codes = create_query_for_cache_filter($and_carrier_code, $and_handler_code, $and_airport_code, $view, $environment, $columns, $table, $table_header, $carrier_code, $table_class);
    return $search_codes;
//    }
    //Do we evem need this part still????
//    $search_codes = array($and_carrier_code, $and_handler_code, $and_airport_code, $and_filter, $columns, $table, $table_header, $carrier_code, $table_class);  //PHP doesn't support returning 2 values
//    return $search_codes;
}

/* Determines what the remainder operators (AND) need to be, specifies a different SQL table to look at if necessary, and changes the table design.
 * I highly recommend to uncomment the echo statement in get_cache_data(), then try search in the different views in CLOUD to see the complete
 * SELECT statements, and understand how all of this comes together. */

function create_query_for_cache_filter($and_carrier_code, $and_handler_code, $and_airport_code, $view, $environment, $columns, $table, $table_header, $carrier_code, $table_class) {
    switch ($view) { //Returns relevant information depending on the view that was selected.
        case 'All'://Rel = All (Handled & Not-Handled Stations), the dropdown list values in Home.php need to be updated.
            $and_filter = "AND (Handler_dcs = '1A' OR Handler_dcs != '1A') AND Handler_code != 'Not specified'";
            break;
        case 'Han_station'://Hos = Stations Handled
            $and_filter = "AND Handler_dcs = '1A' AND Handler_code != 'Not specified'";
            break;
        case 'notHan_station'://NotHos = Stations Not-Handled
            $and_filter = "AND Handler_dcs != '1A' AND Handler_code != 'Not specified'";
            break;
        case 'c_Han'://c_Han = Carriers Handled 
            $and_filter = "AND Carrier!=Handler_code AND Handler_code != 'Not specified'";
            break;
        case 'Han'://Han = Handlers
            $and_filter = "AND Handler_code != '$carrier_code' AND Handler_code != 'Not specified'";
            break;
        case 'Reg'://Reg = Dest with Regulatory Impact
            $columns = "Carrier,Station,City";
            $table = $environment . "_REGULATORY_INFO";
            $and_filter = "";
            $table_header = "<thead> <tr> <th> Carrier </th> <th> Destination </th><th> City </th> </tr></thead>";
            $table_class = 'reg_table';
            break;
        case 'Codeshare'://Codeshare = Codeshare partners
            $columns = "Carrier, Marketing_Carrier";
            $table = "UAT_CODESHARE";
            $and_filter = "AND Marketing_Carrier != Carrier";
            $table_header = "<thead> <tr> <th> Operating Carrier </th> <th> Marketing Carrier </th> </tr></thead>";
            break;
    }
    return array($and_carrier_code, $and_handler_code, $and_airport_code, $and_filter, $columns, $table, $table_header, $table_class);
}
