document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    const BASE_URL = '/login-registration-with-jwt';

    if (loginForm) {
        loginForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = {
                email: this.email.value,
                password: this.password.value
            };

            try {
                const response = await fetch(`${BASE_URL}/login`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(formData)
                });

                const data = await response.json();
                const messageDiv = document.getElementById('loginMessage');
                console.log(data);
                localStorage.setItem('token', data.token);

                if (response.ok) {
                    messageDiv.className = 'alert alert-success';
                    messageDiv.textContent = 'Connexion réussie! Redirection...';

                    // Redirection simple vers le dashboard
                    window.location.href = `${BASE_URL}/dashboard`;
                } else {
                    messageDiv.className = 'alert alert-danger';
                    messageDiv.textContent = data.message || 'Erreur de connexion';
                }
            } catch (error) {
                console.error('Error:', error);
                const messageDiv = document.getElementById('loginMessage');
                messageDiv.className = 'alert alert-danger';
                messageDiv.textContent = 'Erreur de connexion';
            }
        });
    }

    // Register Form Handler
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            if (this.password.value !== this.confirmPassword.value) {
                document.getElementById('registerMessage').className = 'alert alert-danger';
                document.getElementById('registerMessage').textContent = 'Les mots de passe ne correspondent pas';
                return;
            }

            const formData = {
                username: this.username.value,
                email: this.email.value,
                name: this.name.value,
                password: this.password.value
            };

            try {
                const response = await fetch(BASE_URL+'/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(formData)
                });

                console.log(response);

                const data = await response.json();
                const messageDiv = document.getElementById('registerMessage');

                if (response.ok) {
                    messageDiv.className = 'alert alert-success';
                    messageDiv.textContent = 'Inscription réussie! Redirection vers la page de connexion...';
                    setTimeout(() => {
                        window.location.href = BASE_URL+'/login';
                    }, 2000);
                } else {
                    messageDiv.className = 'alert alert-danger';
                    messageDiv.textContent = data.message;
                }
            } catch (error) {
                console.error('Error:', error);
            }
        });
    }
});