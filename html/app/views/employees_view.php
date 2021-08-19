<div class="nav">
    <div class="nav__item"><a href="/Departments">Департаменты</a></div>
    <div class="nav__item active"><a href="/Employees">Сотрудники</a></div>
</div>

<section>
    <div class="employees">
        <table>
            <thead>
                <tr>
                    <th>Сотрудники</th> <th></th> <th></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for='employee in employees'>
                    <td :employee_id="employee.id">{{employee.name}}</td> <td> <i class="bi-pencil"></i> </td> <td> <i class="bi-eraser"></i> </td>
                </tr>
            </tbody>
            <tfoot> 
                <th colspan="3">Добавить нового сотрудника</th>
            </tfoot>
        </table>
    </div>

    <div class="employee-info">
        <table>
            <thead>
                <tr> <th colspan="2"><legend>Информация о сотруднике</legend></th> </tr>
            </thead>
            <tbody>
                <tr> <td>Фамилия</td> <td>{{employeeInfo.surname}}</td> </tr>
                <tr> <td>Имя</td> <td>{{employeeInfo.name}}</td> </tr>
                <tr> <td>Отчество</td> <td>{{employeeInfo.otchestvo}}</td> </tr>
                <tr> <td>Зарплата</td> <td>{{employeeInfo.salary}}</td> </tr>
                <tr> <td>Отдел</td> <td>{{employeeInfo.department}}</td> </tr>
            </tbody>
        </table>
    </div>

    <div class="employee-new">
        <form>
            <legend>Добавление нового сотрудника</legend>
            <label> Фамилия <input class="form-control" type="text" name="surname"> </label>
            <label> Имя <input class="form-control" type="text" name="name"> </label>
            <label> Отчество <input class="form-control" type="text" name="otchestvo"> </label>
            <label> Зарплата <input class="form-control" type="text" name="salary"> </label>
            <label> 
                Отдел
                <select class="form-control" name="department">
                    <option class="form-control" v-for="department in departments" :value="department">{{department}}</option>
                </select>
            </label>
            <label> <input  class="form-control btn-success" type="button" value="Добавить сотрудника"> </label>

            <div class="alert alert-danger" role="alert"></div>
        </form>
    </div>
</section>