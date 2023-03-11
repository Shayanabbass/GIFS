<?php
class db
{
	public $class_name = '';
	public static $db;
	private static $instance = NULL;

	static public function getInstance()
	{
		if(NULL === self::$instance)
		{
			self::$instance = new self();
			try
			{
				self::$db = new PDO('mysql:host='.DB_SERVER.';dbname='.DB_NAME.';charset=utf8', DB_SERVER_USERNAME, DB_SERVER_PASSWORD);

			} catch (PDOException $e) {
			    echo 'Connection failed: ' . $e->getMessage();
			    exit;
			}

		}
		return self::$instance;
	}
	function __construct($class_name = '')
	{
		$this->class_name = $class_name;
		if(db::$db) return db::$db;
		try
		{
			self::$db = new PDO('mysql:host='.DB_SERVER.';dbname='.DB_NAME.';charset=utf8', DB_SERVER_USERNAME, DB_SERVER_PASSWORD);

		} catch (PDOException $e) {
			echo 'Connection failed: ' . $e->getMessage();
			exit;
		}
	}
	function c()
	{
			global $link;
			$link = 1;
			echo($link);
	}

	function result($sql, $is_single = false)
	{
		$stmt = self::$db->prepare($sql);
		$stmt->execute();
		// $stmt = $this->db->result($sql);
		if($is_single)
		{
			return $stmt->fetch(PDO::FETCH_ASSOC);
		}
		else
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
			// return $result->fetchall(PDO::FETCH_ASSOC);
	}
	function sql_query($sql, $is_PDO = true)
	{
		if($is_PDO)
		{
			try {
			$db = new PDO('mysql:host='.DB_SERVER.';dbname='.DB_NAME.';charset=utf8', DB_SERVER_USERNAME, DB_SERVER_PASSWORD);
				//connect as appropriate as above
				$stmt = $db->query($sql); //invalid query!
				if ($stmt === false){
					echo "Line 67: ".$sql;
					$errorInfo = $stmt->errorInfo();
					echo $errorInfo;die;
					//log the error or take some other smart action
				}
				return $stmt;
			} catch(PDOException $ex) {
				echo "An Error occured!"; //user friendly message
				echo $ex->getMessage();
				die;
				//some_logging_function($ex->getMessage());
			}
		}
		echo 'database.php: line 79<br>';
		echo $sql;
		die();
	}

	function sqlq($sql, $is_PDO = true)
	{
		if($is_PDO)
		{
			try {
			$db = new PDO('mysql:host='.DB_SERVER.';dbname='.DB_NAME.';charset=utf8', DB_SERVER_USERNAME, DB_SERVER_PASSWORD);
				//connect as appropriate as above
				$stmt = $db->exec($sql); //invalid query!
				if ($stmt === false){
					echo "Line 99: ".$sql;
					$errorInfo = $stmt->errorInfo();
					echo $errorInfo;die;
					//log the error or take some other smart action
				}
				return $stmt;
			} catch(PDOException $ex) {
				echo "An Error occured!"; //user friendly message
				echo $ex->getMessage();
				die;
				//some_logging_function($ex->getMessage());
			}

		}
	}
	function new1($action_e = 0)
	{
		$tbl = page_name();
		if(strpos($tbl, "_to_") >0)
		{
			$action = "?action=update&id=".get("id");
			$table_names = explode("_to_", $tbl);
			//$this->class_name = $table_names[1];

			// $table_names[1];
			$sql2 = "select * from ".$table_names[0];
			//alert($sql2); return;
			$data = $this->sql_query($sql2);
			$result = $data->fetchall(PDO::FETCH_ASSOC);
			$meta = $data->getColumnMeta(1);
			$first_column_name = $meta['name'];
			$count = 0;
			if(count($result) >0)
			{
				echo '<div class="col-md-5">
			<form id="form5" name="form5" method="post" action="'.$action.'" enctype="multipart/form-data">
				<table width="100%" border="1" cellspacing="0" cellpadding="5">
				<tr>
				<td>
				'.get_name('', $table_names[1]).'
				</td>
				</tr>
				';
				echo '<tr>
                    <td colspan="2">';
			}
			foreach($result as $a)
			{
				eval("\$id = \$a['".$table_names[0]."_id'];");
				eval("\$names = \$a['".$table_names[0]."_name'];");
				eval("\$value = \$a['".$table_names[0]."_id'];");
				//show comments for easy understanding
				if($first_column_name == 'forms_name')
				{
					eval("\$names = \$a['".$table_names[0]."_comments'];");
				}
				$checked = '';
				if($_GET["action"] == "edit" || !$action_e)
				{
					$sql3 =  "select $table_names[0]_id from $this->class_name where $table_names[1]_id = ".get("id")." AND $table_names[0]_id = ".$id;
					$result3 = $this->result($sql3);
					if(count($result3)>0)
					{
						$checked = 'checked="checked"';
					}
					/*					Select all other fields against this and print them 					*/
				}
				//pass field_id and forms_id and get all other field except that in the id

				echo '

				<table border="1" cellspacing="0" cellpadding="3" width="100%">
  <tr>
    <td width=20%>
				 <label>
					<input type="checkbox" name="'.$table_names[0].'[]" value="'.$value.'" id="'.$table_names[0].'_'.$count.'" '.$checked.' />
		            '.$names.'</label></td>
	<td>';

				echo print_field($this->class_name, $id);
				echo	'</td>
						  </tr>
						</table>
						<br />';
				$count++;
			}
			if(count($result) >0)
				echo '</td>
                    </tr>';
		}
		else
		{
			if($_GET["action"] == "new" && !$action_e)
			{
				$name = "Insert ";
				$action = "?action=insert";
				$filter = "";
			}
			else
			{
				$name = "Update ";
				$id = request("id");
				$action = "?action=update&id=".$id;
				$filter = " WHERE ".get_class_id($this->class_name)." = ".$id;
				//alert(":)");
			}
			$sql = "select * from ".$this->class_name.$filter;
			$result = $this->sql_query($sql);

//			echo $sql; return;
			$total_column = $result->columnCount();

			//var_dump($total_column);
			//not needed $f
			$f = $result->fetch(PDO::FETCH_ASSOC);
			if($total_column>0)
			{
				echo '
				<div class="col-md-12">
				<div class="box box-primary">
				<div class="box-body">
				<form id="form5" name="form5" method="post" action="'.page_url().$action.'" enctype="multipart/form-data" onsubmit="return check()">
						<table border="0" width="100%" cellpadding="0" cellspacing="0">
						<tr valign="top">
						<td>

							<!-- start id-form -->
						  <table border="0" cellpadding="0" cellspacing="0"  id="id-form">';
			}

			/*
				$field_value =  $temp_id;
				//users_password_1 remove 1
				echo print_field(get_inherits($this->class_name), $field_name);
				echo print_field($this->class_name, $field_name);
				return;
			*/

			//forced else
			$field_name_non_split = '';
			for ($counter = 0; $counter < $total_column; $counter ++) {
				$meta = $result->getColumnMeta($counter);
				$field_name_non_split = $meta['name'];
				//debug_r($meta);
				$flags = $meta['flags'];
				//
				//echo $flags'."<br />";
				$field_name = explode("_", $field_name_non_split);
				$field_type = strtolower($meta['native_type']);
				if($_GET["action"] == "edit" && !$action_e)
				{
					//check to get al fields
					$temp_id =  $this->get_value($field_name_non_split);
				}
				elseif($field_name_non_split ==  $table_names[1]."_id" && $_GET["action"] == "edit" && !$action_e)
				{
					$temp_value = $this->get_value($table_names[1]."_name", $temp_id);
					echo $temp_value.generate_hidden($field_name_non_split, $temp_id);
					//echo $temp_value.'<input name="'.$field_name_non_split.'" type="hidden" value="'.$temp_id.'" />';
					continue;
				}

				if(($meta['native_type'] == 'LONG' && $meta['len'] == 11 && $meta['name'] == 'added_on') || $meta['native_type'] == 'TIMESTAMP')
				{
						continue;
				}
				if(array_search('primary_key', $meta['flags']) ===false && $field_name_non_split != 'session_id')
				{
					if($_GET["action"] == "edit" && !$action_e)
					{
						$initial_value = "value=\"".$temp_id."\"";
					}
					if($field_type == "blob")
					{
						if($_GET["action"] == "edit")
							$initial_value = $temp_id;
						echo generate_blob($field_name_non_split, $initial_value);
						echo '
						<script>
						// $("#'.$field_name_non_split.'").tinymce({
						// 	plugins: "code",
						// 	height: 200,
						// 	statusbar: false,
						// 	menubar : false,
						// 	toolbar: "code undo redo styleselect bold italic alignleft aligncenter alignright bullist numlist outdent indent",
						// });
						</script>';
					}
					//uploa
					elseif($field_type == "date")
					{
						if($_GET["action"] == "edit" && !$action_e)
						{
							//convert sql format to php
							$initial_value = $temp_id;
							$initial_value =  mysql_to_php_date($initial_value);
							//11/22/2009 --> 2009-11-22
							if($initial_value == "00/00/0000")
							{
								$initial_value = '';
							}
							else
							{
								$initial_value = ' value="'.$initial_value.'"';
							}
						}
						$field["name"] = $field_name_non_split;
						$field["value"] = $initial_value;
						echo generate_date($field);
						// debug_r($field);
					}
					elseif($field_name[count($field_name)-1] == "upload")
					{
						if($_GET["action"] == "edit" && !$action_e)
						{
							$initial_value = $temp_id;
						}
						echo generate_upload($field_name_non_split, $initial_value);
					}
					elseif($field_name[count($field_name)-1] == "id")
					{
						if($field_name_non_split == "users_id" && strpos($tbl,"_to_users")<0 && $_GET["action"] == "edit" && !$action_e)
						{
							$initial_value = $_SESSION["users_id"];
							echo generate_hidden($field_name_non_split, $initial_value);
						}
						elseif($field_name_non_split == "parent_id")
						{
							// debug_r($temp_id);
							// eval("\$initial_value=\$f[\"".$field_name_non_split."\"];");
							$field["name"] = $tbl."_id";
							$field["value"] = $temp_id;
							//debug($field);
							echo generate_parent($field);
						}
						elseif(strpos($field_name_non_split, "added_by")!==false || strpos($field_name_non_split, "updated_by")!==false)
						{
							$initial_value=$_SESSION["users_id"];
							echo generate_hidden($field_name_non_split, $initial_value);
						}
						else
						{
							eval("\$id=\$f[\"".$field_name_non_split."\"];");
							echo generate_dropdown($field_name_non_split, $temp_id);
						}
					}
					elseif(strpos($field_name_non_split, "assigned_to")>0)
					{
						debug_r("350 users");

							echo '
							  <tr>
								<td width="30%">'.field_name($result, $i).'</td>
								<td width="70%">'.str_replace("users_id", $fn, $this->dropdown("users_id", $temp_id)).'</td>
							  </tr>';
						//alert("\$id=\$f[\"".$field_name_non_split."\"];")
					}
					elseif($meta["len"]==1 && $field_type == "int")
					{
						$field["name"] = $field_name_non_split;
						$field['value'] = $temp_id;
						echo generate_bool($field);
						/*echo '<tr>
							   <td width="30%">'.field_name($result, $i).'</td>
							  <td width="70%"><p>
								 <label>
								   <input type="radio" name="'.$field_name_non_split.'" value="1" id=".field_nameresulti._0" />
								   Yes</label>
								 <br />
								 <label>
								   <input type="radio" name="'.$field_name_non_split.'" value="0" id=".field_nameresulti._1" />
								   No</label>
								 <br />
							   </p></td>
							 </tr>';*/
					}
					elseif($field_name[count($field_name)-1] == "password")
					{
						$field["name"] = field_to_page($field_name);
						$field["name"] = $field_name_non_split;
						if($_GET["action"] == "edit" && !$action_e)
						{
							//$field["value"] = md5($temp_id);
						}
						echo generate_password($field);
					}
					else
					{
						/*if($field_name_non_split == 'forms_type')
						{
							continue;
							$sql = "SHOW COLUMNS FROM `forms` LIKE 'forms_type'";
							$result_columns = $this->result($sql, true);
							$columns = $result_columns->fetchall(PDO::FETCH_ASSOC);
							debug_r($columns[0]['Default']);
						}*/
					//	continue;
						$initial_value = '';
						if($_GET["action"] == "edit" && !$action_e)
						{
							$initial_value = $temp_id;
						}
						$field = array("name"=>$field_name_non_split, "value"=>$initial_value);
						if(function_exists('generate_textbox'))
							echo  generate_textbox($field);

						/*
						'
						  <tr>
							<td width="30%">'.field_name($result, $i).'</td>
							<td width="70%"><input name="'.$field_name_non_split.'" type="text" class="inp-form"" id="'.$field_name_non_split.'" '.$initial_value.'/ >
							</td>
						  </tr>
						 ';
						 */
					}
				}
			}
		}
		if(is_array($result) && count($result)>0 || 1)
		{
			echo '
				<tr>
		<th>&nbsp;</th>
		<td valign="top">
			<input type="submit" value="'.$name.$this->class_name2().'" class="btn btn-small btn-blue" />
			<input type="reset" value="Reset '.$name.$this->class_name2().'" class="btn btn-small btn-warning"  />
			<input type="button" onclick="location.href=\''.page_url().'\'" value="Cancel" class="btn btn-small btn-red"  />
		</td>
		<td></td>
	</tr>
	</table>
              </form>
			  </div><!--box-body-->
			  </div>
			  </div>
			<!-- end id-form  -->
			</td>
		</tr>
		</table>
		</div>
		</div>
			  ';
		}
	}

	function insert($id = "")
	{
		$tbl = page_name();
		//update() pointer delete()
		$debug = false;
		// $debug = true;
		if(has_inherits($this->class_name))
		{
			$id = next_id(get_inherits($this->class_name));
			$c = new db();
			$c->class_name = get_inherits($this->class_name);
			$c->insert($id);
			if($debug == false)
			{
				alert(ucwords(get_inherits_parent($this->class_name))." Inserted Successfully");
				redirect(page_url());
			}
		}
		elseif(strpos($this->class_name, "_to_")>1)
		{
			$table_names = explode("_to_", $tbl);
			eval("\$post=\$_REQUEST[\"".$table_names[0]."\"];");
			eval("\$id=\$_REQUEST[\"".$table_names[1]."_id\"];");
			//alert(count($post));
			//debug($_POST);
			/*
			debug("post------------------------------------------------------------");
			debug($post);
			debug("id------------------------------------------------------------");
			debug($id);
			debug("forms------------------------------------------------------------");
			debug($_REQUEST["forms"]);
			debug("------------------------------------------------------------");
			*/
			for($i = 0; $i < count($post); $i++)
			{
				//SYNTAX FOR # OF COLS
				$sql = "select * from ".$this->class_name.' limit 0,1';
				//echo $sql; return;
				$result = $this->sql_query($sql);
				$f = $result->fetch(PDO::FETCH_ASSOC);
				$s1 = '';
				$s2 = '';
				$flag = false;

				$total_column = $result->columnCount();

				for($j = 0; $j <  $total_column; $j++)
				{
					$meta = $result->getColumnMeta($j);
					$field_name_non_split = $meta['name'];
					$flags = $meta['flags'];
					$field_name = explode("_", $field_name_non_split);

					$flag = true;
					//echo mysql_field_flags($result, $i)."<br />";
					$field["length"] = strlen($field_name_non_split);
					//
					$field["flags"] = $flags;
					//$key
					$field["name"] = $field_name_non_split;
					//
					$field["type"] = $meta['native_type'];
					$s1 .= $field["name"].", ";
					//eval("\$field[\"value\"]=\$_POST[\"".$field["name"]."\"];");
					eval("\$field[\"value\"]=\$_REQUEST[\"".$field["name"].'_'.$j."\"];");

					if($field["type"] == 'int')
					{
						$sValue = (int)$sValue;
					}
					eval("\$arr=\$_REQUEST[\"".$table_names[0]."\"];");
					/*
					debug($arr);
					echo "<br /><br />";
					debug($_POST);
					return;
					*/
					//debug($field);
					if($field["name"] == $table_names[1]."_id")
					{
						$field["value"] = (int)$_GET["id"] ? (int)$_GET["id"] : '0';
					}
					elseif($field["name"] == $table_names[0]."_id")
					{
						//alert($field["name"]);
						//debug($arr);
						$field["value"] = $arr[$i];
					}
					else
					{
						//eval("\$temp_arr=\$_REQUEST[\"".$table_names[0]."\"];");
						//debug($i);
						$e = ("\$temp_arr=\$_POST[\"".$field["name"]."_".$arr[$i]."\"];");
						//echo $e;
						eval($e);
						$field["value"] = $temp_arr;
						//entertain many to many realtionship different types of field
						if($field["type"] == "date")
						{
							if($field["value"])
								$field["value"] = php_to_mysql_date($field["value"]);
						}
						//debug($temp_arr);//return;
						//$temp_arr = '["'.$field["name"]."_".$arr[$i].'"];';
						//if($field["name"] == $table_names[1]."_id")
						//if($field["name"] == $table_names[0]."_id")
						//eval("\$field[\"value\"]=\$_POST[\"".$field["name"].'_'.$i."\"];");
						//debug($_POST);return;
					}
					//filter fields
					$field["value"] = filter_inputs($field["value"]);
					if($field["type"] == 'int')
					{
						$field["value"] = (int)$field["value"];
					}

					$s2 .= "'".$field["value"]."', ";
					///echo '<br /><br /><br />';
					//debug($field);
				}

				if($flag)
				{
					$s1 = substr($s1, 0, strlen($s1)-2);
					$s2 = substr($s2, 0, strlen($s2)-2);
				}
				$sql = "Insert into ".page_name()." (".$s1.") VALUES (".$s2.");";
				if($debug)
				{
					debug($sql); continue;
				}
				// debug_r($sql);

				$result = $this->sqlq($sql);
			}
			if($debug)
				return;
			//echo "$key=$value;<br />";
			if( !has_inherits(  page_name()  ) )
			{
				alert(ucwords($this->class_name2())." Inserted Successfully");
				redirect(page_url());
			}
			return;
		}

		$s1 = '';
		$s2 = '';
		$sql = "select * from ".$this->class_name.' limit 0,1';
		// echo $sql; return;
		$result = $this->sql_query($sql);
		$f = $result->fetch(PDO::FETCH_ASSOC);
		$total_column = $result->columnCount();
		for($i = 0; $i < $total_column; $i++)
		{
			$meta = $result->getColumnMeta($i);
			$field_name_non_split = $meta['name'];
			$flags = $meta['flags'];
			$field_name = explode("_", $field_name_non_split);
			//$value
			eval("\$value = \$_REQUEST[\"$field_name_non_split\"];");
			$field["type"] = $this->_translateNativeType($meta['native_type'], $meta['len']);
			$field["name"] = field_to_page($field_name_non_split);
			//$field["value"] = $value;
			//split field
			$fields = explode("_", $field_name_non_split);
			//check field type and act accordingly
			//time stamp
			if($field["type"] ==  "timestamp" || $field_name_non_split == "id")
			{
				continue;
			}
			if(strpos($field_name_non_split, "added_by")!==false)
			{
				$value = $_SESSION["users_id"];
			}
			//users_id end
			if($field["type"] == 'int')
			{
				$value = (int)$value;
			}
			$field['value'] = $value;
			// debug_r($_POST);
			// if($field_name_non_split != 'name')
			// 	debug_r($field);
			if( (substr($value, 0, 3) == "<p>" ))
			{
				$temp = substr(substr($value, 3, strlen($value)), 0, strlen($value)-3);
				$value = $temp;
				$value = substr($value, 0, strlen($value)-4);
				$value = htmlspecialchars($value);
			}
			// debug_r($meta);
			//$s .= "$field_name_non_split=\"$value\", ";
			//
			//check if key is password
			//echo $field["type"]."<br />";
			if($field["type"] == "date")
			{
				if($value)
					$value = php_to_mysql_date($value);
				else {
					$value = "NULL";
				}
			}
			elseif($fields[count($fields)-1] == "password")
			{
				$value = md5($value);
				//alert($value);
			//end check password
			}
			elseif(strpos($field_name_non_split, "_on", round(strlen($field_name_non_split)-3))>0)
			{
				$date_1 = explode("/", $value);
				$value = $date_1[2].'-'.$date_1[0].'-'.$date_1[1];
			}
			if($fields[count($fields)-1] == "upload")
			{
				eval("\$value = \$_FILES[\"$field_name_non_split\"];");
				eval("\$newval_value = \$_REQUEST[\"newval_$field_name_non_split\"];");

				if(!$value["name"])
				{
					if($newval_value)
					{
						$sValue = $newval_value;
					}
				}
				else
				{
					//debug_r($_REQUEST);
					$targetpath = $this->class_name."_images";
					$path = upload($value, $this->class_name."_images");
					$sValue = $path;
				}
				$value = $sValue;
				// debug_r($_POST);
				//upload($file_name, $targetpath)
				// $targetpath = $this->class_name."_images";
				//echo "<br /><br />".$this->class_name."_images"; return;
				// echo $fields;
				// debug_r($sValue);

				// $value = upload($value, $this->class_name."_images");
			}
			//works for users_id  not in inherits class
			if($fields[count($fields)-1] == "id" && !$value)
			{
				/*$field["value"] = (int)$_GET["id"]
				debug_r($value);*/
				if($field_name_non_split == $this->class_name."_id")
				{
					$value = (int)$id? (int)$id : '0';
				}
				elseif(has_inherits($this->class_name))
				{
					$value = (int)$id? (int)$id : '0';
				}
				else
				{
					$value = (int)$id? (int)$id : '0';
				}
			}
			if($field['type'] == 'boolean' && !$value)
				$value = '0';
			$value = filter_inputs($value);

			if($this->class_name.'_id' == $field_name_non_split)continue;
			if($field_name_non_split == 'sort' && $value == '') continue;
			$s1 .= "$field_name_non_split, ";
			if($value == "NULL")
				$s2 .= "$value, ";
			else
				$s2 .= "'".$value."', ";
			//if($field_name_non_split == "is_supervisor") debug_r($value);
		}
		$s1 = substr($s1, 0, strlen($s1)-2);
		$s2 = substr($s2, 0, strlen($s2)-2);
		//continue;	return;
		$sql = "Insert into ".$this->class_name." (".$s1.") VALUES (".$s2.")";
		// debug_r($sql);
		if($debug)
		{
			debug($sql); return;
		}
		//echo '<br />'.$sql;return;
		$result = $this->sqlq($sql);
		if($result)
		{
			alert(ucwords($this->class_name2())." Inserted Successfully");
			redirect(page_url()."?action=new");
		}
	}

	function delete()
	{
		$debug = false;
		$table_names = explode("_to_", $this->class_name);
		/*if($this->class_name.'_id' == "users_id" && $_SESSION["users_name"] != "admin")
		{
			$id = $_SESSION["users_id"];
			alert("You cannot delete yourself");return;
		}*/
		$id = request("id");
		if(count($table_names)>1)
		{
			$from = $this->class_name;
			$where = $table_names[1];
			$condition = $table_names[1]."_id = ".$id;
			$tables = return_tables($where);
		}
		else
		{
			$from = $this->class_name;
			$where = $this->class_name;
			$condition = $this->class_name."_id = ".$id;
			$tables = array($from);
		}

		//check for foreign key dependencies so all can be deleted
		//debug($tables);die("");
		for($i = 0; $i<count($tables); $i++)
		{
			$sql = "DELETE FROM ".$tables[$i]." WHERE ".$condition;
			if($debug)
			{
				echo($sql);continue;
			}
			$result = $this->sqlq($sql);
		}
		if($debug)
			return;
		//end check
		if($result && count($table_names)==1)
		{
			alert(ucwords($this->class_name)." Deleted Successfully");
			redirect(page_url());
		}
	}

	function show()
	{
		echo $this->show_r();
	}

	function show_r()
	{
		$table_names = explode("_to_", page_name());
		$return = '<div class="col-md-12">';
		if(count($table_names)==1)
			$return .='<table width="100%" border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<td align="center">
					<form name="form2" method="post" action="?action=new">
						  <label>
							<input type="submit" name="button2" class="btn btn-blue" id="button2" value="Insert '.field_to_page($this->class_name2()).'">
						  </label>
						</form><br />
					</td>
				  </tr>
				</table>
				<div class="box">';
		if(count($table_names) >1)
		{
			//$sql = "select distinct ".$table_names[1]."_id from ".$this->class_name;
			$sql = "select * from ".$table_names[1];
			//echo $sql; return;
			$data = $this->sql_query($sql);
			$result = $data->fetchall(PDO::FETCH_ASSOC);
			$meta = $data->getColumnMeta(1);
			$first_column_name = $meta['name'];
			$count = count($result);
			if($count>0)
			$return .= '

			<div class="box-body">
					<table class="table table-bordered table-striped data-table">
						<thead>
							<tr>
								<th>'.$this->class_name2().' ID</th>
								<th>'.$this->class_name2().' Name</th>
								<th>Options</th>
							</tr>
						</thead>
			';
			$alternate = "";
			foreach($result as $a)
			{
				eval("\$id = \$a[\"".$table_names[1]."_id\"];");
				if(!$id && $a["id"]) $id = $a["id"];

				$sql2 = "select * from ".$table_names[1]." 	where ".$table_names[1]."_id = ".$id;
				$b = $this->result($sql2, 1);
				eval("\$id = \$a[\"".$table_names[1]."_id\"];");
				eval("\$name = \$b[\"".$table_names[1]."_name\"];");
				//echo $this->dropdown($table_names[0]."_id", $id);

				if($first_column_name == 'forms_name')
				{
					eval("\$name = \$b['".$table_names[1]."_comments'];");
					//alert($table_names[0]."_comments");
				}
				$return .= '
				 <tr'.$alternate.'>
					<td>'.$id.'</td>
					<td>'.$name.'</td>
					<td class="options-width">
					<button onclick="location.href=\'?action=edit&id='.$id.'\'" class="btn btn-small btn-success"><span class="icon-brush"></span>Edit</button></td>
				</tr>';
				if($alternate != "")
				{
					$alternate = 'even';
				}
				else
				{
					$alternate = 'odd';
				}
			}
			if($count>0)
			$return .= '						</tbody>
						</table>
                        </div>';
			return $return;
		}



		if($field_name != '' && $field_name == $this->if_users_id() && ($_SESSION["users_name"] != "admin"))
		{
			//REMAINING LEFT NOT DONE
			$thisilters = " where ".$field_name." = ".$_SESSION["users_id"];
		}
		$sql1 = "select * from ".$this->class_name.$thisilters;
		$result1 = $this->result($sql1);
		$total = count($result1);
		$max = number_of_pages;
	  	$first = 1;
		if(isset($_REQUEST["first"]))
		  if($_REQUEST["first"] != "")
			$first = $_REQUEST["first"];
//		$first--;
		$last = $max;
//		alert();	%$total
		$filter = ' limit '.($first-1).', '.$max;
		$sql = $sql1.$filter;
		$data = $this->sql_query($sql);
		$meta = $data->getColumnMeta(1);
		$first_column_name = $meta['name'];
		$result = $data->fetchall(PDO::FETCH_ASSOC);
		$count = count($result);
		$filter_delete = '';
		if(1 || can_delete())
		{
			$filter_delete = '	<button onclick="location.href=\'?action=edit&id='.$id.'\'" class="btn btn-primary"><span class="icon-home"></span>Edit</button>';
		}
		if($count>0)
			$return .= '
			<div class="box-body">
					<table class="table table-bordered table-striped data-table">
						<thead>
							<tr>
								<th>'.$this->class_name2().' ID</th>
								<th>'.$this->class_name2().' Name</th>
								'.(page_name() == 'forms' ? '<th>Sort</th><th>name</th>' : '').'
								<th>Options</th>
							</tr>
						</thead>';
		foreach($result as $a)
		{
			eval("\$name=\$a['".$this->class_name."_name'];");
			$class_id = get_class_id($this->class_name);
			eval("\$id=\$a['".$class_id."'];");
			if(!$id && $a["id"]) $id = $a["id"];

			if(has_inherits($this->class_name))
			{
				$temp = get_tuple(get_inherits($this->class_name), $id, get_inherits ($this->class_name)."_id");

				$name = $temp[get_inherits($this->class_name)."_name"];
			}
			if($name == "")
			{
				//find one with _name in end
				///alert("null");
				$fn = $this->name();
				$name = $fn;
				eval("\$name=\$a['".$name."'];");

				//$name = ucwords(str_repla$name);
			}
			if($first_column_name == 'forms_name')
			{
				eval("\$name = \$a['".$table_names[0]."_comments'];");
			}
			elseif($first_column_name == 'contact_name')
			{
				$name = $a['First_Name'].' '.$a['Second_Name'];
			}
			$name = respond($name);
			if(strlen($name)>150)
			{
				$name = substr($name, 0, 150)."...";
			}
		  //eval('$elargei='.$check_var1.';');
//			  $name.=('<img src="'.$this->get_value("events_image_75_upload", $id).'" height=100 width=100>');
			///	allow only owner to delete
			$filter_all = '';
			$filter_delete = '';
			if(1 || can_delete())
			{
				$filter_all = '
				<button onclick="location.href=\'?action=delete_confirm&id='.$id.'\'" class="btn btn-small btn-error"><span class="icon-x"></span>Delete</button>';
			}
			$return .= '
			<tr class="'.$alternate.'">
							<td>'.$id.'</td>
							<td>'.$name.'</td>
								'.(page_name() == 'forms' ? '<td>'.$a['sort'].'</td><td>'.$a['forms_name'].'</td>' : '').'
							<td>

				<button onclick="location.href=\'?action=edit&id='.$id.'\'" class="btn btn-small btn-success"><span class="icon-brush"></span>Edit</button>

				'.$filter_all.'
				</td>
							</tr>';
			if($alternate != "odd")
			{
				$alternate = 'odd';
			}
			else
			{
				$alternate = 'even';
			}
		}
		if($count>0)
			$return .= '
							</table>
				<!--  end product-table................................... -->
			  </form>
		  </div>
		  <!--  end content-table  -->
		  <!--  start paging..................................................... -->';

		$end = ($first-1+$max);
		$link = '?first=';
		if($total == 0)
		{
			$first = 0;
			return $return;
		}
		if($end>$total)
		{
			$end = $total;
		}
		$range = $first.'-'.$end;
		$previous_page = $first - $max;
		if($previous_page < 0 )
			$previous_page = 1;
		$next_page = $first+$max;

		//echo "\$total = $total<br />\$max = $max<br />\$first = $first<br />\$end = $end";
		$return .= '
		 <table border="0" cellpadding="0" cellspacing="0" id="paging-table">
			<tr>
			<td>';
				 if($first>1)
				  {
				  $return .= '
                    <a href="'.$link.'1" class="page-left"><i class="fa fa-angle-double-left"></i></a>';

				  $return .= '
                    <a href="'.$link.$previous_page.'" class="page-left"><i class="fa fa-angle-left"></i></a>';
				  }

		$return .= '<div id="page-info">Page <strong>'.ceil($first/$max).'</strong> / '.ceil($total/$max).'</div>';

				if($total>($max+$first-1))
				  {
					  if($first+$max < $total)
					  {
							$return .= '
								 <a href="'.$link.$next_page.'" class="page-right"><i class="fa fa-angle-right"></i></a>';
					  }

					  if($first != $total-$max)
					  {
						$return .= '
							<a href="'.$link.((ceil($total/$max)-1)*$max + 1).'" class="page-far-right"><i class="fa fa-angle-double-right"></i></a>';
					  }
				  }

		//page_selected
		$return .= '
			</td>
			<td>
			<select class="form-control select2" id="styledselect_pages" onChange="">
				<option value="">Number of rows</option>';
			for($i = 10; $i<100; $i+=10)
			{
				$selected = '';
				if($i == number_of_pages)
				{
					$selected = 'selected="selected"';
				}
				$return .= '<option value="" '.$selected.'>'.$i.'</option>';
				//<option value="999999">Unlimited</option>
			}


		$return .= '</select>
			</td>
			</tr>
		  </table>
		  </div><!-- box-->
	';

		if($count>0)
			$return .= '

		  <!--  end paging................ -->
			';
		$return .= '<!--col-md-12-->';
		return $return;
	}

	function get_value($key, $id = '')
	{
		//pointer to new1(
		//alert($key);return;
		if(($id != "") == "")
			$id = request("id");
		if(strpos($this->class_name, "_to_") > 0 )
		{
			$table_names = explode("_to_", $this->class_name);
			$class_name = $this->class_name;
			$class_name_id = $table_names[1]."_id".'  = '.$id;
			if($key != "permission_value")
			{
				$class_name = $table_names[1];
				if($key == $table_names[0]."_id")
				{
					$class_name = $table_names[0];
				}
				elseif($key == $table_names[1]."_id")
				{
					$class_name = $table_names[1];
				}
				else
				{
					$class_name = $table_names[1];
					//$class_name = $key;
					//echo 'bingo';
				}
				$class_name_id = $class_name."_id".'  = '.$id;
			}
		}
		else
		{
			$class_name = $this->class_name;
			$class_name_id = get_class_id($this->class_name).' = '.$id;
			//debug("Key = ".$key."<br />classname = ".$class_name_id.'<br /><br />');
		}
		/*
		if($this->class_name.'_id' == "users_id")
			$id = $_SESSION["users_id"];
		*/

		$sql = "select $key from ".$class_name.' WHERE '.$class_name_id;
		//die($key);
//		echo $sql."<br />";return;
		$a = $this->result($sql, true);
		return($a[$key]);
		//return $a[0];
	}
	function update()
	{
		//insert() pointer
		$debug = false;
		// $debug = true;
		$s = '';
		//
		$table_names = explode("_to_", $this->class_name);
		if(count($table_names)>1)
		{
			$this->delete();
			$this->insert();
			return;
		}
		if(has_inherits($this->class_name))
		{
			$c = new db();
			$c->class_name = get_inherits($this->class_name);
			$c->update();
			if($debug == false)
			{
				alert(ucwords(get_inherits_parent($this->class_name))." Updated Successfully");
				redirect(page_url());
			}
		}
		$sql = "select * from ".$this->class_name;
		//echo $sql; return;
		$result = $this->sql_query($sql);
		$f = $result->fetch(PDO::FETCH_ASSOC);
		$total_column = $result->columnCount();
		$field_name_non_split = '';
		for ($counter = 0; $counter < $total_column; $counter ++)
		{
			$meta = $result->getColumnMeta($counter);
			$type = $this->_translateNativeType($meta['native_type'], $meta['len']);
			$field_name_non_split = $meta['name'];
			//debug_r($meta);
			$flags = $meta['flags'];
			//echo $flags'."<br />";
			$field_name = explode("_", $field_name_non_split);
			$key = $field_name_non_split;
			//$value
			//$type = mysql_field_type($result, $i);
			//type
			eval("\$sValue = \$_REQUEST[\"$key\"];");
			//users_id end
			if($key == $this->class_name."_id" || $key == 'added_on' || $key == 'updated_on' || strpos($key, 'added_on') !== false)
			{
				continue;
			}
			// debug($key);
			// echo $type;
			if(has_inherits(page_name()) && get_inherits_parent(page_name())."_id"==$key)
			{
				continue;
			}
			if( (substr($sValue, 0, 3) == "<p>" ))
			{
				$temp = substr(substr($sValue, 3, strlen($sValue)), 0, strlen($sValue)-3);
				//echo $sValue;alert("");
				$sValue = $temp;
				$sValue = substr($sValue, 0, strlen($sValue)-4);
			}
			if($type == 'blob')
			{
				$allowedTags='<p><strong><em><u><h1><h2><h3><h4><h5><h6><img>';
				$allowedTags.='<li><ol><ul><span><div><br><ins><del>';
				str_replace("’", "&rsquo;", $sValue);
				$sValue = htmlspecialchars($sValue);
				//alert($key);
				//return;
			}
			elseif($type == 'date')
			{
				//debug_r($sValue.'______'.php_to_mysql_date($sValue));
				if($sValue)
					$sValue = php_to_mysql_date($sValue);
				else
					$sValue = 'NULL';
				//alert($key);
				//return;
			}
			elseif($type == 'int')
			{
				$sValue = (int)$sValue;
			}
			//check if key is password
			$fields = explode("_", $key);
			if($fields[count($fields)-1] == "password")
			{
				if($sValue)
					$sValue = md5($sValue);
				//alert($sValue);
			}
			//end check password
			//check if field is name
			if($fields[count($fields)-1] == "upload")
			{
				eval("\$value = \$_FILES[\"$key\"];");
				eval("\$newval_value = \$_REQUEST[\"newval_$key\"];");

				if(!$value["name"])
				{
					if($newval_value)
					{
						$sValue = $newval_value;
					}
				}
				else
				{
					//debug_r($_REQUEST);
					$targetpath = $this->class_name."_images";
					$path = upload($value, $this->class_name."_images");
					$sValue = $path;
				}
			}
			//check field type and act accordingly
			if(strpos($key, "added_by_id")!==false)
			{
				$sValue = $_SESSION["users_id"];
			}
			/*				Commented to avoid forms_id to id conversion
			elseif($fields[count($fields)-1] == "id")
			{
				$sValue = $_GET["id"];
			}*/
			if($fields[count($fields)-1] == "password" || $fields[count($fields)-1] == "upload")
			{
				if($sValue)
					$s .= "$key=\"$sValue\", ";
			}
			else
			{
				if($sValue != 'NULL')
					$s .= "$key=\"$sValue\", ";
				else {
					$s .= "$key=$sValue, ";
				}
			}
			//$kv[] = "$key=$value";
		}
		$s = substr($s, 0, strlen($s)-2);
		//debug($s);
		$id = request("id");

		//prevent any other updates
		if($this->class_name.'_id' == "users_id" && $_SESSION["users_name"] != "admin")
		{
			$id = $_SESSION["users_id"];
		}
		$class_name = get_class_id($this->class_name."_id");
		$sql = "UPDATE ".$this->class_name." SET  ".$s." WHERE ".$class_name." = '".$id."';";
		if($debug)
		{
			debug($sql);return;
		}
		// debug_r($sql);
		$result = $this->sqlq($sql);
		if(has_inherits(page_name()))
		{
			//alert($result);
			return;
		}
		if($result)
		{
			alert(ucwords($this->class_name)." Updated Successfully");
			redirect(page_url());
		}
		else
		{
			alert(ucwords($this->class_name)." NOT Updated Successfully");
		}
	}
	function class_name2()
	{
		//upper case
		return remove_inherits($this->class_name);
	}
	function dropdown($key, $ids = '')
	{
		//alert($ids);
		//alert($filter);
		//$key1 = explode("_", $key);
		$key2 = str_replace("_id", "", $key);
		$fn = $key2."_name";
		if($fn != "")
			$order = ' ORDER BY '.$fn;
		else
			$order = '';
		$sql = "select * from ".$key2.$order;
		//echo $sql."<br />";return;
		$result = $this->result($sql);
		if(count($result)>0)
		{
			//echo '<label><select name="'.$key1[0].'_id" id="'.$key1[0].'_id">';
			$s = '<label><select name="'.$key.'" id="'.$key.'" class="form-control select2">';
		}
		foreach($result as $a)
		{
			eval("\$id = \$a[\"".$key."\"];");
			eval("\$name = \$a[\"".$key2."_name\"];");
			//eval("\$name=\$a['".$this->class_name."_name'];");
			if($ids == $id)
			{
				$s .= '<option value="'.$id.'" selected="selected">'.$name.'</option>';
			}
			else
				$s .= '<option value="'.$id.'">'.$name.'</option>';
		}
		if(count($result)>0)
			$s .= '</select></label>';
		return $s;
	}
	function if_users_id()
	{
		echo "includes/database.php  -- Line: 1307";
		debug_r("1297 users.php");

		$sql = "select * from ".$this->class_name;
		//echo '<br />'.$sql.'<br />'; return;
		$result = $this->result($sql);
		$f = $result;

		for($i = 0; $i < mysql_num_fields($result); $i++)
		{
			$field_name = mysql_field_name($result, $i);
			if($field_name == "users_id" || $field_name == $this->class_name."_assigned_to" || $field_name == $this->class_name."_added_by_id" || $field_name == "added_by")
			{
				return $field_name;
			}
		}

		return false;
	}
	function name()
	{
		$sql = "select * from ".$this->class_name;
		//echo '<br />'.$sql.'<br />'; return;
		$result = $this->sql_query($sql);
		$f = $result->fetch(PDO::FETCH_ASSOC);
		$total_column = $result->columnCount();
		for($i = 0; $i < $total_column; $i++)
		{
			$meta = $result->getColumnMeta($i);
			$field_name_non_split = $meta['name'];
			$field_name = explode("_", $field_name_non_split);
			$type = $this->_translateNativeType($meta['native_type'], $meta['len']);
			//debug_r($meta);
			$field_name = explode("_", $field_name_non_split);

			if($field_name[count($field_name)-1] == "name")
			{
				return $field_name_non_split;
			}
		}

		return "";
	}
	private function _translateNativeType($orig, $len) {
		$trans = array(
			'VAR_STRING' => 'string',
			'STRING' => 'string',
			'BLOB' => 'blob',
			'LONGLONG' => 'int',
			'LONG' => 'int',
			'SHORT' => 'int',
			'DATETIME' => 'datetime',
			'DATE' => 'date',
			'DOUBLE' => 'real',
			'TIMESTAMP' => 'timestamp'
		);
		if($orig == 'TINY' && $len == 1) return 'boolean';
		//600
		return $trans[$orig];
	}
}
