<?php
class Controller_Departments extends Controller
{
	function action_index()
	{	
		$this->view->generate('departments_view', 'template_view.php', array(
			"title" => "Информация по отделам"
		));
	}

	function action_avg_salary($get, $post)
	{	
		if($_SERVER['REQUEST_METHOD'] == "POST"){
			$mysqli = new mysqli("localhost", "root", "super", "unas");

			$sql = "
			SELECT 
				tblA.name, 
				count(tblB.name) AS number, 
				CAST(COALESCE(avg(tblB.salary), 0)  AS UNSIGNED) AS avg_salary
			FROM unas.departments tblA
			LEFT JOIN unas.employees tblB
			USING(department_id)
			GROUP BY
				tblA.name
			ORDER BY
				avg_salary DESC
			";

			$result = $mysqli->query($sql);
			
			echo json_encode($result->fetch_all(MYSQLI_ASSOC));
		}
	}

	function action_list($get, $post){
		if($_SERVER['REQUEST_METHOD'] == "POST"){
			$mysqli = new mysqli("localhost", "root", "super", "unas");

			$sql = "
			SELECT 
				JSON_ARRAYAGG(name) as departments
			FROM unas.departments
			ORDER BY
				name
			";

			$result = $mysqli->query($sql);
			
			echo $result->fetch_assoc()["departments"];
		}
	}

}
?>