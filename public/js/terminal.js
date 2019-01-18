class Terminal {

	constructor(container_id) {

		this.container = document.getElementById(container_id);

	}


	write(message, type) {
		var element = document.createElement("p");
		element.innerHTML = message;
		if (type !== undefined) element.classList.add(type);
		this.container.appendChild(element);
	}



}