import { getLocalUser } from "./helpers/auth";

const user = getLocalUser();

export default {
	state: {
		currentUser: user,
		isLoggedIn: !!user,
		loading: false,
		auth_error: null,
		customers: [],
		manufacturers: [],
		models: [],
		inventories: []
	},
	getters: {
		currentUser(state){
			return this.currentUser;
		},
		isLoggedIn(state){
			return this.isLoggedIn;
		},
		isLoading(state){
			return this.loading;
		},
		authError(state){
			return this.auth_error;
		},
		customers(state){
			return this.customers;
		},
		manufacturers(state){
			return this.manufacturers;
		},
		models(state){
			return this.models;
		},
		inventories(state){
			return this.inventories;
		}
	},
	mutations: {
		login(state){
			state.loading = true;
			state.auth_error = null;
		},
		loginSuccess(state, payload){
			state.auth_error = null;
			state.isLoggedIn = true;
			state.loading = false;
			state.currentUser = Object.assign({}, payload.user, {token: payload.access_token});

			localStorage.setItem("user", JSON.stringify(state.currentUser));
		},
		loginFailed(state, payload){
			state.loading = false;
			state.auth_error = payload.error;
		},
		logout(state){
			localStorage.removeItem("user");
			state.isLoggedIn = false;
			state.currentUser = null;
		},
		updateCustomers(state, payload){
			state.customers = payload;
		},
		updateManufacturers(state, payload){
			state.manufacturers = payload;
		},
		getModels(state, payload){
			state.models = payload;
		},
		getInventories(state, payload){
			state.inventories = payload;
		}
	},
	actions: {
		login(context){
			context.commit("login");
		},
		getCustomers(context){
			axios.get('/api/customers', {
				headers: {
					"Authorization": `Bearer ${context.state.currentUser.token}`
				}
			})
			.then((response) => {
				context.commit('updateCustomers', response.data.customers);
			})
		},
		getManufacturers(context){
			axios.get('/api/manufacturer', {
				headers: {
					"Authorization": `Bearer ${context.state.currentUser.token}`
				}
			})
			.then((response) => {
				context.commit('updateManufacturers', response.data.manufacturers);
			})
		},
		getModels(context){
			axios.get('/api/model', {
				headers: {
					"Authorization": `Bearer ${context.state.currentUser.token}`
				}
			})
			.then((response) => {
				context.commit('getModels', response.data.models);
			})
		},
		getInventories(context){
			axios.get('/api/inventories', {
				headers: {
					"Authorization": `Bearer ${context.state.currentUser.token}`
				}
			})
			.then((response) => {
				context.commit('getInventories', response.data.inventories);
			})
		}
	}
};