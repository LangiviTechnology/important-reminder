export function checkCorrectEmail(event) {
	const email = event.target.value;
	const exp = new RegExp(/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+.[a-zA-Z0-9-.]+$/g);
	const checkedEmail = !!email.match(exp);
	const label = document.querySelector('.email-label');
	label.textContent = checkedEmail ? '' : 'Write correct email please';
};