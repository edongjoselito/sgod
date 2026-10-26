<?php
define('BASEPATH',__DIR__);
require __DIR__.'/../libraries/Reimbursement_rules.php';
$r=new Reimbursement_rules();$count=0;
function check($ok,$label){global $count;if(!$ok)throw new Exception($label);$count++;}
function reject($fn,$label){try{$fn();}catch(InvalidArgumentException $e){check(TRUE,$label);return;}check(FALSE,$label);}
$rows=array(
array('date'=>'2025-08-18','places'=>'Mati to Star Hotel','departure'=>'05:00','arrival'=>'09:00','means'=>'own vehicle','transportation'=>'500','incidental'=>'180','per_diem'=>'360'),
array('date'=>'2025-08-19','date_to'=>'2025-08-20','places'=>'Still at the Venue','departure'=>'08:00','arrival'=>'17:00','per_diem'=>'720'),
array('date'=>'2025-08-20','places'=>'Star Hotel to Mati','departure'=>'17:00','arrival'=>'22:00','means'=>'own vehicle','transportation'=>'500','incidental'=>'180'));
list($items,$total)=$r->itinerary_rows($rows);
check($total===244000,'sample total 2440');check(array_column($items,'amount')===array('1040.00','720.00','680.00'),'sample row totals');
check($items[1]['date_to']==='2025-08-20','multi-day stay preserved');
$bad=$rows[0];$bad['amount']='999999';list($ignored,$sum)=$r->itinerary_rows(array($bad));check($sum===104000,'client total ignored');
foreach(array('date'=>'2025-02-30','date_to'=>'2025-08-17','departure'=>'25:01','arrival'=>'5:30 PM','transportation'=>'-1','per_diem'=>'1.005') as $key=>$value){$bad=$rows[0];$bad[$key]=$value;reject(function()use($r,$bad){$r->itinerary_rows(array($bad));},'reject '.$key);}
$zero=array('date'=>'2025-08-18','places'=>'Office vehicle provided');list($zeroItems,$sum)=$r->itinerary_rows(array($zero));check(count($zeroItems)===1&&$sum===0,'zero-cost travel leg allowed');
list($blank,$sum)=$r->itinerary_rows(array(array()));check(!$blank&&$sum===0,'blank row ignored');
$legacy=$r->itinerary_display_rows(array(array('date'=>'2025-08-18','category'=>'Transportation','description'=>'Bus fare','amount'=>'100.25','reference'=>'OR1')));list($converted,$sum)=$r->itinerary_rows($legacy);check($sum===10025&&$converted[0]['reference']==='OR1','legacy amount and evidence preserved');
$meta=$r->itinerary_meta(array('agency'=>'DepEd Division of Davao Oriental Office','employee_name'=>'TEST EMPLOYEE','purpose'=>'See attached travel documents','prepared_by'=>'TEST EMPLOYEE','certifier_name'=>'TEST CERTIFIER','certifier_position'=>'SGOD - Chief','approver_name'=>'TEST APPROVER','approver_position'=>'Schools Division Superintendent'));
check($meta['number']==='','optional number');reject(function()use($r){$r->itinerary_meta(array());},'required header');
function site_url($path){return '/sgod/'.$path;}
$travel=(object)array('id'=>1);$claim=array('status'=>'Draft','total_cents'=>$total);
ob_start();require __DIR__.'/../views/travel_itinerary_print.php';$html=ob_get_clean();
check(strpos($html,'2,440.00')!==FALSE,'printed grand total');check(strpos($html,'August 19–20, 2025')!==FALSE,'printed date range');check(strpos($html,'5:00 AM')!==FALSE,'printed time');check(strpos($html,'size:A4 portrait')!==FALSE,'print page size');
$meta['purpose']='<script>alert(1)</script>';ob_start();require __DIR__.'/../views/travel_itinerary_print.php';$safe=ob_get_clean();check(strpos($safe,'&lt;script&gt;')!==FALSE,'print escapes user text');
file_put_contents(sys_get_temp_dir().'/travel-itinerary-preview.html',$html);
echo "$count itinerary checks passed.\n";
