<?php
/***************************************
  ______        __         __ __       
 |   _  \.--.--|  |_.-----|  |  .---.-.
 |.  |   |  |  |   _|  -__|  |  |  _  |
 |.  |   |_____|____|_____|__|__|___._|
 |:  |   | CMS BY THE NUTELLA PROJECT                            
 |::.|   | WEBSITE: NUTELLA-PROJECT.COM                            
 `--- ---'    
****************************************/

	if(!defined('NUTELLA_CMS')) 
	{ 
		die('Lo sentimos, pero no puede acceder a este archivo!'); 
	}
	function newsComment()
	{
		global $dbh,$lang;
		if (isset($_POST['newscomment']))
		{
			$count = $dbh->prepare("SELECT userid,newsid FROM nutella_news_message WHERE userid = :id AND newsid = :newsid");
			$count->bindParam(':id', $_SESSION['id']);
			$count->bindParam(':newsid', $_GET['id']);
			$count->execute();
			if ($count->RowCount() <= 2)
			{
				if (!empty($_POST['message']))
				{
					if (strlen($_POST['message']) >= 3)
					{
						$sql = $dbh->prepare("SELECT id,title,longstory FROM cms_news WHERE id = :newsid");
						$sql->bindParam(':newsid', $_GET['id']);
						$sql->execute();
						if (!$sql->RowCount() == 0)
						{
							$message = htmlspecialchars($_POST['message'], ENT_QUOTES, 'UTF-8');
							$hash = user::hashed($_POST['message']);
							$addCommand = $dbh->prepare("
							INSERT INTO nutella_news_message (
							newsid,
							userid,
							message,
							date,
							hash
							) VALUES (
							:newsid, 
							:id,
							:message,
							'". time() ."',
							:hash
							)");
							$addCommand->bindParam(':newsid', $_GET['id']);
							$addCommand->bindParam(':id', $_SESSION['id']);
							$addCommand->bindParam(':message', $message);
							$addCommand->bindParam(':hash', $hash);
							$addCommand->execute();
							header('Location: '.$config['hotelUrl'].'/news/'.$_GET['id'].'');	
						}
						else
						{
							return Html::error($lang["CnoNews"]);
						}
					}
					else
					{
						return Html::error($lang["Ccommandshort"]);
					}
				}
				else
				{
					return Html::error($lang["Ccommandempty"]);
				}
			}
			else
			{
				return Html::error($lang["Ccommandmax"]);
			}
		}
	}
?>	