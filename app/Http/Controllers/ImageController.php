<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImageController extends Controller
{
  public function blogimage($first,$second,$third){
 $location=public_path()."/".$first."/".$second."/".$third;
list($width,$height)= getimagesize($location);
$newheight=360;
$newwidth=360;
$source=imagecreatefrompng($location);
$thumb=imagecreatetruecolor($newwidth,$newheight);
imagecopyresampled($thumb,$source,0,0,0,0,$newheight,$newwidth,$width,$height);
return response()->file(imagepng($thumb));

}
}
