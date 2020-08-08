 <?php

 function resize_image($img, $max_resolution){

  if (file_exists($img)) {
    $original = imagecreatefromjpeg($img);

    //$original resolution

    $original_width = imagesx($original);
    $original_height = imagesy($original);

    //try width first
    if($original_height > $original_width) {
      $ratio = $max_resolution / $original_width;
      $new_width = $max_resolution;
      $new_height = $original_height * $ratio;

    /* $diff = $new_height - $new_width;
    $x = 0;
    $y = round($diff/2); */
    } 
    else{
    $ratio = $max_resolution / $original_height;
    $new_height = $max_resolution;
    $new_width = $original_width * $ratio;

    /* $diff = $new_width -  $new_height;
    $x = round($diff/2);
    $y = 0; */
    }

    if($original) {
    $new_image = imagecreatetruecolor($new_width, $new_height);
    imagecopyresampled($new_image, $original, 0, 0, 0, 0, $new_width, $new_height, $original_width, $original_height);

    $new_cropimage = imagecreatetruecolor($max_resolution, $max_resolution);
    imagecopyresampled($new_cropimage, $new_image, 0, 0, 0, 0, $max_resolution, $max_resolution, $max_resolution, $max_resolution);

    imagejpeg($new_cropimage, $img, 50);
    }
  }
}

if($_SERVER['REQUEST_METHOD'] == "POST"){
  if(isset($_FILES['image'])) {
    if ($_FILES['image']['type'] == 'image/jpeg' || $_FILES['image']['type'] == 'image/png') {

      $uploaded_file            = $_FILES['image']['tmp_name']; //uploaded file
      $uploaded_filename        = $_FILES['image']['name'];  //filename of uploaded file
      $file_extension           = end(explode(".", $uploaded_filename)); //get file extension e.g. jpeg or png
      $unique_name              = time() . mt_rand(); //generate random unique string based on time() & rand()
      $img_filename             = $unique_name . "." . $file_extension; //new imagename = unique name + old extension    

     move_uploaded_file($uploaded_file, $img_filename);
     
     $x = $_POST['x'];
     $y = $_POST['y'];
     resize_image($img_filename, "50");
   }
 }
}

?>

<h1>Collage Maker</h1>

<form  method="post" enctype="multipart/form-data">
  <div>
    <input type="file" name="image" class="text-input" required></input>
    <input type="number" name="x" class="text-input" placeholder="Enter x" min="1" max="16" required></input>
    <input type="number" name="y" class="text-input" placeholder="Enter y" min="1" max="16" required></input>
  </div><br>
  <input  type="submit" value="Make Collage"></input>
</form>

<?php

if($_SERVER['REQUEST_METHOD'] == "POST"){
  echo "<h1>Results</h1>";
}

$num_images = $x*$y;

for ($i=1; $i<=$num_images; $i++) { 

  echo "<img src='$img_filename'> ";

  if($i % $y == 0){

    echo "<br>";
  }
}

?>

