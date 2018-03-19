<?php 
include 'includes/DB_Config.php';
	
function OpenConnection(){
	global $HOST , $USER , $PASSWORD , $NAME;
	$connection = mysqli_connect($HOST , $USER , $PASSWORD , $NAME)
	              or die("Connection Failed ".mysqli_connect_error);
	return $connection;
}
//========================================================================
function CloseConnection($connection){
	mysqli_close($connection);
}
//========================================================================
function Get_Category_Name($Category_ID) {
	$connection = OpenConnection();
    $query = "SELECT * FROM book_category WHERE Category_ID = $Category_ID; ";
    $result = mysqli_query($connection, $query);
    if(mysqli_num_rows($result)>0){
        $row = mysqli_fetch_assoc($result);
        return $row['Name'];
    }
    else {
        echo "Error in Get_Category_Name()<br>";
    }  
     CloseConnection($connection);   
}
    

//========================================================================
function Get_Search_Query($Checked, $Category_ID, $BookKeywords){
	$query ="";
	$connection = OpenConnection();
    
    switch ($Checked) {
        case 0: $query = "SELECT * FROM book"; 
            break;
        case 1: $query = "SELECT * FROM book WHERE Category_ID = $Category_ID";
            break;
        case 2: $query = "SELECT * FROM book WHERE Title like '% $BookKeywords %';" ;
	       break;
    }
	return $query;
    CloseConnection($connection);
}
//===================================================================================================
function Get_All_Books($query) {	
	$books_array = array();
    $connection = OpenConnection();
    $query = $query;
	$result = mysqli_query($connection, $query);
    if(mysqli_num_rows($result)>0){      
        while($row = mysqli_fetch_assoc($result)){
            array_push($books_array, $row);
        } 
        //ksort($books_array);    
    }
    //ksort($books_array);
    SortbyTitle($books_array);
    CloseConnection($connection); 
	return $books_array;		
}

//---------------------------------------------------------

function CompareByTitle($array1 , $array2){
	if($array1['Title'] == $array2['Title'])
		return 0;
	elseif ($array1['Title']>$array2['Title'])
		return 1;
	else
		return -1;
}

function SortbyTitle(&$books_array){
    uasort($books_array, "CompareByTitle");
}

//===================================================================================================
function Display($array){
        
	echo "<table border=0 width='750'>";
    echo "<tr>";
	echo "<th><h4 style = 'font-weight: bold;'>Book</h4></th>";
    echo "<th><h4 style = 'font-weight: bold;'>Description</h4></th>";
	echo "<th><h4 style = 'font-weight: bold;'>Category</h4></th>";
    echo "<th><h4 style = 'font-weight: bold;'>Status</h4></th>";
    echo "</tr>";
        
	foreach($array as $value){
	   $Book_ID = $value['Book_ID'];
	   $Title   = $value['Title']; 
	   $Authors = $value['Authors'];
	   $Status  = $value['Status'];
	   $Image   = $value['Image'];
	   $Category_id = $value['Category_ID'];
       $Category = Get_Category_Name($Category_id);   
	    		
	   echo "<tr>";
	   echo "<td style='height:150px;width:15%'><img src='images/books/$Image' /></td>";
	   echo "<td style='width:30%' ><h4><font color=#CC0000>".$Title."</font></h4><h5>".$Authors."</h5></td>";
       echo "<td style='width:15%' ><h4>".$Category."</h4></td>";	
	   if($Status == 'Available'){
		   echo "<td style='width:10%'><img src='images/Yes.png' /></td>";            
	   }else{
		   echo "<td style='width:10%'><img src='images/No.png' /></td>"; 
	   }
	   echo "</tr>";					
	}
    echo "</table>";
		
}
	
?>