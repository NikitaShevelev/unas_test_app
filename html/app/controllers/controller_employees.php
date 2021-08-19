<?php
class Controller_Employees extends Controller
{
	function action_index()
	{	
		$this->view->generate('employees_view', 'template_view.php', array(
			"title" => "Информация по сотрудникам"
		));
	}

	function action_list($get, $post){	
		if($_SERVER['REQUEST_METHOD'] == "POST"){
			$mysqli = new mysqli("localhost", "root", "super", "unas");

			if(!empty($_POST["employee_id"])){
				$employee_id = $mysqli->real_escape_string($_POST['employee_id']);
				$sql = "
					SELECT 
						tblA.salary, tblB.name AS department
					FROM 
						unas.employees tblA
					LEFT JOIN 
						unas.departments tblB
					USING(department_id)
					WHERE
						employee_id='$employee_id'"
				;
				$result = $mysqli->query($sql);
				echo json_encode($result->fetch_assoc());
			} else {
				$sql = "
					SELECT 
						employee_id AS id, name
					FROM 
						unas.employees
					ORDER BY
						name
				";
				$result = $mysqli->query($sql);
				echo json_encode($result->fetch_all(MYSQLI_ASSOC));
			}

		}
	}

	function action_add($get, $post){
		if($_SERVER['REQUEST_METHOD'] == "POST"){
			$mysqli = new mysqli("localhost", "root", "super", "unas");
			$name = $mysqli->real_escape_string($_POST['name']);
			$salary = $mysqli->real_escape_string($_POST['salary']);
			$department = $mysqli->real_escape_string($_POST['department']);

			$sql = "
			INSERT INTO 
				unas.employees (name, salary, department_id) 
			VALUES
				('$name', $salary, (SELECT department_id FROM unas.departments WHERE name='$department') )
			";

			$mysqli->autocommit(FALSE);

			try{
				$mysqli->query($sql);
				$mysqli->commit();
				echo json_encode([
					"status" => true,
					"message" => "Запись о сотруднике успешно добавлена!"
				]);
			} catch( Exception $e ){
				$mysqli->rollback();
				echo json_encode([
					"status" => false,
					"message" => "Не удалось добавить сотрудника!"
				]);
			}
		}
	}

	function action_edit($get, $post){
		if($_SERVER['REQUEST_METHOD'] == "POST"){
			$mysqli = new mysqli("localhost", "root", "super", "unas");
			$employee_id = $mysqli->real_escape_string($_POST['employee_id']);
			$name = $mysqli->real_escape_string($_POST['name']);
			$salary = $mysqli->real_escape_string($_POST['salary']);
			$department = $mysqli->real_escape_string($_POST['department']);

			$sql = "
				UPDATE 
					unas.employees 
				SET
					name='$name', 
					salary=$salary, 
					department_id = (SELECT department_id FROM unas.departments WHERE name='$department') 
				WHERE
					employee_id=$employee_id
			";

			$mysqli->autocommit(FALSE);

			try{
				$mysqli->query($sql);
				$mysqli->commit();
				echo json_encode([
					"status" => true,
					"message" => "Информация о сотруднике успешно обновленна!"
				]);
			} catch( Exception $e ){
				$mysqli->rollback();
				echo json_encode([
					"status" => false,
					"message" => "Не удалось обновить информацию о сотруднике!"
				]);
			}

		}
	}

	function action_delete($get, $post){
		if($_SERVER['REQUEST_METHOD'] == "POST"){
			$mysqli = new mysqli("localhost", "root", "super", "unas");
			$employee_id = $mysqli->real_escape_string($_POST['employee_id']);

			$sql = "DELETE FROM unas.employees WHERE employee_id=$employee_id";

			$mysqli->autocommit(FALSE);

			try{
				$mysqli->query($sql);
				$mysqli->commit();
				echo json_encode([
					"status" => true,
					"message" => "Запись о сотруднике успешно удалена!"
				]);
			} catch( Exception $e ){
				$mysqli->rollback();
				echo json_encode([
					"status" => false,
					"message" => "Не удалось удалить сотрудника!"
				]);
			}

		}
	}

}
?>