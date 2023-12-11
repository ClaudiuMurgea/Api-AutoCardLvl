<?php
// =========================================================================================================
// DATABASE DEPENDENCIES
// =========================================================================================================
    header('Access-Control-Allow-Origin: *');
    $slaveDhost = "127.0.0.1";
    $slaveDusername = "app";
    $slaveDpassword = "sau03magen";
    $slaveDdatabase = "PlayerTracking";
    $slaveOrMaster = false;
    
    function Q(&$con,$q)
    {
        try
        {
            $res = mysqli_query($con,$q);
        }
        catch(Exception $e)
        {
            $SQL_ERR=$e->getMessage();
            echo "QUERIUL $q a DAT: $SQL_ERR<br>\n";

            $EscapedQ=mysqli_real_escape_string($con,$q);
            $EscapedErr=mysqli_real_escape_string($con,$SQL_ERR);
            $PhpFile=mysqli_real_escape_string($con,$PhpFile);
            mysqli_query($con,
                "Insert into PyramidMonitor.LogPHPErrors (Q,ErrorMsg,PhpFile)
                VALUES
                ('$EscapedQ','$EscapedErr','$PhpFile')");
        }
        return $res;
    }

while(true) {
    sleep(2);
    echo('Ok');

    try {
    	$con  = mysqli_connect($slaveDhost, $slaveDusername, $slaveDpassword, $slaveDdatabase) or die ("Cannot connect to the database");
    	$con2 = mysqli_connect($slaveDhost, $slaveDusername, $slaveDpassword, "Mystery") or die ("Cannot connect to the database");

    	$checkIfSlave = Q($con,"SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = 'MasterOnlyDB'");
    	
	    foreach($checkIfSlave as $index) {
            foreach($index as $columnName => $columnValue) {
                $slaveOrMaster = $columnValue;
            }
	    }

    } catch(Exception $e) {
        unset($checkIfSlave);
    }
    if($slaveOrMaster) {
        // =========================================================================================================
        // MASTER LOGIC
        // =========================================================================================================
        
        $con4 = mysqli_connect($slaveDhost, $slaveDusername, $slaveDpassword, "MasterOnlyDB") or die("Cannot");
        $toPushNotifications = Q($con,"call GET_NOTIFICATION_SMS");
        
        foreach($toPushNotifications as $index) {
            $eventID                = $index['ID'];
            $notificationType       = $index['Type'];
            $scheduleType           = $index['Schedule'];
            $recipientTextMessage   = $index['ClientData'];
            $recipientPhoneNumber   = $index['Destination'];
            $locationCode           = $index['LocationCode'];
            $locationName           = $index['LocationName'];

            // Sending notifications that have to be sent to MasterOnlyDB APINotification table
            $pushNotifications = Q($con4,
            "INSERT INTO APINotification(Destination, UserData, Type, LocationId, LocationName)
                VALUES('$recipientPhoneNumber','$recipientTextMessage','$notificationType',$locationCode,'$locationName')");

            // Now delete them columns so they can be logged in peace
            if($scheduleType == 'Once') {
                mysqli_next_result($con);
                $deleteNotifications = Q($con, "call DELETE_NOTIFICATION_SMS('$eventID')");
            }

            // If the events ScheduleType equals to Daily, a log will be written with the time when the message should be sent
            $timestamp = date('Y-m-d H:i:s');

            if($scheduleType == 'Daily') {
                mysqli_next_result($con);
                $pushNotifications = Q($con,
                "INSERT INTO EventTaskLogs(EventID, Timestamp)
                    VALUES('$eventID','$timestamp')");
            } elseif($scheduleType == 'Weekly') {
                mysqli_next_result($con);
                $pushNotifications = Q($con,
                "INSERT INTO EventTaskLogs(EventID, Timestamp)
                    VALUES('$eventID','$timestamp')");
            } elseif($scheduleType == 'Monthly') {
                mysqli_next_result($con);
                $pushNotifications = Q($con,
                "INSERT INTO EventTaskLogs(EventID, Timestamp)
                    VALUES('$eventID','$timestamp')");
            } elseif($scheduleType == 'Yearly') {
                mysqli_next_result($con);
                $pushNotifications = Q($con,
                "INSERT INTO EventTaskLogs(EventID, Timestamp)
                    VALUES('$eventID','$timestamp')");
            }
        }

    } else {
        // =========================================================================================================
        // SLAVE LOGIC
        // =========================================================================================================
        
        $toPushMasterIP = Q($con2, "SELECT * FROM MasterIP WHERE ServerType = 'Master' LIMIT 1");

        foreach($toPushMasterIP as $index => $data) {
            $masterIP = $data['IP'];
        }
        
        $con3 = mysqli_connect($masterIP, $slaveDusername, $slaveDpassword, "MasterOnlyDB") or die ("Cannot connect to the database");
        $toPushNotifications = Q($con,"call GET_NOTIFICATION_SMS");
        
        foreach($toPushNotifications as $index) {
            $eventID                = $index['ID'];
            $notificationType       = $index['Type'];
            $scheduleType           = $index['Schedule'];
            $recipientTextMessage   = $index['ClientData'];
            $recipientPhoneNumber   = $index['Destination'];
            $locationCode           = $index['LocationCode'];
            $locationName           = $index['LocationName'];

            // Sending notifications that have to be sent to MasterOnlyDB APINotification table
            $pushNotifications = Q($con3,
            "INSERT INTO APINotification(Destination, UserData, Type, LocationId, LocationName)
            VALUES($recipientPhoneNumber,'$recipientTextMessage','$notificationType',$locationCode,'$locationName')");

            // Now delete the events if the ScheduleType equals to Once, single messages events can be logged in peace
            if($scheduleType == 'Once') {
                mysqli_next_result($con);
                $deleteNotifications = Q($con, "call DELETE_NOTIFICATION_SMS('$eventID')");
            }

            // If the events ScheduleType equals to Daily, a log will be written with the time when the message should be sent
            if($scheduleType == 'Daily') {
                mysqli_next_result($con);
                $timestamp = date('Y-m-d H:i:s');
                $pushNotifications = Q($con,
                "INSERT INTO EventTaskLogs(EventID, Timestamp)
                    VALUES('$eventID','$timestamp')");
            } elseif($scheduleType == 'Weekly') {
                mysqli_next_result($con);
                $pushNotifications = Q($con,
                "INSERT INTO EventTaskLogs(EventID, Timestamp)
                    VALUES('$eventID','$timestamp')");
            } elseif($scheduleType == 'Monthly') {
                mysqli_next_result($con);
                $pushNotifications = Q($con,
                "INSERT INTO EventTaskLogs(EventID, Timestamp)
                    VALUES('$eventID','$timestamp')");
            } elseif($scheduleType == 'Yearly') {
                mysqli_next_result($con);
                $pushNotifications = Q($con,
                "INSERT INTO EventTaskLogs(EventID, Timestamp)
                    VALUES('$eventID','$timestamp')");
            }
        }
    }
}
?>
