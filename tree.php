<?php

function dbconnect(){
	$host = "localhost";
	$dbuser = "root";
	$dbpass = "";
	$dbname = "treedb";

	$conn = new mysqli($host, $dbuser, $dbpass, $dbname);

	if($conn->connect_error){
		die("Connection failed: ".$conn->connect_error);
	}
	
	return $conn;
}

function categories()
{
	
	$conn = dbconnect();
	
	$sql = "SELECT * FROM categories WHERE parent_id=0";
	$result = $conn->query($sql);
	
	$categories = array();
	
	while($row = $result->fetch_assoc())
	{
		$categories[] = array(
			'id' => $row['id'],
			'parent_id' => $row['parent_id'],
			'category_name' => $row['category_name'],
			'subcategory' => sub_categories($row['id']),
		);
	}
	
	return $categories;
}

function sub_categories($id)
{	
	$conn = dbconnect();
	
	$sql = "SELECT * FROM categories WHERE parent_id=$id";
	$result = $conn->query($sql);
	
	$categories = array();
	
	while($row = $result->fetch_assoc())
	{
		$categories[] = array(
			'id' => $row['id'],
			'parent_id' => $row['parent_id'],
			'category_name' => $row['category_name'],
			'subcategory' => sub_categories($row['id']),
		);
	}
	return $categories;
}

function viewsubcat($categories)
{
	$html = '<ul class="sub-category">';
	foreach($categories as $category){

		$html .= '<li>'.$category['category_name'].'</li>';
		
		if( ! empty($category['subcategory'])){
			$html .= viewsubcat($category['subcategory']);
		}
	}
	$html .= '</ul>';
	
	return $html;
}


?>
<?php $categories = categories(); ?>
<?php foreach($categories as $category){ ?>
		<ul class="category">
			<li><?php echo $category['category_name'] ?></li>
		<?php 
			if( ! empty($category['subcategory'])){
				echo viewsubcat($category['subcategory']);
			} 
		?>
	</ul>
<?php } ?>