<?php
namespace App\Controller;
use Cake\Datasource\ConnectionManager;
use Cake\Core\Configure;
use Cake\Event\EventInterface;

error_reporting(0);
session_start();
class UsersController extends AppController
{ 
	
	public function index()
	{
		$this->autoLayout = false;
		$connection = ConnectionManager::get('default');
		
		if($this->request->is('ajax'))
		{
			if($_POST['action']=='article_search')
			{
				$_SESSION['combination']=array();
				$_SESSION['category'] = '';
				$_SESSION['tagarr'] = array();
				$explode = explode("\n",trim($_POST['title']));
				$tit_count = count($explode);
				$result_arr = parent::process_title($explode);
				
			}
			if($_POST['action']=='category_filter')
			{
				$catid = $_POST['category'];
				$tit_count = $_POST['tit_count'];
				$_SESSION['category'] = $catid;
				$tagarr = parent::getkwgrp($catid);
				$this->set('tagarr',$tagarr);
				$result_arr = parent::process_title($_SESSION['combination']);
			}
			if($_POST['action']=='tag_filter')
			{
				$tag = $_POST['tag'];
				$check = $_POST['check'];
				if($check=='uncheck')
				{
					if(!in_array($tag,$_SESSION['tagarr']))
						$_SESSION['tagarr'][] = $tag;
				}
				else
					$_SESSION['tagarr'] = array_diff($_SESSION['tagarr'], [$tag] );
				$tit_count = $_POST['tit_count'];
				$tagarr = parent::getkwgrp($_SESSION['category']);
				$this->set('tagarr',$tagarr);
				$result_arr = parent::process_title($_SESSION['combination']);
			}
			if($_POST['action']=='tag_filter_select')
			{
				$select_val = $_POST['select_val'];
				$tit_count = $_POST['tit_count'];
				$tagarr = parent::getkwgrp($_SESSION['category']);
				$this->set('tagarr',$tagarr);
				
				if($select_val=='all_tag_select')
				{
					$_SESSION['tagarr'] = array();
				}
				else
					$_SESSION['tagarr'] = array_column($tagarr, 'tag');
				
				$result_arr = parent::process_title($_SESSION['combination']);
			}
			$all_tag = parent::alltag();
			$dbcount = parent::dbcount();
			$this->set('tit_count',$tit_count);
			$this->set('all_tag',$all_tag);
			$this->set('count_arr',$result_arr[0]);
			$this->set('matchcount_arr',$result_arr[1]);
			$this->set('dbcount',$dbcount);
			return $this->render('index_filter');
			exit;
		}
		$sel_qry = "SELECT Catid,`Category` FROM `tblcategory` 
					ORDER BY `Category` ASC";
		$catarr = $connection->execute($sel_qry)->fetchAll('assoc');
		$this->set('catarr',$catarr);
	}
	public function kwgrp()
	{
		$this->autoLayout = false;
		$connection = ConnectionManager::get('default');
		$_SESSION['kwgrp'] = array();
		$data_arr = parent::get_kwgrp();
		$this->set('resultarr',$data_arr);
		
	}
	
	public function keywords()
	{
		$this->autoLayout = false;
		$connection = ConnectionManager::get('default');
		$kwgrp = $_POST['kwgrp_val'];
		if($kwgrp!='')
		{
			if(!in_array($kwgrp,$_SESSION['kwgrp']))
				$_SESSION['kwgrp'] = [$kwgrp];
		}
		else if(count($_POST['all_kwgrp'])>0)
		{
			$_SESSION['kwgrp'] = $_POST['all_kwgrp'];
		}
		else
			$_SESSION['kwgrp'] = array();
		$kwart_arr = parent::kwgrp_filter();
		$this->set('kwarr',$kwart_arr[0]);
		$this->set('artarr',$kwart_arr[1]);
		
		$data_arr = parent::get_kwgrp();
		$this->set('resultarr',$data_arr);
		
		
	}
	public function articles()
	{
		$this->autoLayout = false;
		$connection = ConnectionManager::get('default');
		$keyword = $_POST['keyword'];
		$kwart_arr = parent::kwgrp_filter($keyword);
		$this->set('kwarr',$kwart_arr[0]);
		$this->set('artarr',$kwart_arr[1]);
		
		$data_arr = parent::get_kwgrp();
		$this->set('resultarr',$data_arr);
		
		
	}
	
	public function master()
	{
		set_time_limit(0);
		$connection = ConnectionManager::get('default');
		//parent::import_csv('client');
	}
	public function masterReport()
	{
		set_time_limit(0);
		$connection = ConnectionManager::get('default');
		if($this->request->is('ajax'))
		{
			if($_POST['action']=='show_allkw')
			{
				$kw = $_POST['kw'];
				$kwarr = parent::master_report_kwgrp($kw);
				$this->set('kwarr',$kwarr);
				$this->set('render_action','show_kw');
				return $this->render('master_report_filter');
			}
			if($_POST['action']=='site_selected')
			{
				$siteid = $_POST['siteid'];
				$response_client = parent::client_report_kwgrp($siteid);
				$this->set('kwgrp_arr',$response_client[0]);
				$this->set('pagegrp_arr',$response_client[1]);
				$this->set('site_arr',$response_client[2]);
				$this->set('render_action','client_report');
				$this->set('siteid',$siteid);
				return $this->render('master_report_filter');
			}
			exit;
		}
		if($_POST['category']!='')
		{
			$catid = $_POST['category'];
			$sel_cat = 'SELECT Category FROM `tblcategory` WHERE Catid='.$catid;
			$cat_arr = $connection->execute($sel_cat)->fetchAll('assoc');
			$category = $cat_arr[0]['Category'];
			$_SESSION['catid'] = $catid;
			$_SESSION['category'] = $category;
			$kwarr_group = parent::master_report_kwgrp();
			$this->set('kwarr_group',$kwarr_group);
			$sitearr = parent::getallsite();
			$this->set('sitearr',$sitearr);
		}
		else
		{
			$_SESSION['catid'] = '';
			$_SESSION['category'] = '';
		}
		$catarr = parent::getallcat();
		$this->set('catarr',$catarr);
		
	}
	
	public function importMaster()
	{
		set_time_limit(0);
		$connection = ConnectionManager::get('default');
		if ($this->request->is('post') == 1) 
		{ 
			if (isset($_FILES ['data']['type']) == "application/vnd.ms-excel") 
			{
				if($_FILES['data']['size'] == 0)
				{
					echo 'File is empty';
				}
				else
				{
					
						$filename = $_FILES ['data']['tmp_name'];
						$file     = fopen($filename, "r");
						$total    = 0;
						//$data     = fgetcsv($file);
						$catid = $_POST['category'];
						$impcount = parent::import_script($file,$catid,'master');
				}
				
			}
			else
				echo 'Check file type';
		}
		
		$catarr = parent::getallcat();
		$this->set('catarr',$catarr);
		$this->set('message',$impcount);
		
	}
	public function importClient()
	{
		set_time_limit(0);
		$connection = ConnectionManager::get('default');
		
		if ($this->request->is('post') == 1) 
		{ 
	
			$catid = $_POST['category'];
			$siteid = $_POST['site'];
			if (isset($_FILES ['data_kw']['type']) == "application/vnd.ms-excel") 
			{
				if($_FILES['data_kw']['size']>0)
				{
					$filename = $_FILES ['data_kw']['tmp_name'];
					$file     = fopen($filename, "r");
					$impcount = parent::import_script($file,$catid,'client',$siteid,'keyword');
					
				}
				
			}
			if (isset($_FILES ['data_page']['type']) == "application/vnd.ms-excel") 
			{
				if($_FILES['data_page']['size']>0)
				{
					$filename_page = $_FILES ['data_page']['tmp_name'];
					$file_page    = fopen($filename_page, "r");
					$impcount = parent::import_script($file_page,$catid,'client',$siteid,'page');
				}
			}
			
		}
		$catarr = parent::getallcat();
		$sitearr = parent::getallsite();
		$this->set('catarr',$catarr);
		$this->set('sitearr',$sitearr);
		$this->set('message',$impcount);
		
	}
	public function addsite()
	{
		$connection = ConnectionManager::get('default');
		if($_POST['sitename']!='')
		{
			$sitename = str_replace('"',"'",$_POST['sitename']);
			$sitedomain = str_replace('"',"'",$_POST['sitedomain']);
			
			$sel_site = 'SELECT * FROM `tblsite` WHERE `Site`="'.$sitename.'" 
						AND `Site_domain`="'.$sitedomain.'"';
			$selarr = $connection->execute($sel_site)->fetchAll('assoc');
			if(count($selarr)==0)
			{
				$ins_site_qry = 'INSERT INTO `tblsite`(`Site`,Site_domain) 
								VALUES ("'.$sitename.'","'.$sitedomain.'")';
				$connection->execute($ins_site_qry);
			}
		}
		$sitearr = parent::getallsite();
		$this->set('sitearr',$sitearr);
	}
	public function addcategory()
	{
		$connection = ConnectionManager::get('default');
		if($_POST['category']!='')
		{
			$category = str_replace('"',"'",$_POST['category']);
			$sel_qry = 'SELECT `Category` FROM `tblcategory` WHERE `Category`="'.$category.'"';
			$selarr = $connection->execute($sel_qry)->fetchAll('assoc');
			if(count($selarr)==0)
			{
				$ins_cat = 'INSERT INTO `tblcategory`(`Category`) VALUES ("'.$category.'")';
				$connection->execute($ins_cat);
			}
		}
		$catarr = parent::getallcat();
		$this->set('catarr',$catarr);
	}
	public function import_old()
	{
		set_time_limit(0);
		$connection = ConnectionManager::get('default');
		$this->layout = false;
		$currentdate = date('Y-m-d H:i:s');
		$status = array();
		try 
		{
			if ($this->request->is('post') == 1) 
			{ 
				if (isset($_FILES ['data']['type']) == "application/vnd.ms-excel") 
				{
					if($_FILES['data']['size'] == 0)
					{
						$status = 'File is empty';
					}
					else
					{
						if($_POST['mode']=='article')
						{
							$filename = $_FILES ['data']['tmp_name'];
							$file     = fopen($filename, "r");
							$total    = 0;
							$data     = fgetcsv($file);
							$category_index = array_search("Category", $data);
							$kwgrp_index = array_search("KW Group", $data);
							$article_index   = array_search("Article", $data);
							$question_index      = array_search("Question", $data);
							$geo_index    = array_search("Geo", $data);
							$kw_index    = array_search("Keyword", $data);
							
							if ((trim($category_index) == "") || (trim($kwgrp_index) == "")||
							(trim($article_index) == "") || (trim($question_index) == "")||
							(trim($geo_index) == "") ||(trim($kw_index) == ""))
							{
								echo "Column doesn't match";
							}
							else 
							{
								$i = 0;
								while (($data = fgetcsv($file)) !== FALSE) 
								{
									
									if (array(null) !== $data) 
									{ // ignore blank lines
										$category = $data[$category_index];
										$kwgrp = $data[$kwgrp_index];
										$kwgrp = str_replace('.',',',$kwgrp);
										$article = $data[$article_index];
										$question = $data[$question_index];
										$geo = $data[$geo_index];
										$kw = $data[$kw_index];
										$date_created = date('Y-m-d h:i:s');
										
										
										/**  check category **/
										$catid = parent::import_category($category);	

										/** check kwgrp **/
										$kwgrpid = parent::import_kwgrp($kwgrp);
										
										
										/** insert article****/
										$ins_article = 'INSERT INTO `tblarticle_new`
																(`Catid`, `Grpid`, `Article`, 
																 `Question`, `Geo`, `Keyword`, `DateCreated`) 
														VALUES ('.$catid.','.$kwgrpid.',"'.$article.'",
																"'.$question.'","'.$geo.'","'.$kw.'","'.$date_created.'")';
										$connection->execute($ins_article);
										$i++;
										
									}
									
								}
								echo $i.' Articles are imported successfully';
							}
														
						}
						else if($_POST['mode']=='keyword')
						{
							$filename = $_FILES ['data']['tmp_name'];
							$file     = fopen($filename, "r");
							$total    = 0;
							$data     = fgetcsv($file);
							$category_index = array_search("Category", $data);
							$kwgrp_index = array_search("KW Group", $data);
							$kw_index   = array_search("Keyword", $data);
							$vol_abrv_index      = array_search("Vol_Abrv (US)", $data);
							$vol_index    = array_search("Vol (US)", $data);
							if ((trim($category_index) == "") || (trim($kwgrp_index) == "")||
							(trim($kw_index) == "") || (trim($vol_abrv_index) == "")|| (trim($vol_index) == ""))
							{
								echo "Column doesn't match";
							}
							else
							{
								$i = 0;
								while (($data = fgetcsv($file)) !== FALSE) 
								{
									if (array(null) !== $data) 
									{ // ignore blank lines
										$category = $data[$category_index];
										$kwgrp = $data[$kwgrp_index];
										$keyword = $data[$kw_index];
										$vol_abrv = $data[$vol_abrv_index];
										$volume = $data[$vol_index];
										$volume = str_replace(",", "", $volume);
										$date_created = date('Y-m-d h:i:s');
										
										/**  check category **/
										$catid = parent::import_category($category);	

										/** check kwgrp **/
										$kwgrpid = parent::import_kwgrp($kwgrp);
										
										/** insert keyword****/
										$ins_kw = 'INSERT INTO `tblkeyword`
													  ( `Catid`, `Grpid`, `Keyword`, 
														`Vol_Abrv (US)`, `Vol (US)`, `DateCreated`) 
												 VALUES ('.$catid.','.$kwgrpid.',"'.$keyword.'",
														"'.$vol_abrv.'",'.$volume.',"'.$date_created.'")';
										$connection->execute($ins_kw);
										$i++;
										
									}
									
								}
								echo $i.' Keywords are imported successfully';
							}
						
								
						}
					}
					
				}
				else
					$status = 'Check file type';
			}
		}
		catch (\Exception $e) 
		{
            $status = $e->getMessage();
        }
		
	}
	public function kwgrping()
	{
		$connection = ConnectionManager::get('default');
		$this->layout = false;
		if($this->request->is('ajax'))
		{
			if($_POST['action']==='cat_selected')
			{
				$catid = $_POST['catid'];
				$sel_match = "SELECT Kw_Search_Phrase,Kw_Group FROM `tblmatch` WHERE `Catid`=".$catid;
				$matcharr = $connection->execute($sel_match)->fetchAll('assoc');
				$matchval = '';
				foreach($matcharr as $match)
				{
					$matchval.=$match['Kw_Search_Phrase'].':'.$match['Kw_Group']."\n";
				}
				echo $matchval;
				exit;
			}
			else if($_POST['action']==='match_request')
			{
				
				$kwgrp_match = urldecode($_POST['kwgrp_match']);
				$match_values = array();
				$kwgrp_assign = trim($kwgrp_match);
				$catid = $_POST['category'];
				$kwgrp_arr = explode("\n",$kwgrp_assign);
				$datetime = date('Y-m-d H:i:s');
				foreach($kwgrp_arr as $match)
				{
					$match = str_replace("\t",'',$match);
					$phrase_grp = explode(":",$match);
					//print_r(explode("\t",$phrase_grp));
					$phrase = strtolower(trim($phrase_grp[0]));
					$ph_grp[]= $phrase;
					$kwgrp = trim($phrase_grp[1]);
					if($phrase!='' && $kwgrp!='')
					{
						$check_match = 'select Matchid from tblmatch 
										where Catid="'.$catid.'" AND 
										Kw_Search_Phrase="'.$phrase.'"';
						$matcharr = $connection->execute($check_match)->fetchAll('assoc');	
						if(count($matcharr)==0)
							$match_values[] = '("'.$catid.'","'.$phrase.'","'.$kwgrp.'","'.$datetime.'")';
						else
						{
							$update_qry = 'UPDATE `tblmatch` SET `Kw_Group`="'.$kwgrp.'" 
											WHERE `Kw_Search_Phrase`="'.$phrase.'" AND 
											Catid="'.$catid.'"';
							$connection->execute($update_qry);
						}
					}
					
					
				}
				if(count($match_values)>0)
				{
					$ins_qry = 'INSERT INTO `tblmatch`(`Catid`, `Kw_Search_Phrase`, `Kw_Group`, `DateCreated`) 
								VALUES'.implode(",",$match_values);
					$connection->execute($ins_qry);
				}
				if(count($ph_grp)>0)
				{
					$del_qry = 'DELETE FROM `tblmatch` 
								WHERE Catid = '.$catid.' AND
								`Kw_Search_Phrase` NOT IN ("'.implode('","',$ph_grp).'")';
					$connection->execute($del_qry);
				}
				$sel_match = "SELECT Kw_Search_Phrase,Kw_Group FROM `tblmatch` WHERE `Catid`=".$catid;
				$matcharr = $connection->execute($sel_match)->fetchAll('assoc');
				$matchval = '';
				foreach($matcharr as $match)
				{
					$matchval.=$match['Kw_Search_Phrase'].':'.$match['Kw_Group']."\n";
				}
				echo $matchval;
				exit;
			}
			
		}
		$catarr = parent::getallcat();
		$this->set('catarr',$catarr);
	}
	public function clientReport()
	{
		$connection = ConnectionManager::get('default');
		$this->layout = false;
		if($this->request->is('ajax'))
		{
			if($_POST['action']==='change_color')
			{
				$kw_check = $_POST['keyword'];
				$sel_qry = 'SELECT `Ranking_page` FROM `tblclient_kw` WHERE `Ranking_keyword` = "'.$kw_check.'"';
				$selurl_arr = $connection->execute($sel_qry)->fetchAll('assoc');
				if(count($selurl_arr)>0)
				{
					$pageurl =$selurl_arr[0]['Ranking_page'];
					echo $pageurl;
				}				
			}
			exit;
		}
		
		
		if($_POST['kwgrp_report']!='')
		{
			$kwgrp = $_POST['kwgrp_report'];
			$siteid = $_POST['siteid'];
			$catid = $_SESSION['catid'];
			
			/** master report **/
			$sel_qry = 'SELECT m.Keyword,m.Volume  
							FROM `tblmaster` m JOIN tblmatch g 
							ON m.Keyword regexp CONCAT("(^|[[:space:]])",g.Kw_Search_Phrase,"([[:space:]]|$)") 
							WHERE m.Catid ='.$catid.' AND 
								  g.Catid ='.$catid.' AND 
								  g.Kw_Group = "'.$kwgrp.'"
							ORDER BY m.Volume DESC';
			$selkw_arr_master = $connection->execute($sel_qry)->fetchAll('assoc');
			$this->set('masterkw',$selkw_arr_master);
			
			/*** client report keyword**/
			echo $sel_qry_cl = 'SELECT c.`Ranking_keyword`,m.Volume,c.Ranking_Position as avg_pos
							FROM `tblclient_kw` c JOIN tblmatch g 
							ON c.`Ranking_keyword` regexp CONCAT("(^|[[:space:]])",g.Kw_Search_Phrase,"([[:space:]]|$)") 
							LEFT OUTER JOIN tblmaster m ON c.`Ranking_keyword` = m.Keyword
							WHERE c.Catid ='.$catid.' AND 
								  g.Catid ='.$catid.' AND 
								  c.Siteid = '.$siteid.' AND 
								  g.Kw_Group = "'.$kwgrp.'"
							ORDER BY m.Volume DESC';
			$selkw_arr_client = $connection->execute($sel_qry_cl)->fetchAll('assoc');
			$this->set('clientkw',$selkw_arr_client);
			
			
			/*** client report page title**/
			$sel_page_url = 'SELECT c.`Url`,k.Ranking_position,c.`Internal_links`,
							 k.Ranking_keyword, c.Quality_score,c.`Internal_links`,
							 c.`Action` FROM `tblclient_page` c JOIN tblmatch g ON 
							 c.`Page_title` regexp CONCAT("(^|[[:space:]])",g.Kw_Search_Phrase,"([[:space:]]|$)") 
							 LEFT OUTER JOIN tblclient_kw k ON k.Ranking_page = c.Url 
							 WHERE 
									c.Catid ='.$catid.' AND 
									g.Catid ='.$catid.' AND 
									c.Siteid = '.$siteid.' AND 
									g.Kw_Group = "'.$kwgrp.'" 
							 GROUP BY c.`Page_title` 
							 ORDER BY c.`Quality_score` DESC';
							
			$selkw_arr_page = $connection->execute($sel_page_url)->fetchAll('assoc');
			$this->set('clientpage',$selkw_arr_page);
			$site_qry = 'SELECT Site_domain FROM `tblsite` WHERE Siteid='.$siteid;
			$sitearr = $connection->execute($site_qry)->fetchAll('assoc');
			$site_domain = $sitearr[0]['Site_domain'];
			
			$sel_qry_action = 'SELECT DISTINCT(`Action`) FROM `tblclient_page` 
							WHERE Action!="" ORDER BY Action ASC';
			$actionarr = $connection->execute($sel_qry_action)->fetchAll('assoc');
			
			$this->set('kwgrp',$kwgrp);
			$this->set('site_domain',$site_domain);
			$this->set('actionarr',$actionarr);
		}
		else
			return $this->redirect('/master-report');
	}
	public function menu()
	{
		$connection = ConnectionManager::get('default');
		$this->layout = false;
	}
	public function masterTemplate()
	{
		$this->layout = false;
	}
	public function clientTemplate()
	{
		$this->layout = false;
	}
	public function clientTemplatePage()
	{
		$this->layout = false;
	}
}