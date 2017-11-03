<?php
    // references -> http://page.math.tu-berlin.de/~kant/teaching/hess/krypto-ws2006/des.htm
    
    // key-64 and M(Message) must be random.
    // in particular, M is able to be shorter or much better longer than 64-bit (maybe cutting and fetching is needed)
    // key = 0x133457799BBCDFF1
    $key_64 = array(0,0,0,1,0,0,1,1,0,0,1,1,0,1,0,0,0,1,0,1,0,1,1,1,0,1,1,1,1,0,0,1,1,0,0,1,1,0,1,1,1,0,1,1,1,1,0,0,1,1,0,1,1,1,1,1,1,1,1,1,0,0,0,1);
    // message = 0x0123456789ABCDEF
    $M = array(0,0,0,0,0,0,0,1,0,0,1,0,0,0,1,1,0,1,0,0,0,1,0,1,0,1,1,0,0,1,1,1,1,0,0,0,1,0,0,1,1,0,1,0,1,0,1,1,1,1,0,0,1,1,0,1,1,1,1,0,1,1,1,1,);


    // Initial permutation (IP)
    $IP = array(58, 50, 42, 34, 26, 18, 10, 2,
                60, 52, 44, 36, 28, 20, 12, 4,
                62, 54, 46, 38, 30, 22, 14, 6,
                64, 56, 48, 40, 32, 24, 16, 8,
                57, 49, 41, 33, 25, 17,  9, 1,
                59, 51, 43, 35, 27, 19, 11, 3,
                61, 53, 45, 37, 29, 21, 13, 5,
                63, 55, 47, 39, 31, 23, 15, 7);

    // Final permutation (IP-1)
    $IP_1 = array(40,  8, 48, 16, 56, 24, 64, 32,
                  39,  7, 47, 15, 55, 23, 63, 31,
                  38,  6, 46, 14, 54, 22, 62, 30,
                  37,  5, 45, 13, 53, 21, 61, 29,
                  36,  4, 44, 12, 52, 20, 60, 28,
                  35,  3, 43, 11, 51, 19, 59, 27,
                  34,  2, 42, 10, 50, 18, 58, 26,
                  33,  1, 41,  9, 49, 17, 57, 25);

    // Expansion function (E)
    $E = array(32,  1,  2,  3,  4,  5,
                4,  5,  6,  7,  8,  9,
                8,  9, 10, 11, 12, 13,
               12, 13, 14, 15, 16, 17,
               16, 17, 18, 19, 20, 21,
               20, 21, 22, 23, 24, 25,
               24, 25, 26, 27, 28, 29,
               28, 29, 30, 31, 32,  1);

    // Permutation (P)
    $P = array(16,  7, 20, 21,
               29, 12, 28, 17,
                1, 15, 23, 26,
                5, 18, 31, 10,
                2,  8, 24, 14,
               32, 27,  3,  9,
               19, 13, 30,  6,
               22, 11,  4, 25);

    // Permuted choice 1 (PC-1)
    $PC1 = array(57, 49,  41, 33,  25,  17,  9,
                  1, 58,  50, 42,  34,  26, 18,
                 10,  2,  59, 51,  43,  35, 27,
                 19, 11,   3, 60,  52,  44, 36,
                 63, 55,  47, 39,  31,  23, 15,
                  7, 62,  54, 46,  38,  30, 22,
                 14,  6,  61, 53,  45,  37, 29,
                 21, 13,   5, 28,  20,  12,  4);
    // Permuted choice 2 (PC-2)
    $PC2 = array(14, 17, 11, 24,  1,  5,
                  3, 28, 15,  6, 21, 10,
                 23, 19, 12,  4, 26,  8,
                 16,  7, 27, 20, 13,  2,
                 41, 52, 31, 37, 47, 55,
                 30, 40, 51, 45, 33, 48,
                 44, 49, 39, 56, 34, 53,
                 46, 42, 50, 36, 29, 32);

    // 8 S-boxes
    $S1 = array(14,  4, 13,  1,  2, 15, 11,  8,  3, 10,  6, 12,  5,  9,  0,  7,
                 0, 15,  7,  4, 14,  2, 13,  1, 10,  6, 12, 11,  9,  5,  3,  8,
                 4,  1, 14,  8, 13,  6,  2, 11, 15, 12,  9,  7,  3, 10,  5,  0,
                15, 12,  8,  2,  4,  9,  1,  7,  5, 11,  3, 14, 10,  0,  6, 13);

    $S2 = array(15,  1,  8, 14,  6, 11,  3,  4,  9,  7,  2, 13, 12,  0,  5, 10,
                 3, 13,  4,  7, 15,  2,  8, 14, 12,  0,  1, 10,  6,  9, 11,  5,
                 0, 14,  7, 11, 10,  4, 13,  1,  5,  8, 12,  6,  9,  3,  2, 15,
                13,  8, 10,  1,  3, 15,  4,  2, 11,  6,  7, 12,  0,  5, 14,  9);

    $S3 = array(10,  0,  9, 14,  6,  3, 15,  5,  1, 13, 12,  7, 11,  4,  2,  8,
                13,  7,  0,  9,  3,  4,  6, 10,  2,  8,  5, 14, 12, 11, 15,  1,
                13,  6,  4,  9,  8, 15,  3,  0, 11,  1,  2, 12,  5, 10, 14,  7,
                 1, 10, 13,  0,  6,  9,  8,  7,  4, 15, 14,  3, 11,  5,  2, 12);

    $S4 = array( 7, 13, 14,  3,  0,  6,  9, 10,  1,  2,  8,  5, 11, 12,  4, 15,
                13,  8, 11,  5,  6, 15,  0,  3,  4,  7,  2, 12,  1, 10, 14,  9,
                10,  6,  9,  0, 12, 11,  7, 13, 15,  1,  3, 14,  5,  2,  8,  4,
                 3, 15,  0,  6, 10,  1, 13,  8,  9,  4,  5, 11, 12,  7,  2, 14);

    $S5 = array( 2, 12,  4,  1,  7, 10, 11,  6,  8,  5,  3, 15, 13,  0, 14,  9,
                14, 11,  2, 12,  4,  7, 13,  1,  5,  0, 15, 10,  3,  9,  8,  6,
                 4,  2,  1, 11, 10, 13,  7,  8, 15,  9, 12,  5,  6,  3,  0, 14,
                11,  8, 12,  7,  1, 14,  2, 13,  6, 15,  0,  9, 10,  4,  5,  3);

    $S6 = array(12,  1, 10, 15,  9,  2,  6,  8,  0, 13,  3,  4, 14,  7,  5, 11,
                10, 15,  4,  2,  7, 12,  9,  5,  6,  1, 13, 14,  0, 11,  3,  8,
                 9, 14, 15,  5,  2,  8, 12,  3,  7,  0,  4, 10,  1, 13, 11,  6,
                 4,  3,  2, 12,  9,  5, 15, 10, 11, 14,  1,  7,  6,  0,  8, 13);

    $S7 = array( 4, 11,  2, 14, 15,  0,  8, 13,  3, 12,  9,  7,  5, 10,  6,  1,
                13,  0, 11,  7,  4,  9,  1, 10, 14,  3,  5, 12,  2, 15,  8,  6,
                 1,  4, 11, 13, 12,  3,  7, 14, 10, 15,  6,  8,  0,  5,  9,  2,
                 6, 11, 13,  8,  1,  4, 10,  7,  9,  5,  0, 15, 14,  2,  3, 12);

    $S8 = array(13,  2,  8,  4,  6, 15, 11,  1, 10,  9,  3, 14,  5,  0, 12,  7,
                 1, 15, 13,  8, 10,  3,  7,  4, 12,  5,  6, 11,  0, 14,  9,  2,
                 7, 11,  4,  1,  9, 12, 14,  2,  0,  6, 10, 13, 15,  3,  5,  8,
                 2,  1, 14,  7,  4, 10,  8, 13, 15, 12,  9,  0,  3,  5,  6, 11);


    $shift_schedule = array(1, 1, 1, 2, 2, 2, 2, 2, 2, 1, 2, 2, 2, 2, 2, 2, 1);

    // 64-bit key generate
    function random_key_generator(){
      $key_array = "";

      for($i=0; $i<64; $i++){
        $key_array[$i] = mt_rand()%2;
      }

      return $key_array;
    }

    // 56-bit key permutate
    function permutate_key($key_64, $PC1){
      $permutated_key = array();

      for($i = 0; $i < 56; $i++ ){
        // $initial_key_permutation(PC) used
        // index must be -1!!
        $permutated_key[$i] = $key_64[$PC1[$i]-1];
      }

      return $permutated_key;
    }

    // devide 56-bit key into C and D array
    // two function is slightly different. (index)
    function init_C($key_56){
      $C = array();
      for($i=0; $i<28; $i++){
        $C[$i] = $key_56[$i];
      }

      return $C;
    }
    function init_D($key_56){
      $D = array();
      for($i=0; $i<28; $i++){
        $D[$i] = $key_56[28+$i];
      }

      return $D;
    }
    
    // for rotate
    function shift_left_rotate($CorD){
      $shifted = array_shift($CorD);
      array_push($CorD, $shifted);
      return $CorD;
    }

    // apply rotational shift to C and D array
    function shift_left_CD($CorD, $shift_schedule){
      $arr[16][28] = array();
      for($i=0; $i<28; $i++){
        $arr[0][$i] = $CorD[$i];
      }
      $arr[0]=shift_left_rotate($arr[0]);

      for($i=1; $i<16; $i++){
        for($j=0; $j<28; $j++){
          $arr[$i][$j]=$arr[$i-1][$j];
        }

        if($shift_schedule[$i]==2)
        {
          $arr[$i]=shift_left_rotate($arr[$i]);
          $arr[$i]=shift_left_rotate($arr[$i]);
        }
        else
        {
          $arr[$i]=shift_left_rotate($arr[$i]);
        }
      }

      return $arr;
    }

    // merge C and D array and permutate for generate K_16 array
    function init_K($C_16, $D_16, $PC2){
      $K_16[16][48] = array();
      $temp[16][56] = array();

      for($i=0; $i<16; $i++){
        for($j=0; $j<56; $j++){
          if($j<28)
          {
            $temp[$i][$j] = $C_16[$i][$j];
          }
          else{
            $temp[$i][$j] = $D_16[$i][$j-28];
          }
        }
      }

      for($i=0; $i<16; $i++){
        for($j=0; $j<48; $j++){
          $K_16[$i][$j]=$temp[$i][$PC2[$j]-1];
        }
      }

      return $K_16;
    }

    function permutate_M($M, $IP){
      $IP_M = array();

      for($i=0; $i<64; $i++){
        // index must minus 1 !!
        $IP_M[$i] = $M[$IP[$i]-1];
      }

      return $IP_M;
    }

    // devide 64-bit message into L and R array
    // two function is slightly different. (index)
    function init_L($IP_M){
      $L = array();
      for($i=0; $i<32; $i++){
        $L[$i] = $IP_M[$i];
      }

      return $L;
    }
    function init_R($IP_M){
      $R = array();
      for($i=0; $i<32; $i++){
        $R[$i] = $IP_M[32+$i];
      }

      return $R;
    }

    // R bit expansion refer to E BIT-SELECTION
    function bit_expansion($R, $E){
      $EXP_R = array();

      for($i=0; $i<48; $i++){
        $EXP_R[$i] = $R[$E[$i]-1];
      }

      return $EXP_R;
    }

    // Feistel rotation
    function feistel_rotation($L, $R, $K_16){

    }





    /* * * * * * * * * * * * * * * * * * * */
    /*             for test                */      
    /* * * * * * * * * * * * * * * * * * * */

    $key_56 = permutate_key($key_64,$PC1);
    $C = init_C($key_56);
    $D = init_D($key_56);
    $C_16 = shift_left_CD($C, $shift_schedule);
    $D_16 = shift_left_CD($D, $shift_schedule);
    $K_16 = init_K($C_16, $D_16, $PC2);
    $IP_M = permutate_M($M, $IP);
    $L = init_L($IP_M);
    $R = init_R($IP_M);
    $EXP_R = bit_expansion($R, $E);

    foreach($key_64 as $value){
      echo $value;
    }
    echo "<br/>";
    echo "<br/>";
    foreach($key_56 as $value){
      echo $value;
    }
    echo "<br/>";
    echo "<br/>C : ";
    foreach($C as $value){
      echo $value;
    }
    echo "<br/>D : ";
    foreach($D as $value){
      echo $value;
    }
    echo "<br/>";
    echo "<br/>";

    for($i=0; $i<16; $i++){
      echo "C".$i.": ";
      for($j=0; $j<28; $j++){
        echo $C_16[$i][$j];
      }
      echo "<br/>";
      echo "D".$i.": ";
      for($j=0; $j<28; $j++){
        echo $D_16[$i][$j];
      }
      echo "<br/>";
      echo "<br/>";
    }
    for($i=0; $i<16; $i++){
      echo "<br/>"."K".$i.": ";
      for($j=0; $j<48; $j++){
        echo $K_16[$i][$j];
      }
    }
    echo "<br/>";
    echo "<br/>M : ";
    foreach($M as $value){
      echo $value;
    }echo "<br/>IP_M : ";
    foreach($IP_M as $value){
      echo $value;
    }
    echo "<br/>";
    echo "<br/>L0 : ";
     foreach($L as $value){
      echo $value;
    }
    echo "<br/>R0 : ";
    foreach($R as $value){
      echo $value;
    }
    echo "<br/>E(R0) : ";
    foreach($EXP_R as $value){
      echo $value;
    }

?>