export function checkCorrectEmail(event) {
	const email = event.target.value;
	const exp = new RegExp(/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+.[a-zA-Z0-9-.]+$/g);
	const checkedEmail = !!email.match(exp);
	const label = document.querySelector('.email-label');
	label.textContent = checkedEmail ? '' : 'Write correct email please';
};

export async function authFetch (resource, originalInit = {}) {
	const authInit = {...originalInit};
	if (!authInit.headers) init.headers = {};
	authInit.headers.Authorization = `Bearer ${localStorage.getItem('token')}`;
	const response = await fetch(resource, authInit);
	if (response.status === 401 && !originalInit._isRetry) {
		authInit._isRetry = true;
		const refresh = await fetch('/auth/refresh');
		if (refresh.status === 401) {
			return response;
		}
		const data = await response.json();
		localStorage.setItem('token', data.tokens.accessToken);
		
		return await authFetch(resource, authInit);
	}
	return response;
}