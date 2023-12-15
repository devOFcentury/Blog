<?php

     function test_input($data)
     {
          $data = trim($data);
          $data = stripslashes($data);
          $data = htmlspecialchars($data);
          return $data;
     }

     function img_check(array $img) {
          if (isset($img) && $img['error'] == 0) {
               if ($img['error'] == 0) {
                    
                    if ($img['size'] <= 1000000) {
                         $pathfile = pathinfo($img['name']);
                         $extension_upload = $pathfile['extension'];
                         $extensions = array('png', 'jpg', 'jpeg');
                         $new_image_name = time().".".$extension_upload;  
                         if (in_array($extension_upload, $extensions)) {
                              return array(
                                   'tmp_img_name' => $img['tmp_name'],
                                   'new_img_name' => $new_image_name,
                                   'old_img_name' => $img['name']
                              );
                         }
     
                         return "this extension is not supported";
                    } 
                    
                    return 'the file is too big';
               } 

               return 'Error in the file to send';
               
          } 

          
     }


    