<?php
define('BASEPATH',__DIR__);
class CI_Controller {public $db; public $claims;}
function show_error($message,$code){throw new RuntimeException($message,$code);}
function show_404(){throw new RuntimeException('Not found',404);}
require __DIR__.'/../controllers/Reimbursement.php';
class TestDB {public $travel;function table_exists($table){return TRUE;}function where($a,$b){return $this;}function get($a,$b){return $this;}function row(){return $this->travel;}}
class TestClaims {public $record;function claim($id){return $this->record;}}
$ref=new ReflectionClass('Reimbursement');$controller=$ref->newInstanceWithoutConstructor();$actor=$ref->getProperty('actor');$actor->setAccessible(TRUE);$actor->setValue($controller,'EMP001');
$controller->db=new TestDB();$controller->claims=new TestClaims();$context=$ref->getMethod('context');$context->setAccessible(TRUE);$count=0;
foreach(array('Draft','Submitted','For Approval','Paid','Saved') as $status){$controller->db->travel=(object)array('id'=>1,'IDNumber'=>'EMP001');$controller->claims->record=array('employee_id'=>'EMP001','status'=>$status);$result=$context->invoke($controller,'1');if(!$result[2])throw new Exception('Owner denied');$count++;}
foreach(array(array('EMP002','EMP002'),array('EMP001','EMP002')) as $pair){$controller->db->travel->IDNumber=$pair[0];$controller->claims->record['employee_id']=$pair[1];try{$context->invoke($controller,'1');throw new Exception('Unauthorized record accessible');}catch(RuntimeException $e){if($e->getCode()!==403)throw $e;$count++;}}
foreach(array('review','assign') as $method){if($ref->hasMethod($method))throw new Exception('Legacy endpoint remains');$count++;}
echo "$count employee ownership and endpoint checks passed.\n";
