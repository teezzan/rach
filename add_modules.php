<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>File Share</title>
    
	<!--Stylesheets-->
	<link href="modules/en-file_share/css/jquery.filer.css" type="text/css" rel="stylesheet">
	<link href="modules/en-file_share/css/themes/jquery.filer-dragdropbox-theme.css" type="text/css" rel="stylesheet">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/mdb.min.css">
    
	<!--[if IE]>
          <script src="js/trunk/html5.js"></script>
    <![endif]-->
    
    <style>
        body {
            font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
            font-size: 14px;
            line-height: 1.42857143;
            color: #333;
            background-color: #fff;
            margin: 0;
            padding: 20px;
        }

        #header {
            margin-top: 30px;
        }
        
        hr {
            margin-top: 20px;
            margin-bottom: 20px;
            border: 0;
            border-top: 1px solid #eee;
        }

        #rachel {
            position: fixed;
            top: 0; left: 0;
            display: block;
            background: #000 url('assets/rachel-banner.png') no-repeat 5px 0;
            width: 100%;
            height: 28px;
            margin-bottom: 5px; 
            text-indent: -1000em;
        }

        .jFiler-input-dragDrop {
            margin: 0 0 25px 256px;
        }
        
    </style>
</head>

<body data-gr-c-s-loaded="true">

<div id="header" class="card">
    <h1 class="pl-4">File Share</h1>
    <!-- <p>You can upload files here to <a href="../../modules/">share with other users</a>.</p> -->
</div>

<hr>

<div id="content">
    <form action="add_modules.php" id="myForm" name="frmupload" method="post" enctype="multipart/form-data">
        <!-- <input type="file" name="file" id="filer_input2"> -->
        <input type="file" name="file" id="upload_file">
        <!-- <input type="submit" value="Extract" name="submit" class="jFiler-input-choose-btn blue"> -->
        <input type="submit" value="Extract" name="submit" class="btn btn-primary">
    </form>

    <?php
        if(isset($_POST['submit'])){    
            $array = explode(".",$_FILES["file"]["name"]);
            $fileName = $array[0];
            $fileExtension = strtolower(end($array));
            // var_dump($fileExtension);

            if($fileExtension == "zip") {
                if(is_dir("modules/"."en-".$fileName) == false) {
                    move_uploaded_file($_FILES["file"]["tmp_name"],"tmp/".$_FILES["file"]["name"]);
                    $zip = new ZipArchive();
                    $zip -> open("tmp/".$_FILES["file"]["name"]);
                    for($num = 0; $num < $zip->numFiles; $num++){
                        $fileInfo = $zip->statIndex($num);
                        // echo "Extracting: ".$fileInfo["name"];
                        $zip->extractTo("modules/"."en-".$fileName);
                    }

                    $zip->close();
                    unlink("tmp/".$_FILES["file"]["name"]);
                }else {
                    echo $fileName." has already been unzipped";
                }
            } else {
                echo "Only .zip files are allowed";
            }
        }
    ?>
</div>


    <script src="js/jquery.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/mdb.min.js"></script>
</body>
</html>