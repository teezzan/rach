

    <?php
        // if(isset($_POST['submit'])){    
            $array = explode(".",$_FILES["file1"]["name"]);
            $fileName = $array[0];
            $fileSize = $_FILES["file1"]["size"];
            $fileExtension = strtolower(end($array));
            // var_dump($fileExtension);

            if($fileExtension == "zip") {
                if(is_dir("modules/"."en-".$fileName) == false) {
                    move_uploaded_file($_FILES["file1"]["tmp_name"],"tmp/".$_FILES["file1"]["name"]);
                    $zip = new ZipArchive();
                    $zip -> open("tmp/".$_FILES["file1"]["name"]);
                    for($num = 0; $num < $zip->numFiles; $num++){
                        $fileInfo = $zip->statIndex($num);
                        // echo "Extracting: ".$fileInfo["name"];
                        echo '<script type="javascript">showLoading();</script>';
                        $zip->extractTo("modules/"."en-".$fileName);
                    }

                    $zip->close();
                    unlink("tmp/".$_FILES["file1"]["name"]);
                }else{
                echo $fileName." has already been unzipped";
                }
            } else {
                echo "Only .zip files are allowed";
            }
        // }
    ?>
