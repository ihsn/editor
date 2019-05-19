<?php


class Misc extends MY_Controller {
 
    public function __construct()
    {
        parent::__construct($skip_auth=TRUE);
        //$this->load->model("Filestore_model");
	}


function index()
{
	echo "hi";
}

function remove_duplicates()
{

#copy citations from the old unpublished studies to new survey IDs

	$old_list="2386:'AFR_1992-1995_CFLD_v01_M',
2394:'AFR_1995-1998_SACMEQ-I_v01_M',
5189:'AFR_1999_AFB-12_v01_M',
2395:'AFR_2000-2002_SACMEQ-II_v01_M',
4489:'',
5172:'BGR_2003_MTHS_v01_M',
4491:'BGR_2013_ES_v02_M',
3027:'BFA_2011_CMEHESD_v01_M_v02_A_PUF',
4496:'BFA_2011_CMEHESD_v01_M_v02_A_PUF',
3612:'',
1373:'BTN_2010_MICS_v01_M',
3102:'ESP_2013_AMADEUS_v01_M',
2764:'ETH_2011_ES_v01_M',
2765:'ETH_2011_MS_v01_M',
1889:'',
3143:'IDN_2009-2011_WSP-IE_v01_M_v01_A_PUF',
357:'BRA_1991_PHC_v01_M',
2333:'IND_2002_NSS58-SCH0.21_v01_M',
2612:'IND_2010_NVBDCP_v01_M_PUF',
5188:'IRN_2000_IDHS_v01_M',
944:'KEN_2010-2012_AEIMPBS_v01_M_v01_A_PUF',
2267:'KEN_2010-2012_AEIMPBS_v01_M_v01_A_PUF',
3235:'KEN_2012_SDI_v01_M_PUF',
3575:'KGZ_2009_PHC_v01_M_v03_A_IPUMS',
2262:'KHM_2007_CSES_v01_M',
3161:'LAO_2010_LFS-CLS_v01_M',
3160:'LAO_2011_AgC_v01_M',
2241:'MEX_2009_SAGE_v01_M',
6000:'',
3532:'PAK_2007_MICS-FATA_v01_M',
3144:'PER_2009-2011_WSP-IE_v01_M_v01_A_PUF',
2087:'PHL_2008_QLFS-Q1_v01_M',
2722:'ROU_2011_FINDEX_v02_M',
3799:'RWA_2009_CFSVA_v01_M',
2766:'RWA_2011_ES_v01_M',
2767:'RWA_2011_InS_v01_M',
2768:'RWA_2011_MS_v01_M',
1852:'THA_2004_ICS_v01_M_WB',
6005:'TJK_2012_DHS_v01_M',
6006:'TJK_2012_DHS_v01_M',
3624:'TZA_2007_AgSCS_v01_M',
2883:'TZA_2007_AgSCS_v01_M',
2596:'TZA_2010_NPS-R2_v03_M',
7125:'',
2809:'VNM_2009-2011_WSP-IE_v01_M_v01_A_PUF',
2756:'WLD_1960-1985_EAD_v01_M',
2757:'WLD_1960-2010_FDS_v02_M',
2372:'WLD_1972-2010_FW_v01_M',
1055:'WLD_1960-1985_EAD_v01_M',
1088:'WLD_1975-2000_PDIM_v01_M',
1367:'WLD_1955-2011_EDAI_v01_M',
1376:'WLD_1950-2012_AG_v01_M',
1369:'WLD_1960-2010_FDS_v02_M',
1925:'WLD_1981-2008_WVS_v01_M',
1525:'WLD_2009_PISA_v02_M',
5182:'',
5183:'',
2824:'',
2761:'ZAF_1994-2012_PALMS_v01_M',
2393:'ZAF_1994-2012_PALMS_v01_M',
2828:'ZAF_1996_CCA_v01_M',
1395:'ZAF_2000_LFS-SEP_v02_M',
1397:'ZAF_2001_LFS-SEP_v02_M',
2839:'ZAF_2001_PHC_v01_M_v01_A_CASASP',
2758:'ZAF_2002-2009_CAPS_v01_M',
2391:'ZAF_2002-2009_CAPS_v01_M',
2297:'ZAF_2002-2009_CAPS_v01_M',
1519:'ZAF_2002-2009_CAPS_v01_M',
1399:'ZAF_2002_LFS-SEP_v02_M',
1402:'ZAF_2004_LFS-MAR_v02_M',
1405:'ZAF_2005_LFS-SEP_v02_M',
2842:'ZAF_2006-2008_DSDS_v01_M',
1406:'ZAF_2006_LFS-MAR_v02_M',
1407:'ZAF_2006_LFS-SEP_v02_M',
1408:'ZAF_2007_LFS-MAR_v02_M',
1409:'ZAF_2007_LFS-SEP_v02_M',
2635:'ZAF_2007_PHC_v01_M_v03_A_IPUMS',
1410:'ZAF_2008_QLFS-Q1_v03_M',
2284:'ZAF_2008_QLFS-Q1_v03_M',
1411:'ZAF_2008_QLFS-Q2_v03_M',
2285:'ZAF_2008_QLFS-Q2_v03_M',
1412:'ZAF_2008_QLFS-Q3_v03_M',
2286:'ZAF_2008_QLFS-Q3_v03_M',
1413:'ZAF_2008_QLFS-Q4_v03_M',
2287:'ZAF_2008_QLFS-Q4_v03_M',
1414:'ZAF_2009_QLFS-Q1_v03_M',
2288:'ZAF_2009_QLFS-Q1_v03_M',
1415:'ZAF_2009_QLFS-Q2_v03_M',
2289:'ZAF_2009_QLFS-Q2_v03_M',
2290:'ZAF_2009_QLFS-Q3_v03_M',
1417:'ZAF_2009_QLFS-Q4_v03_M',
2291:'ZAF_2009_QLFS-Q4_v03_M',
2292:'ZAF_2010_QLFS-Q1_v03_M',
1419:'ZAF_2010_QLFS-Q2_v03_M',
2293:'ZAF_2010_QLFS-Q2_v03_M',
1476:'ZAF_2010_QLFS-Q3_v03_M',
2295:'ZAF_2010_QLFS-Q3_v03_M',
1477:'ZAF_2010_QLFS-Q4_v03_M',
2296:'ZAF_2010_QLFS-Q4_v03_M',
1473:'ZAF_2011_QLFS-Q1_v02_M',
1474:'ZAF_2011_QLFS-Q2_v02_M',
1475:'ZAF_2011_QLFS-Q3_v02_M',
2411:'ZAF_2011_QLFS-Q4_v02_M',
2412:'ZAF_2012_QLFS-Q1_v02_M',
2854:'ZAF_2012_QLFS-Q2_v02_M',
3348:'ZAF_2012_QLFS-Q3_v02_M',
3349:'ZAF_2012_QLFS-Q4_v02_M',
3136:'ZAF_2013_QLFS-Q1_v02_M',
4655:'ZMB_1995_SACMEQ-I_v01_M',
2752:'COD_2011_FINDEX_v02_M',
5191:'ZAF_1996_KDIHS_v01_M',
5199:'ZAF_1996_RD_v01_M',
4093:'ZAF_2013_QLFS-Q3_v02_M',
5193:'ZAF_1982_WVS-W1_v01_M',
5194:'ZAF_1990_WVS-W2_v01_M',
5192:'ZAF_1990_WVS-W2_v01_M',
5195:'ZAF_2001_WVS-W4_v01_M',
";
$old_list=str_replace("'","",$old_list);
	$old_list=explode(",",$old_list);
echo "<pre>";	
foreach($old_list as $item){
	$item=explode(":",$item);

	var_dump($item);

	//find the ID
	if(trim($item[1])=='')
	{
		echo "\r\n skipping ". $item[0]."\r\n";
		continue;
	}

	$new_id=$this->get_id_by_idno($item[1]);

	var_dump($new_id);

	if($new_id>0)
	{
		$this->copy_citations($item[0],$new_id);	
	}

	echo "\r\n===DONE==";
	//die();

}

}

function copy_citations($old_id,$new_id){
	$result=$this->db->query("insert ignore into survey_citations (sid,citationid) select $new_id, citationid from survey_citations where sid=$old_id;");
	echo $this->db->last_query();
	var_dump($result);
}



function get_id_by_idno($idno){
	
	$this->db->where("idno",$idno);
	$this->db->select("id");
	$row=$this->db->get("surveys")->row_array();
	if(!$row){
		return false;
	}
	return $row['id'];
}

}
