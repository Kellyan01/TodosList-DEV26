fetch('https://localhost/TodosList/api/users')
    .then(response => response.json())
    .then(data => console.log(data));