<?php
//------------------------------------------------------------------------------
// table and form automation
// © 2011 dan@marginallyclever.com
//------------------------------------------------------------------------------


//------------------------------------------------------------------------------
// An easy way to modify each table row before it is displayed
// @param $row the row about to be displayed, obtained from mysqli_fetch_assoc
// @param $key the name of the column with the primary key
//------------------------------------------------------------------------------
function _showtable_row_callback($row,$key) {
  if($key!=null) {
    $f=parse_url($_SERVER['REQUEST_URI']);
    $file=$f['path'];
    $sq=empty($f['query'])?"":$f['query'];
    $sym=(strlen($sq)>0?"?".$sq."&":"?");
    
    $row[$key]="<a href='$file$sym$key=".$row[$key]."'>Edit</a>";
  }
  return $row;
}

$showtable_row_callback="_showtable_row_callback";



//------------------------------------------------------------------------------
// An easy way to modify the table headings before they are displayed
// @param $row the row about to be displayed, obtained from mysqli_fetch_assoc
// @param $key the name of the column with the primary key
//------------------------------------------------------------------------------
function _showtable_headings_callback($row,$key) {
  $row[$key]='&nbsp;';
  return "<th>".implode("</th><th>",$row)."</th>";
}

$showtable_headings_callback="_showtable_headings_callback";



//------------------------------------------------------------------------------
// display the contents of a database table
// @param $table the name of the mysql table to display
// @param $display_rows an array describing 
//        which rows to display (keys), and 
//        what title to put on each column (values)
// @param $key
//------------------------------------------------------------------------------
function showtable($table,$display_rows=null) {
  global $showtable_row_callback, $showtable_headings_callback;

  if(!isset($display_rows)) {
    $fields=array_keys(qdescribe($table));
    //d($fields);
    $display_rows="`".implode("`,`",$fields)."`";
    $headings=$fields;
  } else {
    $d2=array_keys($display_rows);
    $headings=$display_rows;
    $display_rows="`".implode("`,`",$d2)."`";
  }
  
  if(!isset($key)) {
    $key=qkey($table);
  }

  $count=qcount($table);
  $limit=showpages($table,$count);
  //echo "LIMIT = ".$limit;
  if($count>0) {
    $r=q("SELECT $display_rows FROM $table $limit");
    
    echo "<table class='table table-bordered'><thead>";
    echo "<tr>".call_user_func($showtable_headings_callback,$headings,$key)."</tr>";
    echo "</thead><tbody>";

    while($row=mysqli_fetch_assoc($r)) {
      $row=call_user_func($showtable_row_callback,$row,$key);
      $values="<td>".implode("</td><td>",$row)."</td>";
      echo "<tr>$values</tr>";
    }
    echo "</tbody></table>";
  }
}


//------------------------------------------------------------------------------
// An easy way to show pagination.
// @return the LIMIT sql clause
// @param $table the name of the table being displayed
// @param $count the number of results being displayed
// @param $link the base link to modify for creating pagination links
//------------------------------------------------------------------------------
function showpages($table,$count) {
  global $limit_first,$per_page,$per_pagination;

  if($count<=$per_page) {
    return '';
  }

  $limit_first = (isset($_REQUEST['pagination'])) ? $_REQUEST['pagination']*$per_page : 0;
  $limit_last = min($limit_first + $per_page,$count);

  if($count==0) echo "<p>No results.</p>";
  else {
    echo "<p>$count results.";
    if($count > $per_page) echo "  Showing results $limit_first to $limit_last.";
    echo "</p>";
  }

  if($count>$per_page) {
    // figure out the first and last button to display
    $last_page=ceil((float)$count/$per_page);
    $current_page=floor($limit_first/$per_page);
    $start=$current_page-$per_pagination/2;
    $end=$current_page+$per_pagination/2;
    if($start<0) {
      $start=0;
      $end=$start+$per_pagination;
    }
    if($end>$last_page) {
      $end=$last_page;
      $start=$end-$per_pagination;
      if($start<0) $start=0;
    }

    // find the next/prev buttons
    $next=min($current_page+1,$last_page-1);
    $prev=max($current_page-1,0);

    // build the URL to link to
    $url = parse_url($_SERVER['REQUEST_URI']);
    $q = (isset($url['query'])) ? explode('&',$url['query']) : array();
    foreach($q as $k=>$v) {
      if(strncasecmp($v, "pagination",10)==0) {
        unset($q[$k]);
        break;
      }
    }
    $link=$url['path'].'?'.implode('&',$q);

    // build the buttons
    $buttons='';
    for($i=$start;$i<$end;++$i) {
      $buttons.="<a class='btn page-$i' href='$link&pagination=$i'>$i</a>";
    }
    $buttons=str_replace("page-$current_page'","page-$current_page this-page'",$buttons);

    echo "<div class='btn-toolbar center'>
      <div class='btn-group'>
        <a class='btn' href='$link&pagination=0'>&lt;&lt;</a>
        <a class='btn' href='$link&pagination=$prev'>&lt;</a>
      </div>
      <div class='btn-group'>$buttons</div>
      <div class='btn-group'>
        <a class='btn' href='$link&pagination=$next'>&gt;</a>
        <a class='btn' href='$link&pagination=".($last_page-1)."'>&gt;&gt;</a>
      </div>
    </div>";
  }

  return "LIMIT $limit_first, $per_page";
}


//------------------------------------------------------------------------------
// An easy way to modify tablerow output.
// @return the new value OR false if the row should not be shown.
// @param $key the heading of the mysql table column.
// @param $value the value of column $key in the row.
//------------------------------------------------------------------------------
function _showtablerow_callback($key,$value) {
  return $value;
}

$showtablerow_callback="_showtablerow_callback";



//------------------------------------------------------------------------------
// display the contents of a database row in vertical format
// @param $table the name of the mysql table to display
// @param $display_rows an array describing 
//        which rows to display (keys), and 
//        what title to put on each column (values)
// @param $key
//------------------------------------------------------------------------------
function showtablerow($table,$id,$display_rows=null) {
  global $showtablerow_callback;
  
  if(!isset($display_rows)) {
    $fields=array_keys(qdescribe($table));
    $headings=$fields;
    $display_cols=$fields;
    $display_rows="`".implode("`,`",$fields)."`";
  } else {
    $display_cols=array_keys($display_rows);
    $headings=array_values($display_rows);
    $display_rows="`".implode("`,`",$display_cols)."`";
  }
  
  if(!isset($key)) {
    $key=qkey($table);
  }

  echo "<h2>Entry $id</h2>";

  $r=q("SELECT $display_rows FROM $table WHERE `$key`='$id' LIMIT 1");
  if(mysqli_num_rows($r)==0) {
    echo "<p class='alert alert-error'>Entry is invalid</p>";
  } else {
    $row=mysqli_fetch_assoc($r);
    echo "<table class='table table-bordered'>";

    for($i=0;$i<count($display_cols);++$i) {
      if(($v=call_user_func($showtablerow_callback,$display_cols[$i],$row[$display_cols[$i]]))===false) continue;
      echo "<tr><th>".$headings[$i]."</th><td>".$v."</td></tr>";
    }
    echo "</table>";
  }
  mysqli_free_result($r);
}



//------------------------------------------------------------------------------
// create one input for a form
// @return the generated input.
// @param $desc table description from qdescribe
// @param $name name of field
// @param $value value of field
//------------------------------------------------------------------------------
function _formatrowinput_callback($type,$name,$value) {
  global $uploads_url;

  if($type=='text') {
    $input = "<textarea class='textarea span8' name='$name' rows='4' cols='60'>".html_encode($value)."</textarea>";
  } else if($type=='date') {
    if($value=='0' || $value=='0000-00-00') $value='';
    $input = "<input class='datepicker' type='text' name='$name' value='".html_encode($value)."' />";
  } else if(!strncmp($type,"enum(",5)) {
    $x=substr($type,6,-2);
    $xx=explode("','",$x);
    $options='';
    foreach($xx as $v) {
      $options.="<option value='$v'>$v</option>";
    }
    $options=str_replace("value='$value'","value='$value' selected",$options);
    $input = "<select name='$name'>$options</select>";
  } else {
    if(preg_match("/(.*)_file$/",$name,$matches)==1) {
      $input = "<a href='$uploads_url/$value'>$value</a><br><input type='file' class='input-file' type='file' name='$name'>";
    } else {
      $input = "<input type='text' name='$name' value='".html_encode($value)."' />";
    }
  }
  
  return $input;
}

$formatrowinput_callback="_formatrowinput_callback";


//------------------------------------------------------------------------------
// display the contents of a database row in vertical format for editing
// @param $table the name of the mysql table to display
// @param $display_rows an array describing 
//        which rows to display (keys), and 
//        what title to put on each column (values)
// @param $key
//------------------------------------------------------------------------------
function showformrow($table,$id,$display_rows=null) {
  global $showtablerow_callback, $formatrowinput_callback,$no_delete;

  $desc=qdescribe($table);

  if(!isset($display_rows)) {
    $fields=array_keys($desc);
    $headings=$fields;
    $display_cols=$fields;
    $display_rows="`".implode("`,`",$fields)."`";
  } else {
    $display_cols=array_keys($display_rows);
    $headings=array_values($display_rows);
    $display_rows="`".implode("`,`",$display_cols)."`";
  }
  
  if(!isset($key)) {
    $key=qkey($table);
  }

  echo "<h2>Entry $id</h2>";

  $r=q("SELECT $display_rows FROM $table WHERE `$key`='$id' LIMIT 1");
  if(mysqli_num_rows($r)==0) {
    echo "<p class='alert alert-error'>Entry is invalid</p>";
  } else {
    $row=mysqli_fetch_assoc($r);
    echo "<form class='form-horizontal' action='' method='post' enctype='multipart/form-data'><fieldset>";

    for($i=0;$i<count($display_cols);++$i) {
      $name=$display_cols[$i];
      $value=call_user_func($showtablerow_callback,$name,$row[$name]);
      if($value===false) continue;

      $input = call_user_func($formatrowinput_callback,$desc[$name][1],$name,$value);
      
      echo "<div class='control-group'>"
           ."  <label for='".$name."' class='control-label'>".ucwords(str_replace('_',' ',$headings[$i]))."</label>"
           ."  <div class='controls'>".$input."</div>"
           ."</div>";
    }

    $delete=isset($no_delete) ? '' : "  <button class='btn btn-danger' style='margin-left:150px' name='delete-now' type='submit'>Delete</button>";

    echo "<div class='form-actions row'>"
           ."  <button class='btn btn-primary' name='save-now' type='submit'>Save changes</button>"
           ."  <button class='btn'>Cancel</button>"
           .$delete
           ."</div>"
           ."</fieldset></form>";
  }
  mysqli_free_result($r);
}


//------------------------------------------------------------------------------
// display the blank form for a database row that does not yet exist.
// @param $table the name of the mysql table to display
// @param $display_rows an array describing 
//        which rows to display (keys), and 
//        what title to put on each column (values)
// @param $key
//------------------------------------------------------------------------------
function shownewformrow($table,$display_rows=null) {
  global $showtablerow_callback, $formatrowinput_callback;

  $desc=qdescribe($table);
  
  if(!isset($display_rows)) {
    $fields=array_keys($desc);
    $headings=$fields;
    $display_cols=$fields;
    $display_rows="`".implode("`,`",$fields)."`";
  } else {
    $display_cols=array_keys($display_rows);
    $headings=array_values($display_rows);
    $display_rows="`".implode("`,`",$display_cols)."`";
  }
  
  if(!isset($key)) {
    $key=qkey($table);
  }

  echo "<h2>New entry</h2>";

  echo "<form class='form-horizontal' action='' method='post' enctype='multipart/form-data'><fieldset>";

  for($i=0;$i<count($display_cols);++$i) {
    $name=$display_cols[$i];
    $value=call_user_func($showtablerow_callback,$name,$desc[$name][4]);
    if($value===false) continue;

    $input = call_user_func($formatrowinput_callback,$desc[$name][1],$name,$value);
    
    echo "<div class='control-group'>"
         ."  <label for='".$name."' class='control-label'>".$headings[$i]."</label>"
         ."  <div class='controls'>".$input."</div>"
         ."</div>";
  }
  
  echo "<div class='form-actions'>"
         ."  <button class='btn btn-primary' name='save-now' type='submit'>Save changes</button>"
         ."  <button class='btn'>Cancel</button>"
         ."</div>"
         ."</fieldset></form>";
}


//------------------------------------------------------------------------------
// saves the contents of a form to a database row
// @return the id of the primary key for the new row.  false if nothing added.
// @param $table the name of the mysql table to save into
// @param $id the row id to save
//------------------------------------------------------------------------------
function saveformrow($table,$id) {
  global $db_link, $uploads_path;
  
  $desc=qdescribe($table);
  $key=qkey($table);

  $c = ($id==0) ? 0 : qcount("$table WHERE $key='$id' LIMIT 1");
  if($c==0) {
    // new entry
    $start="INSERT INTO $table SET ";
    $end="";
    $action='added';
  } else {
    $start="UPDATE $table SET ";
    $end=" WHERE $key='$id' LIMIT 1";
    $action='updated';
  }

  // update post info
  $middle='';
  $sep='';
  foreach($desc as $k=>$v) {
    if(isset($_REQUEST[$k])) {
      $middle.=$sep."$k='".CleanInput($_REQUEST[$k])."'";
      $sep=', ';
    }
  }

  // upload files
  foreach($desc as $k=>$v) {
    if(isset($_FILES[$k])) {
      $f=$_FILES[$k];
      if($f['size']>0 && $f['error']==0) {
        $new_name=$f['name'];
        if(file_exists($uploads_path."/".$new_name)) {
            $middle.=$sep."$k='$new_name'";
            $sep=', ';
          echo "<p class='alert alert-warning'>File <strong>$new_name</strong> already exists.</p>";
        } else {
          if(move_uploaded_file($f['tmp_name'], $uploads_path."/".$new_name)) {
            $middle.=$sep."$k='$new_name'";
            $sep=', ';
            echo "<p class='alert alert-notice'>File <strong>$new_name</strong> uploaded.</p>";
          } else {
            echo "<p class='alert alert-error'>File <strong>$new_name</strong> upload error.</p>";
          }
        }
      }
    }
  }

  $ret=false;
  if(strlen($middle)) {
    q($start.$middle.$end);
    $ret = ($c==0) ? mysqli_insert_id($db_link) : $id;
    echo "<p class='alert alert-success'>Entry $ret $action @ ".date("Y-m-d h:i:s")."</p>";
  }
  
  return $ret;
}
?>
