<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;
use Cake\Controller\Controller;
use Cake\Datasource\ConnectionManager;
use Cake\Core\Configure;
use Cake\Event\EventInterface;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/4/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{
    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('FormProtection');`
     *
     * @return void
     */
	 /*public function beforeFilter() {
        $this->Auth->allow('index');
    }*/
	
    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');
		

        /*
         * Enable the following component for recommended CakePHP form protection settings.
         * see https://book.cakephp.org/4/en/controllers/components/form-protection.html
         */
        //$this->loadComponent('FormProtection');
    }
	public function process_title($titlearr)
	{
		
		$single = array();
		$double = array();
		$triple = array();
		$phrase = array();
		$artids = array();
		$tagarr_return = array();
		foreach($titlearr as $title)
		{
			if($_SESSION['category']=='')
			{
				$split_tit_arr = explode(' ',trim($title));
				$tokenize_arr =$this->tokenization($split_tit_arr);
				$result = $this->query_performance($tokenize_arr,$artids);
			}
			else
			{
				$result = $this->query_performance($title,$artids);
			}
			$artarr = $result[0];
			$cntarr = $result[1];
			
						
			$single_match_arr = $cntarr[1];
			$doble_match_arr = $cntarr[2];
			$triple_match_arr = $cntarr[3];
						
			$singleKW+=count($single_match_arr);
			$dobleKW+=count($doble_match_arr);
			$tripleKW+=count($triple_match_arr);
					
			
			if(count($single_match_arr)>0)
				$single_artids.=  implode(',', array_column($single_match_arr, 'Artid')).',';
			if(count($doble_match_arr)>0)
				$dbl_artids.=  implode(',', array_column($doble_match_arr, 'Artid')).',';
			if(count($triple_match_arr)>0)
				$triple_artids.=  implode(',', array_column($triple_match_arr, 'Artid')).',';
			
			$artids[1] = $single_artids;
			$artids[2] = $dbl_artids;
			$artids[3] = $triple_artids;
						
			$tagarr = $artarr[0];
			$singleKW_arr = $artarr[1];
			$dobleKW_arr = $artarr[2];
			$tripleKW_arr = $artarr[3];
			$phraseKW_arr = $artarr[4];
			
			$tagarr_return = array_merge($tagarr_return,$tagarr);
			if(count($singleKW_arr)>0)
			{
				arsort($singleKW_arr);
				foreach($singleKW_arr as $kw)
				{
					if($kw['token']!='')
					{
						if($s==0)
							
						if (array_key_exists($kw['token'],$single))
						{
							$single[$kw['token']] = $single[$kw['token']]+$kw['sumval'];
						}
						else
						{
							$single[$kw['token']] = $kw['sumval'];
						}
					}

				}
			}
			if(count($dobleKW_arr)>0)
			{
				arsort($dobleKW_arr);
				$token = $dobleKW_arr[0]['token'];
				$sumval = array_sum(array_column($dobleKW_arr,'sumval'));
				if (array_key_exists($token,$double))
				{
					$double[$token] = $double[$token]+$sumval;
				}
				else
				{
					$double[$token] = $sumval;
				}
				/*foreach($dobleKW_arr as $kw)
				{
					if($kw['token']!='')
					{
						if (array_key_exists($kw['token'],$double))
						{
							$double[$kw['token']] = $double[$kw['token']]+$kw['sumval'];
						}
						else
						{
							$double[$kw['token']] = $kw['sumval'];
						}
					}
				}*/
			}
			if(count($tripleKW_arr)>0)
			{
				arsort($tripleKW_arr);
				$token = $tripleKW_arr[0]['token'];
				$sumval = array_sum(array_column($tripleKW_arr,'sumval'));
				if (array_key_exists($token,$triple))
				{
					$triple[$token] = $triple[$token]+$sumval;
				}
				else
				{
					$triple[$token] = $sumval;
				}
				/*foreach($tripleKW_arr as $kw)
				{
					if($kw['token']!='')
					{
						if (array_key_exists($kw['token'],$triple))
						{
							$triple[$kw['token']] = $triple[$kw['token']]+$kw['sumval'];
						}
						else
						{
							$triple[$kw['token']] = $kw['sumval'];
						}
					}
				}*/
			}
			if(count($phraseKW_arr)>0)
			{
				/*foreach($phraseKW_arr as $kw)
				{
					if($kw['token']!='')
					{
						if (array_key_exists($kw['token'],$phrase))
						{
							$phrase[$kw['token']] = $phrase[$kw['token']]+$kw['sumval'];
						}
						else
						{
							$phrase[$kw['token']] = $kw['sumval'];
						}
					}
				}*/
			}
			
		}
		$tagarr_count = array_count_values($tagarr_return);
		$result[0] = $tagarr_count;
		$result[1] = $single;
		$result[2] = $double;
		$result[3] = $triple;
		$result[4] = $phrase;
		
		$countkw[1]	= $singleKW;
		$countkw[2]	= $dobleKW;
		$countkw[3]	= $tripleKW;
		
		$response[0] =  $result;
		$response[1] =  $countkw;
		
		return $response;
	}
	public function query_performance($tokenize_arr,$artids)
	{
		$connection = ConnectionManager::get('default');
		$combination = array();
		$n = sizeof($tokenize_arr);
		for($r=1; $r<=3; $r++)
		{
			if($_SESSION['category']=='')
			{
				$diff_comb_str = $this->printCombination($tokenize_arr, $n, $r);
				//print_r($diff_comb_str);
				$diff_comb_arr = explode('|',$diff_comb_str);
				$combination[$r] = $diff_comb_arr;
				$where = "";
				
			}
			else
			{
				$diff_comb_arr = $tokenize_arr[$r];
				$catid = $_SESSION['category'];
				if(count($_SESSION['tagarr'])==0)
					$where = " AND a.Catid = ".$catid;
				else
				{
					$tagarr = implode("|",$_SESSION['tagarr']);
					$where = ' AND a.Catid = '.$catid.' 
							   AND CONCAT(",", `KW_grp`, ",") NOT REGEXP ",('.$tagarr.'),"';
				}
			}
			$sel_qry = '';
			$phrase_qry = '';
			$case = ' (';
			
			$c=0;
			foreach($diff_comb_arr as $comb)
			{
				/*if($r>1)
				{
					if($c==0)
					{
						$canonical = $comb;
					}
				}
				else
					$canonical = $comb;*/
				
				$sel_qry.= 'SELECT 
								(
									CASE
										WHEN MATCH ( a.Article ) AGAINST ("'.$comb.'" IN BOOLEAN MODE)>0 THEN "'.str_replace('+','',$comb).'"
										ELSE ""
									END
								) AS token, 
								SUM((CASE
											WHEN MATCH ( a.Article ) AGAINST ("'.$comb.'" IN BOOLEAN MODE)>0 THEN 1
											ELSE 0
										END)) as sumval
							FROM tblarticle_new a 
							WHERE MATCH ( a.Article ) AGAINST ("'.$comb.'" IN BOOLEAN MODE)>0';
				$sel_qry.=$where;
				$sel_qry.=' UNION ALL ';
				
				$case.= 'MATCH ( a.Article ) AGAINST ("'.$comb.'" IN BOOLEAN MODE) OR ';
				$c++;
			}
			$kwqry = rtrim($sel_qry,'UNION ALL ');
			$case = rtrim($case,'OR ');
			if($artids[$r]!='')
			{
				$artids[$r] = rtrim($artids[$r],',');
				$case.= ') AND a.Artid NOT IN ('.$artids[$r].')';
			}
			else
				$case.= ')';	
			
			$cnt_query = "SELECT a.Artid FROM tblarticle_new a,tblkwgrp g
						WHERE a.`Grpid`=g.Grpid  AND ".$case.$where." GROUP BY a.Artid";
			$cntarr[$r] = $connection->execute($cnt_query)->fetchAll('assoc');
			
			
			if($r==1)
			{
				$tag_query = "SELECT g.KW_grp FROM tblarticle_new a,tblkwgrp g
						WHERE g.`Grpid`=a.Grpid  AND ".$case.$where.' GROUP BY g.KW_grp';
				$tagarr_return = $connection->execute($tag_query)->fetchAll('assoc');
				$tagarr_return = array_column($tagarr_return,'KW_grp');
				$tagarr_return = implode(',',$tagarr_return);
				if($tagarr_return!='')
					$tagarr_return = explode(',',$tagarr_return);
				else
					$tagarr_return = array();
				$artarr[0] = $tagarr_return;
				
			}
						
			$art_arr = $connection->execute($kwqry)->fetchAll('assoc');
			$artarr[$r] = $art_arr;
			
			/** phrase match ****/
			/*if($r==2)
			{
				if(count($diff_comb_arr)>0)
				{
					foreach($diff_comb_arr as $comb)
					{
						$phrase_comb = str_replace('+','',$comb);
						$phrase_qry.='SELECT (CASE 
												WHEN POSITION("'.$phrase_comb.'" in a.`Article`)>0 THEN "'.$phrase_comb.'" 
												ELSE "" 
											END) as token,
											SUM((CASE WHEN POSITION("'.$phrase_comb.'" in a.`Article`)>0 THEN 1 ELSE 0 END))
											as sumval
									FROM tblarticle_new a
									WHERE POSITION("'.$phrase_comb.'" in a.`Article`)>0'; 
						$phrase_qry.=$where;
						$phrase_qry.=' UNION ALL ';
					}
				
					$phrase_qry = rtrim($phrase_qry,'UNION ALL ');
					$phrase_count_arr = $connection->execute($phrase_qry)->fetchAll('assoc');
				}
				else
					$phrase_count_arr = array();
				$artarr[4] = $phrase_count_arr;
				
			}*/
					
		}
		
		if($_SESSION['category']=='')
			$_SESSION['combination'][] =  $combination;
		$result[0] = $artarr;
		$result[1] = $cntarr;
		return $result;

	}
	public function tokenization($split_title)
	{
		$connection = ConnectionManager::get('default');
		$array_title = preg_replace("/[^A-Za-z0-9'\-]/", '', $split_title);
		$array_title = array_values(array_unique(array_filter($array_title)));
		foreach($array_title as $title)
		{
			if (!is_numeric($title))
			{
				$array_title_new[] = $title;
			}
		}
		
		$array_tit_str = implode('","',$array_title_new);
		$sel_stopword_qry = 'SELECT Stop_word FROM `tblstop_word` 
							WHERE `Stop_word` IN ("'.$array_tit_str.'")';
		$stopword_arr = $connection->execute($sel_stopword_qry)->fetchAll('assoc');
		$stopword_arr = array_column($stopword_arr,'Stop_word');
		$kwarr = array_udiff($array_title_new,$stopword_arr,'strcasecmp');
		if(in_array('-',$kwarr))
			$kwarr = array_diff($kwarr, ['-'] );
		$kwarr = array_values($kwarr);
		return $kwarr;
		
	}
	
	
	/****  different combination ***/
	public function printCombination($arr,$n,$r)
	{
		$data = array();
		$result = $this->combinationUtil($arr, $data, 0,	$n - 1, 0, $r);
		return rtrim($result,'|');
	}
	public function combinationUtil($arr, $data, $start,$end, $index, $r)
	{
		
		$comb = '';
		$separate = '';
		$returnval = '';
		$response = '';
		if ($index == $r)
		{
			for ($j = 0; $j < $r; $j++)
				$comb.='+'.$data[$j].' ';
			$separate.=rtrim($comb,' ');
			$separate.="|";
			$returnval.=$separate;
			return $returnval;
		
		}
		
		for ($i = $start;	$i <= $end && $end - $i + 1 >= $r - $index; $i++)
		{
			$data[$index] = $arr[$i];
			$response.=$this->combinationUtil($arr, $data, $i + 1, $end, $index + 1, $r);
		}
		return $response;
	}
	
	public function kwgrp_filter($keyword='')
	{
		$connection = ConnectionManager::get('default');
		if(count($_SESSION['kwgrp'])>0)
		{
			$kwgrp = implode("|",$_SESSION['kwgrp']);
			$selkw_qry = 'SELECT k.Keyword,k.`Vol (US)` FROM `tblkeyword` k,tblkwgrp g 
						WHERE CONCAT(",", `KW_grp`, ",") REGEXP ",('.$kwgrp.')," AND 
						g.Grpid = k.Grpid';
			$kw_arr = $connection->execute($selkw_qry)->fetchAll('assoc');
			if($keyword=='')
			{
				$selart_qry = 'SELECT a.`Article` FROM `tblarticle_new` a,tblkwgrp g 
						WHERE CONCAT(",", `KW_grp`, ",") REGEXP ",('.$kwgrp.')," AND 
						g.Grpid = a.Grpid';
			}
			else
			{
				$selart_qry = 'SELECT a.`Article` FROM `tblarticle_new` a,tblkwgrp g,tblkeyword k
						WHERE CONCAT(",", `KW_grp`, ",") REGEXP ",('.$kwgrp.')," AND 
						g.Grpid = a.Grpid AND 
						g.Grpid = k.Grpid AND 
						k.Keyword = "'.$keyword.'"';
						
			}
			$art_arr = $connection->execute($selart_qry)->fetchAll('assoc');
			$result[0] = $kw_arr;
			$result[1] = $art_arr;
			return $result;
		}
	}
	public function get_kwgrp()
	{
		/** get distinct tag from database which have keywords ***/
		$connection = ConnectionManager::get('default');
		$sel_all_kwgrp = "SELECT `KW_grp` FROM `tblkwgrp`";
		$kwgrparr = $connection->execute($sel_all_kwgrp)->fetchAll('assoc');
		$kwgrparr = array_column($kwgrparr,'KW_grp');
		$kwgrpstr = implode(',',$kwgrparr);
		$kwgrparr = explode(',',$kwgrpstr);
		$kwgrparr = array_values(array_unique($kwgrparr));
		sort($kwgrparr);
		$i=0;
		
		foreach($kwgrparr as $kwgrp)
		{
			$kwgrp = trim($kwgrp);
			$sel_count_qry = 'SELECT COUNT(k.Keyword) as kwcnt, SUM(k.`Vol (US)`) as sumvol
							  FROM `tblkeyword` k,tblkwgrp g 
							  WHERE FIND_IN_SET("'.$kwgrp.'",`KW_grp`) AND 
							  g.Grpid = k.Grpid';
			
			$countarr = $connection->execute($sel_count_qry)->fetchAll('assoc');
			if($countarr[0]['kwcnt']>0)
			{
				$data_arr[$i]['kwgrp'] = $kwgrp;
				$data_arr[$i]['kwcnt'] = $countarr[0]['kwcnt'];
				$data_arr[$i]['sumvol'] = $countarr[0]['sumvol'];
				$i++;
			}
		}
		return $data_arr;
	}
	
	public function dbcount()
	{
		$connection = ConnectionManager::get('default');
		if($_SESSION['category']=='' && count($_SESSION['tagarr'])==0)
		{
			$sel_qry = "SELECT COUNT(`Artid`) as count FROM `tblarticle_new`";
		}
		else
		{
			if(count($_SESSION['tagarr'])==0)
				$sel_qry = "SELECT COUNT(DISTINCT(`Artid`)) as count FROM `tblarticle_new` WHERE Catid = ".$_SESSION['category'];
			else
			{
				$tagarr = implode("|",$_SESSION['tagarr']);
				$sel_qry = 'SELECT COUNT(DISTINCT(`Artid`)) as count FROM `tblarticle_new`  a, tblkwgrp g 
							WHERE a.Catid ='.$_SESSION['category'].' AND 
							CONCAT(",", `KW_grp`, ",") NOT REGEXP ",('.$tagarr.'),"';
			}
		}
		
		$count_arr = $connection->execute($sel_qry)->fetchAll('assoc');
		return $dbcount = $count_arr[0]['count'];
	}
	public function alltag()
	{
		$connection = ConnectionManager::get('default');
		$sel_tag_qry = "SELECT g.KW_grp FROM `tblarticle_new` a,tblkwgrp g 
								WHERE g.`Grpid` = a.`Grpid` 
								GROUP BY a.Grpid";

		$tagarr = $connection->execute($sel_tag_qry)->fetchAll('assoc');
		$tagarr = array_column($tagarr,'KW_grp');
		$tagarr = implode(',',$tagarr);
		$tagarr = explode(',',$tagarr);
		$tagarr = array_values(array_unique($tagarr));
		sort($tagarr);
		return $tagarr;
	}
	
	public function getkwgrp($catid)
	{
		$connection = ConnectionManager::get('default');
		$sel_tag_qry = "SELECT g.KW_grp FROM `tblarticle_new` a,tblkwgrp g 
								WHERE g.`Grpid` = a.`Grpid` AND 
							a.`Catid` = ".$catid." GROUP BY a.Grpid";
		$tagarr = $connection->execute($sel_tag_qry)->fetchAll('assoc');
		$tagarr = array_column($tagarr,'KW_grp');
		$tagarr = implode(',',$tagarr);
		$tagarr = explode(',',$tagarr);
		$tagarr = array_values(array_unique($tagarr));
		sort($tagarr);
		return $tagarr;
		
	}
	
	public function import_category($category)
	{
		/** check category ***/
		$connection = ConnectionManager::get('default');
		$sel_cat_qry = 'SELECT Catid FROM `tblcategory` WHERE 
						lower(`Category`) ="'.strtolower($category).'"';
		$catarr = $connection->execute($sel_cat_qry)->fetchAll('assoc');
		if(count($catarr)>0)
		{
			$catid = $catarr[0]['Catid'];
		}
		else
		{
			$ins_qry = 'INSERT INTO `tblcategory`(`Category`) 
							VALUES ("'.$category.'")';
			$connection->execute($ins_qry);
			
			$sel_qry = 'SELECT `Catid` FROM `tblcategory` 
						WHERE Category = "'.$category.'"';
			$catarr = $connection->execute($sel_qry)->fetchAll('assoc');
			$catid = $catarr[0]['Catid'];
		}
		return $catid;
	}
	
	public function import_kwgrp($kwgrp)
	{
		/** check kwgrp **/
		$connection = ConnectionManager::get('default');
		$kwgrp = preg_replace("/,([\s])+/",",",$kwgrp);
		$sel_kw_grp = 'SELECT `Grpid` FROM `tblkwgrp` WHERE `KW_grp` IN ("'.$kwgrp.'")';
		$selkwarr = $connection->execute($sel_kw_grp)->fetchAll('assoc');
		if(count($selkwarr)>0)
		{
			$kwgrpid = $selkwarr[0]['Grpid'];
		}
		else
		{
			$ins_qry = 'INSERT INTO `tblkwgrp`(`KW_grp`) VALUES ("'.$kwgrp.'")';
			$connection->execute($ins_qry);
			
			
			$sel_kw_grp = 'SELECT `Grpid` FROM `tblkwgrp` WHERE `KW_grp` = "'.$kwgrp.'"';
			$selkwarr = $connection->execute($sel_kw_grp)->fetchAll('assoc');
			$kwgrpid = $selkwarr[0]['Grpid'];
			
		}
		return $kwgrpid;
									
	}
	public function import_script($file,$catid,$mode,$siteid='',$cl_data='')
	{
		$connection = ConnectionManager::get('default');
		$data     = fgetcsv($file);
		try
		{
			if($mode=='master')
			{
				$master_data = array();
				$kw_index    = array_search("Keyword", $data);
				/*$kwgrp_index    = array_search("KW Group", $data);
				$mlconf_index    = array_search("ML Confidence Score", $data);*/
				$vol_index    = array_search("Volume", $data);
				if (($kw_index== "") && ($vol_index== ""))
				{
					$response =  "Column doesn't match";
				}
				else 
				{
					while (($data = fgetcsv($file)) !== FALSE) 
					{
						
						if (array(null) !== $data) 
						{ // ignore blank lines
							$kw = $data[$kw_index];
							$kw = preg_replace('/[^A-Za-z0-9 \-]/', '', $kw);
							
							$vol = $data[$vol_index];
							$vol = str_replace(',','',$vol);
							$date_created = date('Y-m-d h:i:s');
							if($kw!='' && $vol!='')
								$master_data[] = '("'.$catid.'","'.$kw.'","'.$vol.'","'.$date_created.'")';
												
						}
						
					}
					if(count($master_data)>0)
					{
						$ins_qry = 'INSERT INTO `tblmaster`
											(`Catid`, `Keyword`, `Volume`, `DateCreated`) 
											VALUES '.implode(',', $master_data);
						$connection->execute($ins_qry);
						$response =   'Records are imported successfully';
					
						/** remove duplicate values **/
						$sel_qry = 'SELECT Kwid FROM tblmaster where Kwid not in 
									(select max(Kwid) from tblmaster GROUP BY `Catid`,`Keyword`)';
						$duparr_master = $connection->execute($sel_qry)->fetchAll('assoc');
						$kwidarr = array_column($duparr_master,'Kwid');
						if(count($kwidarr)>0)
						{
							$del_qry = 'delete from tblmaster 
										where Kwid IN ('.implode(',',$kwidarr).')';
							$connection->execute($del_qry);
						}
					}
					//$this->import_csv('master');
				}
											
			}
			if($mode=='client')
			{
				$client_data = array();
				if($cl_data=='keyword')
				{
					$kw_index = array_search("ranking_keyword", $data);
					$rankpos_index = array_search("ranking_position", $data);
					$rankpage_index = array_search("ranking_page", $data);
														
					if(($kw_index=="")&&($rankpos_index=="") && ($rankpage_index ==""))
					{
						$response =  "Column doesn't match";
					}
					else 
					{
						while (($data = fgetcsv($file)) !== FALSE) 
						{
							
							if (array(null) !== $data) 
							{ // ignore blank lines
						
								$kw = $data[$kw_index];
								$kw = preg_replace('/[^A-Za-z0-9 \-]/', '', $kw);
								
								$rankpos = $data[$rankpos_index];
								
								$rankpage = $data[$rankpage_index];
																					
								$date_created = date('Y-m-d h:i:s');
								if($kw!='' && $rankpage!='')					
									$client_data[] = '("'.$catid.'","'.$siteid.'","'.$kw.'","'.$rankpos.'",
														"'.$rankpage.'","'.$date_created.'")';
												
							}
							
						}
						
						if(count($client_data)>0)
						{
							$ins_client_qry = 'INSERT INTO `tblclient_kw`
												(Catid,`Siteid`, `Ranking_keyword`, `Ranking_position`, 
												`Ranking_page`,`DateCreated`) 
												VALUES '.implode(',',$client_data);
							
							$connection->execute($ins_client_qry);
							
							$response =   'Records are imported successfully';
						
							/** remove duplicate values **/
							$sel_dup_qry = 'SELECT Kwid FROM tblclient_kw where Kwid not in 
											(select max(Kwid) from tblclient_kw GROUP BY Catid,Siteid,`Ranking_keyword`)';
							$duparr = $connection->execute($sel_dup_qry)->fetchAll('assoc');
							$kwidarr = array_column($duparr,'Kwid');
							if(count($kwidarr)>0)
							{
								$del_qry = 'delete from tblclient_kw 
											where Kwid IN ('.implode(',',$kwidarr).')';
								$connection->execute($del_qry);
							}
						}
						
					}
				}
				if($cl_data=='page')
				{
					$rankpage_index = array_search("url", $data);
					$pagetit_index = array_search("page_title", $data);
					$qltscore_index = array_search("quality_score", $data);
					$inter_link_index = array_search("internal_links", $data);
					$action_index = array_search("action", $data);
									
					if(($rankpage_index == "")&&($pagetit_index== "") && 
					   ($qltscore_index== "") && ($inter_link_index== "")&&
					   ($action_index== ""))
					{
						$response =  "Column doesn't match";
					}
					else 
					{
						while (($data = fgetcsv($file)) !== FALSE) 
						{
							
							if (array(null) !== $data) 
							{ // ignore blank lines
								
								$rankpage = $data[$rankpage_index];
																
								$pagetit = $data[$pagetit_index];
								$pagetit = preg_replace('/[^A-Za-z0-9 \-]/', '', $pagetit);
								
								$qltscore = $data[$qltscore_index];
								$interlink = $data[$inter_link_index];
								
								$action = $data[$action_index];
								$action = preg_replace('/[^A-Za-z0-9 \-]/', '', $action);
								
								$date_created = date('Y-m-d h:i:s');
								if($rankpage!='' && $pagetit!='')					
									$client_data[] = '("'.$catid.'","'.$siteid.'","'.$rankpage.'",
													   "'.$pagetit.'","'.$qltscore.'","'.$interlink.'",
													   "'.$action.'","'.$date_created.'")';
								
																		
							}
							
						}
						
						if(count($client_data)>0)
						{
							$ins_client_qry = 'INSERT INTO `tblclient_page`
												(Catid,`Siteid`, `Url`, `Page_title`, `Quality_score`, 
												`Internal_links`, `Action`, `DateCreated`) 
												VALUES '.implode(',',$client_data);
							
							$connection->execute($ins_client_qry);
							
							$response =   'Records are imported successfully';
						
							/** remove duplicate values **/
							$sel_dup_qry = 'SELECT Pageid FROM tblclient_page where Pageid not in 
											(select max(Pageid) from tblclient_page WHERE Page_title!="" GROUP BY Catid,Siteid,`Page_title`)';
							$duparr = $connection->execute($sel_dup_qry)->fetchAll('assoc');
							$pageidarr = array_column($duparr,'Pageid');
							if(count($pageidarr)>0)
							{
								$del_qry = 'delete from tblclient_page 
											where Pageid IN ('.implode(',',$pageidarr).')';
								$connection->execute($del_qry);
							}
						}
						
					}
				}
				
			}
		}
		catch(\Exception $e)
		{
			$error = $e->getmessage();
		}
		$data[0] = $response;	
		$data[1] = $error;
		return $data;
	}
	/*public function import_csv($mode)
	{
		$connection = ConnectionManager::get('default');
		$datetime = date('Y-m-d h:i:s');
		if($mode=='master')
		{
			$maintbl = 'tblmaster';
			$connection->execute('Truncate tblchild_master');
			$ins_qry_child = 'INSERT INTO `tblchild_master`
							(`Kwid`,`Catid`, `Keyword`, `KW`, 
							`ML_Conf_Score`, `Volume`, `DateCreated`) 
							SELECT m.Kwid,m.Catid,m.Keyword,t.kw,
							m.ML_Conf_Score,m.Volume,"'.$datetime.'"
							FROM `tblmaster` m,tbltemp t 
							WHERE t.KWgrp = m.KW_Group';
			
		}
		if($mode=='client')
		{
			$maintbl = 'tblclient';
			$connection->execute('Truncate tblchild_client');
			$ins_qry_child = 'INSERT INTO `tblchild_client`
							(`Type`, `Kwid`, `Siteid`, 
							`Ranking_keyword`, `KW`, `Ranking_position`, `DateCreated`) 
							SELECT "keyword",c.Kwid,c.Siteid,
							c.Ranking_keyword,t.kw,c.`Ranking_position`,"'.$datetime.'"
							FROM `tblclient` c,tbltemp t 
							WHERE t.KWgrp = c.KW_Group';
			$ins_qry_child_page = 'INSERT INTO `tblchild_client`
									(`Type`, `Kwid`, `Siteid`, 
									`Ranking_page`, `Page_title`, `Page_KW`, 
									`Quality_score`, `Internal_links`, `Action`, `DateCreated`) 
									SELECT "page",c.Kwid,c.Siteid,
									c.Ranking_page,c.`Page_title`,t.KW,
									c.`Quality_score`,c.`Internal_links`,c.`Action`,"'.$datetime.'"
									FROM `tblclient` c,tbltemp t 
									WHERE t.KWgrp = c.Page_group';
			
			
		}
		$master_qry = "SELECT DISTINCT(KW_Group) FROM ".$maintbl;
		$masterarr = $connection->execute($master_qry)->fetchAll('assoc');
		$parent_array = array_column($masterarr,'KW_Group');
		foreach($parent_array as $kwgrp)
		{
			$kwgrp_array = explode(':',$kwgrp);
			foreach($kwgrp_array as $kw)
			{
				$values[]='("'.$kwgrp.'","'.$kw.'")';
					
			}
				
		}
		$trunc_qry = 'Truncate tbltemp';
		$connection->execute($trunc_qry);
		$ins_query = "INSERT INTO `tbltemp`( `Kwgrp`, `Kw`) VALUES ".implode(",", $values);
		$connection->execute($ins_query);
		$connection->execute($ins_qry_child);
		if($mode=='client')
		{
			$master_qry = "SELECT DISTINCT(Page_group) FROM ".$maintbl;
			$masterarr = $connection->execute($master_qry)->fetchAll('assoc');
			$parent_array = array_column($masterarr,'Page_group');
			foreach($parent_array as $kwgrp)
			{
				$kwgrp_array = explode(':',$kwgrp);
				foreach($kwgrp_array as $kw)
				{
					$values[]='("'.$kwgrp.'","'.$kw.'")';
						
				}
					
			}
			$trunc_qry = 'Truncate tbltemp';
			$connection->execute($trunc_qry);
			$ins_query = "INSERT INTO `tbltemp`( `Kwgrp`, `Kw`) VALUES ".implode(",", $values);
			$connection->execute($ins_query);
			$connection->execute($ins_qry_child_page);
		}
	
	}*/
	
	public function getallcat()
	{
		$connection = ConnectionManager::get('default');
		$sel_qry = "SELECT Catid,`Category` FROM `tblcategory` 
					ORDER BY `Category` ASC";
		$catarr = $connection->execute($sel_qry)->fetchAll('assoc');
		return $catarr;
	}
	public function getallsite()
	{
		$connection = ConnectionManager::get('default');
		$sel_qry = "SELECT * FROM `tblsite` 
					ORDER BY `Site` ASC";
		$sitearr = $connection->execute($sel_qry)->fetchAll('assoc');
		return $sitearr;
	}
	public function master_report_kwgrp($kwgrp='')
	{
		$catid = $_SESSION['catid'];
		$connection = ConnectionManager::get('default');
		if($kwgrp=='')
		{
			$sel_qry = 'SELECT g.Kw_Group,COUNT(m.Keyword) kwcnt,SUM(m.Volume) vol 
						FROM `tblmaster` m JOIN tblmatch g 
						ON m.Keyword regexp CONCAT("(^|[[:space:]])",g.Kw_Search_Phrase,"([[:space:]]|$)") 
						WHERE m.Catid ='.$catid.' AND 
							  g.Catid ='.$catid.'
						GROUP BY g.Kw_Group 
						ORDER BY kwcnt DESC';
		}
		else
		{
			$sel_qry = 'SELECT m.Keyword,COUNT(m.Keyword) kwcnt,m.Volume
						FROM `tblmaster` m JOIN tblmatch g 
						ON m.Keyword regexp CONCAT("(^|[[:space:]])",g.Kw_Search_Phrase,"([[:space:]]|$)") 
						WHERE m.Catid ='.$catid.' AND 
							  g.Catid ='.$catid.' AND
							  g.Kw_Group = "'.$kwgrp.'"
						GROUP BY m.Keyword 
						ORDER BY m.`Volume` DESC';
		}
		$kwarr_group = $connection->execute($sel_qry)->fetchAll('assoc');
		return $kwarr_group;
	}
	public function client_report_kwgrp($siteid)
	{
		$connection = ConnectionManager::get('default');
		$catid = $_SESSION['catid'];
		$sel_qry = 'SELECT g.Kw_Group,COUNT(c.`Ranking_keyword`) kwcnt 
						FROM `tblclient_kw` c JOIN tblmatch g 
						ON c.`Ranking_keyword` regexp CONCAT("(^|[[:space:]])",g.Kw_Search_Phrase,"([[:space:]]|$)") 
						WHERE c.Catid ='.$catid.' AND 
							  g.Catid ='.$catid.' AND 
							  c.Siteid = '.$siteid.'
						GROUP BY g.Kw_Group 
						ORDER BY kwcnt DESC';
		
		$kwarr_group = $connection->execute($sel_qry)->fetchAll('assoc');
		
		$sel_pageqry = 'SELECT COUNT(`Page_title`) as kwcnt,g.Kw_Group
						FROM `tblclient_page` c JOIN tblmatch g 
						ON c.`Page_title` regexp CONCAT("(^|[[:space:]])",g.Kw_Search_Phrase,"([[:space:]]|$)") 
						WHERE c.Catid ='.$catid.' AND 
							  g.Catid ='.$catid.' AND 
							  c.Siteid = '.$siteid.'
						GROUP BY g.Kw_Group 
						ORDER BY kwcnt DESC';
		$pagearr_group = $connection->execute($sel_pageqry)->fetchAll('assoc');
		$site_qry = 'SELECT Site_domain FROM `tblsite` WHERE Siteid='.$siteid;
		$sitearr = $connection->execute($site_qry)->fetchAll('assoc');
		$response[0] = $kwarr_group;
		$response[1] = $pagearr_group;
		$response[2] = $sitearr;
		return $response;
		
	}
	
}
