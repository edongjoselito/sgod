<?php
define('BASEPATH', __DIR__);
require __DIR__.'/../libraries/Reimbursement_rules.php';
$r=new Reimbursement_rules(); $count=0;
function check($ok,$label){global $count;if(!$ok)throw new Exception('FAILED: '.$label);$count++;}
function rejects($fn,$label){try{$fn();}catch(InvalidArgumentException $e){check(TRUE,$label);return;}check(FALSE,$label);}
check($r->cents('123.45')===12345,'exact decimal parsing');
check($r->cents('0.1')===10,'one decimal digit');
foreach(array('-1','1.001','1e5','1,000','NaN','999999999',array()) as $bad)rejects(function()use($r,$bad){$r->cents($bad);},'reject malformed money');
$line=array('date'=>'2026-01-15','category'=>'Transportation','description'=>'Bus fare','reference'=>'OR 123','amount'=>'100.25');
list($items,$total)=$r->expenses(array($line,$line)); check($total===20050 && count($items)===2,'expense sum in cents');
$bad=$line;$bad['date']='2026-02-30';rejects(function()use($r,$bad){$r->expenses(array($bad));},'invalid calendar date');
$bad=$line;$bad['category']='Injected';rejects(function()use($r,$bad){$r->expenses(array($bad));},'invalid category');
$bad=$line;$bad['amount']='0';rejects(function()use($r,$bad){$r->expenses(array($bad));},'zero-value item');
$travel=(object)array('status'=>'Approved','date_end'=>'2026-01-15');
$claim=array('total_cents'=>20050,'advance_cents'=>5000,'document_path'=>'package.pdf','checklist_json'=>json_encode(array_fill_keys(array_keys($r->documents),TRUE)));
check($r->submission_errors($travel,$claim)===array(),'complete approved claim accepted');
$travel->status='Pending';check(count($r->submission_errors($travel,$claim))>0,'pending travel cannot submit');$travel->status='Approved';
$travel->date_end='2099-01-01';check(count($r->submission_errors($travel,$claim))>0,'future travel cannot submit');$travel->date_end='2026-01-15';
$bad=$claim;$bad['advance_cents']=20050;check(count($r->submission_errors($travel,$bad))>0,'no positive claim balance');
$bad=$claim;$bad['document_path']='';check(count($r->submission_errors($travel,$bad))>0,'missing evidence package');
$bad=$claim;$bad['checklist_json']='{}';check(count($r->submission_errors($travel,$bad))===5,'missing checklist confirmations');
foreach($r->stages as $status=>$stage){check($r->can_review($status,array($stage[0]),'owner','reviewer'),'proper stage role');check(!$r->can_review($status,array($stage[0]),'owner','owner'),'no self approval');check(!$r->can_review($status,array(),'owner','reviewer'),'unassigned role blocked');}
check(!$r->can_review('Paid',array('cashier'),'owner','reviewer'),'paid claim immutable');
check(!$r->can_review('Submitted',array('cashier'),'owner','reviewer'),'no skipped stage');
echo "$count reimbursement rule checks passed.\n";
