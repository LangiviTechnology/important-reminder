function parseJwt (token) {
    var base64Url = token.split('.')[1];
    var base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
    var jsonPayload = decodeURIComponent(atob(base64).split('').map(function(c) {
        return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
    }).join(''));

    return JSON.parse(jsonPayload);
};

export function logout () {
	// request('/auth/logout');
	localStorage.removeItem('token');
	setLoginStatus('');
}

function setLoginStatus (login) {
	const loginName = document.querySelector('.login-name');
	const loginButton = document.querySelector('.login-button');
	if (login) {
		loginName.textContent = login;
		loginButton.textContent = 'Logout';
		loginButton.onclick = logout;
		return;
	}
	loginName.textContent = '';
	loginButton.textContent = 'Login';
	loginButton.onclick = () => window.location='/auth/login';
}

export function checkLogged() {
	const token = localStorage.getItem('token');
	if (token) {
		const user = parseJwt(token); 
		setLoginStatus(user.login);
		return;
	}
	setLoginStatus('');
}

export async function login(e) {
	e.preventDefault();
	const fields = Array.from(e.target.getElementsByTagName('input'));
	const loginData = fields.reduce((result, field) => {
		result[field.name] = field.value;
		return result;
	}, {});  

	const response = await fetch('/auth/login', {
		method: 'POST', 
		headers: { 'Content-Type': 'application/json' },
		body: JSON.stringify(loginData) 
	});
	const notification = document.querySelector('.notification');
	const data = await response.json();
	if (response.status === 404 ) {
		const suggestElement = document.querySelector('.suggest-registration');
		suggestElement.classList.add('show');
	}
	if (!response.ok) {
		notification.textContent = data?.error;
		return false;
	}      
	localStorage.setItem('token', data.tokens.accessToken);
	console.log(data);
	document.referrer ? window.location = document.referrer : history.back()
}

export async function registration(e) {
	e.preventDefault();
	const fields = Array.from(e.target.getElementsByTagName('input'));
	const registrationData = fields.reduce((result, field) => {
		result[field.name] = field.value;
		return result;
	}, {});  

	const response = await fetch('/auth/registration', {
		method: 'POST', 
		headers: { 'Content-Type': 'application/json' },
		body: JSON.stringify(registrationData) 
	});
	const notification = document.querySelector('.notification');
	const data = await response.json();
	if (!response.ok) {
		notification.textContent = data?.error;
	}      
	console.log(data);
}
