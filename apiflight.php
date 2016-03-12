<?php
require ('php/scraperwiki.php');
    /******************
    * sort them doan javascrip vao
    * VN em be =  10%;
    *    tre em = 75%;
    * VJ embe = 10 %
    *    tre em = 100%
    * JET embe = 0%;
    *     treem = 100%
    * AIRmekong embe = 0%
    *     treem = 75%  
    * supoort@inet.vn
    ********************/   
class APIFlight 
{

private $cookies = '';private $allcookies = '' ;
private $config_special_air = 0;
private $config_taxairvn = 0;
private $config_taxsoichieuvn = 0;                
private $config_txtcomvn = 0;
private $config_taxquantrivn = 0;
private $config_taxquantrivj = 0;
private $config_taxsoichieuvj = 0;
private $config_taxairvj = 0;
private $config_taxquantrijet = 0;
private $config_txtcomjet = 0;
private $config_taxairjet = 0;
private $config_txtcomvj  = 0;
private $config_taxsoichieujet =0;
public function index(){ 
//$time_start = microtime(true);
// Sleep for a while
//usleep(100);
if($_SERVER['REQUEST_METHOD'] == 'POST'){
$this->curl();
$airline = $_GET['airline'];
if($airline == "VJ"){
$this->config_taxquantrivj = $_POST['config_taxquantrivj'];
$this->config_taxsoichieuvj = $_POST['config_taxsoichieuvj'];
$this->config_taxairvj = $_POST['config_taxairvj'];
$this->config_txtcomvj  = $_POST['config_txtcomvj'];
        /** get vietjet **/ 
        $_POST = $this->convert_Vietjet($_POST);
        $urlpost = "https://book.vietjetair.com/ameliapost.aspx?lang=vi"; 
         $result = $this->makeRequest('post',$urlpost,$_SERVER['REQUEST_URI'],$_POST,''); 
            $html = str_get_html($result['EXE']);
            if(method_exists($html,"find")){
                foreach($html->find('input[name="__VIEWSTATE"]') as $viewstate){
                    $_POST['__VIEWSTATE'] =    $viewstate->value;break;
                }  
            }
            $result = $this->makeRequest('post',$urlpost,$_SERVER['REQUEST_URI'],$_POST,'');           
            $html_vietjet = str_get_html($result['EXE']);
            $array_vjresult = ($this->get_vietjet($html_vietjet,$_POST['direction']));
            echo  json_encode($array_vjresult);   
            
    }elseif($airline == "VN"){
        $this->config_special_air = $_POST['config_special_air'];
        $this->config_taxairvn = $_POST['config_taxairvn'];
        $this->config_taxsoichieuvn = $_POST['config_taxsoichieuvn'];
        $this->config_txtcomvn = $_POST['config_txtcomvn'];
        $this->config_txtcomvn = $_POST['config_txtcomvn'];
        $this->config_taxquantrivn = $_POST['config_taxquantrivn'];
        //get vietnamairlines 
        $url = "https://wl-prod.sabresonicweb.com/SSW2010/B3QE/webqtrip.html?searchType=NORMAL";
        $url = $this->Convert_VNairline($url); 
		
        $responst = $this->makeRequest('get', $url, $_SERVER['REQUEST_URI'],'', $this->cookies);
        $html_vn = str_get_html($responst['EXE']);  
        echo   json_encode($this->get_vnairline($html_vn,$_POST['direction']));
    }else{   
        $this->config_taxquantrijet = $_POST['config_taxquantrijet'];
        $this->config_txtcomjet = $_POST['config_txtcomjet'];
        $this->config_taxairjet = $_POST['config_taxairjet'];
        $this->config_taxsoichieujet =$_POST['config_taxsoichieujet']; 
           $responst_jet = $this->makeRequest('post','http://book.jetstar.com/Search.aspx',$_SERVER['REQUEST_URI'],$this->conver_Jettart($_POST),'');
            $str =  str_get_html(($responst_jet['EXE'])) ;
            if($str!=""){
                if($_POST['direction']=="0"){    
                    if (method_exists($str,"find")) {
                        $dometics = $str->find('table[class="domestic"]');
                        foreach($dometics as $dometic){
                            $doematic[] =  $dometic->outertext;
                        }
                    }
                }else{
                    if(method_exists($str,"find")){
                        $dometics = $str->find('table[class="domestic"]');
                        foreach($dometics as $dometic){
                            $doematic[] =  $dometic->outertext;
                        }   
                    }
                }
                if(isset($doematic)){
                        $array_jsresult = ($this->get_jetstart($doematic,$_POST['direction'],$_POST['depdate']));
                } 
                echo json_encode($array_jsresult);    
            }
       } 
    }    
    //echo $data_result;   
    /*$time_end = microtime(true);
    $time = $time_end - $time_start;
    echo "Đoạn chương trình đã thực thi trong $time giây \n";*/
 }
  /** function getVN_AIRline **/
/**
* $direction la bien loai ve khu hoi hay 1 chiu
*      
**/
function get_vnairline($html,$loaive)
{ 
// $arrcode = array("SGN","HAN","HPH","HPH","DAD","PXU");
$typepri_arr = array();
$dep = $_POST['dep'];$des =$_POST['des'];
 $adult = $_POST['adult'];
 $child = $_POST['child'];
 $inf = $_POST['infant'];
$direction = 'tr[id=row_out_0]';$vnairline= NULL;
 if($this->config_special_air){
  $price_special = $this->config_special_air;
}else{
  $price_special = 1;
}
$arrayDep=array();$arrayDic = array();
$arrayDeptime = array();$arrayDictime= array();$arraystop= array();$arrayCode = array();$arrayNameplain= array();$arraySS=array();$arrayloaive = array();
$chart = array(" ",",","VND","&nbsp;");
if($loaive == 0){
    $i=0;
    foreach($html->find('div[id="dtcontainer-both"]') as $detail){
        
        foreach($detail->find('th[id]') as $th){
            array_push($typepri_arr, $th->id);   
        }
        //print_r($typepri_arr);exit;
        $n =  count($typepri_arr);
        foreach($detail->find('tbody tr') as $tr){  
		
		//echo (int)str_replace($chart,'',$tr->children(($n-7))->first_child()->outertext);exit;
            if( isset($tr->children(0)->first_child()->first_child()->plaintext) && $tr->children(0)->first_child()->first_child()->plaintext!=""){
                if(strpos($tr->first_child()->innertext,"No flights are available for the selected date. Please choose alternate date.")==FALSE){       
                    if(strpos($tr->children($n-1),"Sold out")==true || (!isset($tr->children($n-1)->innertext))){                 
                        if(strpos($tr->children($n-2),"Sold out")==true || (!isset($tr->children($n-2)->innertext)) ){
                            if(strpos($tr->children($n-3),"Sold out")==true || (!isset($tr->children($n-3)->innertext)) ){
                                if(strpos($tr->children($n-4),"Sold out")==true || (!isset($tr->children($n-4)->innertext)) ){
                                   if(strpos($tr->children($n-5),"Sold out")==true || (!isset($tr->children($n-5)->innertext)) ){
									    if(strpos($tr->children($n-6),"Sold out")==true || (!isset($tr->children($n-6)->innertext)) ){
											
											if(strpos($tr->children($n-7),"Sold out")==true || (!isset($tr->children($n-7)->innertext)) ){
											  
								           }else{
											    $typeprice  = $typepri_arr[$n-7];
                                                $pri =  (int)str_replace($chart,'',$tr->children(($n-7))->first_child()->first_child()->first_child()->children(1)->first_child()->first_child()->first_child()->plaintext);                                  
										   }
										 
								        }else{
											 $typeprice  = $typepri_arr[$n-6];
                                              $pri =  (int)str_replace($chart,'',$tr->children(($n-6))->first_child()->first_child()->first_child()->children(1)->first_child()->first_child()->first_child()->plaintext);                                  
										}
								   }else{
									   $typeprice  = $typepri_arr[$n-5];
                                              $pri =  (int)str_replace($chart,'',$tr->children(($n-5))->first_child()->first_child()->first_child()->children(1)->first_child()->first_child()->first_child()->plaintext);
								   }
                                }else{
									 $typeprice  = $typepri_arr[$n-4];
                                     $pri =  (int)str_replace($chart,'',$tr->children(($n-4))->first_child()->first_child()->first_child()->children(1)->first_child()->first_child()->first_child()->plaintext);
                                          
                                }
                            }else{
                                $typeprice = $typepri_arr[$n-3];
                                $pri =  (int)str_replace($chart,'',$tr->children(($n-3))->first_child()->first_child()->first_child()->children(1)->first_child()->first_child()->first_child()->plaintext);
                                
                            }  
                        }else{ 
                           
                            $typeprice = $typepri_arr[$n-2];        
                            $pri =  (int)str_replace($chart,'',$tr->children(($n-2))->first_child()->first_child()->first_child()->children(1)->first_child()->first_child()->first_child()->plaintext);
                           
                        }
                    }else{ 
                        $pri =  ((int)str_replace($chart,'',$tr->children($n-1)->first_child()->first_child()->first_child()->children(1)->first_child()->first_child()->first_child()->plaintext) );                        
                        $typeprice = $typepri_arr[$n-1];
                    }
                }    

 if($typeprice == "both-ES" || $typeprice == "outbounds-ES") {
      $config_g = $this->config_txtcomvn*$price_special;
 }else{
      $config_g = $this->config_txtcomvn; 
 }               
 
 $config = $config_g + $this->config_taxairvn + $this->config_taxsoichieuvn + $this->config_taxquantrivn;
                $vat_value = ($pri*10)/100;
                $priss_child = ($pri*75)/100;
                $vat_child   = $priss_child *10/100;                                   
                $priss_baby  = ($pri*10)/100;
                $config_tax_baby = $config_g;$vat_baby = $priss_baby*10/100;
                $config_tax_child = $config;
                $vat_value = ($pri*10)/100;
                $taxfree = $vat_value + $config;
                    $childarr  = $infarr = array();     
                    $adultarr = array(
                        'taxfee'=>$taxfree ,
                        'total' => $adult * ($taxfree + $pri ),
                        );
                    $subtotal =  $adult *($taxfree + $pri);
                    if($child>0){
                        $config_child = $config_g + ($this->config_taxairvn/2) + ($this->config_taxsoichieuvn/2);

                    $taxfreechild = $vat_value + $config_child;
                    
                                 $childarr = array(
                                'taxfee'=>$taxfreechild ,
                                'total' => $child * ($taxfreechild + $priss_child),
                                );
                                
                                $subtotal +=  $child *($taxfreechild + $priss_child);
                        }
                            if($inf>0){
                                $taxfree = $config_tax_baby + $vat_baby;                                            
                                 $infarr = array(
                                'taxfee'=>$taxfree ,
                                'total' => $inf * ($taxfree + $priss_baby),
                                );
                                  $subtotal +=  $inf *($taxfree + $priss_baby);
                  
                            }
                            $myVar ='vn'.((int)strtotime("now")+$i);
                            $myText = (string)$myVar;    
                            $vjarrdata[$myText] = array(
                                    'flightid'=> $myText,
                                    'depcode'=>$dep,
                                    'descode' => $des,
                                    'deptime' => $tr->children(2)->first_child()->plaintext,
                                    'destime' =>$tr->children(3)->first_child()->plaintext,
                                    'flightno' => $tr->first_child()->plaintext,
                                    'faretype' => $typeprice,
                                    'airline' => 'Vietnam airlines',
                                    'air_code' => 'VN',
                                    'baseprice' => $pri,
                                    'adult'=> $adultarr,
                                    'child' => $childarr,
                                    'datefull' => $_POST['depdate'],
                                    'inf' => $infarr,
                                    'subtotal' => $subtotal
                    );  
                   
           $i++; }
        }
    }
  
    return array($vjarrdata);        
}     
if($loaive==1){
$childarr = array();$infarr  = array();
$typepri_arr = $arrayDep=array();$arrayDic = array();
$arrayDeptime = array();$arrayDictime= array();$arraystop= array();$arrayCode = array();$arrayNameplain= array();$arraySS=array();$arrayloaive = array();
foreach($html->find('div[id="dtcontainer-outbounds"]') as $detail){
foreach($detail->find('th[id]') as $th){
    array_push($typepri_arr, $th->id);   
}
$n =  count($typepri_arr);$i=0;
foreach($detail->find('tbody tr') as $tr){
    if( isset($tr->children(0)->first_child()->first_child()->plaintext) && $tr->children(0)->first_child()->first_child()->plaintext!=""){
        
        if(strpos($tr->first_child()->innertext,"No flights are available for the selected date. Please choose alternate date.")==FALSE){             
            if(strpos($tr->children($n-1),"Sold out")==true || (!isset($tr->children($n-1)->innertext))){
                if(strpos($tr->children($n-2),"Sold out")==true || (!isset($tr->children($n-2)->innertext)) ){
                    if(strpos($tr->children($n-3),"Sold out")==true || (!isset($tr->children($n-3)->innertext)) ){
                        if(strpos($tr->children($n-4),"Sold out")==true || (!isset($tr->children($n-4)->innertext)) ){
                            if(strpos($tr->children($n-5),"Sold out")!=true || (!isset($tr->children($n-5)->innertext)) ){
								 if(strpos($tr->children($n-6),"Sold out")!=true || (!isset($tr->children($n-5)->innertext)) ){
									  if(strpos($tr->children($n-7),"Sold out")!=true || (!isset($tr->children($n-5)->innertext)) ){
										  
									  }else{
										  $typeprice  =$typepri_arr[$n-7];
                                          $pri =  (int)str_replace($chart,'',$tr->children(($n-7))->first_child()->first_child()->first_child()->children(1)->first_child()->first_child()->first_child()->plaintext);                        
									  }
								 }else{
								   $typeprice = $typepri_arr[$n-6];
                                   $pri =  (int)str_replace($chart,'',$tr->children(($n-6))->first_child()->first_child()->first_child()->children(1)->first_child()->first_child()->first_child()->plaintext);	 
								 }
                            }else{
								$typeprice = $typepri_arr[$n-5];
                                $pri =  (int)str_replace($chart,'',$tr->children(($n-5))->first_child()->first_child()->first_child()->children(1)->first_child()->first_child()->first_child()->plaintext);
							}
                        }else{
                            $typeprice  =$typepri_arr[$n-4];
                            $pri =  (int)str_replace($chart,'',$tr->children(($n-4))->first_child()->first_child()->first_child()->children(1)->first_child()->first_child()->first_child()->plaintext);
                            
                        }
                    }else{
                        $typeprice = $typepri_arr[$n-3];
                        $pri =  (int)str_replace($chart,'',$tr->children(($n-3))->first_child()->first_child()->first_child()->children(1)->first_child()->first_child()->first_child()->plaintext);
                        
                    }  
                }else{
                    $typeprice = $typepri_arr[$n-2];   
                    $pri =  (int)str_replace($chart,'',$tr->children(($n-2))->first_child()->first_child()->first_child()->children(1)->first_child()->first_child()->first_child()->plaintext);
                    
                }
            }else{   
                $pri =  ((int)str_replace($chart,'',$tr->children($n-1)->first_child()->first_child()->first_child()->children(1)->first_child()->first_child()->first_child()->plaintext) );
                
                $typeprice = $typepri_arr[$n-1];
            }
        }    
                          
                            
if($typeprice == "both-ES" || $typeprice == "outbounds-ES") {
$config_g = $this->config_txtcomvn*$price_special;
}else{
$config_g = $this->config_txtcomvn; 
}               
  
$config = $config_g + $this->config_taxairvn + $this->config_taxsoichieuvn + $this->config_taxquantrivn;
$vat_value = ($pri*10)/100;
$priss_child = ($pri*75)/100;
$vat_child   = $priss_child *10/100;                                   
$priss_baby  = ($pri*10)/100;
$config_tax_baby = $config_g;$vat_baby = $priss_baby*10/100;
$config_tax_child = $config;
$vat_value = ($pri*10)/100;
$taxfree = $vat_value + $config;
$adultarr = array(
'taxfee'=>$taxfree ,
'total' => $adult * ($taxfree + $pri ),
);
$subtotal = $adult * ($taxfree + $pri);
if($child>0){
$config_child = $config_g + ($this->config_taxairvn/2) + ($this->config_taxsoichieuvn/2);
$taxfreechild = $vat_value + $config_child;

         $childarr = array(
        'taxfee'=>$taxfreechild ,
        'total' => $child * ($taxfreechild + $priss_child),
        );
        
        $subtotal +=  $child *($taxfreechild + $priss_child);
}
if($inf>0){
$taxfree = $config_tax_baby + $vat_baby;                                            
$infarr = array(
'taxfee'=>$taxfree ,
'total' => $inf * ($taxfree + $priss_baby),
);
$subtotal += $inf * ($taxfree + $priss_baby);
}
$myVar ='vn'.((int)strtotime("now")+$i);
$myText = (string)$myVar;    
$vjarrdata[$myText] = array(
'flightid'=> $myText,
'depcode'=>$dep,
'descode' => $des,
'deptime' => $tr->children(2)->first_child()->plaintext,
'destime' =>$tr->children(3)->first_child()->plaintext,
'flightno' => $tr->first_child()->plaintext,
'faretype' => $typeprice,
'airline' => 'Vietnam airlines',
'air_code' => 'VN',
'baseprice' => $pri,
'adult'=> $adultarr,
'child' => $childarr,
'datefull' => $_POST['resdate'],
'inf' => $infarr,
'subtotal' => $subtotal
); 
            
    }
    $i++;
}
}

/* luotve */
$arrayDep=array();$arrayDic = array();
$arrayDeptime = array();$arrayDictime= array();$arraystop= array();$arrayCode = array();$arrayNameplain= array();$arraySS=array();$arrayloaive = array();
$typepri_arr = $arrayDep=array();$arrayDic = array();
$arrayDeptime = array();$arrayDictime= array();$arraystop= array();$arrayCode = array();$arrayNameplain= array();$arraySS=array();$arrayloaive = array();
foreach($html->find('div[id="dtcontainer-inbounds"]') as $detail){
foreach($detail->find('th[id]') as $th){
    array_push($typepri_arr, $th->id);   
}
$n =  count($typepri_arr);$i=0;
if($n>=6){
foreach($detail->find('tbody tr') as $tr){
    //if( $tr->children(0)->first_child()->first_child()->plaintext!=""){
         if( isset($tr->children(0)->first_child()->first_child()->plaintext) && $tr->children(0)->first_child()->first_child()->plaintext!=""){
        if(strpos($tr->first_child()->innertext,"No flights are available for the selected date. Please choose alternate date.")==FALSE){             
            if(strpos($tr->children($n-1),"Sold out")==true || (!isset($tr->children($n-1)->innertext))){
                if(strpos($tr->children($n-2),"Sold out")==true || (!isset($tr->children($n-2)->innertext)) ){
                    if(strpos($tr->children($n-3),"Sold out")==true || (!isset($tr->children($n-3)->innertext)) ){
                        if(strpos($tr->children($n-4),"Sold out")==true || (!isset($tr->children($n-4)->innertext)) ){
                            if(strpos($tr->children($n-5),"Sold out")!=true || (!isset($tr->children($n-5)->innertext)) ){
                                $typeprice = $typepri_arr[$n-5];
                                $pri =  (int)str_replace($chart,'',$tr->children(($n-5))->first_child()->first_child()->first_child()->children(1)->first_child()->first_child()->first_child()->plaintext);
                                
                            }
                        }else{
                            $typeprice  = $typepri_arr[$n-4];
                            $pri  =  (int)str_replace($chart,'',$tr->children(($n-4))->first_child()->first_child()->first_child()->children(1)->first_child()->first_child()->first_child()->plaintext);
                          
                        }
                    }else{
                        $typeprice = $typepri_arr[$n-3];
                        $pri =  (int)str_replace($chart,'',$tr->children(($n-3))->first_child()->first_child()->first_child()->children(1)->first_child()->first_child()->first_child()->plaintext);
                        
                    }  
                }else{
                    $typeprice = $typepri_arr[$n-2];        
                    $pri =  (int)str_replace($chart,'',$tr->children(($n-2))->first_child()->first_child()->first_child()->children(1)->first_child()->first_child()->first_child()->plaintext);
                    
                }
            }else{
                $pri =  ((int)str_replace($chart,'',$tr->children($n-1)->first_child()->first_child()->first_child()->children(1)->first_child()->first_child()->first_child()->plaintext) );
                $typeprice = $typepri_arr[$n-1];
            }
        }    
                         
                            
if($typeprice == "both-ES" || $typeprice == "inbounds-ES") {
          $config_g = $this->config_txtcomvn*$price_special;
     }else{
          $config_g = $this->config_txtcomvn; 
     }               
                    
    $vat_value = ($pri*10)/100;
    $priss_child = ($pri*75)/100;
    $vat_child   = $priss_child *10/100;                                   
    $priss_baby  = ($pri*10)/100;
    $config_tax_baby = $config_g;$vat_baby = $priss_baby*10/100;
    $config_tax_child = $config;
    $vat_value = ($pri*10)/100;
                $taxfree = $vat_value + $config;
                $adultarr = array(
                    'taxfee'=>$taxfree ,
                    'total' => $adult * ($taxfree + $pri ),
                    );
                $subtotal = $adult * ($taxfree + $pri);
                if($child>0){
                     $config_child = $config_g + ($this->config_taxairvn/2) + ($this->config_taxsoichieuvn/2);
        $taxfreechild = $vat_value + $config_child;
                     $childarr = array(
                    'taxfee'=>$taxfreechild ,
                    'total' => $child * ($taxfreechild + $priss_child),
                    );
                    
                    $subtotal +=  $child *($taxfreechild + $priss_child);
              
                }
                if($inf>0){
                    $taxfree = $config_tax_baby + $vat_baby;
                    
                     $infarr = array(
                    'taxfee'=>$taxfree ,
                    'total' => $inf * ($taxfree + $priss_baby),
                    );
                      $subtotal = $inf * ($taxfree + $priss_baby);
              
                }
                $myVar ='vnin'.((int)strtotime("now")+$i);
                $myText = (string)$myVar;    
                $vjarrdatain[$myText] = array(
                        'flightid'=> $myText,
                        'depcode'=>$des,
                        'descode' => $dep,
                        'deptime' => $tr->children(2)->first_child()->plaintext,
                        'destime' =>$tr->children(3)->first_child()->plaintext,
                        'flightno' => $tr->first_child()->plaintext,
                        'faretype' => $typeprice,
                        'airline' => 'Vietnam airlines',
                        'air_code' => 'VN',
                        'baseprice' => $pri,
                        'adult'=> $adultarr,
                        'child' => $childarr,
                        'datefull' => $_POST['resdate'],
                        'inf' => $infarr,
                        'subtotal' => $subtotal
        ); 
        }
        $i++;
    }
    }
}     
        return  array($vjarrdata,$vjarrdatain);
    }     
}
/***********************
        * convert_Vietjnam     *
        * *********************/
    public function Convert_VNairline($url)
    {
        $arrday1 = explode("-",$_POST['depdate']);
        if( $_POST['dep']=="NHA"){
              $_POST['dep'] = "CXR";
        }
        if($_POST['des']=="NHA" ){
            $_POST['des'] = "CXR";
        }
            if($_POST['direction']=="0"){
                $url."lang=en&&currency=VND";
                $url.="&journeySpan=OW";
                $url.="&origin=".$_POST['dep'];
                $url.="&destination=".$_POST['des'];
                $url.="&numAdults=".$_POST['adult'];
                $url.="&numChildren=".$_POST['child'];
                $url.="&numInfants=".$_POST['infant'];
                $url.="&promoCode=";
                $url.="&alternativeLandingPage=true";
                $url.="&departureDate=".$arrday1[2].'-'.$arrday1[1].'-'.$arrday1[0];
                return $url;
            } else{
                $arrday2 = explode('-',$_POST['resdate']);
                $url."lang=en&&currency=VND";
                $url.="&journeySpan=RT";
                $url.="&origin=".$_POST['dep'];
                $url.="&destination=".$_POST['des'];
                $url.="&numAdults=".$_POST['adult'];
                $url.="&numChildren=".$_POST['child'];
                $url.="&numInfants=".$_POST['infant'];
                $url.="&promoCode=";
                $url.="&alternativeLandingPage=true";
                $url.="&departureDate=".$arrday1[2].'-'.$arrday1[1].'-'.$arrday1[0];
                $url.="&returnDate=".$arrday2[2].'-'.$arrday2[1].'-'.$arrday2[0];
            }
            return $url.="&";    
    }         
   /** function get_jetstart **
        * ************************/
        function get_jetstart($html,$loaive,$day){
            if(isset($html) && $html!=""){
                $htmlin = preg_replace(array('@<thead[^>]*?>.*?</thead>@siu',
                '@<tr class="business-options[^>]*?">.*?</tr>@siu',
                '@<tr class="starter-options[^>]*?">.*?</tr>@siu') ,array('',''),$html[0]);
                $htmlin = str_get_html($htmlin);
                // var_dump($_POST);exit;
                     $dep = $_POST['dep'];$des =$_POST['des'];
                     $adult = $_POST['adult'];
                     $child = $_POST['child'];
                     $inf = $_POST['infant'];
                     $config_tax_child = $config = $this->config_txtcomjet;
                     $config = $config + $this->config_taxairjet + $this->config_taxsoichieujet + $this->config_taxquantrijet;
                    if($htmlin->find('table[class=domestic]')){ 
                        $arrayDep=array();$arrayDic = array();$arrayloaive=array();
                        $arrayDeptime = array();$arrayDictime= array();$arraystop= array();$arrayCode = array();$arrayNameplain= array();$arraySS=array();
                        $i=0;
                        foreach($htmlin->find('table[class=domestic] tbody tr') as $tr){  
                            //echo $tr->first_child()->first_child()->innertext;exit;
                              $stop =$tr->children(2)->first_child('span')->getAttribute('number-of-stops');
                                   
                            if($tr->first_child()->first_child()->innertext=='Rất tiếc, không có chuyến bay nào. Vui lòng chọn ngày hoặc địa điểm khác và thử lại.'){
                                $jetstartout =array("");
                            }else{
                                if($tr->children(3)->plaintext!="Hết vé"){    
                                    if($tr->children(3)->first_child()->getAttribute('class')=="hidden"){
                                       // echo 'price='.(int)$tr->children(3)->children(1)->first_child()->getAttribute('data-price')."<br/>";
                                        $gia = (int)$tr->children(3)->children(1)->first_child()->getAttribute('data-price');    
                                    }elseif($tr->children(3)->first_child()->first_child()->getAttribute('class') == "sale-banner png-bg"){
                                        $gia = (int)$tr->children(3)->first_child()->children(1)->getAttribute('data-price');    
                                    }else{
                                        $type = "Stater";
                                        $gia = (int)$tr->children(3)->first_child()->first_child()->getAttribute('data-price');
                                    }
                                   
                                    if($gia>0){
                                         $vat_value = ($gia*10)/100;
                                         $taxfree = $vat_value + $config;
                                        if($stop!=0){
                                           $taxfree = $taxfree + ($config-$this->config_txtcomjet);

                                        }

                                        $adultarr = array();
                                        $adultarr = array(
                                            'taxfee'=>$taxfree ,
                                            'total' => $adult * ($taxfree + $gia ),
                                        );
                                         $subtotal = $adult * ($taxfree + $gia ); 
                                        
                                        $childarr = array();                          
                                        if($child>0){
                                            $config_child = $this->config_txtcomjet + ($this->config_taxairjet/2) + ($this->config_taxsoichieujet/2) + $this->config_taxquantrijet;
                                            $taxfreechild = $vat_value + $config_child;
                                             $childarr = array(
                                                'baseprice' => $gia,
                                                'taxfee'=>$taxfreechild ,
                                                'total' => $child * ($taxfreechild + $gia),
                                            );
                                            $subtotal += $child * ($taxfreechild + $gia);
                                        }
                                        $infarr = array();
                                        if($inf>0){
                                            $taxfree =0;
                                             $infarr = array(
                                            'taxfee'=>0 ,
                                            'total' => 0,
                                            );
                                        }
                                        $myVar ='js'.((int)strtotime("now")+$i);
                                        $myText = (string)$myVar; 
                                        $vjarrdata[$myText] = array(
                                                'flightid'=> $myText,
                                                'depcode'=>$dep,
                                                'descode' => $des,
                                                'stop'    => $stop,
                                                'deptime' => substr($tr->first_child()->children(0)->plaintext,0,5),
                                                'destime' => $tr->children(1)->first_child()->plaintext,
                                                'flightno' => str_replace("Lượt đi 1",'',$tr->children(2)->children(2)->children(1)->first_child()->first_child('span')->plaintext),
                                                'faretype' => $type,
                                                'airline' => 'Jetstar',
                                                'datefull' => $_POST['depdate'],
                                                'air_code' => 'BL',
                                                'baseprice' => $gia,
                                                'adult'=> $adultarr,
                                                'child' => $childarr,
                                                'inf' => $infarr,
                                                'subtotal' => $subtotal
                                                ); 

                                }  elseif( (int)str_replace(array('VND','.'),'',$tr->children(3)->first_child()->plaintext)>0){
                                       
                                        $vat_value = ($gia*10)/100;
                                        $taxfree = $vat_value + $config;
                                        if($stop!=0){
                                              $taxfree = $taxfree +  ($config-$this->config_txtcomjet);
                                        }
                                        $adultarr = array(
                                            'taxfee'=>$taxfree ,
                                            'total' => $adult * ($taxfree + $gia ),
                                            );
                                        $subtotal += $adult * ($taxfree + $gia);
                                        if($child>0){
                                            $config_child = $this->config_txtcomjet + ($this->config_taxairjet/2) + ($this->config_taxsoichieujet/2) + $this->config_taxquantrijet;; 
                                        $taxfreechild = $vat_value + $config_child;
                                             $childarr = array(
                                                'baseprice' => $gia,
                                                'taxfee'=>$taxfreechild ,
                                                'total' => $child * ($taxfreechild + $gia),
                                            );
                                            $subtotal += $child * ($taxfreechild + $gia);
                                       
                                        }
                                        if($inf>0){
                                            $taxfree =0;
                                             $infarr = array(
                                            'taxfee'=>0 ,
                                            'total' => 0,
                                            );
                                        }
                                        $myVar ='js'.((int)strtotime("now")+$i);
                                        $myText = (string)$myVar;    
                                        $vjarrdata[$myText] = array(
                                                'flightid'=> $myText,
                                                'depcode'=>$dep,
                                                'descode' => $des,
                                                'stop'    => $stop, 
                                                'deptime' => substr($tr->first_child()->children(0)->plaintext,0,5),
                                                'destime' => $tr->children(1)->children(1)->plaintext,
                                                'flightno' => str_replace("Lượt đi 1",'',$tr->children(2)->children(2)->children(1)->first_child()->first_child('span')->plaintext),
                                                'faretype' => 'Starter',
                                                'airline' => 'Jetstar',
                                                'air_code' => 'BL',
                                                'datefull' => $_POST['depdate'],
                                                'baseprice' => $gia,
                                                'adult'=> $adultarr,
                                                'child' => $childarr,
                                                'inf' => $infarr,
                                                'subtotal' => $subtotal,
                                );  
                                            }
                                } 
                            }     
                            $i++;       
                        }
     
                        if($loaive==1){                            
                            $htmlout = preg_replace(array('@<thead[^>]*?>.*?</thead>@siu',
                            '@<tr class="business-options[^>]*?">.*?</tr>@siu',
                            '@<tr class="starter-options[^>]*?">.*?</tr>@siu') ,array('',''),$html[1]);
                            if($htmlout!="")
                            $htmlout = str_get_html($htmlout);                         
                            foreach($htmlout->find('table[class=domestic] tbody tr') as $tr){ 
                                if(strpos($tr->innertext,'<h3>Rất tiếc, không có chuyến bay nào. Vui lòng chọn ngày hoặc địa điểm khác và thử lại.</h3>')==true){
                                    $jetstartin =null;
                                }else{
                                    $subtotal = 0;
                                    if($tr->children(3)->innertext!="Hết vé"){     
                                         if($tr->children(3)->first_child()->getAttribute('class')=="hidden"){
                                            //  echo 'price='.(int)$tr->children(3)->children(1)->first_child()->getAttribute('data-price')."<br/>";
                                            $pri = (int)$tr->children(3)->children(1)->first_child()->getAttribute('data-price');    
                                        }elseif($tr->children(3)->first_child()->first_child()->getAttribute('class') == "sale-banner png-bg"){
                                            $pri = (int)$tr->children(3)->first_child()->children(1)->getAttribute('data-price');    
                                        }else{
                                            $type = "Stater";
                                            $pri = (int)$tr->children(3)->first_child()->first_child()->getAttribute('data-price');
                                        }
                                        $stop =$tr->children(2)->first_child('span')->getAttribute('number-of-stops');
                                        $vat_value = ($pri*10)/100;
                                        $taxfree = $vat_value + $config;
                                        if($stop!=0){
                                            $taxfree = $taxfree +  ($config-$this->config_txtcomjet);
                                        }
                                        $adultarr = array(
                                            'taxfee'=>$taxfree ,
                                            'total' => $adult * ($taxfree + $pri ),
                                            );
                                        $subtotal += $adult * ($taxfree + $pri);
                                       
                                        if($child>0){
                                            $config_child = $this->config_txtcomjet + ($this->config_taxairjet/2) + ($this->config_taxsoichieujet/2) + $this->config_taxquantrijet; 
                                            $taxfreechild = $vat_value + $config_child;
                                             $childarr = array(
                                                'baseprice' => $pri,
                                                'taxfee'=>$taxfreechild ,
                                                'total' => $child * ($taxfreechild + $gia),
                                            );
                                            $subtotal += $child * ($taxfreechild + $gia);
                                      
                                        }
                                        if($inf>0){
                                            $taxfree =0;
                                             $infarr = array(
                                            'taxfee'=>$taxfree ,
                                            'total' => 0,
                                            );
                                        }
                                        $myVar ='js'.((int)strtotime("now")+$i);
                                        $myText = (string)$myVar;    
                                        $vjarrdatain[$myText] = array(
                                                'flightid'=> $myText,
                                                'depcode'=>$des,
                                                'stop' => $stop,
                                                'descode' => $dep,
                                                'deptime' => substr($tr->first_child()->children(0)->plaintext,0,5),
                                                'destime' =>$tr->children(1)->first_child()->plaintext,
                                                'flightno' => str_replace(array('Lượt về','1'),'',$tr->children(2)->children(2)->children(1)->first_child()->first_child('span')->plaintext),
                                                'faretype' => 'Starter',
                                                'airline' => 'Jetstar',
                                                'air_code' => 'BL',
                                                'baseprice' => $pri,
                                                'datefull' => $_POST['resdate'],
                                                'adult'=> $adultarr,
                                                'child' => $childarr,
                                                'inf' => $infarr,
                                                'subtotal' => $subtotal
                                );  
                                         }   
                                } $i++;                          
                            }  
                                return  array($vjarrdata,$vjarrdatain);
                        }
                        return array($vjarrdata);  
                    }else{
                        return  array();
                }
            }else
            {
                return  array();
            }  
        }
/***********************
    * convert_Vietjet     *
    * *********************/
public function convert_Vietjet($post){
   $_POST['lstOrigAP'] = $post['dep'];
   $_POST['lstDestAP'] = $post['des'];
     $arrayday1 = explode("-",$post['depdate']);
    $_POST['dlstDepDate_Day']=$arrayday1['0'];
    $_POST['dlstDepDate_Month']= $arrayday1['2'].'/'.$arrayday1['1'];
    if($post['direction']=="1"){
        $_POST['chkRoundTrip']="on";
        $arrayday2 = explode("-",$post['resdate']);
        $_POST['dlstRetDate_Day'] = $arrayday2['0'];
        $_POST['dlstRetDate_Month']= $arrayday2['2'].'/'.$arrayday2['1'];
    }else{
        $_POST['dlstRetDate_Day']=$arrayday1['0'];
        $_POST['dlstRetDate_Month']=$arrayday1['2'].'/'.$arrayday1['1'];
    }
    $_POST['lstDepDateRange']="0";
    $_POST['lstRetDateRange']="0";               
    $_POST['lstLvlService']="1";
    $_POST['lstResCurrency']="VND";
    $_POST['txtNumAdults']=$post['adult'];
    $_POST['txtNumChildren']=$post['child'];
    $_POST['txtNumInfants']=$post['infant'];
    $_POST["blnFares"]="False";
    return $_POST;
}
 /** function validateForm **/
 public function validateform(){

 }
  /************************
        * function get_vietjet
        *************************/  
public function get_vietjet($html,$loaive){
            // echo $html;
    $vjarrdatain = $vjarrdata = array();$arrayDep=array();
    $arrayDic = array();$arrayloaive=array();
    $arrayDeptime = array();$arrayDictime= array();
    $arraystop= array();$arrayCode = array();
    $arrayNameplain= array();
    $arraySS=array();
    $table = $html->find('table[class=FlightsGrid]',0);
    $dep = $_POST['dep'];$des =$_POST['des'];
    $adult = $_POST['adult'];
    $child = $_POST['child'];
    $inf = $_POST['infant'];
    $config = $this->config_txtcomvj;   
    $config = $config + $this->config_taxairvj + $this->config_taxsoichieuvj + $this->config_taxquantrivj;
    $config_tax_child = $config;$i=0;
    if(isset($table)){ ;$vjarrdata = array();
        foreach($table->find('tr[id]') as $tr){        
          if(!$tr->find('.ErrorCaption')){  
            if( $tr->children(1)->first_child()->first_child()->children(1)->innertext !='' && isset($tr->children(1)->first_child()->first_child()->children(1)->first_child()->value)){
                $pri = (int)str_replace(',','',$tr->children(1)->first_child()->first_child()->children(1)->first_child()->value);    
            }
            if($pri>0){                
            if(strpos($tr->children(1)->first_child()->first_child()->children(1)->innertext,"Hết vé")==false){
                $flighttype = "Eco";
            }elseif(strpos($tr->children(1)->first_child()->first_child()->children(1)->innertext,"Hết vé")==false){
                $flighttype = "Promo";
            }
            $vat_value = ($pri*10)/100;
            $taxfree = $vat_value + $config;
            $adultarr = array(
                'taxfee'=>$taxfree ,
                'total' => $adult * ($taxfree + $pri ),
                );
            $subtotal = $adult * ($taxfree + $pri ) ;
            $childarr = array();
            if($child>0){
                $config_child = $this->config_txtcomvj + ($this->config_taxairvj/2) + ($this->config_taxsoichieuvj/2) + $this->config_taxquantrivj; 
                $taxfreechild = $vat_value + $config_child;
                
                 $childarr = array(
                'taxfee'=>$taxfreechild ,
                'total' => $child * ($taxfreechild + $pri),
                );
                 $subtotal += $child * ($taxfreechild + $pri); 
            }
            $infarr = array();
            if($inf>0){
                $taxfree =0;
                 $infarr = array(
                'taxfee'=>$taxfree ,
                'total' => 0,
                );
                 
            }
             $myVar ='vj'.((int)strtotime("now")+$i);
             $myText = (string)$myVar;
             $vjarrdata[$myText] = array(
             'flightid'=> $myText,
            'depcode'=>$dep,
            'descode' => $des,
            'deptime' => substr($tr->first_child()->first_child()->first_child()->children(1)->innertext,0,5),
            'destime' => substr($tr->first_child()->first_child()->first_child()->children(2)->innertext,0,5),
            'flightno' => $tr->first_child()->first_child()->first_child()->children(3)->first_child()->plaintext,
            'faretype' => $flighttype,
            'datefull' => $_POST['depdate'],
            'airline' => 'Vietjet',
            'air_code' => 'VJ',
            'baseprice' => $pri,
            'adult'=> $adultarr,
            'child' => $childarr,
            'inf' => $infarr,
            'subtotal' => $subtotal
                );
                      
        }  
      }
    $i++;
   }
}else{       
                $vjarrdata = array();
 }  
    if($loaive==1){
    $arrayDep=array();$arrayDic = array();$arrayloaive=array();
    $arrayDeptime = array();$arrayDictime= array();$arraystop= array();$arrayCode = array();$arrayNameplain= array();$arraySS=array();
    $table = $html->find('table[class=FlightsGrid]',1);
    if(isset($table)){ $i=0;
        foreach($table->find('tr[id]') as $tr){
        if( $tr->children(1)->first_child()->first_child()->children(1)->innertext !='' && isset($tr->children(1)->first_child()->first_child()->children(1)->first_child()->value)){
            $pri = (int)str_replace(',','',$tr->children(1)->first_child()->first_child()->children(1)->first_child()->value);    
        }
        if($pri>0){
        if(!$tr->find('.ErrorCaption')){
             foreach($tr->find('td[id]') as $td){
                $inp=$td->find('input',6);
                if(strpos($td->find('input',6)->value,"Eco")==true){
                    $str = $td->find('input',0)->value;
                    //array_push($arraySS,$pri);
                    $flighttype= "Eco";
                }elseif(strpos($td->find('input',6)->value,"Eco")==true){
                    $str = $td->find('input',0)->value;
                    //array_push($arraySS,$pri);
                   $flighttype= "Eco";
                }elseif(strpos($td->find('input',6)->value,"Promo")==true){
                    $str = $td->find('input',0)->value;
                   $flighttype= "Promo";
                    //array_push($arraySS,$pri);
                }
            }     
            $vat_value = ($pri*10)/100;
            $taxfree = $vat_value + $config;
            $adultarr = array(
                'taxfee'=>$taxfree ,
                'total' => $adult * ($taxfree + $pri ),
                );
            $subtotal = $adult * ($taxfree + $pri );
            $childarr = array();
            if($child>0){
                $config_child = $this->config_txtcomvj + ($this->config_taxairvj/2) + ($this->config_taxsoichieuvj/2) + $this->config_taxquantrivj; 
                $taxfreechild = $vat_value + $config_child;
                
                 $childarr = array(
                'taxfee'=>$taxfreechild ,
                'total' => $child * ($taxfreechild + $pri),
                );
                 $subtotal += $child * ($taxfreechild + $pri); 
            }
            $infarr = array();
            if($inf>0){
                $taxfree =0;
                 $infarr = array(
                'taxfee'=>$taxfree ,
                'total' => 0,
                );
            }
            $myVar ='vjin'.((int)strtotime("now")+$i);
             $myText = (string)$myVar;
             $vjarrdatain[$myText] = array(
                                'flightid'=> $myText,
                                'depcode'=>$des,
                                'descode' => $dep,
                                'deptime' => substr($tr->first_child()->first_child()->first_child()->children(1)->innertext,0,5),
                                'destime' => substr($tr->first_child()->first_child()->first_child()->children(2)->innertext,0,5),
                                'flightno' => $tr->first_child()->first_child()->first_child()->children(3)->first_child()->plaintext,
                                'faretype' => $flighttype,
                                'airline' => 'Vietjet',
                                'air_code' => 'VJ',
                                'baseprice' => $pri,
                                'adult'=> $adultarr,
                                'child' => $childarr,
                                'datefull' => $_POST['resdate'],
                                'inf' => $infarr,
                                'subtotal' => $subtotal
                );
         $i++;  
     }
    }
     }
    }else{
        $vjarrdata = array();
    } 
       return  array($vjarrdata,
        $vjarrdatain );
}
return  array($vjarrdata);

}   
/**
* convert jetstart ***/
public function conver_Jettart($post)
{       
   
    $_POST['culture'] = "vi-VN";
            if($_POST['dep']=="NHA"){
                $_POST['dep']="CXR"; 
            }
            if($_POST['des']=="NHA"){
                $_POST['des']="CXR"; 
            }
            if ($_POST['direction'] == "1")
            {
                $_POST['RadioButtonMarketStructure'] = "RoundTrip";
                $_POST['Origin2'] = $_POST['dep'];
                $_POST['Destination2'] = $_POST['des'];
                $arrayday2 = explode("-", $_POST['resdate']);
                $_POST['Day2'] = $arrayday2['0'];
                $_POST['MonthYear2'] = $arrayday2['2'] . "-" . $arrayday2['1'];
            } else
            {
                $_POST['RadioButtonMarketStructure'] = "OneWay";
            }
            $_POST['Origin1'] = $_POST['dep'];
            $_POST['Destination1'] = $_POST['des'];
            $arrayday1 = explode("-", $_POST['depdate']);
            $_POST['Day1'] = $arrayday1['0'];
            $_POST['MonthYear1'] = $arrayday1['2'] . "-" . $arrayday1['1'];
            $_POST['ADT'] = $_POST['adult'];                
            $_POST['CHD'] = $_POST['child'];
            $_POST['INF'] = $_POST['infant'];
            $_POST['AutoSubmit'] = "Y";
            //$_POST['ControlGroupCalendarSearchView%24AvailabilitySearchInputCalendarSearchView%24DropDownListCurrency'] ="VND";
            $_POST['ctl04%24ctl06%24ctl00$rptNavigationSection%24ctl00%24ctl00%24rptTopNavigationLevel1%24ctl00%24ctl00%24ddlCurrency'] = 'VND';
            return $_POST;
}       
 /** **/
   /** curl **/
function curl()
{
    $this->channel = curl_init();
    // you might want the headers for http codes
    curl_setopt( $this->channel, CURLOPT_HEADER, true );
    curl_setopt($this->channel, CURLOPT_USERAGENT, sprintf("Mozilla/%d.0",rand(4,5)));
    curl_setopt( $this->channel, CURLOPT_FOLLOWLOCATION, true );   
      curl_setopt($this->channel, CURLOPT_VERBOSE, true);
    curl_setopt($this->channel, CURLOPT_FAILONERROR, true);
    curl_setopt($this->channel, CURLOPT_TIMEOUT,30); 
    curl_setopt($this->channel, CURLOPT_FOLLOWLOCATION, TRUE); // Follow redirects      
    curl_setopt( $this->channel, CURLOPT_RETURNTRANSFER, true );
    curl_setopt($this->channel,CURLOPT_ENCODING,"gzip"); 
     curl_setopt($this->channel, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($this->channel, CURLOPT_SSL_VERIFYPEER, FALSE);
}  

    /** makerequest curl **/
function makeRequest( $method, $url,$referer, $vars,$cookies )
{
curl_setopt ($this->channel, CURLOPT_REFERER, $referer);
curl_setopt( $this->channel, CURLOPT_URL, $url );
if(isset($_COOKIE)){           
    foreach($_COOKIE as $key=> $value){
      $this->allcookies .=$key."=".$value.";path=/";   
    }              
  curl_setopt($this->channel, CURLOPT_COOKIE,$this->allcookies);
}        
// probably unecessary, but cookies may be needed to
curl_setopt( $this->channel, CURLOPT_COOKIEJAR, $cookies);
// the actual post bit
if ( strtolower( $method ) == 'post' ) :
    $elements='';            
    foreach ($vars as $name=>$value)
    {            
     $elements.='&';
     $elements.="{$name}=".urlencode($value);
    }               
   // $this->http_build_query_for_curl($vars,$elements);            
    curl_setopt( $this->channel, CURLOPT_POST, true );
    curl_setopt( $this->channel, CURLOPT_POSTFIELDS, $elements );
endif;              
// return data        
$result['EXE'] = curl_exec($this->channel);       
$result['INF'] = curl_getinfo($this->channel); 
$result['ERR'] = curl_error($this->channel); 
// the download speed must be at least 1 byte per second
curl_setopt(CURLOPT_LOW_SPEED_LIMIT, 1);
// if the download speed is below 1 byte per second for
// more than 30 seconds curl will give up
curl_setopt(CURLOPT_LOW_SPEED_TIME, 30);
return ($result);        
    }
}  
$apiflight = new APIFlight();
$apiflight->index();