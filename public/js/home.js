class Chat {
	constructor( ) {
		this.base     = {};
		this.baseUrl  = window.location.origin;
		this.messages = [];
		this.choosenUser;
		this.chatId;

		this.bindEvents();
		this.getAttributes();
		this.connect();
	}

	connect() {
		this.Echo = new Echo({
		    broadcaster: 'pusher',
		    key: 'asdasd',
		    wsHost: window.location.hostname,
		    wsPort: 6001,
		    forceTLS: false,
		    disableStats: true,
		    csrfToken: document.querySelector('[name="_token"]').value
		});
	}

	listen(id) {
		this.Echo.channel(`chat.${id}`)
		.listen('ChatEvent', e => {
			console.log(e)
		})
	}

	getAttributes() {
		let fores = document.querySelectorAll('[b-for]'),
			data  = {}, key;

		Object.values(fores).forEach(e => {
			data.attr      = e.getAttribute('b-for');
			data.arr       = data.attr.match(/(\S+)$/i) && data.attr.match(/(\S+)$/)[1]
			data.variable  = data.attr.match(/^[a-z]+ (\S+)/i) && data.attr.match(/^[a-z]+ (\S+)/i)[1]
			data.element   = e;
			data.contaienr = data.element.parentElement;

			this.insertData(data);
			this.base[data.arr] = data;
		})

		for(key in this.base) {
			this.base[key].push = (e) => {
				Array.prototype.push.call(e);
				this.addItem(e, this.base[key]);
			}
		}
	}

	insertData(data) {
		this.empty(data.contaienr);
		this[data.arr].forEach(el => {
			this.addItem(el, data);
		})
	}

	addItem(el, data) {
		let text    = this.elementToString(this.addIfs(el, data)),
			matches = [...text.matchAll(new RegExp(`\\{${data.variable}\\.(\\S+)\\}`, 'g'))];

		matches.forEach(e => {
			text = text.replace(new RegExp(`\\{message\\.${e[1]}\\}`, 'g'), el[e[1]])
		})

		data.contaienr.appendChild(this.toHtml(text));
	}

	addIfs(data, info) {
		let clone = info.element.cloneNode(true),
			ifs = Object.values(clone.querySelectorAll('[b-class]')),  cond;

		clone.getAttribute('b-class') && ifs.push(clone);
		Object.values(ifs).forEach(e => {
			cond = e.getAttribute('b-class');
			cond = eval(cond.replace(new RegExp(`${info.variable}`, 'g'), 'data'))
			e.classList += ` ${cond}`;
		})

		return clone;
	}

	elementToString(element) {
		let container = document.createElement('div');
		container.appendChild(element.cloneNode(true));

		return container.innerHTML
	}

	post(data, action) {
        var myHeaders = new Headers().append("Content-Type", "application/x-www-form-urlencoded"),
            urlencoded = new URLSearchParams(), requestOptions;

		data['_token'] = document.querySelector('[name="_token"]').value;

        for(let key in data) {
            urlencoded.append(key, data[key])
        }

        requestOptions = {
            method: 'POST',
            headers: myHeaders,
            body: urlencoded,
            redirect: 'follow'
        };

        return new Promise((res, rej) => {
            fetch(`${this.baseUrl}/${action}`, requestOptions)
            .then(response => response.text())
            .then(result => res(result))
            .catch(error => rej('error', error));
        })
    }

	toggleSidebar(elem) {
		document.getElementById('wrapper').classList.toggle('toggled')
	}

	openModals(elem) {
		let modal = document.querySelector(elem.dataset.target)

		modal.style.display = 'block';
		modal.style.paddingRight = '17px';
		modal.classList.toggle('show');
	}

	closeModal(elem, event) {
		let modal = elem.closest('.modal.show');

		modal.style.display = 'none';
		modal.style.paddingRight = '17px';
		modal.classList.toggle('show');
	}

	async addChat(elem) {
		let input = document.getElementById('chatName'),
			chatName = input.value && input.value.trim(),
			message = document.querySelector('.chat-name'),
			container = document.getElementById('chatsContainer'),
			a = document.createElement('a'), res;

		if(!chatName || chatName.length < 3) {
			input.classList.add('is-invalid');
			message.classList.remove('fade');
		} else {
			input.classList.remove('is-invalid');
			message.classList.add('fade');
			res = JSON.parse(await this.post({chatName}, 'addchat'));
			input.value = '';
			this.closeModal(document.getElementById('closeChatModal'));

			if(res.success) {
				a.classList.add("list-group-item", "list-group-item-action", "bg-light", "chat-item");
				a.dataset.id = res.success.chat_id;
				a.innerText = chatName;
				container.appendChild(a);
				this.bindEvents();
			}
		}
	}

	async selectChat(elem) {
		let oldActive = document.querySelector('.list-group-item.list-group-item-action.bg-light.selected'),
			addUserModal = document.getElementById('addUserModal'), res;

		addUserModal.style.display = 'block';
		document.querySelector('.chat-content').style.display = 'flex';
		document.querySelector('.list-group-item.list-group-item-action.bg-light.selected') &&
		document.querySelector('.list-group-item.list-group-item-action.bg-light.selected').classList.remove('selected');
		elem.classList.add('selected');
		this.chatId = elem.dataset.id;

		this.messages = JSON.parse(await this.post({chatId: this.chatId}, 'getmessages'));
		this.messages.push = this.base.messages.push;
		this.insertData(this.base.messages);
		this.toBottom();
		this.listen(this.chatId);
	}

	eventBinder(e) {
		chat[e.currentTarget.key].call(chat, e.currentTarget, e.target);
	}

	async getUsers(e) {
		let fusersContainer = document.getElementById('foundUsers'),
			value = document.getElementById('userInput').value, foundUser, el;

		this.choosenUser = undefined;
		foundUser = JSON.parse(await this.post({value}, 'search'));
		this.empty(fusersContainer)
		foundUser.forEach(element => {
			el = this.toHtml(`<div class="found-user">${element.name} ${element.email}</div>`)
			el.addEventListener('click', this.chooseUser.bind(this, element, e, el))
			fusersContainer.appendChild(el);
		})
		fusersContainer.style.display = foundUser.length ? 'block' : 'none';
	}

	chooseUser(e, input, el) {
		input.value = e.name;
		this.choosenUser = e;
		document.querySelector('.found-user.selected') &&
		document.querySelector('.found-user.selected').classList.remove('selected')

		el.classList.add('selected')
	}

	toHtml(string) {
		return new DOMParser()
            .parseFromString(string, 'text/html')
            .querySelector('body')
            .children[0]
	}

	empty(element) {
		while(element.children.length) {
			element.children[0].remove()
		}
	}

	async addUser(e) {
		if(this.choosenUser && this.chatId) {
			await this.post({userId: this.choosenUser.id, chatId: this.chatId}, 'adduser')
			document.querySelector('.user-name').classList.add('fade');
			this.closeModal(document.getElementById('closeSearchModal'))
		} else {
			document.querySelector('.user-name').classList.remove('fade');
		}
	}

	async sendMessage(e) {
		let element = document.getElementById('messageText'),
			text = element.innerHTML, res;

		element.innerHTML = '';
		if(text.trim()) {
			this.messages.push(JSON.parse(await this.post({text, chatId: this.chatId}, 'addmessage')));
			this.toBottom();
		}
	}

	toBottom() {
		let container = document.getElementById('messagesContainer');

		container.scrollTo(0,container.scrollHeight);
	}

	bindEvents() {
		const events = {
			toggleSidebar: {
				type: 'click',
				element: [document.getElementById('menu-toggle')]
			},
			openModals: {
				type: 'click',
				element: document.querySelectorAll('[data-toggle="modal"]')
			},
			closeModal: {
				type: 'click',
				element: document.querySelectorAll('[data-dismiss="modal"]')
			},
			addChat: {
				type: 'click',
				element: document.querySelectorAll('.add-chat')
			},
			selectChat: {
				type: 'click',
				element: document.querySelectorAll('.chat-item')
			},
			getUsers: {
				type: 'input',
				element: [document.getElementById('userInput')]
			},
			addUser: {
				type: 'click',
				element: document.querySelectorAll('.add-user-to-chat')
			},
			sendMessage: {
				type: 'click',
				element: document.querySelectorAll('.send-message')
			}
		}

		for(let key in events) {
			Object.values(events[key].element).forEach(elem => {
				elem.key = key;
				if(this[key]) {
					elem.removeEventListener(events[key].type, this.eventBinder, false)
					elem.addEventListener(events[key].type, this.eventBinder, false)
				}
			})
		}
	}
}

window.onload = () => {
	window.chat = new Chat();
}