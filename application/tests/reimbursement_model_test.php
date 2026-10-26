<?php
// Temporary tables shadow the feature's tables on this connection only.
$root=getenv('SGOD_TEST_ROOT') ?: dirname(__DIR__,2);
define('BASEPATH',$root.'/system/'); define('APPPATH',$root.'/application/'); define('ENVIRONMENT','testing');
function log_message($level,$message) {}
require BASEPATH.'core/Common.php'; require BASEPATH.'database/DB.php';
require APPPATH.'config/database.php';
$db['default']['db_debug']=FALSE;
$connection=DB($db['default'],TRUE);
if(!$connection->conn_id)throw new Exception('Test database connection unavailable.');
class CI_Model { public $db; }
class TemporaryDatabase {
    public $db_debug=FALSE; private $db;
    function __construct($db){$this->db=$db;}
    function query($sql){return $this->db->query(str_replace('CREATE TABLE IF NOT EXISTS','CREATE TEMPORARY TABLE IF NOT EXISTS',$sql));}
    function __call($method,$args){return call_user_func_array(array($this->db,$method),$args);}
}
require __DIR__.'/../models/Reimbursement_model.php';
$model=new Reimbursement_model();$model->db=new TemporaryDatabase($connection);$model->install();
$count=0;function check($ok,$label){global $count;if(!$ok)throw new Exception('FAILED: '.$label);$count++;}
$data=array('travel_id'=>1,'employee_id'=>'TEST-OWNER','status'=>'Draft','items_json'=>'[]','checklist_json'=>'{}','notes'=>'','total_cents'=>12345,'advance_cents'=>0,'itinerary_meta_json'=>json_encode(array('agency'=>'Test Agency','employee_name'=>'Test Employee','certifier_name'=>'Test Certifier')));
check($model->save(NULL,$data,'TEST-OWNER','Draft saved','test'),'draft creation');
$old=$model->claim(1);check(json_decode($old['itinerary_meta_json'],TRUE)['certifier_name']==='Test Certifier','itinerary metadata round trip');check($old['status']==='Draft' && (int)$old['revision']===1,'draft persisted');
check(!$model->save(NULL,$data,'TEST-OWNER','Draft saved','duplicate'),'one claim per travel');
check($model->save($old,array('status'=>'Submitted'),'TEST-OWNER','Submitted','test'),'submission persisted');
check(!$model->save($old,array('status'=>'Draft'),'TEST-OWNER','Draft saved','stale'),'stale write blocked');
$claim=$model->claim(1);check($claim['status']==='Submitted' && (int)$claim['revision']===2,'stale update leaves claim unchanged');
check($connection->count_all('one_sgod_reimbursement_events')===2,'only successful updates logged');
foreach(array('For Budget Certification','For Approval','For Payment','Paid') as $status){$claim=$model->claim(1);check($model->save($claim,array('status'=>$status),'TEST-REVIEWER',$status,'test'),'persist workflow '.$status);}
check($connection->count_all('one_sgod_reimbursement_events')===6,'complete workflow audit');
$connection->close();echo "$count temporary-table model checks passed.\n";
