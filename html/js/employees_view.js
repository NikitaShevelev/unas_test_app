"use strict"
let app = new Vue({
    el: '#vueapp',
    data: {
        employees: [],
        employeeInfo: {
            surname: "",
            name: "",
            otchestvo: "",
            salary: "",
            department: ""
        },
        departments: []
    },

    mounted: function () {
        this.getEmployees();
        this.getDepartments();
    },

    methods : {
        getEmployees : () => {
            axios({
                method: "POST",
                url: "Employees/list"
            })
            .then((response) => {
                app.employees = response.data;
            })
            .catch((response) => {
                console.log(response)
            });
        },

        getDepartments : () => {
            axios({
                method: "POST",
                url: "Departments/list"
            })
            .then((response) => {
                app.departments = response.data;
            })
            .catch((response) => {
                console.log(response)
            });
        }
    }

})

app.$watch('employees', () => {
    for(const tr of document.querySelectorAll("div.employees tbody tr")){
        tr.cells[0].addEventListener("click", function () {
            const employee_id = this.getAttribute("employee_id");
            let fio = this.innerText;

            fio = fio.split(" ");

            app.employeeInfo.surname = fio[0];
            app.employeeInfo.name = fio[1];
            app.employeeInfo.otchestvo = fio[2];

            let formData = new FormData();
            formData.append('employee_id', employee_id);

            axios({
                method: "POST",
                url: "Employees/list",
                data: formData
            })
            .then((response) => {
                app.employeeInfo.salary = response.data.salary;
                app.employeeInfo.department = response.data.department;
            })
            .catch((response) => {
                console.log(response)
            });
        })

        // EDIT EMPLOYEE
        tr.cells[1].addEventListener("click", (e) => {
            const fio = e.target.parentElement.parentElement.cells[0].innerText.split(" ");
            const alertField = document.querySelector('div.employee-new form div.alert');
            const formButton = document.querySelector('div.employee-new form input[type="button"]');
            const employee_id = e.target.parentElement.parentElement.cells[0].getAttribute("employee_id");
            const inputs = [
                document.querySelector('div.employee-new form input[name="surname"]'),
                document.querySelector('div.employee-new form input[name="name"]'),
                document.querySelector('div.employee-new form input[name="otchestvo"]')
            ];

            document.querySelector("div.employee-new form legend").innerHTML = "Обновление инфо о сотруднике";
            document.querySelector("div.employee-new").style.display = "flex";

            alertField.innerHTML = "";
            alertField.style.display = "none";

            formButton.value = "Обновить инфо о сотруднике";
            formButton.setAttribute("class", "form-control btn-warning");
            formButton.addEventListener("click", () => {
                let fio = "";
                let allow_send = true;
                let salaryField = document.querySelector('div.employee-new form input[name="salary"]');
        
                for(const input of inputs){
                    if(["surname", "name"].indexOf(input.getAttribute("name")) > -1){
                        if(input.value === ""){
                            allow_send = false;
                            alertField.innerHTML = "Поля <Фамилия> и <Имя> обязательны к заполнению!"
                            alertField.style.display = "block";
                            break;
                        }
                    }
        
                    fio += input.value.capitalize() + " ";
                }
        
                fio = fio.trim();
                
                if(salaryField.value === "" && allow_send){
                    allow_send = false;
                    alertField.innerHTML = "Поле <Зарплата> обязательно к заполнению!"
                    alertField.style.display = "block";
                }
        
                if( allow_send ) {
                    alertField.innerHTML = ""
                    alertField.style.display = "none";
                    
                    let formData = new FormData();
                    formData.append('employee_id', fio);
                    formData.append('name', fio);
                    formData.append('salary', salaryField.value);
                    formData.append('department', document.querySelector('div.employee-new form select[name="department"]').value);
                    formData.append('employee_id', employee_id);
                    
                    axios({
                        method: "POST",
                        url: "Employees/edit",
                        data: formData
                    })
                    .then((response) => {
                        app.getEmployees();
                        document.querySelector("div.employee-new").style.display = "none";
                        alert(response.data.message);
                    })
                    .catch((response) => {
                        alert("Не удалось обновить запись о сотруднике!")
                    });
        
                }
        
            })

            let formData = new FormData();
            formData.append('employee_id', employee_id);

            axios({
                method: "POST",
                url: "Employees/list",
                data: formData
            })
            .then((response) => {
                document.querySelector('div.employee-new form input[name="surname"]').value = fio[0];
                document.querySelector('div.employee-new form input[name="name"]').value = fio[1];
                console.log("otchestvo: ", fio[2]);
                document.querySelector('div.employee-new form input[name="otchestvo"]').value =  fio[2] === undefined ? "":fio[2];
                document.querySelector('div.employee-new form input[name="salary"]').value = response.data.salary;
                document.querySelector('div.employee-new form select[name="department"]').value = response.data.department;
            })
            .catch((response) => {
                alert("Не удалось загрузить дополнительную информацию о сотруднике!");
            });

        })

        // DELETE EMPLOYEE
        tr.cells[2].addEventListener("click", (e) => {
            if(confirm("Вы уверены, что хотите удалить запись о сотруднике!\nДействие является необратимым!")){
                const employee_id = e.target.parentElement.parentElement.cells[0].getAttribute("employee_id");
                let formData = new FormData();
                formData.append('employee_id', employee_id);

                axios({
                    method: "POST",
                    url: "Employees/delete",
                    data: formData
                })
                .then((response) => {
                    alert(response.data.message);
                    app.employeeInfo = {
                        surname: "",
                        name: "",
                        otchestvo: "",
                        salary: "",
                        department: ""
                    }
                    app.getEmployees();
                })
                .catch(() => {
                    alert("Не удалось удалить запись о сотруднике!");
                });
            }
        })
    }
})

String.prototype.capitalize = function() {
    return this.charAt(0).toUpperCase() + this.slice(1).toLowerCase();
}

document.querySelector("div.employees tfoot th").addEventListener("click", () => {
    const alertField = document.querySelector('div.employee-new form div.alert');
    const formButton = document.querySelector('div.employee-new form input[type="button"]');
    const inputs = [
        document.querySelector('div.employee-new form input[name="surname"]'),
        document.querySelector('div.employee-new form input[name="name"]'),
        document.querySelector('div.employee-new form input[name="otchestvo"]')
    ];


    document.querySelector("div.employee-new form legend").innerHTML = "Добавление нового сотрудника";
    
    alertField.innerHTML = "";
    alertField.style.display = "none";

    for(const input of inputs) {
        input.addEventListener("input", (e) => {
            let value = "";
        
            for(const ch of e.target.value){
                if(/[а-яА-ЯЁё]/.test(ch) ) {
                    value += ch;
                }
            }

            e.target.value = value;
        })
    }

    document.querySelector('div.employee-new form input[name="salary"]').addEventListener("input", (e) => {
        let value = "";
        
        for(const ch of e.target.value){
            if("0123456789".search(ch) > -1 && ch != ".") {
                value += ch;
            }
        }

        while(value[0] === "0" && value.length > 1){
            value = value.substring(1, value.length);
        }

        value = value === "" ? "0" : value;
        e.target.value = value;
    });

    formButton.value = "Добавить сотрудника";
    formButton.setAttribute("class", "form-control btn-success");
    formButton.addEventListener("click", () => {
        let fio = "";
        let allow_send = true;
        let salaryField = document.querySelector('div.employee-new form input[name="salary"]');

        for(const input of inputs){
            if(["surname", "name"].indexOf(input.getAttribute("name")) > -1){
                if(input.value === ""){
                    allow_send = false;
                    alertField.innerHTML = "Поля <Фамилия> и <Имя> обязательны к заполнению!"
                    alertField.style.display = "block";
                    break;
                }
            }

            fio += input.value.capitalize() + " ";
        }

        fio = fio.trim();
        
        if(salaryField.value === "" && allow_send){
            allow_send = false;
            alertField.innerHTML = "Поле <Зарплата> обязательно к заполнению!"
            alertField.style.display = "block";
        }

        if( allow_send ) {
            alertField.innerHTML = ""
            alertField.style.display = "none";
            
            let formData = new FormData();
            formData.append('name', fio);
            formData.append('salary', salaryField.value);
            formData.append('department', document.querySelector('div.employee-new form select[name="department"]').value);
            
            axios({
                method: "POST",
                url: "Employees/add",
                data: formData
            })
            .then((response) => {
                app.getEmployees();
                document.querySelector("div.employee-new").style.display = "none";
                alert(response.data.message);
            })
            .catch((response) => {
                alert("Не удалось создать запись о сотруднике!")
            });

        }

    })
})