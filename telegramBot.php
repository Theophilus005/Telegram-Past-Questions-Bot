<?php
require_once("php/databaseConnection.php");

$token = "5259607440:AAGxEg6H-NDS096IophIJ32ZBWOhisoDXAA";
$link = "https://api.telegram.org/bot" . $token;

$updates = file_get_contents('php://input');
$updates = json_decode($updates, TRUE);

$chatId = $updates['message']['chat']['id'];
$name = $updates['message']['chat']['first_name'];
$message = strtolower($updates['message']['text']);


function sendmsg($chat_id, $text)
{
    $url = "https://api.telegram.org/bot5259607440:AAGxEg6H-NDS096IophIJ32ZBWOhisoDXAA/sendMessage?chat_id=" . $chat_id . "&text=" . $text;
    file_get_contents($url);
}

function sendsticker($chat_id, $text)
{
    $url = "https://api.telegram.org/bot5259607440:AAGxEg6H-NDS096IophIJ32ZBWOhisoDXAA/sendSticker?chat_id=" . $chat_id . "&sticker=" . $text;
    file_get_contents($url);
}

function zipper($path, $destination) {
$pathdir = $path; 
  
// Enter the name to creating zipped directory
$zipcreated = $destination;
  
// Create new zip class
$zip = new ZipArchive;
   
if($zip -> open($zipcreated, ZipArchive::CREATE ) === TRUE) {
      
    // Store the path into the variable
    $dir = opendir($pathdir);
       
    while($file = readdir($dir)) {
        if(is_file($pathdir.$file)) {
            $zip -> addFile($pathdir.$file, $file);
        }
    }
    $zip ->close();
    }
}

//Check phase
    $select_query = "SELECT * FROM progress WHERE chat_id = $chatId";
    $result = $conn->query($select_query);
    if($result->num_rows > 0) {
        $result_object = $result->fetch_object();
        $phase = $result_object->phase;
    }

    
if($phase == "department") {
    if($message != "cpen" && $message != "computer" && $message != "computer engineering" && $message != "bmen" && $message != "biomedical" && $message != "biomed" && $message != "biomedical engineering" && $message != "food" && $message != "fpen" && $message != "food processing" && $message != "mten" && $message != "material" && $message != "materials" && $message != "material engineering" && $message != "agric" && $message != "agricultural engineering" && $message != "aren" && $message != "/start") {
        sendmsg($chatId, "This department doesn't look familiar...");
        sendmsg($chatId, "😕");
        sendmsg($chatId, "Enter one that I can recognize...");
    }
}

if($phase == "level") {
    if($message != "100" && $message != "200" && $message != "300" && $message != "400" && $message != "/start") {
        sendmsg($chatId, "I don't know what level that is");
        sendmsg($chatId, "😐");
        sendmsg($chatId, "Check and enter a level I can recognize...");
        
    }
}

if($phase == "semester") {
    if($message != "first" && $message != "first semester" && $message != "semester 1" && $message != "1" && $message != "1st" && $message != "second" && $message != "second semester" && $message != "semester 2"&& $message != "2" && $message != "2nd" && $message != "/start") {
        sendmsg($chatId, "I don't know of this semester");
        sendmsg($chatId, "😐");
        sendmsg($chatId, "Check and enter a semester I can recognize...");
    }
}

if($phase == "file") {
    if($message != "/start" && $message != "/broadcast") {
        sendmsg($chatId, "Unfortunately, I can't respond to what you typed...");
        sendmsg($chatId, "🤕");
        sendmsg($chatId, "If you want new past questions, tap on /start");
        sendmsg($chatId, "😬");
        
    }
}

if($message == "/broadcast") {
     $update_query = "UPDATE progress SET phase = 'broadcast' WHERE chat_id = $chatId";
     $conn->query($update_query);
 
    sendmsg($chatId, "You are in broadcast mode...");
    sendmsg($chatId, "Send message to broadcast...");
    
}

if($message == "/broadcast_remove") {
     $update_query = "UPDATE progress SET phase = 'file' WHERE chat_id = $chatId";
     $conn->query($update_query);
 
    sendmsg($chatId, "Broadcast mode removed");
    sendmsg($chatId, "You are in file mode...");
    
}

//broadcast message
if($phase == "broadcast" && $message != "/broadcast_remove") {
    
    $chatIds = array();
    $count = 0;
    
     $select_query = "SELECT * FROM progress";
     $result = $conn->query($select_query);
     if($result->num_rows > 0) {
         while($data = $result->fetch_assoc()) {
             $chatIds[] = $data[chat_id];
         }
     }
     
    for($i=0; $i<$result->num_rows; $i++) {
        sendmsg($chatIds[$i], $message);
        $count = $count + 1;
    }
    
    sendmsg($chatId, "Broadcast is done...");
    sendmsg($chatId, $count ." messages sent");
    
     $update_query = "UPDATE progress SET phase = 'file' WHERE chat_id = $chatId";
     $conn->query($update_query);
 
    sendmsg($chatId, "Broadcast mode removed");
    sendmsg($chatId, "You are in file mode...");
    
}



if($message == "/start") {
    
     $select_query = "SELECT * FROM progress WHERE chat_id = $chatId";
    $result = $conn->query($select_query);
    if($result->num_rows > 0) {
            sendmsg($chatId, "Hey there, " . $name);
            sendmsg($chatId, "Great to see you again");
            sendmsg($chatId, "I suppose you want new past questions...");
            sendmsg($chatId, "Let me help you out...");
            sendmsg($chatId, "😉");
    } else {
            sendmsg($chatId, "Hi, " . $name);
            sendmsg($chatId, "You're new here, seems we've not interacted before...");
            sendmsg($chatId, "What brings you here 😁");
            sendmsg($chatId, "I suppose you want some past questions...");
            sendmsg($chatId, "Anyways, I'm Bob, the past questions plug");
            sendmsg($chatId, "I can help you get all your past questions");
            sendmsg($chatId, "😌");
    }
        sendmsg($chatId, "Which department do you want me to look into?");
        sendmsg($chatId, "...");
            
            //Check if user has interacted with bot before
            $check_query = "SELECT * FROM progress WHERE chat_id = $chatId";
            $result = $conn->query($check_query);
            if($result->num_rows > 0) {
                $update_query = "UPDATE progress SET phase = 'department' WHERE chat_id = $chatId";
                $conn->query($update_query);
            } else {
            $insert_query = "INSERT INTO progress VALUES($chatId, 'department', 'null', 'null', 'null')";
            $conn->query($insert_query);
        }

} else if($message == "cpen" || $message == "computer" || $message == "computer engineering") {
    //Check phase
    $select_query = "SELECT * FROM progress WHERE chat_id = $chatId";
    $result = $conn->query($select_query);
    if($result->num_rows > 0) {
        $result_object = $result->fetch_object();
        $phase = $result_object->phase;
    }
    if($phase != "department") {
        sendmsg($chatId, "Oops, It seems you've jumped something...");
        sendmsg($chatId, "😕");
        sendmsg($chatId, "Check and try again...");
        
    } else {
        $update_query = "UPDATE progress SET department = 'CPEN' WHERE chat_id = $chatId";
        $conn->query($update_query);
        sendmsg($chatId, "Tell me the level you're interested in");
        sendmsg($chatId, "...");
        
        $update_query = "UPDATE progress SET phase = 'level' WHERE chat_id = $chatId";
        $conn->query($update_query);
     
    }
} else if($message == "bmen" || $message == "biomed" || $message == "biomedical engineering" || $message == "biomedical") {
     //Check phase
    $select_query = "SELECT * FROM progress WHERE chat_id = $chatId";
    $result = $conn->query($select_query);
    if($result->num_rows > 0) {
        $result_object = $result->fetch_object();
        $phase = $result_object->phase;
    }
    if($phase != "department") {
        sendmsg($chatId, "Oops, It seems you've jumped something...");
        sendmsg($chatId, "😕");
        sendmsg($chatId, "Check and try again...");
    } else {
        $update_query = "UPDATE progress SET department = 'BMEN' WHERE chat_id = $chatId";
        $conn->query($update_query);
        sendmsg($chatId, "Tell me the level you're interested in");
        sendmsg($chatId, "...");
        $update_query = "UPDATE progress SET phase = 'level' WHERE chat_id = $chatId";
        $conn->query($update_query);
    }
} else if($message == "fpen" || $message == "food" || $message == "food engineering") {
     //Check phase
    $select_query = "SELECT * FROM progress WHERE chat_id = $chatId";
    $result = $conn->query($select_query);
    if($result->num_rows > 0) {
        $result_object = $result->fetch_object();
        $phase = $result_object->phase;
    }
    if($phase != "department") {
        sendmsg($chatId, "Oops, It seems you've jumped something...");
        sendmsg($chatId, "😕");
        sendmsg($chatId, "Check and try again...");
    } else {
         $update_query = "UPDATE progress SET department = 'FPEN' WHERE chat_id = $chatId";
        $conn->query($update_query);
        sendmsg($chatId, "Tell me the level you're interested in");
        sendmsg($chatId, "...");
        $update_query = "UPDATE progress SET phase = 'level' WHERE chat_id = $chatId";
        $conn->query($update_query);    }
} else if($message == "mten" || $message == "materials" || $message == "materials engineering" || $message == "material engineering") {
     //Check phase
    $select_query = "SELECT * FROM progress WHERE chat_id = $chatId";
    $result = $conn->query($select_query);
    if($result->num_rows > 0) {
        $result_object = $result->fetch_object();
        $phase = $result_object->phase;
    }
    if($phase != "department") {
        sendmsg($chatId, "Oops, It seems you've jumped something...");
        sendmsg($chatId, "😕");
        sendmsg($chatId, "Check and try again...");
    } else {
         $update_query = "UPDATE progress SET department = 'MTEN' WHERE chat_id = $chatId";
        $conn->query($update_query);
        sendmsg($chatId, "Tell me the level you're interested in");
        sendmsg($chatId, "...");
        $update_query = "UPDATE progress SET phase = 'level' WHERE chat_id = $chatId";
        $conn->query($update_query);    }
} else if($message == "aren" || $message == "agric" || $message == "agricultural engineering") {
     //Check phase
    $select_query = "SELECT * FROM progress WHERE chat_id = $chatId";
    $result = $conn->query($select_query);
    if($result->num_rows > 0) {
        $result_object = $result->fetch_object();
        $phase = $result_object->phase;
    }
    if($phase != "department") {
        sendmsg($chatId, "Oops, It seems you've jumped something...");
        sendmsg($chatId, "😕");
        sendmsg($chatId, "Check and try again...");
    } else {
         $update_query = "UPDATE progress SET department = 'AREN' WHERE chat_id = $chatId";
        $conn->query($update_query);
        sendmsg($chatId, "Sorry, I can't provide agric past questions");
        sendmsg($chatId, "🤕");
        $update_query = "UPDATE progress SET phase = 'level' WHERE chat_id = $chatId";
        $conn->query($update_query);    }
} else if($message == "100" || $message == "200" || $message == "300" || $message == "400") {
    $select_query = "SELECT * FROM progress WHERE chat_id = $chatId";
    $result = $conn->query($select_query);
    if($result->num_rows > 0) {
        $result_object = $result->fetch_object();
        $phase = $result_object->phase;
    }
    if($phase != "level") {
        sendmsg($chatId, "Oops, It seems you've jumped something...");
        sendmsg($chatId, "😕");
        sendmsg($chatId, "Check and try again...");
    } else {
         $update_query = "UPDATE progress SET level = '$message' WHERE chat_id = $chatId";
        $conn->query($update_query);
        sendmsg($chatId, "Which semester should I pick? First or Second...");
        sendmsg($chatId, "...");
        $update_query = "UPDATE progress SET phase = 'semester' WHERE chat_id = $chatId";
        $conn->query($update_query);
    }
} 

else if($message == "first" || $message == "first semester" || $message == "semester 1" || $message == "1" || $message == "second" || $message == "second semester" || $message == "semester 2" || $message == "2" || $message == "1st" || $message == "2nd") {
    
    if($message == "first" || $message == "first semester" || $message == "semester 1" || $message == "1" || $message == "1st") {
        $sem = "Semester_1";
    } else {
        $sem = "Semester_2";
    }   
        
    $select_query = "SELECT * FROM progress WHERE chat_id = $chatId";
    $result = $conn->query($select_query);
    if($result->num_rows > 0) {
        $result_object = $result->fetch_object();
        $phase = $result_object->phase;
        $department = $result_object->department;
        $level = $result_object->level;
    }
    if($phase != "semester") {
        sendmsg($chatId, "Oops, It seems you've jumped something...");
        sendmsg($chatId, "😕");
        sendmsg($chatId, "Check and try again...");
    } else {
         $update_query = "UPDATE progress SET semester = '$sem' WHERE chat_id = $chatId";
        $conn->query($update_query);
        
        //Get all folder names in an array
        $files = scandir("pasco/".$department."/".$level."/".$sem);
        
    //Keyboard array
    $stmt = array(
        array(
            "text" => "button",
            "callback_data" => "button_0"
        ),
        
    );

        foreach ($stmt as $row) {
        for($i=2; $i<count($files); $i++) {
        $options[] = array('text'=> '📘 '.$files[$i], 'callback_data'=>$files[$i]);
    }
        $options[] = array('text'=> "📕 Download all", 'callback_data'=>"all");
}

    $keyboard = array('inline_keyboard' => array_chunk($options,2));
        
    //Sending curl  
    $reply = "Here you go...😌";
    $url = "https://api.telegram.org/bot5259607440:AAGxEg6H-NDS096IophIJ32ZBWOhisoDXAA/sendMessage";
    
    $postfields = array(
    'chat_id' => "$chatId",
    'text' => "$reply",
    'reply_markup' => json_encode($keyboard)
    );
    
    if (!$curld = curl_init()) {
    exit;
    }
    
    curl_setopt($curld, CURLOPT_POST, true);
    curl_setopt($curld, CURLOPT_POSTFIELDS, $postfields);
    curl_setopt($curld, CURLOPT_URL,$url);
    curl_setopt($curld, CURLOPT_RETURNTRANSFER, true);
    
    $output = curl_exec($curld);
    
    curl_close ($curld);
    
    $update_query = "UPDATE progress SET phase = 'file' WHERE chat_id = $chatId";
    $conn->query($update_query);
    
        }
    } 
    
    
    if(isset($updates["callback_query"])) {
    $data = $updates['callback_query']['data'];
    $chatId = $updates['callback_query']['from']['id'];
    
    
    $select_query = "SELECT * FROM progress WHERE chat_id = $chatId";
    $result = $conn->query($select_query);
    if($result->num_rows > 0) {
        $result_object = $result->fetch_object();
        $phase = $result_object->phase;
        $department = $result_object->department;
        $level = $result_object->level;
        $semester = $result_object->semester;
    }
    if($phase != "file") {
        sendmsg($chatId, "Invalid Input");
    } else {
            if($data == "all") {
                $files2 = scandir("pasco/".$department."/".$level."/".$semester);
            for($i=2; $i<count($files2); $i++) {
                $path = "pasco/".$department."/".$level."/".$semester."/".$files2[$i]."/";
                $destination = "pasco/".$department."/".$level."/".$semester."/".$files2[$i].".zip";
                    
            //Zip file
            zipper($path, $destination);
            
            //Send file
            $file_link = rawurlencode("https://inauthentic-amplifi.000webhostapp.com/".$destination);
            
            $url = "https://api.telegram.org/bot5259607440:AAGxEg6H-NDS096IophIJ32ZBWOhisoDXAA/sendDocument?chat_id=" . $chatId . "&document=" . $file_link;
            
            if(file_get_contents($url)) {    
                unlink($destination);
                
            }
            
        }
        
        sendmsg($chatId, "My job here is done...");
        sendmsg($chatId, "If you need me to help you again, tap on /start");
        sendmsg($chatId, "🤝");
        
        
            } else {
            
            $path = "pasco/".$department."/".$level."/".$semester."/".$data."/";
            $destination = "pasco/".$department."/".$level."/".$semester."/".$data.".zip";
        
            //Zip file
            zipper($path, $destination);
            
            //Send file
            $file_link = "https://inauthentic-amplifi.000webhostapp.com/".$destination;
            
            $url = "https://api.telegram.org/bot5259607440:AAGxEg6H-NDS096IophIJ32ZBWOhisoDXAA/sendDocument?chat_id=" . $chatId . "&document=" . $file_link;
            
            if(file_get_contents($url)) {    
                unlink($destination);
                
            }
               sendmsg($chatId, "My job here is done...");
               sendmsg($chatId, "If you need me to help you again, tap on /start");
               sendmsg($chatId, "🤝");
               
        }
    
        }
    }
    
    
