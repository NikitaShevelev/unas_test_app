var app = new Vue({
    el: '#vueapp',
    data: {
        departments: []
    },

    mounted: function () {
        this.getDepartments();
    },

    methods : {
        getDepartments : () => {
            axios({
                method: "post",
                url: "Departments/avg_salary"
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