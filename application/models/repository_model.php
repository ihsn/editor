<?php
class Repository_model extends CI_Model {
 
    public function __construct()
    {
        parent::__construct();
		//$this->output->enable_profiler(TRUE);
    }
	
	//search
    function search($limit = NULL, $offset = NULL,$filter=NULL,$sort_by=NULL,$sort_order=NULL)
    {
		$this->db->start_cache();

		//select columns for output
		$this->db->select('*');
		
		//allowed_fields
		$db_fields=array('repositoryid', 'title','url','organization');
		
		//set where
		if ($filter)
		{			
			foreach($filter as $f)
			{
				//search only in the allowed fields
				if (in_array($f['field'],$db_fields))
				{
					$this->db->like($f['field'], $f['keywords']); 
				}
				else if ($f['field']=='all')
				{
					foreach($db_fields as $field)
					{
						$this->db->or_like($field, $f['keywords']); 
					}
				}
			}
		}

		//set order by
		if ($sort_by!='' && $sort_order!='')
		{
			$this->db->order_by($sort_by, $sort_order); 
		}
		
		//set Limit clause
	  	$this->db->limit($limit, $offset);
		$this->db->from('repositories');
		$this->db->stop_cache();

        $result= $this->db->get();
		if ($result)
		{			
			$result=$result->result_array();
			return $result;
		}
		else
		{
			echo $this->db->last_query();
			return FALSE;
		}	
    }
  	
    function search_count()
    {
          return $this->db->count_all_results('repositories');
    }
	
	/**
	* Select a single repository item
	*
	**/
	function select_single($id){
		$this->db->where('id', $id); 
		return $this->db->get('repositories')->row_array();
	}
	
	
	/**
	* Select surveys by repositoryid
	*
	**/
	function get_surveys_by_repository($repositoryid){
		$this->db->where('repositoryid', $repositoryid); 
		return $this->db->get('harvester_queue')->result_array();
	}
	

	/**
	*
	* Add/update items in the harvester queue
	*
	* Status checks
	*	- new	- not in the catalog
	*	- changed	- checksum is different
	* 	- deleted	- 
	*/
	function update_queue($repositoryid, $options)
	{
		$allowed_fields=array('retries','repositoryid','survey_url',
						'status','ddi_local_path','changed','title',
						'survey_timestamp', 'retries','country','survey_year',
						'accesspolicy','checksum','surveyid','type');
		
		$data=array();
		foreach($options as $key=>$value)
		{
			if (in_array($key,$allowed_fields))
			{
				$data[$key]=$value;
			}
		}
		
		$data['repositoryid']=$repositoryid;
		
		//check if exists
		$queued_item=$this->get_queue_item($repositoryid,$options['survey_url']);
		
		//check if survey is already harvested
		$isharvested=$this->survey_exists($repositoryid,$options['surveyid']);
		
		if ($queued_item)
		{			
			$data['status']=$queued_item['status'];
			/*if (!$isharvested)
			{
				$data['status']="new";
			}
			else
			{
				$data['status']="harvested";
			}*/

			$this->db->where('survey_url',$options['survey_url']);
			$this->db->where('repositoryid',$repositoryid);
			$this->db->update('harvester_queue', $data); 
		}
		else
		{
			if (!$isharvested)
			{
				$data['status']="new";
			}
			else
			{
				$data['status']="harvested";
			}
			
			//insert db
			$result=$this->db->insert('harvester_queue', $data); 
		}	
	}

	/**
	*
	* Checks if a survey exists in the queue
	**/
	function check_queue_item_exists($repositoryid,$survey_url)
	{
		$this->db->where('repositoryid', $repositoryid); 
		$this->db->where('survey_url', $survey_url); 
		$row=$this->db->get('harvester_queue')->row_array();
		
		if (count($row)>0)
		{
			return TRUE;
		}
		return FALSE;
	}
	
	/**
	* Return queue item array
	*
	**/
	function get_queue_item($repositoryid,$survey_url)
	{
		$this->db->where('repositoryid', $repositoryid); 
		$this->db->where('survey_url', $survey_url); 
		$row=$this->db->get('harvester_queue')->row_array();
		
		return $row;
	}


	function update_survey_data_access($repositoryid,$surveyid,$data_access_type)
	{
		//get survey id
		$survey_id=$this->get_survey_id($repositoryid,$surveyid);
		
		//get data acess form id
		$this->db->select("formid");
		$this->db->where('model', $data_access_type); 
		$form_row=$this->db->get('forms')->row_array();
		
		if ($form_row)
		{
			//update survey form id
			$data=array('formid'=>$form_row['formid']);
			$this->db->where('id', $survey_id); 
			$this->db->update("surveys",$data);	
			
			return TRUE;
		}
		
		return FALSE;
	}

	/**
	*
	* Update status codes for harvester queue items
	*
	* Status checks
	*	- new	- not in the catalog
	*	- changed	- checksum is different
	* 	- deleted	- 
	*/
	/*function update_queue_stats()
	{
		$queue=$this->db->get('harvester_queue')->result_array();
		
		foreach($queue as $row)
		{
			$exists=$this->survey_exists($row['repositoryid'],$row['surveyid']);
			if (!$exists)
			{
				//not found, mark as new
				$this->update_status_code($id,"NEW");
			}
			else
			{
				$this->update_status_code($id,"HARVESTED");
			}
		}
		
		//delete
		
		
		
		if (count($rows)>0)
		{
			return TRUE;
		}
		return FALSE;
	}
	*/
	
	
	function update_status_code($id,$status)
	{
		$data=array('status'=>$status);
		$this->db->where('id', $id); 
		$this->db->update("harvester_queue",$data);
	}
	
	

	//check if a survey exists in the catalog
	function survey_exists($repositoryid,$surveyid)
	{
		$this->db->where('repositoryid', $repositoryid); 
		$this->db->where('surveyid', $surveyid); 
		$rows=$this->db->get('surveys')->result_array();
		
		if (count($rows)>0)
		{
			return TRUE;
		}
		return FALSE;	
	}
	
	function get_survey_id($repositoryid,$surveyid)
	{
		$this->db->select("id");
		$this->db->where('repositoryid', $repositoryid); 
		$this->db->where('surveyid', $surveyid); 
		$rows=$this->db->get('surveys')->result_array();
		
		if (count($rows)>0)
		{
			return $rows[0]['id'];
		}
		return FALSE;
	}
	
	function get_row($repositoryid,$surveyid)
	{
		$this->db->select("harvester_queue.*,repositories.url as repo_url, repositories.title as repo_title");
		$this->db->join('repositories', 'harvester_queue.repositoryid = repositories.repositoryid','left');
		$this->db->where('harvester_queue.repositoryid', $repositoryid); 
		$this->db->where('harvester_queue.surveyid', $surveyid); 
		$query=$this->db->get('harvester_queue');
		$rows=$query->result_array();

		if (count($rows)>0)
		{
			return $rows[0];
		}
		return FALSE;
	}
	
	/**
	*
	* Return all queued items
	*
	**/
	/*function load_queue()
	{
		//$this->db->where('status!=', "'completed'",FALSE); 
		$query=$this->db->get('harvester_queue');
	//	echo $this->db->last_query();
		return $query->result_array();
	}*/

	//get a single item from the queue for processing
	function queue_pop($id=NULL)
	{
		//$this->db->where('status!=', "'completed'",FALSE);
		$this->db->where('retries<', 3,FALSE); 
		
		if($id!=NULL)
		{
			$this->db->where('id', (int)$id); 
		}
		
		$query=$this->db->get('harvester_queue',1,0);
		//echo $this->db->last_query();
		return $query->row_array();
	}

	/**
	*
	* update survey info in queue
	**/
	function update_queued_survey($survey_url,$options)
	{
		$fields=array('retries','repositoryid','survey_url',
						'status','ddi_local_path','changed','title',
						'survey_timestamp', 'retries','country','survey_year');
		
		$data=array();

		foreach($options as $key=>$value)
		{
			if (in_array($key,$fields))
			{
				$data[$key]=$value;
			}
		}
				
		$this->db->where('survey_url',$survey_url);
		//$data=array('status'=>$status, 'retries'=>$retries, 'ddi_local_path'=>$ddi_path);
		
		$query=$this->db->update('harvester_queue',$data);
		//echo $this->db->last_query();
		return $query;
	}




	/**
	* update 
	*
	*	id			int
	* 	options		array
	**/
	function update($id,$options)
	{
		//allowed fields
		$valid_fields=array(
			'repositoryid',
			'title',
			'url',
			'organization',
			'country',
			'status',
			'changed',
			'scan_interval',
			'scan_lastrun',
			'short_text',
			'long_text',
			'thumbnail',
			'type',
			'weight',
			'ispublished',
			'section',
			'group_da_public',
			'group_da_licensed'
			);

		//add date modified
		$options['changed']=date("U");
					
		//pk field name
		$key_field='id';
		
		$data=array();
		
		//build update statement
		foreach($options as $key=>$value)
		{
			if (in_array($key,$valid_fields) )
			{
				$data[$key]=$value;
			}
		}

		//update db
		$this->db->where($key_field, $id);
		$result=$this->db->update('repositories', $data); 

		return $result;		
	}



/**
	* add 
	*
	* 	options			array
	**/
	function insert($options)
	{
		//allowed fields
		$valid_fields=array(
			'repositoryid',
			'title',
			'url',
			'organization',
			'country',
			'status',
			'changed',
			'scan_interval',
			'scan_lastrun',
			'short_text',
			'long_text',
			'thumbnail',
			'type',
			'weight',
			'ispublished',
			'section',
			'group_da_public',
			'group_da_licensed'
			);

		//add date modified
		$options['changed']=date("U");
							
		$data=array();
		
		//build update statement
		foreach($options as $key=>$value)
		{
			if (in_array($key,$valid_fields) )
			{
				$data[$key]=$value;
			}
		}
		
		//insert record into db
		$result=$this->db->insert('repositories', $data); 
		
		return $result;	
	}


	/**
	* checks if a URL exists
	*
	*/
	function url_exists($url,$id=NULL)
	{
		$this->db->select('id');		
		$this->db->from('repositories');		
		$this->db->where('url',$url);		
		if ($id!=NULL)
		{
//			$this->db->where('id',$id);		
			$this->db->where('id !=', $id);
		}
        $result= $this->db->count_all_results();
		return $result;
	}
	
	/**
	*
	* Delete a repository
	*
	**/
	function delete($id=NULL)
	{
		if (!is_numeric($id))
		{
			return FALSE;
		}
		
		$repo=$this->select_single($id);
		
		$this->db->where('id',$id);
		$this->db->delete('repositories');
		
		//remove from survey_repos
		$this->db->where('repositoryid',$repo['repositoryid']);
		$this->db->delete('survey_repos');
	}
	
	
	/**
	*
	* Returns an array of all repositories
	*	
	* Note: duplicate function see catalog_model.php
	**/
	function get_repositories($published=FALSE, $system=TRUE)
	{
		$this->db->select('repositories.*,repository_sections.title as section_title, repository_sections.weight as section_weight');

		if ($published==TRUE)
		{
			$this->db->where("repositories.ispublished",1);
		}

		if ($system==FALSE)
		{
			//show system repositories
			$this->db->where("repositories.type !=",2);
		}
		
		$this->db->order_by('repository_sections.weight ASC, repositories.weight ASC, repositories.title'); 
		$this->db->join('repository_sections', 'repository_sections.id= repositories.section','inner');
		$query=$this->db->get('repositories');

		if (!$query)
		{
			return array();
		}
		
		$result=$query->result_array();
		
		if (!$result)
		{
			return array();
		}

		//create an array, making the repositoryid array key
		$repos=array();
		foreach($result as $row)
		{
			$repos[$row['repositoryid']]=$row;
		}
	
		return $repos;
	}
	
	
	function get_repository_by_repositoryid($repositoryid)
	{
		$this->db->select('*');
		$this->db->where('repositoryid',$repositoryid);
		$query=$this->db->get('repositories');

		if (!$query)
		{
			return FALSE;
		}
		
		return $query->row_array();
	}
	
	/**
	*
	* Assign a user to a repository
	*
	* @roleid	0=delete role
	**/
	function assign_role($repositoryid,$userid,$roleid)
	{
		//delete existing role
		$this->db->where('userid',$userid);
		$this->db->where('repositoryid',$repositoryid);
		$this->db->delete('user_repositories'); 
		
		//0 to remove role
		if ($roleid==0)
		{
			return;
		}
		
		$options=array(
			'userid'=>$userid,
			'repositoryid'=>$repositoryid,
			'roleid'=>$roleid
			);
		
		//add new role
		$this->db->insert('user_repositories', $options); 
	}
	
	/**
	*
	* Returns Catalog admins for a repo
	*
	**/
	function get_repository_admins($repositoryid=NULL)
	{
		if (!is_numeric($repositoryid))
		{
			return FALSE;
		}

		/*
		$sql='select
			  u.id,u.username,u.email,u.active,
			  ur.roleid as user_repo_role_id,ur.repositoryid as repositoryid,
			  ug.name as user_group_name,
			  rug.name as repo_role_name
			  from users u
			left join user_repositories ur on u.id=ur.userid
			left join user_groups ug on u.group_id=ug.id
			left join repo_user_groups rug on rug.id=ur.roleid
			where ug.name not in (\'admin\',\'user\')
			';
		*/
		$sql='select * from user_repositories where repositoryid='.$this->db->escape($repositoryid);
			
		$query=$this->db->query($sql);
		
		if ($query)
		{
			return $query->result_array();
		}
		
		return FALSE;
	}
	
	function group_add_repo($group_id, $repo_id) {
		$data = array(
			'group_id' => $group_id,
			'repo_id'  => $repo_id
		);
		$this->db->insert('repo_permissions', $data);
	}
	
	function group_repos($group_id) {
		$q = $this->db->select('*')
			->from('repo_permissions')
			->where('group_id', $group_id);
			
		$result = $q->get()->result();
		
		return $result;
	}
	function group_has_repo($group_id, $repo_id) {
		$q = $this->db->select('id')
			->from('repo_permissions')
			->where('repo_id', $repo_id)
			->where('group_id', $group_id);
		$result = $q->get()->result();
		
		return isset($result[0]->id);
	}
	
	function group_remove_repo($group_id, $repo_id) {
		$this->db->where('group_id', $group_id);
		$this->db->where('repo_id', $repo_id);
		$this->db->delete('repo_permissions');
	}
	/**
	*
	* Checks if studies in the repository has citations
	**/
	function has_citations($repositoryid)
	{
		$this->load->library('cache');
		
		//check cache
		$count= $this->cache->get( md5('repository-has-citations'.$repositoryid));
		
		//no cache found
		if ($count===FALSE)
		{					
			$this->db->select('count(survey_repos.sid) as found');
			$this->db->join('survey_repos', 'survey_repos.sid = survey_citations.sid','inner');
			$this->db->where('survey_repos.repositoryid', $repositoryid); 
			$query=$this->db->get("survey_citations")->row_array();
			
			if (count($query)>0)
			{
				$count=$query['found'];
			}
		
			//write cache data
			$this->cache->write($count, md5('repository-has-citations'.$repositoryid));
		}
			
		return $count;
	}

	/**
	*
	* Returns an array of repository sections
	**/
	function get_repository_sections()
	{
		$this->db->order_by('weight','ASC');
		$result= $this->db->get('repository_sections')->result_array();

	/*	$list=array();
		foreach($result as $row)
		{
			$list[$row['title']]=$row['title'];
		} 
		return $list; */
		
		return $result;
	}


	/**
	*
	* Check if survey's data access is set by collection
	*
	* Returns collection id or false
	**/
	function survey_has_da_by_collection($sid)
	{
		$this->db->select('repositories.repositoryid,group_da_public,group_da_licensed,repositories.title');
		$this->db->from('repositories');
		$this->db->join('survey_repos sr', 'sr.repositoryid = repositories.repositoryid','left');		
		$this->db->where('sid',$sid);
		$this->db->where('(group_da_public=1 OR group_da_licensed=1)',NULL,FALSE);
		$result=$this->db->get()->result_array();

		if (!$result)
		{
			return FALSE;
		}

		return $result;
	}
	
	
	/**
	* 
	* Get survey repository info by survey id
	**/
	function get_survey_repositories($survey_id_array)
	{
		$survey_id_array=(array)$survey_id_array;
		
		if (count($survey_id_array)==0)
		{
			return FALSE;
		}
		
		$this->db->select('*');
		$this->db->where_in('sid',$survey_id_array);
		$query=$this->db->get('survey_repos')->result_array();
		
		$output=NULL;
		foreach($query as $row)
		{
			//survey can belong to one or more repos
			$output[$row['sid']][]=$row;
		}
		
		return $output;
	}


	/**
	* checks if a repositoryid exists
	*
	*/
	function repository_exists($repositoryid,$id=NULL)
	{
		$this->db->select('id');
		$this->db->from('repositories');		
		$this->db->where('repositoryid',$repositoryid);		
		if ($id!=NULL)
		{
			$this->db->where('id !=', $id);
		}
        $result= $this->db->count_all_results();
		return $result;
	}


	/**
	*
	* Return survey counts per repository
	**/
	function survey_stats_by_repo()
	{
		$result=$this->db->query('select repositoryid,count(sid) as total from survey_repos group by repositoryid')->result_array();
		return $result;
	}
	
	
	/**
	*
	* Survey counts per data access in the repository e.g. PUF, LIC, Direct Downloads, Remote
	**/
	public function repo_survey_counts_by_data_access($repositoryid,$da_types=NULL)
	{
		$this->db->select('count(surveys.formid) as total,surveys.formid,forms.model as da_type');		
		$this->db->join('forms', 'forms.formid = surveys.formid','inner');
		$this->db->join('survey_repos', 'surveys.id = survey_repos.sid','inner');
		$this->db->group_by('surveys.formid, forms.model');
		$this->db->where('survey_repos.repositoryid',$repositoryid);
		$this->db->where('surveys.published',1);
		
		if(is_array($da_types))
		{
			$this->db->where_in('forms.model',$da_types);
		}
		
		return $this->db->get('surveys')->result_array();
	}
	
	/**
	*
	* Get surveys by repository
	*
	*	@data_access_types	array	public, licensed, etc.
	**/
	public function repo_survey_list($repositoryid,$data_access_types=NULL)
	{
		$this->db->select('surveys.id,surveys.titl,surveys.nation,surveys.data_coll_start,surveys.data_coll_end,forms.model as da_model');		
		$this->db->join('survey_repos', 'surveys.id = survey_repos.sid','inner');
		$this->db->join('forms', 'surveys.formid = forms.formid','left');
		$this->db->where('survey_repos.repositoryid',$repositoryid);
		if ($data_access_types)
		{
			$this->db->where_in('forms.model',$data_access_types);
		}
		$this->db->where('surveys.published',1);		
		return $this->db->get('surveys')->result_array();
	}
	
	
	/**
	*
	* Get tree structure for repos/sections
	**/
	public function get_repositories_tree()
	{
		//get repository sections
		$this->db->select('*');
		$this->db->order_by('weight');
		$sections=$this->db->get('repository_sections')->result_array();
		
		//find repos by section
		foreach($sections as $key=>$section)
		{
			$children=$this->get_repositories_by_section($section['id']);
			if($children)
			{
			$sections[$key]['children']=$children;
			}
		}
		
		return $sections;
	}
	
	
	/**
	*
	* Get repositories by section id
	**/
	public function get_repositories_by_section($section_id)
	{
		$this->db->select('r.title,r.repositoryid,count(sr.sid) as surveys_found');
		$this->db->join('survey_repos sr', 'r.repositoryid= sr.repositoryid','INNER');
		$this->db->where('r.ispublished',1);
		$this->db->where('r.pid >',0);
		$this->db->where('r.section',$section_id);
		$this->db->group_by('r.id,r.pid,r.title,r.repositoryid');
		$this->db->order_by('r.weight');		
		return $this->db->get('repositories r')->result_array();	
	}
	
	public function get_repositories_with_survey_counts()
	{
		$this->db->select('r.id,r.pid,r.title,r.repositoryid,count(sr.sid) as surveys_found');
		$this->db->join('survey_repos sr', 'r.repositoryid= sr.repositoryid','INNER');
		$this->db->where('r.ispublished',1);
		$this->db->where('r.pid >',0);
		$this->db->group_by('r.id,r.pid,r.title,r.repositoryid');
		$this->db->order_by('r.weight');		
		return $this->db->get('repositories r')->result_array();	
	}
	
	
		/**
	*
	* Returns an array of all repository names
	*	
	**/
	function get_repository_array()
	{
		$this->db->select('repositoryid');
		$query=$this->db->get('repositories');

		if (!$query)
		{
			return array();
		}
		
		$result=$query->result_array();
		
		if (!$result)
		{
			return array();
		}

		//create an array, making the repositoryid array key
		$repos=array();
		foreach($result as $row)
		{
			$repos[]=$row['repositoryid'];
		}
	
		return $repos;
	}

	/**
	*
	* check if the repo/collection has Data Access by Collection enabled
	**/
	/*
	public function repo_has_group_data_access($repositoryid,$data_access_type)
	{
		$this->db->select('group_da_public,group_da_licensed');		
		$this->db->where('repositoryid',$repositoryid);		
		$row=$this->db->get('repositories')->row_array();
		
		if ($row)
		{
			if ($data_access_type=='public')
			{
				return (bool)$row['group_da_public'];
			}
			else if($data_access_type=='licensed')
			{
				return (bool)$row['group_da_licensed'];
			}	
		}
		
		return FALSE;
	}*/
}
?>