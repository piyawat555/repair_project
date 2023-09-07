<?php
include('../connect_db.php');

if(isset($_POST['push_message'])){

        $msg = $_POST['push_message'];
        $user_id = $_POST['user_id'];
   
        $key = 'mgtIuuXCYv8mjK56mk38MnLB0o5OgKEUlL37aZtHXMzTMAjEoe43kt36vKz9jEWqySiKVogBcNJD5F9j5texIi1Zo1E32d40TxdAE6+Hw6qFvjEg9B4xWaJ/M5595zfHZECeCsFPqtSs13P2dURgdAdB04t89/1O/w1cDnyilFU=';
      
        $sql_line = "SELECT line_id FROM users WHERE id=$user_id";
        $result_line = mysqli_query($conn,$sql_line);
        $row = mysqli_fetch_array($result_line);

        if($result_line){
                    try {
                        $curl = curl_init();
                    
                        curl_setopt_array($curl, array(
                        CURLOPT_URL => 'https://api.line.me/v2/bot/message/push',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_POSTFIELDS =>'{
                            "to": "'.$row['line_id'].'",
                            "messages": [
                                {
                                    "type": "flex",
                                    "altText": "ทดสอบแจ่งเตือน",
                                    "contents": {
                                        "type": "bubble",
                                        "body": {
                                            "type": "box",
                                            "layout": "vertical",
                                            "spacing": "md",
                                            "contents": [
                                                {
                                                    "type": "text",
                                                    "text": "'.$msg.'",
                                                    "weight": "bold",
                                                    "size": "xl"
                                                } 
                                            ]
                                        }
                                    }
                                }
                            ]
                        }',
                        CURLOPT_HTTPHEADER => array(
                            'Content-Type: application/json',
                            'Authorization: Bearer '.$key
                        ),
                        ));
            
                            $response = curl_exec($curl);
                            curl_close($curl);
            
                            if(strlen($response)<3){
                                    $sql_update = "UPDATE users SET line_validate='Y' WHERE id='$user_id'";
                                        $result_update = mysqli_query($conn,$sql_update);
                                        $return_arr = array("status_code" => 200,"msg" => "สำเร็จ");
                                        echo json_encode($return_arr);
                            }else{
                                $return_arr = array("status_code" => 500,"msg" => "ยังไม่เพิ่มเพื่อนไลน์บอท");
                                echo json_encode($return_arr);
                            }
                        
                            
                } catch(Exception $e) {
                    echo json_encode($e);
                }
        }

     
      
}


?>