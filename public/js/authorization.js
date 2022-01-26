export function checkLogin() {
	const token = localStorage.getItem('token');
	if (token) {
		const logoutElement = document.activeElement('div');
		logoutElement.innerHTML = `
			<div>
				<span>${login}</span> 
				<button type="button">Logout</button>
			</div>
		`;
		const header = document.querySelector('.header');
		header.append(logoutElement);
	}
}

