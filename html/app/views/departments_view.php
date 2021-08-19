<div class="nav">
    <div class="nav__item active"><a href="/Departments">Департаменты</a></div>
    <div class="nav__item"><a href="/Employees">Сотрудники</a></div>
</div>

<section>
    <div class="departments">
        <table>
            <thead>
                <tr>
                    <th>Отдел</th> <th>Кол-во сотрудников</th> <th>Средний размер зп</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for='department in departments'>
                    <th>{{department.name}}</th>
                    <td>{{department.number}}</td>
                    <td>{{department.avg_salary}}</td> 
                </tr>
            </tbody>
        </table>
    </div>
</section>