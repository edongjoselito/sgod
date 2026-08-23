<?php
$personnelExpiryDates = array(); $personnelManageRows = array();
foreach ((array) ($personnel ?? array()) as $record) { $personnelExpiryDates[] = (string) ($record->prc_expiration ?? ''); $personnelManageRows[] = (int) ($record->id ?? 0); }
?>
<style>.personnel-card .table-responsive{overflow:visible!important}.personnel-card .btn-group.dropup{position:relative;z-index:10}.personnel-card .btn-group.dropup.show{z-index:1060}.personnel-card .dropdown-menu{z-index:1061}</style>
<script>
document.addEventListener('DOMContentLoaded', function () {
 var table=document.querySelector('.personnel-card table'); if(!table)return;
 var expiryDates=<?= json_encode($personnelExpiryDates); ?>, ids=<?= json_encode($personnelManageRows); ?>, headers=[].slice.call(table.querySelectorAll('thead th')), licenseIndex=headers.map(function(h){return h.textContent.trim()}).indexOf('License'); if(licenseIndex===-1)return;
 var expirationHeader=document.createElement('th'); expirationHeader.textContent='PRC Expiration'; headers[licenseIndex].parentNode.insertBefore(expirationHeader,headers[licenseIndex].nextSibling);
 [].slice.call(table.querySelectorAll('tbody tr')).forEach(function(row,index){if(!row.children.length||row.children.length===1)return;var cell=document.createElement('td'),expiry=expiryDates[index]||'';if(!expiry)cell.textContent='Not specified';else{var days=Math.ceil((new Date(expiry+'T00:00:00')-new Date().setHours(0,0,0,0))/86400000);cell.appendChild(document.createTextNode(expiry+' '));if(days<0){var badge=document.createElement('span');badge.className='badge badge-danger';badge.textContent='Expired';cell.appendChild(badge)}}row.insertBefore(cell,row.children[licenseIndex+1]);});
 var actionHeader=table.querySelector('thead th:last-child');if(actionHeader)actionHeader.textContent='Manage';
 [].slice.call(table.querySelectorAll('tbody tr')).forEach(function(row,index){if(!row.children.length||row.children.length===1||!ids[index])return;row.lastElementChild.innerHTML='<div class="btn-group dropup"><button type="button" class="btn btn-outline-primary btn-sm dropdown-toggle" data-toggle="dropdown">Manage</button><div class="dropdown-menu dropdown-menu-right"><a class="dropdown-item" href="<?= base_url(); ?>Page/school_personnel_form/'+ids[index]+'"><i class="mdi mdi-pencil-outline mr-1"></i>Edit</a><a class="dropdown-item" href="<?= base_url(); ?>Page/school_personnel_details/'+ids[index]+'"><i class="mdi mdi-card-account-details-outline mr-1"></i>View More Details</a><div class="dropdown-divider"></div><a class="dropdown-item text-danger" href="<?= base_url(); ?>Page/school_personnel_delete/'+ids[index]+'" onclick="return confirm(\'Remove this personnel record?\');"><i class="mdi mdi-delete-outline mr-1"></i>Delete</a></div></div>';});
});
</script>
