<?php
    
    class view{
        function htmlTag($tag, $input, $att=""){
            return "<$tag $att>$input</$tag>";
        }
        
        function makeForm($type, $extra=""){
            $att = "";
            foreach($type as $a=>$v){
                $att .= $a."='".$v."'";
            }
            return "<input $att $extra>";
        }
        
        function formDetails(){
            $in = $this->makeForm(
                            array(
                                "type"=>"text",
                                "name"=>"info",
                                "placeholder"=>"Insert JSON codes here"
                            ));
            $in .= $this->makeForm(
                            array(
                                "type"=>"submit",
                                "name"=>"submit",
                                "value"=>"Submit"
                            ));
            return $in;
        }
            
        function render($data){
            $head = file_get_contents("./head.php");
            $foot = file_get_contents("./foot.php");
            $form = $this->htmlTag("form", $this->formDetails(),"action='' method='post'");
            $table = $this->htmlTag("table", $data); 
            
            $html = $this->htmlTag("h1", "Convert <br> <span class='line'>Json array &rarr; tabular data</span>");
            $html .= $this->htmlTag("div", $form, "class='center-this'");
            $html .= $this->htmlTag("div", $table, "class='center-this table-c'"); 
            $html .= $this->htmlTag("canvas","Your browser does not support the HTML5 canvas tag","class='shadow'");
            $html .= $this->htmlTag("div", $foot, "class='foot'");
            $html = $this->htmlTag("head",$head).$html;
            $html = $this->htmlTag("html",$html);
            echo $html;
        }
    }

    class controller{
        function isAnArray($data){
            $out = "";
            foreach($data as $m => $n){
                if(is_array($n)){
                    $out.="<tr>";
                    foreach($data as $k => $m){
                        $out .= "<th>$k</th>";
                    }
                    $out.="</tr>";
                    $out.="<tr>";
                    foreach($data as $k => $m){
                        $out .= "<td>"."<table>".$this->isAnArray($m)."</table>"."</td>";
                    }
                    $out.="</tr>";
                    break;
                }else{
                    $out.="<tr>";
                    $out .= "<td>$m</td><td>$n</td>";
                    $out.="</tr>";
                }
            }
            return $out;
        }
        
        function main(){
            $table = "";
            $see = new view;            
            
            if(isset($_POST['submit'])){
                unset($_POST['submit']);
                if(empty($_POST['info'])){
                    echo $see->htmlTag("p", "It's empty!", "class='error-msg'");
                }else{
                    $data = json_decode($_POST['info'],true);
                    if(is_array($data)){
                        $table = $this->isAnArray($data);
                    }
                }
                    
            } //end if
            $see->render($table);
        }
    }


$launch = new controller;
$launch->main();

?>