<?php


	include_once('config.php');
	include_once('lib.php');
	require_once('libs/uap/parser.php');

	$_SESSION['user'] = 1;

	$userId = @makeNumber($_SESSION['user']);
	$wid = @makeNumber($_POST['wid']);
	$website = ORM::for_table('website')->find_one($wid);
	$access = ORM::for_table('portfolio2user')->where('portfolioId', $website->portfolioId)->where('userId', $userId)->find_one();
	if(@!$access) {
		die;
	}



	switch(@makeLetter($_POST['ajax'])) {


		
		case 'displayFireSite':


			$page = ORM::for_table('pages')->find_one(@makeNumber($_POST['pid']));

			if(@!$page) {
				die;
			}


			$i = 0;
			foreach(ORM::for_table('boxes')->where('pagesId', $page->id)->order_by_asc('sort')->find_many() as $box) {
				

				$array[$i]['id'] = $box->id;
				$array[$i]['layout'] = $box->layout;

				$array[$i]['content'][0] = $box->content1;
				if(strpos($box->content1, '[FBP]') !== false) {
					$array[$i]['plugin'][0] = 1;
				}
				else {
					$array[$i]['plugin'][0] = 0;
				}

				$array[$i]['content'][1] = $box->content2;
				if(strpos($box->content2, '[FBP]') !== false) {
					$array[$i]['plugin'][1] = 1;
				}
				else {
					$array[$i]['plugin'][1] = 0;
				}

				$array[$i]['content'][2] = $box->content3;
				if(strpos($box->content3, '[FBP]') !== false) {
					$array[$i]['plugin'][2] = 1;
				}
				else {
					$array[$i]['plugin'][2] = 0;
				}

				$array[$i]['content'][3] = $box->content4;
				if(strpos($box->content4, '[FBP]') !== false) {
					$array[$i]['plugin'][3] = 1;
				}
				else {
					$array[$i]['plugin'][3] = 0;
				}

				$array[$i]['content'][4] = $box->content5;
				if(strpos($box->content5, '[FBP]') !== false) {
					$array[$i]['plugin'][4] = 1;
				}
				else {
					$array[$i]['plugin'][4] = 0;
				}

				$array[$i]['content'][5] = $box->content6;
				if(strpos($box->content6, '[FBP]') !== false) {
					$array[$i]['plugin'][5] = 1;
				}
				else {
					$array[$i]['plugin'][5] = 0;
				}

				$array[$i]['content'][6] = $box->content7;
				if(strpos($box->content7, '[FBP]') !== false) {
					$array[$i]['plugin'][6] = 1;
				}
				else {
					$array[$i]['plugin'][6] = 0;
				}

				$array[$i]['content'][7] = $box->content8;
				if(strpos($box->content8, '[FBP]') !== false) {
					$array[$i]['plugin'][7] = 1;
				}
				else {
					$array[$i]['plugin'][7] = 0;
				}

				$array[$i]['content'][8] = $box->content9;
				if(strpos($box->content9, '[FBP]') !== false) {
					$array[$i]['plugin'][8] = 1;
				}
				else {
					$array[$i]['plugin'][8] = 0;
				}

				$array[$i]['content'][9] = $box->content10;
				if(strpos($box->content10, '[FBP]') !== false) {
					$array[$i]['plugin'][9] = 1;
				}
				else {
					$array[$i]['plugin'][9] = 0;
				}

				$array[$i]['content'][10] = $box->content11;
				if(strpos($box->content11, '[FBP]') !== false) {
					$array[$i]['plugin'][10] = 1;
				}
				else {
					$array[$i]['plugin'][10] = 0;
				}

				$array[$i]['content'][11] = $box->content12;
				if(strpos($box->content12, '[FBP]') !== false) {
					$array[$i]['plugin'][11] = 1;
				}
				else {
					$array[$i]['plugin'][11] = 0;
				}


				$i++;

			}


			if(@!$array) {

				$array['readMe'] = 'none';

			}


			echo json_encode(stripslashesFull($array));


		break;




		case 'moveRow':


			$rowid = makeNumber($_POST['rowid']);
			$direction = makeLetter($_POST['direction']);


			if($direction == 'down') {


				$oldsort = ORM::for_table('boxes')->find_one($rowid);
				$newsort = ORM::for_table('boxes')->where_gt('sort', $oldsort->sort)->order_by_asc('sort')->find_one();


				if(@!$newsort) {

					$newsort = ORM::for_table('boxes')->order_by_desc('sort')->find_one();

				}


				$myNewSort = $newsort->sort;
				$myOldSort = $oldsort->sort;

				$oldsort->sort = $myNewSort;
				$oldsort->save();

				$newsort->sort = $myOldSort;
				$newsort->save();


			}


			else {


				$oldsort = ORM::for_table('boxes')->find_one($rowid);
				$newsort = ORM::for_table('boxes')->where_lt('sort', $oldsort->sort)->order_by_desc('sort')->find_one();


				if(@!$newsort) {

					$newsort = ORM::for_table('boxes')->order_by_asc('sort')->find_one();

				}


				$myNewSort = $newsort->sort;
				$myOldSort = $oldsort->sort;

				$oldsort->sort = $myNewSort;
				$oldsort->save();

				$newsort->sort = $myOldSort;
				$newsort->save();
				

			}


		break;
		


	}










?>
