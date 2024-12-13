// Function to validate login form
function validateLoginForm() {
    var username = document.getElementById('username').value;
    var password = document.getElementById('password').value;
    
    if (username === '' || password === '') {
        alert('Username and Password cannot be empty');
        return false;
    }
    return true;
}

// Example for form submission using Ajax (login)
document.getElementById('loginForm').addEventListener('submit', function(e) {
    e.preventDefault();
    if (validateLoginForm()) {
        var formData = new FormData(this);
        fetch('login.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = data.redirect_url;
            } else {
                alert('Login failed: ' + data.message);
            }
        })
        .catch(error => console.log('Error:', error));
    }
});
