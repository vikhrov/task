$(document).ready(function() {
    new DataTable('#empTable', {
        order: [[1, 'asc']],
        processing: true,
        serverSide: true,
        ajax: '/getEmployees',
        columns: [
            {
                data: 'photo',
                name: 'employees.photo',
                title: 'Photo',
            },
            {
                data: 'name',
                name: 'employees.name',
                title: 'Name',
            },
            {
                data: 'position',
                name: 'position',
                title: 'Position',
            },
            {
                data: 'date_of_employment',
                name: 'date_of_employment',
                title: 'Date of employment',
            },
            {
                data: 'phone_number',
                name: 'phone_number',
                title: 'Phone number',
            },
            {
                data: 'email',
                name: 'email',
                title: 'Email',
            },
            {
                data: 'salary',
                name: 'salary',
                title: 'Salary',
            },
            {
                data: 'actions',
                title: 'Actions',
                orderable: false,
            }
        ],
    });
});
