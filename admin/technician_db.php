<?php
include('../connect_db.php');


    if(isset($_GET['technician_id']) && isset($_GET['code_id'])){

        $technician_id = $_GET['technician_id'];
        $code_id = $_GET['code_id'];
        $user_id = $_GET['user_id'];

        $date_assign_now = date("Y-m-d H:i:s");

        $sql_add_tec = "UPDATE tbl_reports SET 
                        status=2,
                        technician_id='$technician_id',
                        date_assign='$date_assign_now',
                        assign_by='$user_id' 
                        WHERE code ='$code_id'";
        $result_update = mysqli_query($conn,$sql_add_tec);

        if($result_update){
            
            $sql_select_user = "SELECT technician_id,user_id,assign_by,date_report FROM tbl_reports WHERE code = '$code_id'";
            $result_select = mysqli_query($conn,$sql_select_user);

            $row_user = mysqli_fetch_array($result_select);
            
            $user_array = array($row_user["technician_id"],$row_user["user_id"],$row_user["assign_by"]);
            $user_emp=$row_user["user_id"];

            $sql_pushmessage_user = "SELECT u.line_id,r.title FROM users u
            INNER JOIN role_user ru ON ru.user_id = u.id
            INNER JOIN roles r ON r.id = ru.role_id 
            WHERE u.line_id IS NOT NULL AND u.id IN ( '" . implode( "', '" , $user_array ) . "' )";
            $result_msg = mysqli_query($conn,$sql_pushmessage_user);
            

            $date_emp_one = DateThai($date_assign_now);
            $time_emp_one = TimeThai($date_assign_now);
            $date_emp_two = DateThai($row_user["date_report"]);
            $time_emp_two = TimeThai($row_user["date_report"]);

            $sql_select_emp = "SELECT first_name,last_name FROM users WHERE id = $user_emp";
            $result_select_emp = mysqli_query($conn,$sql_select_emp);
            $row_emp = mysqli_fetch_array($result_select_emp);
            $name = $row_emp['first_name'].' '. $row_emp['last_name'];

            foreach($result_msg as $list_list) {

                if($list_list['title'] == "admin"){
                    pushMessageToadmin($list_list['line_id'],$code_id,$name,$date_emp_one,$date_emp_two,$time_emp_one,$time_emp_two);
                }else if($list_list['title'] == "employee"){
                    pushMessageToEmp($list_list['line_id'],$code_id);
                }else{
                    pushMessageToadmin($list_list['line_id'],$code_id,$name,$date_emp_one,$date_emp_two,$time_emp_one,$time_emp_two);
                }
            }

            echo '<script>';
            echo "window.location='index.php?act=working&add=success';";
            echo '</script>';

        }else{
            echo '<script>';
            echo "window.location='index.php?add=erros';";
            echo '</script>';
        }
    }


    function DateThai($strDate)
    {

            $strYear = date("Y",strtotime($strDate))+543;

            $strMonth= date("n",strtotime($strDate));

            $strDay= date("j",strtotime($strDate));

            $strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");

            $strMonthThai=$strMonthCut[$strMonth];

            return "$strDay $strMonthThai $strYear";

    }


    function TimeThai($strDate)
    {

            $strHour= date("H",strtotime($strDate));

            $strMinute= date("i",strtotime($strDate));

            return "$strHour:$strMinute น. ";

    }

    function pushMessageToadmin($line_id,$code,$name_user,$date_one,$date_two,$time_one,$time_two){

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
                                                    "contents": [
                                                      {
                                                        "type": "text",
                                                        "text": "วันที่ '.$date_two.'",
                                                        "size": "sm",
                                                        "wrap": true,
                                                        "align": "start",
                                                        "gravity": "center",
                                                        "margin": "none"
                                                      },
                                                      {
                                                        "type": "text",
                                                        "text": "เวลา '.$time_two.'",
                                                        "margin": "none",
                                                        "size": "sm",
                                                        "wrap": true,
                                                        "gravity": "center",
                                                        "align": "start"
                                                      }
                                                    ],
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
                                                    "contents": [],
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
                                                    "contents": [
                                                      {
                                                        "type": "text",
                                                        "text": "วันที่ '.$date_one.'",
                                                        "size": "sm",
                                                        "wrap": true,
                                                        "align": "start",
                                                        "gravity": "center",
                                                        "margin": "none"
                                                      },
                                                      {
                                                        "type": "text",
                                                        "text": "เวลา '.$time_one.'",
                                                        "margin": "none",
                                                        "size": "sm",
                                                        "wrap": true,
                                                        "gravity": "center",
                                                        "align": "start"
                                                      }
                                                    ],
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
                                                        "weight": "bold",
                                                        "size": "lg"
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
                                                    "text": "กำลังดำเนินการ",
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
                                                    "text": "แจ้งสถานะการซ่อม 📣",
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
                                                    "text": "50%",
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
                                                        "width": "50%",
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
                                                        "text": "📋 สถานะ กำลังดำเนินการ",
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