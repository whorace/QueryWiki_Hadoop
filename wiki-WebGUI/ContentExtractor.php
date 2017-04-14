<?php
class contentExtractor{
   	public function getHightlighted($content_t,$highlight_position){ 
   		$content="";
   		$lastposition=0;
   		$window=20;
   		for($n=0;$n<count($highlight_position);$n+=2){
            $qurey_length=$highlight_position[$n+1];
            
   			if($highlight_position[$n]-$lastposition>$window){
   				$content=$content."...".mb_strimwidth($content_t,$highlight_position[$n]-$window,$window,'','utf8')."<strong>".mb_strimwidth($content_t,$highlight_position[$n],$qurey_length,'','utf8')."</strong>";
   			}else
            {
   				 	// $content=$content.substr($content_t,$lastposition,$highlight_position[$n]-$lastposition)."<strong>".substr($content_t,$highlight_position[$n],$qurey_length)."</strong>";

               $content=$content.mb_strimwidth($content_t, $lastposition, $highlight_position[$n]-$lastposition, '', 'utf8')."<strong>".mb_strimwidth($content_t,$highlight_position[$n],$qurey_length,'','utf8')."</strong>";

   			}
   			$lastposition=$highlight_position[$n]+$qurey_length;
   			// echo $highlight_position[$n];
   		}

   		if(strlen($content)<200)
   			$content=$content.substr($content_t,$lastposition,300-strlen($content));
   		return $content;
   	}
public function getfullHightlighted($content_t,$highlight_position){ 
         $content="";
         $lastposition=0;
         $window=20;
         for($n=0;$n<count($highlight_position);$n+=2){
            $qurey_length=$highlight_position[$n+1];
            
            // if($highlight_position[$n]-$lastposition>$window){
            //    $content=$content."...".mb_strimwidth($content_t,$highlight_position[$n]-$window,$window,'','utf8')."<strong>".mb_strimwidth($content_t,$highlight_position[$n],$qurey_length,'','utf8')."</strong>";
            // }else
            {
                  // $content=$content.substr($content_t,$lastposition,$highlight_position[$n]-$lastposition)."<strong>".substr($content_t,$highlight_position[$n],$qurey_length)."</strong>";

               $content=$content.mb_strimwidth($content_t, $lastposition, $highlight_position[$n]-$lastposition, '', 'utf8')."<strong>".mb_strimwidth($content_t,$highlight_position[$n],$qurey_length,'','utf8')."</strong>";

            }
            $lastposition=$highlight_position[$n]+$qurey_length;
            // echo $highlight_position[$n];
         }

         // if(strlen($content)<200)
            $content=$content.substr($content_t,$lastposition,strlen($content_t)-$lastposition);
         return $content;
      }
}
?>