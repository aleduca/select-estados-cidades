import http from '../http';

function users() {
	return {
		data: [],
		async getUsers() {
			try {
				const { data } = await http.get('/users');
				this.data = data;
			} catch (error) {
				console.log(error);
			}
		},
	};
}

export default users;
