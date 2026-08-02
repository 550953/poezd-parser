<?php
 include '../examples/train_carriages.php'; 
 $from = $_GET['from'];
 $to = $_GET['to'];
 $date = $_GET['date'];
 $time = $_GET['time'];
 $train_number = $_GET['train'];
 


$error = 0;
for($i=0;  $i<10;$i++){
    $array_ = getTrainCars($from,$to,$date,$time,$train_number);
     if($array_['cars'] == null){
        $all_mass = array('messege' => 'false');
        $error = 1;
    }else{
        $error = 0;
        //echo "NOT NULL";
        break;
    }
 }
 
 
if($error != 1){ 
$array_cars = $array_['cars'];
// echo "<pre>";
//  print_r($array_cars);
//  echo "<pre>";
 
     foreach ($array_cars as $car){
        $number = $car['cnumber'];
        $free = $car['seats'];
        $seats = $car['places'];
        $final_seat = array();
        $freeseat = 0;
        foreach($free as $seat){
            $freeseat += $seat['free'];
        }
        
        $seats_arr = explode(",", $seats);
        $findme = "-";
        $seat_2 = array();
        foreach($seats_arr as $seat){
            $search = stripos($seat, $findme);
            if($search === false){
                $seat_2[] = $seat;
            }else{
              //  echo $seat."<br>";
                $lin_arr = explode($findme, $seat);
                for ($i = $lin_arr[0]; $i <= $lin_arr[1]; $i++){
                  //  echo $i."<br>";
                    $seat_2[] = $i;
                }
            }
            
        }
        
        foreach($seat_2 as $se){
            //  echo $se."<br>";
             $se = substr($se, 0, 3);
             // echo $se."<br>";
              //echo strlen($se)."<br>";
               $final_seat[] = $se;
            //   if(strlen($se) == 3){
            
            //     $se = substr($se, 1, 3);
            //     if( $se{0} == 0){
            //         $se = $se{1};
            //     }
      
            //       $final_seat[] = $se;
                  
                  
            //   }else{
            //       if( $se{0} == 0){
            //         $se = $se{1};
            //     }
            //       $final_seat[] = $se;
            //   }
        }
        
        //echo "VAgon ".$number."<br>";
      //  echo "freeseat ".$freeseat."<br>";
        $result_arr[] =  array(
                "number" => $number,
                "free" => $freeseat,
                "seats" => $final_seat
                );
     }
  $all_mass = array("data" => $result_arr);
}
 

//$all_mass = array('messege' => 'false');
 echo json_encode($all_mass);
?>