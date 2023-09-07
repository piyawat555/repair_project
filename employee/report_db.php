<?php

include('../connect_db.php');

    if(isset($_POST['rp_submit'])){

        $sql_code = "SELECT * FROM tbl_code WHERE code='RP'";
        $result_code = mysqli_query($conn,$sql_code);

        $row = mysqli_fetch_array($result_code);
        $date_f = date("Y-m-d");
        $str_date = str_replace("-", "",$date_f);
        $run_number = $row["run_number"];
        $code = $row["code"];

        $run_number_new = str_pad($run_number, 3, 0, STR_PAD_LEFT);

        $code_rp = $code.''.$str_date.''.$run_number_new;

        $title = $_POST['title'];
        $location = $_POST['location'];
        $desc = $_POST['desc'];
        $rp_user_id = $_POST['rp_submit'];
        $name_user = $_POST['name_user'];

        $date_report_now = date("Y-m-d H:i:s");
        $date_pm=DateThai($date_report_now);
        $is_active = "Y";
        $sql_insert_up = "INSERT INTO tbl_reports (code,title,description,location,status,user_id,date_report,is_active) 
        VALUES ('$code_rp','$title','$desc','$location',1,'$rp_user_id','$date_report_now','$is_active')";
        $result_rp = mysqli_query($conn,$sql_insert_up);

        if($result_rp){
            if($run_number+1 > 1000){
                $run_number_update=1;
            }else{
                if($run_number == 999){
                    $run_number_update = $run_number;
                }else{
                    $run_number_update=$run_number+1;
                }
            }
        
            $date_now = date("Y-m-d");
            $sql_code_update = "UPDATE tbl_code set run_number='$run_number_update',date='$date_now' WHERE code = 'RP'";
            $result_code_update = mysqli_query($conn,$sql_code_update);
            if($result_rp&&$result_code_update){
                    $sql_pushmessage = "SELECT u.line_id FROM users u
                        INNER JOIN role_user ru ON ru.user_id = u.id
                            INNER JOIN roles r ON r.id = ru.role_id
                            WHERE r.title = 'admin' AND u.line_id IS NOT NULL";
                            $result_pm = mysqli_query($conn,$sql_pushmessage);
                                if(mysqli_num_rows($result_pm)>0){    
                                    while($row_pm = mysqli_fetch_array($result_pm))
                                    {
                                        pushMessageToadmin($row_pm['line_id'],$code_rp,$name_user,$date_pm);
                                    }
                                }
                    $sql_pushmessage_user = "SELECT line_id FROM users
                            WHERE id = $rp_user_id AND line_id IS NOT NULL";
                            $result_u_pm = mysqli_query($conn,$sql_pushmessage_user);
                                if(mysqli_num_rows($result_u_pm)>0){    
                                    $row_u_pm = mysqli_fetch_array($result_u_pm);
                                    pushMessageToEmp($row_u_pm['line_id'],$code_rp);
                                }

                $return_arr = array("status_code" => 200,"msg" => "แจ้งซ่อมสำเร็จแล้ว");
                echo json_encode($return_arr);
            }else{
                $return_arr = array("status_code" => 500,"msg" => "เกิดข้อผิดพลาดกรุณาติดต่อแอดมิน");
                echo json_encode($return_arr);
            }
        }
    
    }

    if(isset($_POST['report_delete'])){
        $code = $_POST['code'];

        $sql_update = "UPDATE tbl_reports SET is_active='N' WHERE code='$code'";
        $result_update = mysqli_query($conn,$sql_update);

        if($result_update){
            $return_arr = array("status_code" => 200,"msg" => "ลบข้อมูลสำเร็จ");
            echo json_encode($return_arr);
        }else{
            $return_arr = array("status_code" => 200,"msg" => "ลบข้อมูลสำเร็จ");
            echo json_encode($return_arr);
        }
    }
 
    function DateThai($strDate)
    {

            $strYear = date("Y",strtotime($strDate))+543;

            $strMonth= date("n",strtotime($strDate));

            $strDay= date("j",strtotime($strDate));

            $strHour= date("H",strtotime($strDate));

            $strMinute= date("i",strtotime($strDate));

            $strSeconds= date("s",strtotime($strDate));

            $strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");

            $strMonthThai=$strMonthCut[$strMonth];

            return "$strDay $strMonthThai $strYear, เวลา $strHour:$strMinute น. ";

    }

    function pushMessageToadmin($line_id,$code,$name_user,$date){

        $key = 'mgtIuuXCYv8mjK56mk38MnLB0o5OgKEUlL37aZtHXMzTMAjEoe43kt36vKz9jEWqySiKVogBcNJD5F9j5texIi1Zo1E32d40TxdAE6+Hw6qFvjEg9B4xWaJ/M5595zfHZECeCsFPqtSs13P2dURgdAdB04t89/1O/w1cDnyilFU=';
  
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
                                "to": "'.$line_id.'",
                                "messages": [
                                    {
                                        "type": "flex",
                                        "altText": "มีการแจ้งซ่อมใหม่",
                                        "contents": {
                                          "type": "bubble",
                                          "size": "mega",
                                          "header": {
                                            "type": "box",
                                            "layout": "vertical",
                                            "contents": [
                                              {
                                                "type": "box",
                                                "layout": "vertical",
                                                "contents": [
                                                  {
                                                    "type": "text",
                                                    "text": "เลขใบแจ้งซ่อม",
                                                    "color": "#ffffff",
                                                    "size": "sm"
                                                  },
                                                  {
                                                    "type": "text",
                                                    "text": "'.$code.'",
                                                    "color": "#ffffff",
                                                    "size": "xl",
                                                    "flex": 4,
                                                    "weight": "bold"
                                                  }
                                                ]
                                              },
                                              {
                                                "type": "box",
                                                "layout": "vertical",
                                                "contents": [
                                                  {
                                                    "type": "text",
                                                    "text": "แจ้งโดย",
                                                    "color": "#ffffff",
                                                    "size": "sm"
                                                  },
                                                  {
                                                    "type": "text",
                                                    "text": "คุณ '.$name_user.'",
                                                    "color": "#ffffff",
                                                    "size": "xl",
                                                    "flex": 4,
                                                    "weight": "bold"
                                                  }
                                                ]
                                              }
                                            ],
                                            "paddingAll": "20px",
                                            "backgroundColor": "#0367D3",
                                            "spacing": "md",
                                            "height": "154px",
                                            "paddingTop": "22px"
                                          },
                                          "body": {
                                            "type": "box",
                                            "layout": "vertical",
                                            "contents": [
                                              {
                                                "type": "text",
                                                "text": "วันที่แจ้ง : '.$date.'",
                                                "color": "#2e2eb8",
                                                "size": "md"
                                              },
                                              {
                                                "type": "box",
                                                "layout": "horizontal",
                                                "contents": [
                                                  {
                                                    "type": "box",
                                                    "layout": "baseline",
                                                    "contents": [
                                                      {
                                                        "type": "filler"
                                                      }
                                                    ],
                                                    "flex": 1
                                                  },
                                                  {
                                                    "type": "box",
                                                    "layout": "vertical",
                                                    "contents": [
                                                      {
                                                        "type": "filler"
                                                      },
                                                      {
                                                        "type": "box",
                                                        "layout": "vertical",
                                                        "contents": [],
                                                        "cornerRadius": "30px",
                                                        "height": "12px",
                                                        "width": "12px",
                                                        "borderColor": "#6486E3",
                                                        "borderWidth": "2px"
                                                      },
                                                      {
                                                        "type": "filler"
                                                      }
                                                    ],
                                                    "flex": 0
                                                  },
                                                  {
                                                    "type": "text",
                                                    "text": "แจ้งซ่อม",
                                                    "gravity": "center",
                                                    "flex": 2,
                                                    "size": "lg",
                                                    "align": "center",
                                                    "weight": "bold"
                                                  }
                                                ],
                                                "spacing": "lg",
                                                "cornerRadius": "30px",
                                                "margin": "xl"
                                              },
                                              {
                                                "type": "box",
                                                "layout": "horizontal",
                                                "contents": [
                                                  {
                                                    "type": "box",
                                                    "layout": "baseline",
                                                    "contents": [
                                                      {
                                                        "type": "filler"
                                                      }
                                                    ],
                                                    "flex": 1
                                                  },
                                                  {
                                                    "type": "box",
                                                    "layout": "vertical",
                                                    "contents": [
                                                      {
                                                        "type": "box",
                                                        "layout": "horizontal",
                                                        "contents": [
                                                          {
                                                            "type": "filler"
                                                          },
                                                          {
                                                            "type": "box",
                                                            "layout": "vertical",
                                                            "contents": [],
                                                            "width": "2px",
                                                            "backgroundColor": "#6486E3"
                                                          },
                                                          {
                                                            "type": "filler"
                                                          }
                                                        ],
                                                        "flex": 1
                                                      }
                                                    ],
                                                    "width": "12px"
                                                  },
                                                  {
                                                    "type": "box",
                                                    "layout": "vertical",
                                                    "contents": [],
                                                    "flex": 2,
                                                    "alignItems": "flex-start"
                                                  }
                                                ],
                                                "spacing": "lg",
                                                "height": "80px"
                                              },
                                              {
                                                "type": "box",
                                                "layout": "horizontal",
                                                "contents": [
                                                  {
                                                    "type": "box",
                                                    "layout": "baseline",
                                                    "contents": [
                                                      {
                                                        "type": "text",
                                                        "text": "สถานะ",
                                                        "size": "lg",
                                                        "weight": "bold"
                                                      }
                                                    ],
                                                    "flex": 1
                                                  },
                                                  {
                                                    "type": "box",
                                                    "layout": "vertical",
                                                    "contents": [
                                                      {
                                                        "type": "filler"
                                                      },
                                                      {
                                                        "type": "box",
                                                        "layout": "vertical",
                                                        "contents": [],
                                                        "cornerRadius": "30px",
                                                        "height": "12px",
                                                        "width": "12px",
                                                        "borderColor": "#EF454D",
                                                        "borderWidth": "2px"
                                                      },
                                                      {
                                                        "type": "filler"
                                                      }
                                                    ],
                                                    "flex": 0
                                                  },
                                                  {
                                                    "type": "text",
                                                    "text": "รอดำเนินการ",
                                                    "gravity": "center",
                                                    "flex": 2,
                                                    "size": "lg",
                                                    "align": "center",
                                                    "weight": "bold"
                                                  }
                                                ],
                                                "spacing": "lg",
                                                "cornerRadius": "30px",
                                                "margin": "sm"
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
                  
                          curl_exec($curl);
                  
                          curl_close($curl);
                            
                } catch(Exception $e) {
                    echo json_encode($e);
                }
    }


    function pushMessageToEmp($line_id,$code){
        $key = 'mgtIuuXCYv8mjK56mk38MnLB0o5OgKEUlL37aZtHXMzTMAjEoe43kt36vKz9jEWqySiKVogBcNJD5F9j5texIi1Zo1E32d40TxdAE6+Hw6qFvjEg9B4xWaJ/M5595zfHZECeCsFPqtSs13P2dURgdAdB04t89/1O/w1cDnyilFU=';
  
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
                                "to": "'.$line_id.'",
                                "messages": [
                                    {
                                        "type": "flex",
                                        "altText": "แจ้งเตือนสถานะการซ่อม",
                                        "contents": {
                                          "type": "carousel",
                                          "contents": [
                                            {
                                              "type": "bubble",
                                              "size": "giga",
                                              "header": {
                                                "type": "box",
                                                "layout": "vertical",
                                                "contents": [
                                                  {
                                                    "type": "text",
                                                    "text": "แจ้งซ่อมสำเร็จ ✅",
                                                    "color": "#ffffff",
                                                    "align": "start",
                                                    "size": "lg",
                                                    "gravity": "center"
                                                  },
                                                  {
                                                    "type": "text",
                                                    "text": "เลขใบแจ้งซ่อม : '.$code.'",
                                                    "color": "#ffffff",
                                                    "align": "start",
                                                    "size": "md",
                                                    "gravity": "center"
                                                  },
                                                  {
                                                    "type": "text",
                                                    "text": "25%",
                                                    "color": "#ffffff",
                                                    "align": "start",
                                                    "size": "xs",
                                                    "gravity": "center",
                                                    "margin": "lg"
                                                  },
                                                  {
                                                    "type": "box",
                                                    "layout": "vertical",
                                                    "contents": [
                                                      {
                                                        "type": "box",
                                                        "layout": "vertical",
                                                        "contents": [
                                                          {
                                                            "type": "filler"
                                                          }
                                                        ],
                                                        "width": "25%",
                                                        "backgroundColor": "#0D8186",
                                                        "height": "10px"
                                                      }
                                                    ],
                                                    "backgroundColor": "#9FD8E36E",
                                                    "height": "10px",
                                                    "margin": "sm"
                                                  }
                                                ],
                                                "backgroundColor": "#27ACB2",
                                                "paddingTop": "19px",
                                                "paddingAll": "12px",
                                                "paddingBottom": "16px"
                                              },
                                              "body": {
                                                "type": "box",
                                                "layout": "vertical",
                                                "contents": [
                                                  {
                                                    "type": "box",
                                                    "layout": "horizontal",
                                                    "contents": [
                                                      {
                                                        "type": "text",
                                                        "text": "📋 สถานะ รอดำเนินการ",
                                                        "color": "#8C8C8C",
                                                        "size": "md",
                                                        "wrap": true
                                                      }
                                                    ],
                                                    "flex": 1
                                                  }
                                                ],
                                                "spacing": "md",
                                                "paddingAll": "12px"
                                              },
                                              "styles": {
                                                "footer": {
                                                  "separator": false
                                                }
                                              }
                                            }
                                          ]
                                        }
                                      }
                                ]
                            }',
                            CURLOPT_HTTPHEADER => array(
                                'Content-Type: application/json',
                                'Authorization: Bearer '.$key
                            ),
                            ));
                  
                          curl_exec($curl);
                  
                          curl_close($curl);
                            
                } catch(Exception $e) {
                    echo json_encode($e);
                }
    }

?>