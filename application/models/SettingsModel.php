<?php
class SettingsModel extends CI_Model 
{

	//Get Track
	function getTrack()
	{
	$query=$this->db->query("select * from qualifications group by Track order by Track");
	return $query->result();
	}

	//Get Strand
	function getStrand()
	{
	$query=$this->db->query("select * from qualifications group by Qualification order by Qualification");
	return $query->result();
	}
	
	//Get Track List
	function getSectionList()
	{
	$query=$this->db->query("select * from sections order by Section");
	return $query->result();
	}
	
	//Get Track List
	function getDepartmentList()
	{
	$query=$this->db->query("select * from course_table order by Major");
	return $query->result();
	}
	

}
