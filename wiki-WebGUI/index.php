<?php

function my_exec($cmd, $input='') 
         {$proc=proc_open($cmd, array(0=>array('pipe', 'r'), 1=>array('pipe', 'w'), 2=>array('pipe', 'w')), $pipes); 
          fwrite($pipes[0], $input);fclose($pipes[0]); 
          $stdout=stream_get_contents($pipes[1]);fclose($pipes[1]); 
          $stderr=stream_get_contents($pipes[2]);fclose($pipes[2]); 
          $rtn=proc_close($proc); 
          return array('stdout'=>$stdout, 
                       'stderr'=>$stderr, 
                       'return'=>$rtn 
                      ); 
         }
  $enter=$_POST["inputValue"];
  $result_index=$_POST["result_index"];
  
  echo $result_index;
  // var_export(my_exec("/Library/Frameworks/Python.framework/Versions/2.7/bin/python2.7  server_connect.py dog"));
  // for($i=0;$i<count($array);$i++)
  //   echo($array[$i]."\n");
  //   echo("ret is $ret");
  
  if($enter==null&&$result_index==null)
  {
    // exec("python delete.py",$array,$ret);
    // echo("result : $array"."\n");
    // echo("ret is $ret");
  }else{
    
    echo $enter;
    exec("/Library/Frameworks/Python.framework/Versions/2.7/bin/python2.7  server_connect.py"." ".$enter);
require_once(dirname(__FILE__)."/ContentExtractor.php");
  
$path="";
// $myfile = fopen("/Applications/MAMP/htdocs/wiki/test_output.txt", "r") or die("Unable to open file!");
// $text= fread($myfile,filesize("/Applications/MAMP/htdocs/wiki/test_output.txt"));
$text=file_get_contents("/Applications/MAMP/htdocs/wiki/test_output.txt");


$title = array();
$content = array();

preg_match_all("/\<title\>(.*?)\<\/title\>/",$text,$title);
preg_match_all("/\<content\>(.*?)\<\/content\>/s",$text,$content);
preg_match_all("/\<positions\>(.*?)\<\/positions\>/",$text,$position);
echo count($title[1]);
preg_match_all("/\<count\>(.*?)\<\/count\>/",$text,$result_count_array);
$result_count=$result_count_array[1][0];
echo $result_count;
//$result_count=min(3000,count($title[1]));
$show_count=30;
$page_count=$result_count%$show_count==0?$result_count/$show_count:$result_count/$show_count+1;

$result_title=array();
$result_content=array();
$result_position=array();

// $query='Farquhar';
// $qurey_length=strlen($query);

for($n=0;$n<=$result_count;$n++){
  // get title
  
  array_push($result_title,$title[1][$n]);
  // get content
  $order=array("\r\n","\n","\r");
  $replace="<br/>";
  //$trans_content=tr_replace($order,$replace,$content[1][$n]); 
  $content[1][$n]=preg_replace('/\n|\r\n|\r|\s/',' ',$content[1][$n]);
  
  array_push($result_content,$content[1][$n]);
  // get highlight position for each article
  // $result_position_row=array();

  // for($i=0;$i<10;$i++){
  //   //this is a fake value, you define the way you read value by your self;
  //   $position=($i+$n)*20;
  //   array_push($result_position_row,$position);
  // }
  preg_match_all("/\<word\>(.*?)\<\/word\>/s",$position[1][$n],$word);
  $result_position_row=array();
  $num_word=count($word[1]);
  for($w=0;$w<$num_word;$w++){
    $seperate=explode("#",$word[1][$w]);
    $word_len=strlen($seperate[0]);
    $word_pos=explode(",",$seperate[1]);
    // echo $word_len."\n".$seperate[0]."\n".$word[1][$w]."\n";
    for($u=0;$u<count($word_pos);$u++){
      array_push($result_position_row, $word_pos[$u]);
      array_push($result_position_row, $word_len);
      
      // echo $word_len;
    }

  }
  // $position_array=explode(",",$position[1][$n]);
  // $position_len=count($position_array);
  // for($i=0;$i<$position_len;$i++){

  //   array_push($result_position,$position_array[$i]);
    

  // }
  //push the highlight position for the article in the two demenion array

  array_push($result_position,$result_position_row);
   
}
 

 $contentextractor=new contentExtractor();   

  }
  
  
?>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Wiki Search</title>
    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
</head>
<script>
      function clicke(){
        var result=document.getElementById("query_blank").value;
        var id = 10;
        //alert(result);
        show(result);
      
        $.post("/Applications/MAMP/htdocs/wiki/try.php",
    {
        name:"fd",
        url:"http://www.runoob.com"
    },
        function(data,status){
        alert("data: \n" + data + "\nstatus: " + status);
    });
     }
     // function myFunction(){

     //    var a=myform.inputValue.value;
     //    alert(a);
     //    if(a==null || a==1){

     //    }
     //    else{

     //    }_

     // }

   
   </script>
<body role="document">
    <div class="container theme-showcase" role="main">
      <!-- Main jumbotron for a primary marketing message or call to action -->
      <div class="jumbotron">
        <h1>Wiki Search!</h1>
        <form name="myform" action="index.php" method="post">
            <div class="form-group">
              <input name="inputValue" type="text" class="form-control" placeholder="key words" id="query_blank">
            </div>
            <button class="btn btn-primary" type="submit" >Search</button> 
        </form> 
        
      </div>

      <div class="jumbotron">

      <p><small>About <?php echo $result_count ?> results</small></p>
     <!--  <form action="showarticle.php" method="get"> -->
      <table class="table table-striped table-bordered table-hover"><tbody>
        <?php
              //echo $result_index."print";
              $page_index=$_POST["current_page"];
              //echo $page_index;
              $page_add=0;
              if($page_index!=null){
                $page_add=$page_index*$show_count;
              }

              if($result_index==null||$result_index==-1){
                $begin=$page_add;
                $end=$show_count+$page_add;
              
              echo " ".$begin.$end;

              for ($n = $begin; $n <= $end; $n++) 
              {
                
                // echo "<div class='form-group'>"
                // echo "<input type='hidden' name='title' value='".$result_title[$n]."'/>"
                // echo "<button class='btn btn-primary' type='submit'>123<button>"
                $show=$contentextractor->getHightlighted($result_content[$n],$result_position[$n]);
                // if($show==null)continue;
                echo "<tr><td>";
                echo "<p><strong>";
                
                echo '<form  action="index.php?" method="post"><input name="result_index" type="hidden" value="'.$n.'">';
              
                echo $result_title[$n];
                echo "</strong></p>";
                echo "<p><small>";
                echo $show;
                echo "</small></p>";
                $title=$result_title[$n];
                
                
                
         
               echo '<button class="btn btn-primary" type="submit" >See the details</button>';
               echo "</form>";
                
                echo "</td></tr>";} 
              }
              else{
                $begin=$result_index;
                $end=$result_index;
              
              echo " ".$begin.$end;

              for ($n = $begin; $n <= $end; $n++) 
              {
                
                // echo "<div class='form-group'>"
                // echo "<input type='hidden' name='title' value='".$result_title[$n]."'/>"
                // echo "<button class='btn btn-primary' type='submit'>123<button>"
                $show=$contentextractor->getfullHightlighted($result_content[$n],$result_position[$n]);
                // if($show==null)continue;
                echo "<tr><td>";
                echo "<p><strong>";
                
                echo '<form  action="index.php?" method="post"><input name="result_index" type="hidden" value="'.(-1).'">';
              
                echo $result_title[$n];
                echo "</strong></p>";
                echo "<p><small>";
                echo $show;
                echo "</small></p>";
                $title=$result_title[$n];
                
                
                
         
               echo '<button class="btn btn-primary" type="submit" >Back</button>';
               echo "</form>";
                
                echo "</td></tr>";} 

              }
        ?>
          
      </tbody>
      </table>
      <!-- </form> -->
        <ul class="pagination pagination-sm">
           <li><a href="#">Prev</a></li>
            <?php
              for ($n = 0; $n <= $page_count; $n++) 
              {
                
                echo '<li><a href="#" name="current_page" method="post">';
                echo $n;
                echo "</a></li>";
                
              } 
              ?>
              <li><a href="#">Next</a></li>
          </ul>
      </div>        
    </div> <!-- /container -->
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<!--     <script type="text/javascript">
      $(function() {
        $('.pagination li a').click(function() { 
            dstpage = $(this).data('page');
            if (dstpage == 0)
                return; 
            $('#idCurPage').val(dstpage);
            cur = $('#idCurPage').val();
            $('form').submit();
            return false;
        });
    });
    </script> -->
</body>
</html>
